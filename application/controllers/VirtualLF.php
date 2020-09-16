<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class VirtualLF extends CI_Controller
{

    //public class variables
    protected $headerdata = array();
    protected $footer_data = array();

    private $sort_options;

    public function __construct()
    {

        parent::__construct();

        $languageSession = sess_var('lang');
        get_language_file($languageSession);

        //Load the default model for this controller
        $this->load->model('forums_model');
        $this->load->model('expertise_model');


        // If the user is not logged in then redirect to the login page
        auth_check();
        
        
        // Load breadcrumb library
        $this->load->library('breadcrumb');

        // Set Header Data for this page like title,bodyid etc
        $this->headerdata['bodyid'] = 'forum';
        $this->headerdata['bodyclass'] = '';
        $this->headerdata['title'] = build_title(lang('forums'));

        $this->output->enable_profiler(FALSE);

        $this->footer_data['lang'] = langGet();

        $this->sort_options = array(
            1 => 'Sort Alphabetically',
            2 => 'Total Value Descending',
            3 => 'Random'

        );
    }

    /**
     * View Method
     * Load Individual Detail Page
     * @param $id
     */
    public function show($id)
    {

        $id = (int) $id;

        $model = $this->forums_model;

        $forum = $model->find($id);
        // If we can't find a forum redirect to the forums list view
        if (empty($forum)) {
            redirect('forums', 'refresh');
            exit;
        }
        // Prevent the forum in a draft status to be shown
        if (isset($forum['status']) && $forum['status'] != STATUS_ACTIVE) {
            redirect('forums', 'refresh');
            exit;
        }

        $data = array(
            'details' => $forum,
            'sort_options' => $this->sort_options,

        );

        $this->headerdata['title'] = build_title($forum['title']);

        // Provide page analitics data for Segment Analitics
        $this->headerdata['page_analytics'] = array(
            'category' => 'Forum',
            'properties' => array(
                'Target Id' => $id,
                'Target Name' => $forum['title']
            )
        );


        // Render the page
        $this->load->view('virtualLF/header_stimulus', $this->headerdata);
        $this->load->view('virtualLF/index', $data);
        $this->load->view('templates/footer', $this->footer_data);
    }

    /**
     * Display a paginated list of all projects associated with the forum
     *
     * @param $id
     */
    public function projects($id) {

        // If the user is not logged in then redirect to the login page
        

        $perpage =	12;
        $page = $this->input->get_post('per_page', TRUE);

        $details = $this->forums_model->find($id, 'f.id, title');

        if (empty($details)) {
            redirect('virtualLF', 'refresh');
            exit;
        }

        $rows = $this->forums_model->projects($id, 'pid, slug, projectname, projectphoto, p.country, p.sector, stage', null, $perpage, $page, true);
        $total = (count($rows) > 0) ? $rows[0]['row_count'] : 0;

        $config = array (
            'base_url'   => "/virtualLF/projects/$id?",
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

        $data	=	array(
            'rows'       => $rows,
            'total_rows' => $total,
            'paging'     => $this->pagination->create_links(),
            'page_from'  => $page_from,
            'page_to'    => $page_to
        );

        $this->breadcrumb->append_crumb(lang('B_FORUMS'), '/virtualLF');
        $this->breadcrumb->append_crumb($details['title'], '/virtualLF/' . $details['id']);
        $this->breadcrumb->append_crumb(lang('B_PROJECTS'), '/virtualLF/projects/' . $details['id']);
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        $this->headerdata['title'] = build_title(lang('ForumProjects'));

        // Render the page
        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('virtualLF/projects', $data);
        $this->load->view('templates/footer', $this->footer_data);

    }
    private function check_sort($value)
    {
        $allowed = array_keys($this->sort_options);
        $default = 3;

        if (in_array($value, $allowed)) {
            return $value;
        } else {
            return $default;
        }
    }

    /**
     * View Method
     * Load Individual Detail Page
     * @param $id
     */
    public function map($id)
    {
        $id = (int) $id;

        $model = $this->forums_model;

        $forum = $model->find($id);
        // If we can't find a forum redirect to the forums list view
        if (empty($forum)) {
            redirect('forums', 'refresh');
            exit;
        }

        $sort = $this->check_sort($this->input->get_post('sort', true));


        $filter = array(
            'state' => $this->input->get_post('state', true),
            'sector' => $this->input->get_post('sector', true),
            'subsector' => $this->input->get_post('subsector', true),
            'stage' => $this->input->get_post('stage', true),
            'searchtext' => $this->input->get_post('searchtext', true)
        );
        array_walk($filter, function (&$value, $key) {
            $value = $value ? : '';
        });

        $sector_data = sector_subsectors();
        $subsectors = array();
        if (! empty($subsector)) {
            if (isset($sector_data[$subsector])) {
                $subsectors = $sector_data[$subsector];
            }
        }

        // Fetch projects and members (experts) accociated with the forum
        $projects = $model->projects($id, 'pid, slug, projectname, projectphoto, p.sector, p.country, p.lat, p.lng, p.totalbudget, p.sponsor, p.stage, p.subsector, p.location, p.description', array('p.id' => 'random'), 700, 0, true, $filter, $sort);

        $this->load->model('projects_model');

        $data = array(
            'projects' => array(
                'rows' => $projects,
                'total_rows' => (count($projects) > 0) ? $projects[0]['row_count'] : 0,

            ),
            'details' => $forum,
            'filter'       => $filter,
            'sort'       => $sort,
            'sort_options' => $this->sort_options,
            'subsectors' => $subsectors,
            'all_subsectors'   => $sector_data,
            'model_obj' => $this->projects_model,

        );

        $this->headerdata['title'] = build_title($forum['title']);

        // Render the page
        $this->load->view('virtualLF/header_stimulus', $this->headerdata);
        $this->load->view('virtualLF/show', $data);
        $this->load->view('templates/footer', $this->footer_data);
    }

    /**
     * View Method
     * Load Individual Detail Page
     * @param $id
     */
    public function press()
    {

        // If the current user doesn't have access to the forum show 404
            if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != 'press' || $_SERVER['PHP_AUTH_PW'] != 'blueprint2020') {
                header('WWW-Authenticate: Basic realm="MyProject"');
                header('HTTP/1.0 401 Unauthorized');
                die('Access Denied');
            }

        $id = 37;

        $model = $this->forums_model;

        $forum = $model->find($id);
        // If we can't find a forum redirect to the forums list view
        if (empty($forum)) {
            redirect('forums', 'refresh');
            exit;
        }
        // Prevent the forum in a draft status to be shown
        if (isset($forum['status']) && $forum['status'] != STATUS_ACTIVE) {
            redirect('forums', 'refresh');
            exit;
        }

        $data = array(
            'details' => $forum,
            'sort_options' => $this->sort_options,

        );

        $this->headerdata['title'] = build_title($forum['title']);

        // Provide page analitics data for Segment Analitics
        $this->headerdata['page_analytics'] = array(
            'category' => 'Forum',
            'properties' => array(
                'Target Id' => $id,
                'Target Name' => $forum['title']
            )
        );


        // Render the page
        $this->load->view('virtualLF/header_stimulus', $this->headerdata);
        $this->load->view('virtualLF/index', $data);
        $this->load->view('templates/footer', $this->footer_data);
    }

    /**
     * View Method
     * Load Individual Detail Page
     * @param $id
     */
    public function speaker()
    {

        // If the current user doesn't have access to the forum show 404
        if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != 'speaker' || $_SERVER['PHP_AUTH_PW'] != 'blueprint2020') {
            header('WWW-Authenticate: Basic realm="MyProject"');
            header('HTTP/1.0 401 Unauthorized');
            die('Access Denied');
        }

        $id = 37;

        $model = $this->forums_model;

        $forum = $model->find($id);
        // If we can't find a forum redirect to the forums list view
        if (empty($forum)) {
            redirect('forums', 'refresh');
            exit;
        }
        // Prevent the forum in a draft status to be shown
        if (isset($forum['status']) && $forum['status'] != STATUS_ACTIVE) {
            redirect('forums', 'refresh');
            exit;
        }

        $data = array(
            'details' => $forum,
            'sort_options' => $this->sort_options,

        );

        $this->headerdata['title'] = build_title($forum['title']);

        // Provide page analitics data for Segment Analitics
        $this->headerdata['page_analytics'] = array(
            'category' => 'Forum',
            'properties' => array(
                'Target Id' => $id,
                'Target Name' => $forum['title']
            )
        );


        // Render the page
        $this->load->view('virtualLF/header_stimulus', $this->headerdata);
        $this->load->view('virtualLF/index', $data);
        $this->load->view('templates/footer', $this->footer_data);
    }
    
    public function privatemeetings()
    {

        // Render the page
        $this->load->view('virtualLF/header_stimulus');
        $this->load->view('virtualLF/privatemeetings');
        $this->load->view('templates/footer');
    }

}
