<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Mygvip extends CI_Controller {

    protected $headerdata = array();
    protected $footer_data = array();

    protected $uid;
    protected $sort_options;

    public function __construct()
    {
        parent::__construct();

        $languageSession = sess_var('lang');
        get_language_file($languageSession);

        // If the user is not logged in then redirect to the login page
        auth_check();

        $this->uid = sess_var('uid');

        $this->footer_data['lang'] = langGet();

        $this->sort_options = array(
            1 => lang('SortAlphabetically'),
        );

        // TODO: Revisit this logic to use array of events
        // deffered through flashdata
        // TODO: Use a hook or extend a controller to flush segment analytics events
        $page_analytics = $this->session->flashdata('page_analytics');
        if (! empty($page_analytics)) {
            $this->headerdata['page_analytics'] = $page_analytics;
        }
    }

    public function index()
    {
        $this->load->model('projects_model');
        $my_projects = $this->projects_model->my_projects($this->uid);
        $my_project_ids = flatten_assoc($my_projects, null, 'id');
        $my_projects_count = count($my_projects);

        // Key Executives
        $this->load->model('expertise_model');
        $key_executives = array();

        if ($my_projects_count > 0) {
            // Add similar projects' ids and limit the number of ids to 3
        }
        // If the user don't have My projects or there are no matching experts
        // Default to getting random experts
        if (empty($key_executives)) {
            $data = $this->expertise_model->get_filter_user_list2(3, rand(1, 500));
            $key_executives = $data['filter'];
        }

        // New Experts
        $new_experts = $this->expertise_model->get_new_experts(array($this->uid));
        // GViP Store Items
        $this->load->model('store_items_model');
        $store_items = $this->store_items_model->all();

        // My Experts (Experts that I follow)
        $this->load->model('members_model');
        $my_experts = $this->members_model->my_experts($this->uid);

        // PCI
        $pci = $this->expertise_model->get_pci($this->uid);
        $show_pci = ! empty($pci['show']) && (bool) $pci['show'];

        // My discussions
        $this->load->model('discussions_model');
        $my_discussions = $this->discussions_model->my_discussions($this->uid);

        $data = compact(
            'my_projects',
            'store_items',
            'key_executives',
            'new_experts',
            'my_discussions'
        );

        $this->set_headers();
        $this->set_breadcrumb();

        // Inject map data into the footer
        $map = $this->initialize_map();
        $this->footer_data['footer_extra'] = $this->load->view('mygvip/_footer_extra', compact('map'), true);

        $this->load->view('templates/header', $this->headerdata);

        if ($show_pci) {
            $this->load->view('templates/_pci_meter', $pci);
        }

        $this->load->view('templates/_map_assets', '');
        $this->load->view('templates/_map_templates', '');
        $this->load->view('mygvip/index', $data);
        $this->load->view('templates/footer', $this->footer_data);

    }

    public function mydiscussions()
    {
        $limit = view_check_limit($this->input->get_post('limit', TRUE));
        $offset	= $this->input->get_post('per_page', TRUE);
        if (empty($offset)) {
            $offset = 0;
        }

        $filter = array('member_id' => $this->uid);
        $this->load->model('discussions_model');
        $discussions = $this->discussions_model->all($limit, $offset, $filter);
        $total = count($discussions) > 0 ? $discussions[0]['row_count'] : 0;

        $config = array(
            'base_url' => "/mygvip/mydiscussions?limit=$limit",
            'total_rows' => $total,
            'per_page' => $limit,
            'num_links' => 1,
            'next_link' => lang('Next') . '  ' . '&gt;',
            'prev_link' => '&lt;' . '  ' . lang('Prev'),
            'first_link' => lang('First'),
            'last_link' =>  lang('Last'),
            'page_query_string' => TRUE
        );
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'discussions' => $discussions,
            'total_rows' => $total,
            'limit'      => $limit,
            'paging'     => $this->pagination->create_links(),
            'page_from'  => $offset + 1,
            'page_to'    => ($offset + $limit <= $total) ? $offset + $limit : $total
        );

        $this->set_mydiscussions_headers();
        $this->set_mydiscussions_breadcrumb();

        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('mygvip/mydiscussions', $data);
        $this->load->view('templates/footer', $this->footer_data);
    }

    /**
     * Display a paginated list of my followers (experts)
     *
     */
    public function myfollowers()
    {
        $limit = view_check_limit($this->input->get_post('limit', TRUE));
        $offset	= $this->input->get_post('per_page', TRUE);
        if (empty($offset)) {
            $offset = 0;
        }
        $sort = $this->check_sort($this->input->get_post('sort', TRUE));
        $this->load->model('members_model');
        $experts = $this->members_model->my_followers($this->uid, $limit, $offset);
        $total = count($experts) > 0 ? (int) $experts[0]['row_count'] : 0;

        $config = array(
            'base_url' => '/mygvip/myfollowers?' . http_build_query(compact('limit')),
            'total_rows' => $total,
            'per_page' => $limit,
            'num_links' => 1,
            'next_link' => lang('Next') . '  ' . '&gt;',
            'prev_link' => '&lt;' . '  ' . lang('Prev'),
            'first_link' => lang('First'),
            'last_link' =>  lang('Last'),
            'page_query_string' => TRUE
        );
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'title'     => mb_convert_case(lang('MyVipMyFollowers'), MB_CASE_TITLE),
            'experts'   => $experts,
            'total_rows' => $total,
            'sort_options' => $this->sort_options,
            'sort'         => $sort,
            'limit'     => $limit,
            'paging'    => $this->pagination->create_links(),
            'page_from' => $offset + 1,
            'page_to'   => ($offset + $limit <= $total) ? $offset + $limit : $total,
        );

        $this->set_myfollowers_headers();
        $this->set_myfollowers_breadcrumb();

        // Render the page
        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('mygvip/experts', $data);
        $this->load->view('templates/footer', $this->footer_data);
    }

    /**
     * Display a paginated list of experts that I'm following
     *
     */
    public function myexperts()
    {
        $limit = view_check_limit($this->input->get_post('limit', TRUE));
        $offset	= $this->input->get_post('per_page', TRUE);
        if (empty($offset)) {
            $offset = 0;
        }
        $sort = $this->check_sort($this->input->get_post('sort', TRUE));
        $this->load->model('expertise_model');
        $experts = $this->expertise_model->myexperts($this->uid, $limit, $offset);
        $total = count($experts) > 0 ? (int) $experts[0]['row_count'] : 0;

        $config = array(
            'base_url' => '/mygvip/myexperts?' . http_build_query(compact('limit')),
            'total_rows' => $total,
            'per_page' => $limit,
            'num_links' => 1,
            'next_link' => lang('Next') . '  ' . '&gt;',
            'prev_link' => '&lt;' . '  ' . lang('Prev'),
            'first_link' => lang('First'),
            'last_link' =>  lang('Last'),
            'page_query_string' => TRUE
        );
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'title'     => mb_convert_case(lang('MyVipMyExperts'), MB_CASE_TITLE),
            'experts'   => $experts,
            'total_rows' => $total,
            'sort_options' => $this->sort_options,
            'sort'         => $sort,
            'limit'     => $limit,
            'paging'    => $this->pagination->create_links(),
            'page_from' => $offset + 1,
            'page_to'   => ($offset + $limit <= $total) ? $offset + $limit : $total,
        );

        $this->set_myexperts_headers();
        $this->set_myexperts_breadcrumb();

        // Render the page
        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('mygvip/experts', $data);
        $this->load->view('templates/footer', $this->footer_data);
    }

    /**
     * Display a paginated list of my projects
     *
     */
    public function myprojects()
    {
        $perpage =	12;

        $page = $this->input->get_post('per_page', TRUE);
        $page = (! $page) ? 0 : $page;

        $scope = $this->input->get_post('scope', TRUE);
        $scope = (! $scope) ? 'all' : $scope;

        $filterby = array_filter(compact('scope'));

        $this->load->model('projects_model');
        $rows = $this->projects_model->all_my_projects($this->uid, $scope, $perpage, $page, true);
        $total = (count($rows) > 0) ? $rows[0]['row_count'] : 0;

        $config = array (
            'base_url'   => '/mygvip/myprojects?scope=' . urlencode($scope),
            'total_rows' => $total,
            'per_page'   => $perpage,
            'next_link'	 => lang('Next') . '  ' . '&gt;',
            'prev_link'  => '&lt;' . '  ' . lang('Prev'),
            'first_link' => FALSE,
            'last_link'  => FALSE,
            'page_query_string' => TRUE
        );
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $pages = $page != '' ? $page : 0;
        $page_from = ($total < 1) ? 0 : ($pages + 1);
        $page_to = (($pages + $perpage) <= $total) ? ($pages + $perpage) : $total;

        $data = array(
            'rows'       => $rows,
            'total_rows' => $total,
            'filter_by'  => $filterby,
            'paging'     => $this->pagination->create_links(),
            'page_from'  => $page_from,
            'page_to'    => $page_to
        );

        $this->set_myprojects_headers();
        $this->set_myprojects_breadcrumb();

        // Render the page
        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('mygvip/myprojects', $data);
        $this->load->view('templates/footer', $this->footer_data);
    }

    /**
     * Initialize the map
     */
    private function initialize_map()
    {
        //session data
        $session_map = false;

        if (isset($this->session->userdata['map']) &&
            isset($this->session->userdata['map']['zoom'])) {

            $m = $this->session->userdata['map'];

            $session_map = array(
                'zoom' => $m['zoom'],
                'lat'  => $m['lat'],
                'lng'  => $m['lng'],
                'searchtype' => 'projects', // $m['type'], // 'myprojects',
                'filters' => isset($m['filters']) ? $m['filters'] : false,
                'forum' => false
            );

            foreach ($session_map as $key => $value) {
                if ($value == false) unset($session_map[$key]);
            }
        }

        return json_encode($session_map);
    }

    private function set_headers()
    {
        //Set Header Data for this page like title,bodyid etc
        $this->headerdata['bodyid'] = 'myvip';
        $this->headerdata['bodyclass'] = '';
        $this->headerdata['title'] = build_title(lang('myVip'));
    }

    private function set_myprojects_headers()
    {
        //Set Header Data for this page like title,bodyid etc
        $this->headerdata['bodyid'] = 'myprojects';
        $this->headerdata['bodyclass'] = '';
        $this->headerdata['title'] = build_title(mb_convert_case(lang('MyVipMyProjects'), MB_CASE_TITLE));
        $this->headerdata['header_extra'] = '';
    }

    private function set_myfollowers_headers()
    {
        //Set Header Data for this page like title,bodyid etc
        $this->headerdata['bodyid'] = 'myfollowers';
        $this->headerdata['bodyclass'] = '';
        $this->headerdata['title'] = build_title(mb_convert_case(lang('MyVipMyFollowers'), MB_CASE_TITLE));
        $this->headerdata['header_extra'] = '';
    }

    private function set_myexperts_headers()
    {
        //Set Header Data for this page like title,bodyid etc
        $this->headerdata['bodyid'] = 'myexperts';
        $this->headerdata['bodyclass'] = '';
        $this->headerdata['title'] = build_title(mb_convert_case(lang('MyVipMyExperts'), MB_CASE_TITLE));
        $this->headerdata['header_extra'] = '';
    }

    private function set_mydiscussions_headers()
    {
        //Set Header Data for this page like title,bodyid etc
        $this->headerdata['bodyid'] = 'mydiscussions';
        $this->headerdata['bodyclass'] = '';
        $this->headerdata['title'] = build_title(mb_convert_case(lang('MyVipMyDiscussions'), MB_CASE_TITLE));
        $this->headerdata['header_extra'] = '';
    }

    private function set_breadcrumb()
    {
        $this->load->library('breadcrumb');
        $this->breadcrumb->append_crumb(lang('myVip'), "/myvip");
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();
    }

    private function set_myprojects_breadcrumb()
    {
        $this->load->library('breadcrumb');
        $this->breadcrumb->append_crumb(lang('myVip'), '/myvip');
        $this->breadcrumb->append_crumb(mb_convert_case(lang('MyVipMyProjects'), MB_CASE_TITLE), '/mygvip/myprojects');
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();
    }

    private function set_myfollowers_breadcrumb()
    {
        $this->load->library('breadcrumb');
        $this->breadcrumb->append_crumb(lang('myVip'), '/myvip');
        $this->breadcrumb->append_crumb(mb_convert_case(lang('MyVipMyFollowers'), MB_CASE_TITLE), '/mygvip/myfollowers');
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();
    }
    private function set_myexperts_breadcrumb()
    {
        $this->load->library('breadcrumb');
        $this->breadcrumb->append_crumb(lang('myVip'), '/myvip');
        $this->breadcrumb->append_crumb(mb_convert_case(lang('MyVipMyExperts'), MB_CASE_TITLE), '/mygvip/myexperts');
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();
    }

    private function set_mydiscussions_breadcrumb()
    {
        $this->load->library('breadcrumb');
        $this->breadcrumb->append_crumb(lang('myVip'), '/myvip');
        $this->breadcrumb->append_crumb(mb_convert_case(lang('MyVipMyDiscussions'), MB_CASE_TITLE), '/mygvip/mydiscussions');
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();
    }

    private function check_sort($value) {
        $allowed = array_keys($this->sort_options);
        $default = 1;

        if (in_array($value, $allowed)) {
            return $value;
        } else {
            return $default;
        }
    }
}



