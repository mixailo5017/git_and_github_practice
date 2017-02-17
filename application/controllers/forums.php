<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Forums extends CI_Controller {

    //public class variables
    protected $headerdata = array();
    protected $footer_data = array();

    public function __construct() {

        parent::__construct();

        $languageSession = sess_var('lang');
        get_language_file($languageSession);

        // If the user is not logged in then redirect to the login page
        auth_check();

        //Load the default model for this controller
        $this->load->model('forums_model');

        // Load breadcrumb library
        $this->load->library('breadcrumb');

        // Set Header Data for this page like title,bodyid etc
        $this->headerdata['bodyid'] = 'forum';
        $this->headerdata['bodyclass'] = '';
        $this->headerdata['title'] = build_title(lang('forums'));

        $this->output->enable_profiler(FALSE);

        $this->footer_data['lang'] = langGet();
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

        // If the current user doesn't have access to the forum show 404
        if (! $this->forums_model->has_access_to(Auth::id(), $id)) {
            show_404();
        }

        // If there is a meeting URL (for private meetings bookings), append user's details to the meeting URL query string
        if ($forum['meeting_url']) {
            $authenticatedUserID = Auth::id();
            $this->load->model('expertise_model');
            $authenticatedUser = $this->expertise_model->get_user($authenticatedUserID);
            $forum['meeting_url'] .= '?name=' . urlencode($authenticatedUser['firstname'] . ' ' . $authenticatedUser['lastname'])
                . '&email=' . urlencode($authenticatedUser['email'])
                . '&company=' . urlencode($authenticatedUser['organization']);
        }

        // Fetch projects and members (experts) accociated with the forum
        $projects = $model->projects($id, 'pid, slug, projectname, projectphoto, p.sector, p.country', array('p.id' => 'random'), FORUM_PROJECT_LIMIT, 0, true);
        $members = $model->members($id, 'm.uid, firstname, lastname, userphoto, m.title, organization', array('m.id' => 'random'), FORUM_EXPERT_LIMIT, 0, true);

        // List of all other forums for navigation bar
        $forums_by_categories = $model->all_by_categories($id);

        $data = array(
            'projects' => array(
                'rows' => $projects,
                'total_rows' => (count($projects) > 0) ? $projects[0]['row_count'] : 0
            ),
            'members' => array(
                'rows' => $members,
                'total_rows' => (count($members) > 0) ? $members[0]['row_count'] : 0
            ),
            'details' => $forum,
            'forums_by_categories' => $forums_by_categories
        );

        // No Breadcrumb for this page

        // Set the default coordinates for the map to the coordinates of the forum's venue
        $coordinates = array();
        if (isset($forum['venue_lat']) && isset($forum['venue_lng'])) {
            $coordinates = array(
                'lat' => $forum['venue_lat'],
                'lng' => $forum['venue_lng'],
            );
        }
        $map = $this->initialize_map($id, $coordinates);
        $this->footer_data['footer_extra'] = $this->load->view('forums/_footer_extra', compact('map'), true);

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
        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('templates/_map_assets', '');
        $this->load->view('templates/_map_templates', '');
        $this->load->view('forums/show', $data);
        $this->load->view('templates/footer', $this->footer_data);
    }

    /**
     * Display a paginated list of forums
     *
     */
    public function index() {


        $limit = view_check_limit($this->input->get_post('limit', TRUE));
        $offset = $this->input->get_post('per_page', TRUE);
        if (empty($offset)) {
            $offset = 0;
        }

        $select = 'f.id, title, start_date, end_date, category_id, fc.name AS category, venue, venue_url, register_url, meeting_url, photo, is_featured';

        // Fetch a featured forum if any
        $where = array(
            'end_date >=' => date('Y-m-d'),
            'is_featured' => '1',
            'status' => STATUS_ACTIVE
        );
        $order_by = array(
            'start_date' => 'asc',
            'end_date' => 'asc'
        );

        $featured = $this->forums_model->all($where, $select, $order_by, 1, null, false);
        $featured = (is_array($featured) && count($featured) == 1) ? $featured[0] : null;


        $category   = $this->input->get_post('category', TRUE);
        $scope      = $this->input->get_post('scope', TRUE);
        $searchtext = $this->input->get_post('search_text', TRUE);


        $filterby = array_filter(compact('scope', 'category', 'searchtext'));

        // Fetch forums applying filters and limit for pagination
        $where = array('status' => STATUS_ACTIVE);

        if ($category) {
            $where['category_id'] = (int) trim($category, '[]');
        }
        if (! $scope) {
            $scope = 'all'; //'upcoming';
        }
        if ($scope != 'all') {
            $where['end_date ' . (($scope == 'upcoming') ? '>=' : '<')] = date('Y-m-d');
        }
        if (! empty($searchtext)) {
            $terms = split_terms($searchtext);
            $columns = 'title';
            $where[] = where_like($columns, $terms);
        }

        // Hide the emergency US projects forum page (for Donald Trump), unless user is a CG/LA employee
        if (! $this->forums_model->has_access_to(Auth::id(), EMERGENCY_PROJECTS_FORUM_ID)) {
            $where['f.id !='] = EMERGENCY_PROJECTS_FORUM_ID;
        }

        $order_by = array(
            'start_date' => 'desc',
            'end_date' => 'desc'
        );

        $rows = $this->forums_model->all($where, $select, $order_by, $limit, $offset, true);
        $total = (count($rows) > 0) ? $rows[0]['row_count'] : 0;
        $categories = $this->forums_model->categories();
        $config = array(
            'base_url' 	 => '/forums?scope=' . urlencode($scope) .
                            '&category=' . urlencode($category) .
                            '&search_text' . urlencode($searchtext) .
                            '&limit=' . urlencode($limit),
            'total_rows' => $total,
            'per_page' 	 => $limit,
            'next_link'	 => lang('Next') . '  ' . '&gt;',
            'prev_link'  => '&lt;' . '  ' . lang('Prev'),
            'first_link' => lang('First'),
            'last_link' =>  lang('Last'),
            'page_query_string' => TRUE
        );
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $pages = $offset != '' ? $offset : 0;
        $page_from = ($total < 1) ? 0 : ($pages + 1);
        $page_to = (($pages + $limit) <= $total) ? ($pages + $limit) : $total;

        $data = array(
            'rows' => $rows,
            'total_rows' => $total,
            'categories' => flatten_assoc($categories, 'id', 'name', '[', ']'),
            'filter_by' => $filterby,
            'paging'   => $this->pagination->create_links(),
            'page_from' => $page_from,
            'page_to'   => $page_to
            
        );
        if (! is_null($featured)) {
            $data['featured'] = $featured;
        }

        $this->breadcrumb->append_crumb(lang('B_FORUMS'), '/forums');
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        // Render the page
        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('forums/index', $data);
        $this->load->view('templates/footer', $this->footer_data);
    }

    /**
     * Display a paginated list of all projects associated with the forum
     *
     * @param $id
     */
    public function projects($id) {

        $perpage =	12;
        $page = $this->input->get_post('per_page', TRUE);

        $details = $this->forums_model->find($id, 'f.id, title');

        if (empty($details)) {
            redirect('forums', 'refresh');
            exit;
        }

        $rows = $this->forums_model->projects($id, 'pid, slug, projectname, projectphoto, p.country, p.sector, stage', null, $perpage, $page, true);
        $total = (count($rows) > 0) ? $rows[0]['row_count'] : 0;

        $config = array (
            'base_url'   => "/forums/projects/$id?",
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

        $this->breadcrumb->append_crumb(lang('B_FORUMS'), '/forums');
        $this->breadcrumb->append_crumb($details['title'], '/forums/' . $details['id']);
        $this->breadcrumb->append_crumb(lang('B_PROJECTS'), '/forums/projects/' . $details['id']);
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        $this->headerdata['title'] = build_title(lang('ForumProjects'));

        // Render the page
        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('forums/projects', $data);
        $this->load->view('templates/footer', $this->footer_data);

    }

    /**
     * Display a paginated list of all experts (members) associated with (attending) the forum
     *
     * @param $id
     */
    public function experts($id) {
    
        $limit = view_check_limit($this->input->get_post('limit', TRUE));
        $offset = $this->input->get_post('per_page', TRUE);
        $details = $this->forums_model->find($id, 'f.id, title');

        if (empty($details))
        {
            redirect('forums', 'refresh');        
            exit;     
        }

        if (empty($offset)) {
            $offset = 0;
        }
        $filter = array(
            'country' => $this->input->get_post('country', TRUE),
            'sector' => $this->input->get_post('sector', TRUE),
            'subsector' => $this->input->get_post('subsector', TRUE),
            'searchtext' => $this->input->get_post('searchtext', TRUE),
            'discipline' => $this->input->get_post('discipline', TRUE),
        );
        array_walk($filter, function(&$value, $key) {
            $value = $value ? : '';
        });

        $users = $this->forums_model->get_filter_user_list2($id, $limit, $offset, $filter, MEMBER_TYPE_MEMBER, null);
		$total = $users['filter_total'];
		
		/* This fixes the 1 - 0 error if no users are found make offset 0*/ 
		if ($total == 0 ){
    		$offset = -1;
		}
		$sector_data = sector_subsectors();
        $subsectors = array();
        if (! empty($subsector)) {
            if (isset($sector_data[$subsector])) {
                $subsectors = $sector_data[$subsector];
            }
        }
 			
        $config = array(
            'base_url'   => '/forums/experts/'.$id.'?'.http_build_query(array_merge($filter, compact('sort', 'limit'))),
            'total_rows' => $total,
            'num_links' => 1,
            'per_page'   => $limit,
            'next_link'	 => lang('Next') . '  ' . '&gt;',
            'prev_link'  => '&lt;' . '  ' . lang('Prev'),
            'first_link' => lang('First'),
            'last_link' =>  lang('Last'),
            'page_query_string' => TRUE
        );
        
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $data = array(
            'users'         => $users['filter'],
            'filter_total'  => $total,
            'filter'        => $filter,
            'sectors'       => array_keys($sector_data),
            'subsectors'    => $subsectors,
            'all_subsectors'=> $sector_data,
            'filter_total'  => $total,
            'iduser'        => $id,
            'limit'         => $limit,
            'paging'        => $this->pagination->create_links(),
            'page_from'     => $offset+1,
            'page_to'       => ($offset + $limit <= $total) ? $offset + $limit : $total
        );

        $this->breadcrumb->append_crumb(lang('B_FORUMS'), '/forums');
        $this->breadcrumb->append_crumb($details['title'], '/forums/' . $details['id']);
        $this->breadcrumb->append_crumb(lang('B_EXPERTS'), '/forums/experts/' . $details['id']);
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        $this->headerdata['title'] = build_title(lang('ExpertAttendees'));

    	// Analytics
        // Check if we have any search filters setup
        if (count(array_filter($filter)) > 0) {
            $event_properties = array(
                'Category' => 'Forum Attendee',
                'Found' => $total
            );
            foreach ($filter as $key => $value) {
                if (! empty($value)) {
                    $event_properties[ucfirst($key)] = $value;
                }
            }

            $this->headerdata['page_analytics'] = array(
                'event' => array(
                    'name' => 'Searched',
                    'properties' => $event_properties
                )
            );
        }
        
        // Render the page
        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('forums/experts', $data);
        $this->load->view('templates/footer', $this->footer_data);
    }

    /**
     * @param $id
     * @param array $coordinates Should look like array('lat' => 43.123456, 'lng' => 72.123456)
     * @return string
     */
    private function initialize_map($id, $coordinates = array()) {
//        $session_map = false;
//        if (isset($this->session->userdata['map']) &&
//            isset($this->session->userdata['map']['zoom'])) {
//
//            $m = $this->session->userdata['map'];
//            $session_map = array(
//                'zoom'		=> $m['zoom'],
//                'lat'		=> $m['lat'],
//                'lng'		=> $m['lng'],
//                'searchtype'=> $m['type'],
//                'filters'	=> isset($m['filters']) ? $m['filters'] : false,
//                // Pass in flag telling that we are using map search for projects and experts asocciated with the forum
//                'forum'		=> true,
//                // Pass in forum id to be used to filter search results for projects and experts through /api/search/map_search
//                'forum_id'  => $id
//            );
//            foreach ($session_map as $key => $value) {
//                if($value == false) unset($session_map[$key]);
//            }
//        }
//        $init['map'] = json_encode($session_map);

        $forum_map = array(
            // Pass in flag telling that we are using map search for projects and experts asocciated with the forum
            'forum'	=> true,
            // Pass in forum id to be used to filter search results for projects and experts through /api/search/map_search
            'forum_id' => $id
        );

        if (! empty($coordinates)) {
            $forum_map = array_merge($forum_map, $coordinates);
        }

        return json_encode($forum_map);
    }
}