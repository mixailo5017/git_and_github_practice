<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Projects extends CI_Controller
{
    private $member_project_lib;
     
    //public class variables
    public $headerdata    = array();
    public $uid            = '';
    public $pid            = '';
    public $dataLang    = array();

    private $sort_options;

    public function __construct()
    {
        parent::__construct();

        $languageSession = sess_var('lang');
        get_language_file($languageSession);
        $this->dataLang['lang'] = langGet();
        
        // If the user is not logged in then redirect to the login page
        auth_check();

        //Load the default model for this controller
        $this->load->model('projects_model');
        
        //load form_validation library for default validation methods
        $this->load->library('form_validation');
        //load breadcrumb library
        $this->load->library('breadcrumb');
        
        //Set Header Data for this page like title,bodyid etc
        $this->headerdata['bodyid'] = 'projects';
        $this->headerdata['bodyclass'] = '';
        $this->headerdata['title'] = build_title(lang('projects'));

        $this->uid = (int) sess_var('uid');
//		$this->output->enable_profiler(FALSE);

        $this->headerdata['header_extra'] = $this->load->view('projects/header_extra', '', true);
        $this->footer_data['lang'] = $this->dataLang['lang'];
        $this->footer_data['footer_extra'] = $this->load->view('projects/footer_extra', '', true);

        $this->sort_options = array(
            1 => lang('SortAlphabetically'),
        );

        // TODO: Revisit this logic to use array of events
        // deffered through flashdata
        $page_analytics = $this->session->flashdata('page_analytics');
        if (! empty($page_analytics)) {
            $this->headerdata['page_analytics'] = $page_analytics;
        }
    }

    public function create_discussion($id)
    {
        // Process POST request first
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->load->library('form_validation');
            $this->set_create_discussion_validation_rules();

            if ($this->form_validation->run() === true) {
                $input = $this->input->post(null, true);
                $input['project_id'] = (int) $id;

                $this->load->model('discussions_model');
                if ($discussion_id = $this->discussions_model->create($input)) {
                    redirect("/projects/discussions/$id/$discussion_id", 'refresh');
                }
            }
        }

        $project = $this->projects_model->find($id);
        // If we can't find the project by id
        if (empty($project)) {
            show_404();
        }

        $this->breadcrumb->append_crumb(lang('B_PROJECTS'), '/projects/');
        $this->breadcrumb->append_crumb($project['projectname'], "/projects/$id");
        $this->breadcrumb->append_crumb(lang('DiscussionAddNew'), "/projects/discussions/create/$id");

        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        $this->headerdata['title'] = build_title(lang('DiscussionCreate'));

        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('projects/discussion_create', compact('project'));
        $this->load->view('templates/footer', $this->footer_data);
    }

    public function discussion($id, $discussion_id)
    {
        $discussion_id = (int) $discussion_id;
        $this->load->model('discussions_model');
        $discussion = $this->discussions_model->find($discussion_id);
        // If we can't find the discussion by id
        if (empty($discussion)) {
            show_404();
        }

        // If the current user doesn't have access to the discussion show 404
        if (! $this->discussions_model->has_access_to($this->uid, $discussion_id)) {
            show_404();
        }

        $project = $this->projects_model->find($discussion['project_id']);
        // If we can't find the project by id
        if (empty($project)) {
            show_404();
        }

        $executive = $this->projects_model->get_user_general($project['uid']);

        $experts = $this->discussions_model->experts($discussion_id);

        $this->breadcrumb->append_crumb(lang('B_PROJECTS'), '/projects/');
        $this->breadcrumb->append_crumb($project['projectname'], '/projects/' . $discussion['project_id']);
        $this->breadcrumb->append_crumb(lang('Discussions'), '/projects/discussions/' . $discussion['project_id']);
        $this->breadcrumb->append_crumb($discussion['title'], '/projects/discussions/' . $discussion['project_id'] . '/' . $discussion_id);

        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        $this->headerdata['title'] = build_title($discussion['title']);

        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('projects/discussion', compact('project', 'discussion', 'executive', 'experts'));
        $this->load->view('templates/footer', $this->footer_data);
    }

    public function discussions($id)
    {
        $id = (int) $id;

        $project = $this->projects_model->find($id);
        // If we can't find a project by id
        if (empty($project)) {
            show_404();
        }

        $this->load->model('discussions_model');

        // If the current user doesn't have aceess to any of the project's discussions show 404
        if (! $this->discussions_model->has_access($this->uid, $id)) {
            show_404();
        }

        $limit = view_check_limit($this->input->get_post('limit', true));
        $offset    = $this->input->get_post('per_page', true);
        if (empty($offset)) {
            $offset = 0;
        }

        $filter = array('project_id' => $id, 'member_id' => $this->uid);
        $discussions = $this->discussions_model->all($limit, $offset, $filter);
        $total = count($discussions) > 0 ? $discussions[0]['row_count'] : 0;

        $config = array(
            'base_url' => "/projects/discussions/$id?limit=$limit",
            'total_rows' => $total,
            'per_page' => $limit,
            'num_links' => 1,
            'next_link' => lang('Next') . '  ' . '&gt;',
            'prev_link' => '&lt;' . '  ' . lang('Prev'),
            'first_link' => lang('First'),
            'last_link' =>  lang('Last'),
            'page_query_string' => true
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

        $this->breadcrumb->append_crumb(lang('B_PROJECTS'), '/projects/');
        $this->breadcrumb->append_crumb($project['projectname'], "/projects/$id");
        $this->breadcrumb->append_crumb(lang('Discussions'), "/projects/discussions/$id");
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        $this->headerdata['title'] = build_title($project['projectname']);

        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('projects/discussions', $data);
        $this->load->view('templates/footer', $this->footer_data);
    }

    /**
     * Makes a relationship between a project a currently logged in user (member)
     * User (member) follows the project
     *
     * @return bool
     */
    public function follow()
    {
        $model = $this->projects_model;

        $userid = (int) sess_var('uid');
        $id = (int) $this->input->post('id', true);

        if (! $result = $model->follow($id, $userid)) {
            sendResponse(array('status' => 'error'));
            exit;
        }

        $response = array('status' => 'success');

        if ($this->input->post('return_follows', true) == '1') {
            $response['follows'] = $model->follows($id);
        }

        // Analytics
        // Project name is not available to us at this point;
        // therefore we need to fetch it explicitly
        // TODO: Revisit and extract logic to reuse between follow and unfollow methods
        $project = $model->find($id, 'projectname');

        $page_analytics = array(
            'event' => array(
                'name' => 'Project Followed',
                'properties' => array(
                    'Project Id' => $id,
                    'Project Name' => $project['projectname']
                )
            )
        );
        $response['analytics'] = $page_analytics;

        sendResponse($response);
        exit;
    }

    /**
     * Deletes a relationship between a project a currently logged in user (member)
     * User (member) unfollows the project
     *
     * @return bool
     */
    public function unfollow()
    {
        $model = $this->projects_model;

        $userid = (int) sess_var('uid');
        $id = (int) $this->input->post('id', true);

        if (! $result = $model->unfollow($id, $userid)) {
            sendResponse(array('status' => 'error'));
            exit;
        }

        $response = array('status' => 'success');

        if ($this->input->post('return_follows', true) == '1') {
            $response['follows'] = $model->follows($id);
        }

        // Analytics
        // Project name is not available to us at this point;
        // therefore we need to fetch it explicitly
        // TODO: Revisit and extract logic to reuse between follow and unfollow methods
        $project = $model->find($id, 'projectname');

        $page_analytics = array(
            'event' => array(
                'name' => 'Project Unfollowed',
                'properties' => array(
                    'Project Id' => $id,
                    'Project Name' => $project['projectname']
                )
            )
        );
        $response['analytics'] = $page_analytics;

        sendResponse($response);
        exit;
    }

// 	public function index()
// 	{
// 		redirect("/projects/list", "refresh");
//	}

    /**
    * Handle the projects map draw functions
    *
    * @access public
    * @param string of the slug
    */
    public function update_map_draw($params)
    {

        // grab post data
        $gid        = $this->input->post('id', null);
        $action    = $this->input->post('action', false);
        $data        = $this->input->post('data', false);
        
        // echo "<pre>"; var_dump( $data ); exit;

        // check the project
        $slug    = $params;
        $uid    = $this->projects_model->get_uid_from_slug($slug);

        if (! $project = $this->projects_model->check_project($slug, true)) {
            // error
            show_404('page', 'log_error');
        }

        $pid = $project->pid;

        $this->form_validation->set_rules('geom', 'Geometry', 'trim|required');

        $response = array();
        $response["status"]    = "error";
        $response["message"]    = array();
        $response["isload"]    = "no";

        if ($this->form_validation->run() !== true) {
            // die( json_encode($response) );
        }

        // add new or update record
        if ($action === 'update') {
            $response = $this->projects_model->save_geom($pid, $gid, $data);
            die(json_encode($response));
        }

        // remove record
        if ($action === 'delete') {
            $response = $this->projects_model->delete_geom($pid, $gid);
            die(json_encode($response));
        }

        // error
        show_404('page', 'log_error');
    }

    /**
     * Load Individual Project Detail Page
     *
     * @param $params
     */
    public function view($params)
    {
        $model = $this->projects_model;

        // Allow for $params to be either a slug or an id
        if (is_numeric($params)) {
            $slug = $model->get_slug_from_pid((int) $params);
            if (! empty($slug)) {
                redirect("projects/$slug", 'refresh');
            }
        }

        // TODO: Revisit this logic and eliminate unnecessary call to DB
        $slug = $params;
        $userid    = $model->get_uid_from_slug($slug);
        $exist_slug = $model->check_project($slug);
        $pid = (int) $model->get_pid_from_slug($slug);

        if (! $exist_slug) {
            //			redirect('projects/list', 'refresh');
            redirect('/projects', 'location', 301);
            exit;
        }
        
        $viewdata = array();
        $viewdata['slug'] = $slug;
        $viewdata['project']['pid'] = $pid; // Needed for follow/unfollow functions
        $viewdata['userdata'] = $model->get_user_general($userid);

        // Check if user (the project owner) has been deleted
        if (empty($viewdata['userdata']['status']) || $viewdata['userdata']['status'] != STATUS_ACTIVE) {
            redirect('/projects', 'location', 301);
        }

        $viewdata['project']['isfollowing'] = $model->isfollowing($pid, $this->uid); // Is current user following the project
        $viewdata['project']['projectdata'] = $model->get_project_data($slug, $userid);
        $viewdata['project']['fundamental'] = $model->get_fundamental_data($slug, $userid);
        $viewdata['project']['financial'] = $model->get_financial_data($slug, $userid);
        $viewdata['project']['regulatory'] = $model->get_regulatory_data($slug, $userid);
        $viewdata['project']['participants'] = $model->get_participants_data($slug, $userid);
        $viewdata['project']['procurement'] = $model->get_procurement_data($slug, $userid);
        $viewdata['project']['files'] = $model->get_files_data($slug, $userid);
        $viewdata['project']['ad'] = $model->get_ad_data();
        $viewdata['project']['comment'] = $model->get_project_comment($slug, $userid);
        $viewdata['project']['assessment'] = $model->get_project_assessment($slug, $userid);

        // Generate a random number to display as the WEB score
        // $viewdata['project']['webscore'] = rand(150, 1000);

        // Global Experts and SME Experts are only visible to project owners
        $global_experts = array();
        $sme_experts = array();
        $similar_projects = array();
        if ($viewdata['userdata']['uid'] == $this->uid) {
            $this->load->model('expertise_model');
            // Global Experts
            $global_experts = $this->expertise_model->get_global_experts($pid, array($userid));
            // SME Service Providers
            $sme_experts = $this->expertise_model->get_sme_experts($pid, array($userid));
        } else {
            // Similar project are visible only for non project owners
            $similar_projects = $this->projects_model->similar_projects2($pid);
        }

        $viewdata['project']['topexperts'] = $global_experts;
        $viewdata['project']['smeexperts'] = $sme_experts;
        $viewdata['project']['similar_projects'] = $similar_projects;

        $viewdata['project']['organizationmatch'] = $model->get_proj_matches($slug, $userid);

        $viewdata['project']['isaddcomment'] = $userid == $this->uid ? true : false;
        $viewdata['project']['prettylocation'] = $model->get_city_state($slug, $userid);

        // Determine which sections of the project profile have data,
        // and hence should be displayed
        $viewdata['project_sections'] = [];
        if (! ($viewdata['project']['procurement']['totalprocurement'] == 0)) {
            $viewdata['project_sections']['procurement'] = true;   
        }
        if (! (($viewdata['project']['fundamental']['totalfundamental'] - count($viewdata['project']['fundamental']["map_point"])) == 0)) {
            $viewdata['project_sections']['fundamentals'] = true;
        }
        if (! ($viewdata['project']['financial']['totalfinancial'] == 0)) {
            $viewdata['project_sections']['financial'] = true;
        }
        if (! ($viewdata['project']['regulatory']['totalregulatory'] == 0)) {
            $viewdata['project_sections']['regulatory'] = true;
        }
        if (! ($viewdata['project']['participants']['totalparticipants'] == 0)) {
            $viewdata['project_sections']['participants'] = true;   
        }
        if (! ($viewdata['project']['files']['totalfiles'] == 0)) {
            $viewdata['project_sections']['files'] = true;   
        }

		$viewdata['isAdminorOwner'] = $this->isAdminOrOwner($userid);
		$viewdata['map_geom'] = $model->get_geom($pid);

        // Get the id of the Ligtning company
        $this->load->model('expertadvert_model');
        $viewdata['project']['lightning'] = $this->expertadvert_model->get_random();

        // Discussions
        $this->load->model('discussions_model');
        $viewdata['project']['discussions_access'] = $this->discussions_model->has_access($this->uid, $pid);

        $this->breadcrumb->append_crumb(lang('B_PROJECTS'), '/projects');
        $this->breadcrumb->append_crumb($viewdata['project']['projectdata']['projectname'], "/projects/$slug");
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        $this->headerdata['title'] = build_title($viewdata['project']['projectdata']['projectname']);

        // Provide page analitics data for Segment Analitics
        $this->headerdata['page_analytics'] = array(
            'category' => 'Project',
            'properties' => array(
                'Target Id' => $pid,
                'Target Name' => $viewdata['project']['projectdata']['projectname']
            )
        );

        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('projects/projects_view', $viewdata);
        $this->load->view('templates/footer', $this->footer_data);
    }
    
    /**
    * Create Method
    * Method call for Project Create form
    *
    * @access public
    */
    public function create()
    {
        // Don't allow create projects for Lightning companies with this method
        if (sess_var('usertype') == MEMBER_TYPE_EXPERT_ADVERT) {
            redirect(index_page(), 'refresh');
        }

        if ($this->input->post('create_project')) {
            $this->form_validation->set_rules('title', lang('NameofProject'), 'trim|required');
            if ($this->form_validation->run() === true) {
                $uid = (int) sess_var('uid');

                $projectname = $this->input->post('title', true);

                // add_project() method from Projets Model to add project and generate slug
                if ($pid = $this->projects_model->add_project($uid, $projectname)) {
                    // Retrieve a slug for the project
                    $project = $this->projects_model->find($pid, 'slug');

                    // Post a new update in the projects feed
                    $data = array(
                        'author' => $uid,
                        'target_type' => PROJECT_UPDATE,
                        'target_id' => (int) $pid,
                        'type' => UPDATE_TYPE_NEWPROJECT,
                        'created_at' => date('Y-m-d H:i:s'),
                        'content' => '',
                        'reply_to' => null
                    );
                    $this->load->model('updates_model');
                    $result = $this->updates_model->create($data);

                    // Analytics
                    $page_analytics = array(
                        'event' => array(
                            'name' => 'Project Created',
                            'properties' => array(
                                'Project Id' => (int) $pid,
                                'Project Name' => $projectname,
//                                'Organization' => ''
                            )
                        )
                    );
                    // Set flash data before redirect
                    $this->session->set_flashdata('page_analytics', $page_analytics);

                    //redirect to the newly created project edit page
                    redirect("/projects/edit/{$project['slug']}", 'refresh');
                }
            }
        }

        $this->breadcrumb->append_crumb(lang('B_PROJECTS'), '/projects');
        $this->breadcrumb->append_crumb(lang('AddNewProject'), 'create');
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        $this->headerdata['title'] = build_title(lang('AddNewProject'));

        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('projects/projects_create');
        $this->load->view('templates/footer', $this->dataLang);
    }
    
    /**
    * Edit Method
    * Method call for Project Edit form
    *
    * @access public
    */
    public function edit($params)
    {
        $slug = $params;
        $this->pid = $this->projects_model->check_user_project($slug, $this->uid);
        
        if (($this->pid == '') || (sess_var('usertype') == MEMBER_TYPE_EXPERT_ADVERT)) {
            redirect("/projects/$slug", 'refresh');
            exit;
        }
        $this->load->helper('string');
        $editdata['slug'] = $slug;
        
        //check if form posted or not to get project data
        if ($this->input->post('return') != '') {
            $this->form_validation->set_error_delimiters('<label>', '</label>');
            $this->form_validation->set_rules('project_overview', lang('Description'), 'trim|required');
            $this->form_validation->set_rules('project_keywords', lang('Keywords'), 'trim|required');
            $this->form_validation->set_rules('project_country', lang('Country'), 'required');
            $this->form_validation->set_rules('project_location', lang('Location'), 'trim|required');
            $this->form_validation->set_rules('project_sector_main', lang('Sector'), 'required');
            $this->form_validation->set_rules('project_sector_sub', lang('Sub-Sector'), 'required');
            $this->form_validation->set_rules('project_budget_max', lang('TotalBudget'), 'integer|greater_than[-1]|required');
            $this->form_validation->set_rules('project_developer', lang('Developer'), 'trim|callback_isCompleted_developer_sponsor');
            $this->form_validation->set_rules('project_sponsor', lang('Sponsor'), 'trim|callback_isCompleted_developer_sponsor');
            $this->form_validation->set_rules('website', lang('ProjectWebsite'), 'trim|prep_url|max_length[255]');

            $this->form_validation->set_rules('project_eststart', lang('Est.Start'), 'trim|callback_valid_monthyear_format|callback_valid_period');
            $this->form_validation->set_rules('project_estcompletion', lang('Est.Completion'), 'trim|callback_valid_monthyear_format|callback_valid_period');

            if ($this->form_validation->run() === true) {
                $this->projects_model->update_project($slug, $this->uid);

                // Analytics
                $this->headerdata['page_analytics'] = $this->project_updated_event_data($this->pid);
            }
        }

        $editdata['project'] = $this->projects_model->get_project_data($slug, $this->uid);
        $editdata['proj_org'] = $this->projects_model->get_project_organization($this->pid);

        $editdata['photoerror'] = '';

        $editdata['map_geom'] = $this->projects_model->get_geom($editdata['project']['pid']);

        $this->breadcrumb->append_crumb(lang('B_PROJECTS'), '/projects');
        $this->breadcrumb->append_crumb($editdata['project']['projectname'] . ' (edit)', "/projects/$slug");
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        $this->headerdata['title'] = build_title($editdata['project']['projectname'] . ' (edit)');

        // get map draw objects
        $this->headerdata['header_extra'] .= $this->load->view('projects/leaflet-draw-js', '', true);

        // Render the page
        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('projects/projects_edit', $editdata);
        $this->load->view('templates/footer', $this->footer_data);

        // echo "<pre>"; var_dump($this->headerdata); exit;
    }
    
    /**
    * Listing Method
    * Method call for Project Listing Page
    *
    * @access public
    */
    public function index()
    {
        $limit = view_check_limit($this->input->get_post('limit', true));
        $offset    = $this->input->get_post('per_page', true);
        if (empty($offset)) {
            $offset = 0;
        }
        $sort = $this->check_sort($this->input->get_post('sort', true));

        $filter = array(
            'country' => $this->input->get_post('country', true),
            'sector' => $this->input->get_post('sector', true),
            'subsector' => $this->input->get_post('subsector', true),
            'stage' => $this->input->get_post('stage', true),
            'searchtext' => $this->input->get_post('searchtext', true),
        );
        array_walk($filter, function (&$value, $key) {
            $value = $value ? : '';
        });

        $projects = $this->projects_model->all($limit, $offset, $filter, $sort);
        $total = count($projects) > 0 ? (int) $projects[0]['row_count'] : 0;
        
        $sector_data = sector_subsectors();
        $subsectors = array();
        if (! empty($subsector)) {
            if (isset($sector_data[$subsector])) {
                $subsectors = $sector_data[$subsector];
            }
        }

        $config = array(
            'base_url' => '/projects/?' . http_build_query(array_merge($filter, compact('sort', 'limit'))),
            'total_rows' => $total,
            'per_page' => $limit,
            'num_links' => 1,
            'next_link' => lang('Next') . '  ' . '&gt;',
            'prev_link' => '&lt;' . '  ' . lang('Prev'),
            'first_link' => lang('First'),
            'last_link' =>  lang('Last'),
            'page_query_string' => true
        );
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        
        $data = array(
            'projects'     => $projects,
            'total'        => $total,
            'subsectors'   => $subsectors,
            'all_subsectors'   => $sector_data,
            'filter'       => $filter,
            'sort_options' => $this->sort_options,
            'sort'         => $sort,
            'limit'        => $limit,
            'paging'       => $this->pagination->create_links(),
            'page_from'    => $offset + 1,
            'page_to'      => ($offset + $limit <= $total) ? $offset + $limit : $total,
        );

        $this->breadcrumb->append_crumb(lang('B_PROJECTS'), '/projects');
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        // Analytics
        // Check if we have any serach filters setup
        if (count(array_filter($filter)) > 0) {
            $event_properties = array(
                'Category' => 'Project',
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
        $this->load->view('projects/projects_list', $data);
        $this->load->view('templates/footer', $this->dataLang);
    }

    /**
     * Show a list of Global Experts
     *
     * @param $id Project id (pid)
     */
    public function topexperts($id)
    {
        $this->view_experts($id, 'top');
    }

    /**
     * Show a list of SME Service Providers
     *
     * @param $id Project id (pid)
     */
    public function smeexperts($id)
    {
        $this->view_experts($id, 'sme');
    }
    
    private function view_experts($id, $expert_type)
    {
        $limit = view_check_limit($this->input->get_post('limit', true));
        $offset        = $this->input->get_post('per_page', true);
        if (empty($offset)) {
            $offset = 0;
        }

        // Allow for $params to be either a slug or an id (pid)
        if (! is_numeric($id)) {
            $id = $this->projects_model->get_pid_from_slug($id);
        }
        $project = $this->projects_model->find($id, 'uid, slug, projectname');
        $slug = $project['slug'];

        $exclude = array($project['uid']);
        $this->load->model('expertise_model');

        if ($expert_type == 'top') {
            $experts = $this->expertise_model->get_global_experts_list($id, $exclude, $limit, $offset);
            $title = lang('TopExperts');
            $breadcrumb_title = lang('B_TOP_EXPERTS');
            $uri_segment = $expert_type = 'topexperts';
        } else {
            $experts = $this->expertise_model->get_sme_experts_list($id, $exclude, $limit, $offset);
            $title = lang('SMEExperts');
            $breadcrumb_title = lang('B_SME_EXPERTS');
            $uri_segment = $expert_type = 'smeexperts';
        }
        $total = count($experts) > 0 ? $experts[0]['row_count'] : 0;

        $config = array(
            'base_url' => "/projects/$uri_segment/$slug/?limit=$limit",
            'total_rows' => $total,
            'per_page' => $limit,
            'num_links' => 1,
            'next_link' => lang('Next') . '  ' . '&gt;',
            'prev_link' => '&lt;' . '  ' . lang('Prev'),
            'first_link' => lang('First'),
            'last_link' =>  lang('Last'),
            'page_query_string' => true
        );
        $this->load->library('pagination');
        $this->pagination->initialize($config);
        
        $data = array(
            'title'      => $title,
            'experts'    => $experts,
            'total_rows' => $total,
            'limit'      => $limit,
            'paging'     => $this->pagination->create_links(),
            'page_from'  => $offset + 1,
            'page_to'    => ($offset + $limit <= $total) ? $offset + $limit : $total
        );
                
        $this->breadcrumb->append_crumb(lang('B_PROJECTS'), '/projects');
        $this->breadcrumb->append_crumb($project['projectname'], "/projects/$slug");
        $this->breadcrumb->append_crumb($breadcrumb_title, "/projects/$uri_segment/$slug");
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        $this->headerdata['title'] = build_title($title);

        // Render the page
        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('projects/projects_experts', $data);
        $this->load->view('templates/footer', $this->dataLang);
    }

    /**
    * Delete Executive
    * Method call delete existing executive of project
    *
    * @access public
    * @param int
    *
    */
    public function delete_executive($params)
    {
        if ($params != "") {
            $this->projects_model->delete_executive($params, $this->uid);
        }
    }
    
    /**
    * Upload project photo
    * upload project picture and update his/her account detail
    *
    * @access public
    */
    public function upload_projectphoto($params)
    {
        $slug = $params;
        $this->pid = $this->projects_model->check_user_project($slug, $this->uid);

        // TODO: Why do we have login check in here?
        if (! sess_var('logged_in') || $this->pid == '') {
            redirect("projects/$slug", 'refresh');
            exit;
        }
        
        $editdata['slug'] = $slug;

        $error_response = array(
            'status' => 'error',
            'isload' => 'no'
        );

        $error_delimiters = array('open' => '<label>', 'close' => '</label>');
        $upload = upload_image('/' . PROJECT_IMAGE_PATH, 'project_photo', true, array(
            array('width' => '150', 'height' => '150'),
            array('width' => '80', 'height' => '67'),
            array('width' => '158', 'height' => '132'),
            array('width' => '50', 'height' => '50'),
            array('width' => '198', 'height' => '198')
            ), $error_delimiters
        );
        
        if ($upload['error'] !== '') {
            $error_response['message'] = array('photo_filename' => $upload['error']);
            sendResponse($error_response);
            exit;
        }

        if (! $this->projects_model->upload_photo($upload, $slug, $this->uid)) {
            $error_response['message'] = lang('ErrorwhileupdatingProjectpicture');
            sendResponse($error_response);
            exit;
        }

        $response = array(
            'status' => 'success',
            'message' => lang('Projectpictureupdatedsuccessfully'),
            'isload' => 'no',
            'imgpath' => project_image($upload['file_name'], 150),
        );

        // Analytics
        $response['analytics'] = $this->project_updated_event_data($this->pid);

        sendResponse($response);
        exit;
    }
    
    /**
    * Update Project Name
    * update new project name
    *
    * @param string
    *
    */
    public function updatename($params)
    {
        $this->projects_model->updateprojectname($params, $this->uid);
    }
    
    
    /**
    * Update Legal Info
    * update new legal info
    *
    * @access public
    * @param string
    */

    public function add_legal($params)
    {
        $this->projects_model->add_legal($params, $this->uid);
    }
    
    
    /**
    * Add Executive
    * Add new executive for selected project
    *
    * @access public
    * @param string
    */
    public function add_executive($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_executives_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('project_executives_company', 'Company', 'trim|required');
        $this->form_validation->set_rules('project_executives_role', 'Role', 'trim|required');
        //$this->form_validation->set_rules('project_executives_role_other', 'Other', 'trim|required');
        $this->form_validation->set_rules('project_executives_email', 'Email', 'trim|required|valid_email');

        if ($this->form_validation->run() === true) {
            $this->projects_model->add_executive($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
            $response["message"]    = array('project_executives_name'=>form_error('project_executives_name'),
                                            'project_executives_company'=>form_error('project_executives_company'),
                                            'project_executives_role'=>form_error('project_executives_role'),
                                            'project_executives_email'=>form_error('project_executives_email')
                                    );
            $response["isload"]    = "no";
            //'project_executives_role_other'=>form_error('project_executives_role_other'),

                    
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }
    
    /**
    * Update Executive
    * update executive for selected project
    *
    * @access public
    * @param string
    */
    public function update_executive($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_executives_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('project_executives_company', 'Company', 'trim|required');
        $this->form_validation->set_rules('project_executives_role', 'Role', 'trim|required');
        $this->form_validation->set_rules('project_executives_email', 'Email', 'trim|required|valid_email');

        if ($this->form_validation->run() === true) {
            $this->projects_model->update_executive($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
            $response["message"]    = array('project_executives_name'=>form_error('project_executives_name'),
                                            'project_executives_company'=>form_error('project_executives_company'),
                                            'project_executives_role'=>form_error('project_executives_role'),
                                            'project_executives_email'=>form_error('project_executives_email')
                                    );
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }
    
    
    /**
    * Add Organization
    * Add new organization for selected project
    *
    * @access public
    * @param string
    */
    public function add_organization($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_organizations_company', 'Company', 'trim|required');
        $this->form_validation->set_rules('project_organizations_role', 'Role', 'trim|required');
        $this->form_validation->set_rules('project_organizations_contact', 'Contact', 'trim|required');
        $this->form_validation->set_rules('project_organizations_email', 'Email', 'trim|required|valid_email');

        if ($this->form_validation->run() === true) {
            $this->projects_model->add_organization($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
            $response["message"]    = array('project_organizations_company'=>form_error('project_organizations_company'),
                                            'project_organizations_role'=>form_error('project_organizations_role'),
                                            'project_organizations_contact'=>form_error('project_organizations_contact'),
                                            'project_organizations_email'=>form_error('project_organizations_email')
                                    );
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }
    
    
    
    public function update_orgExpert($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_expAdv', 'Organization', 'trim|required');
        if ($this->form_validation->run() === true) {
            $this->projects_model->update_orgExpert($params);
        } else {
            $response = array();
            $response["status"]    = "error";
            $response["message"]    = array('project_expAdv'=>form_error('project_expAdv'));
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }
    
    /**
    * Update Organization
    * update organization for selected project
    *
    * @access public
    * @param string
    */
    public function update_organization($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_organizations_company', 'Company', 'trim|required');
        $this->form_validation->set_rules('project_organizations_role', 'Role', 'trim|required');
        $this->form_validation->set_rules('project_organizations_contact', 'Contact', 'trim|required');
        $this->form_validation->set_rules('project_organizations_email', 'Email', 'trim|required|valid_email');

        if ($this->form_validation->run() === true) {
            $this->projects_model->update_organization($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
            $response["message"]    = array('project_organizations_company'=>form_error('project_organizations_company'),
                                            'project_organizations_role'=>form_error('project_organizations_role'),
                                            'project_organizations_contact'=>form_error('project_organizations_contact'),
                                            'project_organizations_email'=>form_error('project_organizations_email')
                                    );
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    /**
    * Delete Organization
    * Method call delete existing organization of project
    *
    * @access public
    * @param int
    *
    */
    public function delete_organization($params)
    {
        if ($params != "") {
            $this->projects_model->delete_organization($params, $this->uid);
        }
    }

    /**
    * Add Engineering
    * Add new engineering for selected project
    *
    * @access public
    * @param string
    */
    public function add_engineering($params)
    {
        $response = array(
            'status' => 'error',
            'isload' => 'no',
            'message' => array()
        );

        $fieldname = 'project_engineering_schedule';

        // Check if the post_max_size vaue is exceded
        if ($error = is_post_msize_exceeded()) {
            $response['message'] = array_merge($response['message'],
                array($fieldname => $error));
            sendResponse($response);
            exit;
        }

        $this->set_engineering_validation_rules();
        
        if ($this->form_validation->run() === false) {
            $response['message'] = array_merge($response['message'], array(
                'project_engineering_company' => form_error('project_engineering_company'),
                'project_engineering_role' => form_error('project_engineering_role'),
                'project_engineering_cname' => form_error('project_engineering_cname'),
                'project_engineering_challenges' => form_error('project_engineering_challenges'),
                'project_engineering_innovations' => form_error('project_engineering_innovations')
            ));
            sendResponse($response);
            exit;
        }

        $upload = array('file_name' => '', 'error' => '');
        // Attachment is optional
        if (! empty($_FILES[$fieldname]['name'])) {
            $upload = $this->proj_uploadfiles($params, $fieldname);
            if (! $upload) {
                return;
            }
        }

        $this->projects_model->add_engineering($params, $this->uid, $upload);
    }

    /**
    * Update engineering
    * update engineering for selected project
    *
    * @access public
    * @param string
    */
    public function update_engineering($params)
    {
        $response = array(
            'status' => 'error',
            'isload' => 'no',
            'message' => array()
        );

        $fieldname = 'project_engineering_schedule';

        // Check if the post_max_size vaue is exceded
        if ($error = is_post_msize_exceeded()) {
            $response['message'] = array_merge($response['message'],
                array($fieldname => $error));
            sendResponse($response);
            exit;
        }

        $this->set_engineering_validation_rules();

        if ($this->form_validation->run() === false) {
            $response['message'] = array_merge($response['message'], array(
                'project_engineering_company' => form_error('project_engineering_company'),
                'project_engineering_role' => form_error('project_engineering_role'),
                'project_engineering_cname' => form_error('project_engineering_cname'),
                'project_engineering_challenges' => form_error('project_engineering_challenges'),
                'project_engineering_innovations' => form_error('project_engineering_innovations')
            ));
            sendResponse($response);
            exit;
        }

        $upload = array('file_name' => '', 'error' => '');
        $existing_filename = $this->input->post('project_engineering_schedul_hidden');
        if ($existing_filename) {
            $upload['file_name'] = $existing_filename;
        }

        // Attachment is optional
        if (! empty($_FILES[$fieldname]['name'])) {
            $upload = $this->proj_uploadfiles($params, $fieldname);
            if (! $upload) {
                return;
            }
        }

        $this->projects_model->update_engineering($params, $this->uid, $upload);
    }

    /**
    * Delete engineering
    * Method call delete existing engineering of project
    *
    * @access public
    * @param int
    *
    */
    public function delete_engineering($params)
    {
        if (! empty($params)) {
            $this->projects_model->delete_engineering($params, $this->uid);
        }
    }

    /**
    * Add map_point
    * Add new map_point for selected project
    *
    * @access public
    * @param string
    */
    public function add_map_point($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_map_points_mapname', 'Name', 'trim|required');
        $this->form_validation->set_rules('project_map_points_latitude', 'Latitude', 'trim|required');
        $this->form_validation->set_rules('project_map_points_longitude', 'Longitude', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->add_map_point($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
            $response["message"]    = array('project_map_points_mapname'=>form_error('project_map_points_mapname'),
                                            'project_map_points_latitude'=>form_error('project_map_points_latitude'),
                                            'project_map_points_longitude'=>form_error('project_map_points_longitude')
                                            );
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    /**
    * Update map_point
    * update map_point for selected project
    *
    * @access public
    * @param string
    */
    public function update_map_point($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_map_points_mapname', 'Name', 'trim|required');
        $this->form_validation->set_rules('project_map_points_latitude', 'Latitude', 'trim|required');
        $this->form_validation->set_rules('project_map_points_longitude', 'Longitude', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->update_map_point($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
            $response["message"]    = array('project_map_points_mapname'=>form_error('project_map_points_mapname'),
                                            'project_map_points_latitude'=>form_error('project_map_points_latitude'),
                                            'project_map_points_longitude'=>form_error('project_map_points_longitude')
                                            );
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    /**
    * Delete map_point
    * Method call delete existing map_points of project
    *
    * @access public
    * @param int
    *
    */
    public function delete_map_point($params)
    {
        if ($params != "") {
            $this->projects_model->delete_map_point($params, $this->uid);
        }
    }

    /**
    * Update the projects lat, lng and location
    *
    * @access public
    * @param string of the slug
    */
    public function update_project_location($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_lat', 'Latitude', 'trim|required');
        $this->form_validation->set_rules('project_lng', 'Longitude', 'trim|required');
        $this->form_validation->set_rules('project_location', 'Location', 'trim|required');
        $this->form_validation->set_rules('project_geocode', 'Geocode', 'trim|required');
        // $this->form_validation->set_rules('project_country', 'Country', 'trim|required');

        if ($this->form_validation->run() === true) {
            // Need a new set location.
            $response = $this->projects_model->update_location($params, $this->uid);

            /* Algorithms */
            //$pid = $this->projects_model->get_pid_from_slug($params);
            //$this->member_project_lib->schedule_proj_expert_pairs_from_project_id($pid);

            echo json_encode($response);
        } else {
            $response = array();
            $response["status"]    = "error";
            $response["message"]    = array(
                                            'project_lat'=>form_error('project_lat'),
                                            'project_lng'=>form_error('project_lng'),
                                            'project_location'=>form_error('project_location'),
                                            'project_geocode'=>form_error('project_geocode'),
                                            // 'project_country'=>form_error('project_country'),
                                            );
            $response["isload"]    = "no";

            echo json_encode($response);
        }
    }

    /**
    * Add design_issue
    * Add new design_issue for selected project
    *
    * @access public
    * @param string
    */
    public function add_design_issue($params)
    {
        $response = array(
            'status' => 'error',
            'isload' => 'no',
            'message' => array()
        );

        $fieldname = 'project_design_issues_attachment';

        // Check if the post_max_size vaue is exceded
        if ($error = is_post_msize_exceeded()) {
            $response['message'] = array_merge($response['message'],
                array($fieldname => $error));
            sendResponse($response);
            exit;
        }

        $this->set_design_issue_validation_rules();

        if ($this->form_validation->run() === false) {
            $response['message'] = array_merge($response['message'], array(
                'project_design_issues_title' => form_error('project_design_issues_title'),
                'project_design_issues_desc' => form_error('project_design_issues_desc'),
//                'project_design_issues_attachment' => form_error('project_design_issues_attachment')
            ));

            sendResponse($response);
            exit;
        }

        $upload = array('file_name' => '', 'error' => '');
        // Attachment is optional
        if (! empty($_FILES[$fieldname]['name'])) {
            $upload = $this->proj_uploadfiles($params, $fieldname);
            if (! $upload) {
                return;
            }
        }
        $this->projects_model->add_design_issue($params, $this->uid, $upload);
    }

    /**
    * Update design_issue
    * update design_issue for selected project
    *
    * @access public
    * @param string
    */
    public function update_design_issue($params)
    {
        $response = array(
            'status' => 'error',
            'isload' => 'no',
            'message' => array()
        );

        $fieldname = 'project_design_issues_attachment';

        // Check if the post_max_size vaue is exceded
        if ($error = is_post_msize_exceeded()) {
            $response['message'] = array_merge($response['message'],
                array($fieldname => $error));
            sendResponse($response);
            exit;
        }

        $this->set_design_issue_validation_rules();
        
        if ($this->form_validation->run() === false) {
            $response['message'] = array_merge($response['message'], array(
                'project_design_issues_title' => form_error('project_design_issues_title'),
                'project_design_issues_desc' => form_error('project_design_issues_desc'),
//                'project_design_issues_attachment' => form_error('project_design_issues_attachment')
            ));
            sendResponse($response);
            exit;
        }

        $upload = array('file_name' => '', 'error' => '');
        // TODO: Spelling error in the field name
        $existing_filename = $this->input->post('project_design_issues_attachmen_hidden');
        if ($existing_filename) {
            $upload['file_name'] = $existing_filename;
        }

        // Attachment is optional
        if (! empty($_FILES[$fieldname]['name'])) {
            $upload = $this->proj_uploadfiles($params, $fieldname);
            if (! $upload) {
                return;
            }
        }
        $this->projects_model->update_design_issue($params, $this->uid, $upload);
    }

    /**
    * Delete design_issuet
    * Method call delete existing design_issues of project
    *
    * @access public
    * @param int
    *
    */
    public function delete_design_issue($params)
    {
        if (! empty($params)) {
            $this->projects_model->delete_design_issue($params, $this->uid);
        }
    }
    
    
    /**
    * Add environment
    * Add new environment for selected project
    *
    * @access public
    * @param string
    */
    public function add_environment($params)
    {
        $response = array(
            'status' => 'error',
            'isload' => 'no',
            'message' => array()
        );

        $fieldname = 'project_environment_attachment';

        // Check if the post_max_size vaue is exceded
        if ($error = is_post_msize_exceeded()) {
            $response['message'] = array_merge($response['message'],
                array($fieldname => $error));
            sendResponse($response);
            exit;
        }

        $this->set_environment_validation_rules();

        if ($this->form_validation->run() === false) {
            $response['message'] = array_merge($response['message'], array(
                'project_environment_title' => form_error('project_environment_title'),
                'project_environment_desc' => form_error('project_environment_desc')
            ));
            sendResponse($response);
            exit;
        }

        $upload = array('file_name' => '', 'error' => '');
        // Attachment is optional
        if (! empty($_FILES[$fieldname]['name'])) {
            $upload = $this->proj_uploadfiles($params, $fieldname);
            if (! $upload) {
                return;
            }
        }

        $this->projects_model->add_environment($params, $this->uid, $upload);
    }

    /**
    * Update environment
    * update environment for selected project
    *
    * @access public
    * @param string
    */
    public function update_environment($params)
    {
        $response = array(
            'status' => 'error',
            'isload' => 'no',
            'message' => array()
        );

        $fieldname = 'project_environment_attachment';

        // Check if the post_max_size vaue is exceded
        if ($error = is_post_msize_exceeded()) {
            $response['message'] = array_merge($response['message'],
                array($fieldname => $error));
            sendResponse($response);
            exit;
        }

        $this->set_environment_validation_rules();

        if ($this->form_validation->run() === false) {
            $response['message'] = array_merge($response['message'], array(
                'project_environment_title' => form_error('project_environment_title'),
                'project_environment_desc' => form_error('project_environment_desc')
            ));
            sendResponse($response);
            exit;
        }

        $upload = array('file_name' => '', 'error' => '');
        $existing_filename = $this->input->post('project_environment_attachmen_hidden');
        if ($existing_filename) {
            $upload['file_name'] = $existing_filename;
        }

        // Attachment is optional
        if (! empty($_FILES[$fieldname]['name'])) {
            $upload = $this->proj_uploadfiles($params, $fieldname);
            if (! $upload) {
                return;
            }
        }

        $this->projects_model->update_environment($params, $this->uid, $upload);
    }

    /**
    * Delete environment
    * Method call delete existing environment of project
    *
    * @access public
    * @param int
    *
    */
    public function delete_environment($params)
    {
        if (! empty($params)) {
            $this->projects_model->delete_environment($params, $this->uid);
        }
    }

    /**
    * Add studies
    * Add new studies for selected project
    *
    * @access public
    * @param string
    */
    public function add_studies($params)
    {
        $response = array(
            'status' => 'error',
            'isload' => 'no',
            'message' => array()
        );

        $fieldname = 'project_studies_attachment';

        // Check if the post_max_size vaue is exceded
        if ($error = is_post_msize_exceeded()) {
            $response['message'] = array_merge($response['message'],
                array($fieldname => $error));
            sendResponse($response);
            exit;
        }

        $this->set_study_validation_rules();

        if ($this->form_validation->run() === false) {
            $response['message'] = array_merge($response['message'], array(
                'project_studies_title' => form_error('project_studies_title'),
                'project_studies_desc' => form_error('project_studies_desc')
            ));
            sendResponse($response);
            exit;
        }

        $upload = array('file_name' => '', 'error' => '');
        // Attachment is optional
        if (! empty($_FILES[$fieldname]['name'])) {
            $upload = $this->proj_uploadfiles($params, $fieldname);
            if (! $upload) {
                return;
            }
        }

        $this->projects_model->add_studies($params, $this->uid, $upload);
    }


    /**
    * Update studies
    * update studies for selected project
    *
    * @access public
    * @param string
    */
    public function update_studies($params)
    {
        $response = array(
            'status' => 'error',
            'isload' => 'no',
            'message' => array()
        );

        $fieldname = 'project_studies_attachment';

        // Check if the post_max_size vaue is exceded
        if ($error = is_post_msize_exceeded()) {
            $response['message'] = array_merge($response['message'],
                array($fieldname => $error));
            sendResponse($response);
            exit;
        }

        $this->set_study_validation_rules();

        if ($this->form_validation->run() === false) {
            $response['message'] = array_merge($response['message'], array(
                'project_studies_title' => form_error('project_studies_title'),
                'project_studies_desc' => form_error('project_studies_desc')
            ));
            sendResponse($response);
            exit;
        }

        $upload = array('file_name' => '', 'error' => '');
        $existing_filename = $this->input->post('project_studies_attachmen_hidden');
        if ($existing_filename) {
            $upload['file_name'] = $existing_filename;
        }

        // Attachment is optional
        if (! empty($_FILES[$fieldname]['name'])) {
            $upload = $this->proj_uploadfiles($params, $fieldname);
            if (! $upload) {
                return;
            }
        }

        $this->projects_model->update_studies($params, $this->uid, $upload);
    }


    /**
    * Delete studies
    * Method call delete existing studies of project
    *
    * @access public
    * @param int
    *
    */
    public function delete_studies($params)
    {
        if (! empty($params)) {
            $this->projects_model->delete_studies($params, $this->uid);
        }
    }


    /**
    * Add financial
    * Add new financial info for selected project
    *
    * @access public
    * @param string
    */
    public function add_financial($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_fs_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('project_fs_contact', 'Contact Name', 'trim|required');
        $this->form_validation->set_rules('project_fs_role', 'Role', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->add_financial($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
            $response["message"]    = array('project_fs_name'=>form_error('project_fs_name'),
                                            'project_fs_contact'=>form_error('project_fs_contact'),
                                            'project_fs_role'=>form_error('project_fs_role')
                                            );
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }


    /**
    * Add fund_sources
    * Add new fund_sources for selected project
    *
    * @access public
    * @param string
    */
    public function add_fund_sources($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_fund_sources_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('project_fund_sources_role', 'Role', 'trim|required');
        $this->form_validation->set_rules('project_fund_sources_amount', 'Amount', 'trim|required');
        $this->form_validation->set_rules('project_fund_sources_desc', 'Description', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->add_fund_sources($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";

            $response["message"]    = array('project_fund_sources_name'=>form_error('project_fund_sources_name'),
                                            'project_fund_sources_role'=>form_error('project_fund_sources_role'),
                                            'project_fund_sources_amount'=>form_error('project_fund_sources_amount'),
                                            'project_fund_sources_desc'=>form_error('project_fund_sources_desc')
                                            );
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    /**
    * Update fund_sources
    * update fund_sources for selected project
    *
    * @access public
    * @param string
    */
    public function update_fund_sources($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_fund_sources_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('project_fund_sources_role', 'Role', 'trim|required');
        $this->form_validation->set_rules('project_fund_sources_amount', 'Amount', 'trim|required');
        $this->form_validation->set_rules('project_fund_sources_desc', 'Description', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->update_fund_sources($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";

            $response["message"]    = array('project_fund_sources_name'=>form_error('project_fund_sources_name'),
                                            'project_fund_sources_role'=>form_error('project_fund_sources_role'),
                                            'project_fund_sources_amount'=>form_error('project_fund_sources_amount'),
                                            'project_fund_sources_desc'=>form_error('project_fund_sources_desc')
                                            );
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    /**
    * Delete fund_sources
    * Method call delete existing fund_sources of project
    *
    * @access public
    * @param int
    *
    */
    public function delete_fund_sources($params)
    {
        if ($params != "") {
            $this->projects_model->delete_fund_sources($params, $this->uid);
        }
    }

    
    /**
    * Add ROI
    * Add new ROI for selected project
    *
    * @access public
    * @param string
    */
    public function add_roi($params)
    {
        $response = array(
            'status' => 'error',
            'isload' => 'no',
            'message' => array()
        );

        $fieldname = 'project_roi_keystudy';

        // Check if the post_max_size vaue is exceded
        if ($error = is_post_msize_exceeded()) {
            $response['message'] = array_merge($response['message'],
                array($fieldname => $error));
            sendResponse($response);
            exit;
        }

        $this->set_roi_validation_rules();

        if ($this->form_validation->run() === false) {
            $response['message'] = array_merge($response['message'], array(
                'project_roi_name' => form_error('project_roi_name'),
                'project_roi_percent' => form_error('project_roi_percent'),
                'project_roi_type' => form_error('project_roi_type'),
                'project_roi_approach' => form_error('project_roi_approach')
            ));
            sendResponse($response);
            exit;
        }

        $upload = array('file_name' => '', 'error' => '');
        // Attachment is optional
        if (! empty($_FILES[$fieldname]['name'])) {
            $upload = $this->proj_uploadfiles($params, $fieldname);
            if (! $upload) {
                return;
            }
        }
        $this->projects_model->add_roi($params, $this->uid, $upload);
    }

    /**
    * Update ROI
    * update ROI for selected project
    *
    * @access public
    * @param string
    */
    public function update_roi($params)
    {
        $response = array(
            'status' => 'error',
            'isload' => 'no',
            'message' => array()
        );

        $fieldname = 'project_roi_keystudy';

        // Check if the post_max_size vaue is exceded
        if ($error = is_post_msize_exceeded()) {
            $response['message'] = array_merge($response['message'],
                array($fieldname => $error));
            sendResponse($response);
            exit;
        }

        $this->set_roi_validation_rules();

        if ($this->form_validation->run() === false) {
            $response['message'] = array_merge($response['message'], array(
                'project_roi_name' => form_error('project_roi_name'),
                'project_roi_percent' => form_error('project_roi_percent'),
                'project_roi_type' => form_error('project_roi_type'),
                'project_roi_approach' => form_error('project_roi_approach')
            ));
            sendResponse($response);
            exit;
        }

        $upload = array('file_name' => '', 'error' => '');
        $existing_filename = $this->input->post('project_roi_keystud_hidden');
        if ($existing_filename) {
            $upload['file_name'] = $existing_filename;
        }

        // Attachment is optional
        if (! empty($_FILES[$fieldname]['name'])) {
            $upload = $this->proj_uploadfiles($params, $fieldname);
            if (! $upload) {
                return;
            }
        }

        $this->projects_model->update_roi($params, $this->uid, $upload);
    }

    /**
    * Delete ROI
    * Method call delete existing ROI of project
    *
    * @access public
    * @param int
    *
    */
    public function delete_roi($params)
    {
        if (! empty($params)) {
            $this->projects_model->delete_roi($params, $this->uid);
        }
    }
    
    /**
    * Add critical_participants
    * Add new critical_participant for selected project
    *
    * @access public
    * @param string
    */
    public function add_critical_participants($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_critical_participants_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('project_critical_participants_role', 'Role', 'trim|required');
        $this->form_validation->set_rules('project_critical_participants_desc', 'Description', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->add_critical_participants($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";

            $response["message"]    = array('project_critical_participants_name'=>form_error('project_critical_participants_name'),
                                            'project_critical_participants_role'=>form_error('project_critical_participants_role'),
                                            'project_critical_participants_desc'=>form_error('project_critical_participants_desc')
                                            );
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }


    /**
    * Update critical_participants
    * update critical_participants for selected project
    *
    * @access public
    * @param string
    */
    public function update_critical_participants($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_critical_participants_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('project_critical_participants_role', 'Role', 'trim|required');
        $this->form_validation->set_rules('project_critical_participants_desc', 'Description', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->update_critical_participants($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";

            $response["message"]    = array('project_critical_participants_name'=>form_error('project_critical_participants_name'),
                                            'project_critical_participants_role'=>form_error('project_critical_participants_role'),
                                            'project_critical_participants_desc'=>form_error('project_critical_participants_desc')
                                            );
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }


    /**
    * Delete critical_participants
    * Method call delete existing critical_participants of project
    *
    * @access public
    * @param int
    *
    */
    public function delete_critical_participants($params)
    {
        if (! empty($params)) {
            $this->projects_model->delete_critical_participants($params, $this->uid);
        }
    }


    /**
    * Add regulatory
    * Add new regulatory for selected project
    *
    * @access public
    * @param string
    */
    public function add_regulatory($params)
    {
        $response = array(
            'status' => 'error',
            'isload' => 'no',
            'message' => array()
        );

        $fieldname = 'project_regulatory_filename';

        // Check if the post_max_size vaue is exceded
        if ($error = is_post_msize_exceeded()) {
            $response['message'] = array_merge($response['message'],
                array($fieldname => $error));
            sendResponse($response);
            exit;
        }

        $this->set_regulatory_validation_rules();
        
        if ($this->form_validation->run() === false) {
            $response['message'] = array_merge($response['message'], array(
                'project_regulatory_desc' => form_error('project_regulatory_desc')
            ));
            sendResponse($response);
            exit;
        }

        $upload = array('file_name' => '', 'error' => '');
        // Attachment is optional
        if (! empty($_FILES[$fieldname]['name'])) {
            $upload = $this->proj_uploadfiles($params, $fieldname);
            if (! $upload) {
                return;
            }
        }
        $this->projects_model->add_regulatory($params, $this->uid, $upload);
    }
    
    /**
    * Update regulatory
    * update regulatory for selected project
    *
    * @access public
    * @param string
    */
    public function update_regulatory($params)
    {
        $response = array(
            'status' => 'error',
            'isload' => 'no',
            'message' => array()
        );

        $fieldname = 'project_regulatory_filename';

        // Check if the post_max_size vaue is exceded
        if ($error = is_post_msize_exceeded()) {
            $response['message'] = array_merge($response['message'],
                array($fieldname => $error));
            sendResponse($response);
            exit;
        }

        $this->set_regulatory_validation_rules();
        
        if ($this->form_validation->run() === false) {
            $response['message'] = array_merge($response['message'], array(
                'project_regulatory_desc' => form_error('project_regulatory_desc')
            ));
            sendResponse($response);
            exit;
        }

        $upload = array('file_name' => '', 'error' => '');
        $existing_filename = $this->input->post('project_regulatory_filenam_hidden');
        if ($existing_filename) {
            $upload['file_name'] = $existing_filename;
        }

        // Attachment is optional
        if (! empty($_FILES[$fieldname]['name'])) {
            $upload = $this->proj_uploadfiles($params, $fieldname);
            if (! $upload) {
                return;
            }
        }

        $this->projects_model->update_regulatory($params, $this->uid, $upload);
    }
    
    /**
    * Delete regulatory
    * Method call delete existing regulatory of project
    *
    * @access public
    * @param int
    *
    */
    public function delete_regulatory($params)
    {
        if (! empty($params)) {
            $this->projects_model->delete_regulatory($params, $this->uid);
        }
    }
    
    /**
    * Add participants_public
    * Add new participants_public for selected project
    *
    * @access public
    * @param string
    */
    public function add_participants_public($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_participants_public_name', 'Name', 'trim|required');
        if ($this->form_validation->run() === true) {
            $this->projects_model->add_participants_public($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
    
            $response["message"]    = array('project_participants_public_name'=>form_error('project_participants_public_name'));
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    
    /**
    * Update participants_public
    * update participants_public for selected project
    *
    * @access public
    * @param string
    */
    public function update_participants_public($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_participants_public_name', 'Name', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->update_participants_public($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
    
            $response["message"]    = array('project_participants_public_name'=>form_error('project_participants_public_name'));
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    
    /**
    * Delete participants_public
    * Method call delete existing participants_public of project
    *
    * @access public
    * @param int
    *
    */
    public function delete_participants_public($params)
    {
        if ($params != "") {
            $this->projects_model->delete_participants_public($params, $this->uid);
        }
    }

    
    /**
    * Add participants_political
    * Add new participants_politicalfor selected project
    *
    * @access public
    * @param string
    */
    public function add_participants_political($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_participants_political_name', 'Name', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->add_participants_political($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
    
            $response["message"]    = array('project_participants_political_name'=>form_error('project_participants_political_name'));
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }
    
    
    /**
    * Update participants_political
    * update participants_politicalfor selected project
    *
    * @access public
    * @param string
    */
    public function update_participants_political($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_participants_political_name', 'Name', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->update_participants_political($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
    
            $response["message"]    = array('project_participants_political_name'=>form_error('project_participants_political_name'));
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    
    /**
    * Delete participants_political
    * Method call delete existing participants_politicalof project
    *
    * @access public
    * @param int
    *
    */
    public function delete_participants_political($params)
    {
        if ($params != "") {
            $this->projects_model->delete_participants_political($params, $this->uid);
        }
    }
        
    /**
    * Add participants_companies
    * Add new participants_companies for selected project
    *
    * @access public
    * @param string
    */
    public function add_participants_companies($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_participants_companies_name', 'Name', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->add_participants_companies($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
    
            $response["message"]    = array('project_participants_companies_name'=>form_error('project_participants_companies_name'));
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }
    

    /**
    * Update participants_companies
    * update participants_companies for selected project
    *
    * @access public
    * @param string
    */
    public function update_participants_companies($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_participants_companies_name', 'Name', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->update_participants_companies($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
    
            $response["message"]    = array('project_participants_political_name'=>form_error('project_participants_political_name'));
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    
    /**
    * Delete participants_companies
    * Method call delete existing participants_companies of project
    *
    * @access public
    * @param int
    *
    */
    public function delete_participants_companies($params)
    {
        if ($params != "") {
            $this->projects_model->delete_participants_companies($params, $this->uid);
        }
    }

    /**
    * Add participants_owners
    * Add new participants_owners for selected project
    *
    * @access public
    * @param string
    */
    public function add_participants_owners($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_participants_owners_name', 'Name', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->add_participants_owners($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
    
            $response["message"]    = array('project_participants_owners_name'=>form_error('project_participants_owners_name'));
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    
    /**
    * Update participants_owners
    * update participants_owners for selected project
    *
    * @access public
    * @param string
    */
    public function update_participants_owners($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_participants_owners_name', 'Name', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->update_participants_owners($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
    
            $response["message"]    = array('project_participants_owners_name'=>form_error('project_participants_owners_name'));
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }
    
    /**
    * Delete participants_owners
    * Method call delete existing participants_owners of project
    *
    * @access public
    * @param int
    *
    */
    public function delete_participants_owners($params)
    {
        if ($params != "") {
            $this->projects_model->delete_participants_owners($params, $this->uid);
        }
    }


    /**
    * Add machinery
    * Add new machinery for selected project
    *
    * @access public
    * @param string
    */
    public function add_machinery($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_machinery_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('project_machinery_process', 'Procurement Process', 'trim|required');
        $this->form_validation->set_rules('project_machinery_financial_info', 'Financial Information', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->add_machinery($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
    
            $response["message"]    = array('project_machinery_name'=>form_error('project_machinery_name'),
                                            'project_machinery_process'=>form_error('project_machinery_process'),
                                            'project_machinery_financial_info'=>form_error('project_machinery_desc')
                                            );
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }
    
    /**
    * Update machinery
    * update machinery for selected project
    *
    * @access public
    * @param string
    */
    public function update_machinery($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_machinery_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('project_machinery_process', 'Procurement Process', 'trim|required');
        $this->form_validation->set_rules('project_machinery_financial_info', 'Financial Information', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->update_machinery($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
    
            $response["message"]    = array('project_machinery_name'=>form_error('project_machinery_name'),
                                            'project_machinery_process'=>form_error('project_machinery_process'),
                                            'project_machinery_financial_info'=>form_error('project_machinery_desc')
                                            );
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }
    
    
    
    /**
    * Delete machinery
    * Method call delete existing machinery of project
    *
    * @access public
    * @param int
    *
    */
    public function delete_machinery($params)
    {
        if ($params != "") {
            $this->projects_model->delete_machinery($params, $this->uid);
        }
    }

    /**
    * Add procurement_technology
    * Add new procurement_technology for selected project
    *
    * @access public
    * @param string
    */
    public function add_procurement_technology($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_procurement_technology_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('project_procurement_technology_process', 'Procurement Process', 'trim|required');
        $this->form_validation->set_rules('project_procurement_technology_financial_info', 'Financial Information', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->add_procurement_technology($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
    
            $response["message"]    = array('project_procurement_technology_name'=>form_error('project_procurement_technology_name'),
                                            'project_procurement_technology_process'=>form_error('project_procurement_technology_process'),
                                            'project_procurement_technology_financial_info'=>form_error('project_procurement_technology_desc')
                                            );
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }
    
    /**
    * Update procurement_technology
    * update procurement_technology for selected project
    *
    * @access public
    * @param string
    */
    public function update_procurement_technology($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_procurement_technology_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('project_procurement_technology_process', 'Procurement Process', 'trim|required');
        $this->form_validation->set_rules('project_procurement_technology_financial_info', 'Financial Information', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->update_procurement_technology($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
    
            $response["message"]    = array('project_procurement_technology_name'=>form_error('project_procurement_technology_name'),
                                            'project_procurement_technology_process'=>form_error('project_procurement_technology_process'),
                                            'project_procurement_technology_financial_info'=>form_error('project_procurement_technology_desc')
                                            );
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }
    
    
    
    /**
    * Delete procurement_technology
    * Method call delete existing procurement_technology of project
    *
    * @access public
    * @param int
    *
    */
    public function delete_procurement_technology($params)
    {
        if ($params != "") {
            $this->projects_model->delete_procurement_technology($params, $this->uid);
        }
    }


    /**
    * Add procurement_services
    * Add new procurement_services for selected project
    *
    * @access public
    * @param string
    */
    public function add_procurement_services($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_procurement_services_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('project_procurement_services_type', 'Type', 'trim|required');
        $this->form_validation->set_rules('project_procurement_services_process', 'Procurement Process', 'trim|required');
        $this->form_validation->set_rules('project_procurement_services_financial_info', 'Financial Information', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->add_procurement_services($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
    
            $response["message"]    = array('project_procurement_services_name'=>form_error('project_procurement_services_name'),
                                            'project_procurement_services_type'=>form_error('project_procurement_services_type'),
                                            'project_procurement_services_process'=>form_error('project_procurement_services_process'),
                                            'project_procurement_services_financial_info'=>form_error('project_procurement_services_desc')
                                            );
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    
    /**
    * Update procurement_services
    * update procurement_services for selected project
    *
    * @access public
    * @param string
    */
    public function update_procurement_services($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_procurement_services_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('project_procurement_services_type', 'Type', 'trim|required');
        $this->form_validation->set_rules('project_procurement_services_process', 'Procurement Process', 'trim|required');
        $this->form_validation->set_rules('project_procurement_services_financial_info', 'Financial Information', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->update_procurement_services($params, $this->uid);
        } else {
            $response = array();
            $response["status"]    = "error";
    
            $response["message"]    = array('project_procurement_services_name'=>form_error('project_procurement_services_name'),
                                            'project_procurement_services_type'=>form_error('project_procurement_services_type'),
                                            'project_procurement_services_process'=>form_error('project_procurement_services_process'),
                                            'project_procurement_services_financial_info'=>form_error('project_procurement_services_desc')
                                            );
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }
    
        
    /**
    * Delete procurement_services
    * Method call delete existing procurement_services of project
    *
    * @access public
    * @param int
    *
    */
    public function delete_procurement_services($params)
    {
        if ($params != "") {
            $this->projects_model->delete_procurement_services($params, $this->uid);
        }
    }


    /**
    * Add project_files
    * Add new project_files for selected project
    *
    * @access public
    * @param string
    */
    public function add_project_files($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_files_desc', 'Description', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            if ($file = $this->proj_uploadfiles($params, 'project_files_filename')) {
                $this->projects_model->add_project_files($params, $this->uid, $file);
            }
        } else {
            $response = array();
            $response["status"]    = "error";
    
            $response["message"]    = array(//'project_files_filename'=>form_error('project_files_filename'),
                                            'project_files_desc'=>form_error('project_files_desc')
                                            );
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }
    
    
    /**
    * Update project_files
    * update project_files for selected project
    *
    * @access public
    * @param string
    */
    public function update_project_files($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_files_desc', 'Description', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $hdnuploaded       = $this->input->post("project_files_filenam_hidden");
            if ($hdnuploaded != '') {
                $filename = array('file_name'=>$hdnuploaded,'error'=>'','file_size'=>'');
                $this->projects_model->update_project_files($params, $this->uid, $filename);
            } else {
                if ($file = $this->proj_uploadfiles($params, 'project_files_filename')) {
                    $this->projects_model->update_project_files($params, $this->uid, $file);
                }
            }
        } else {
            $response = array();
            $response["status"]    = "error";
    
            $response["message"]    = array('project_files_filename'=>form_error('project_files_filename'),
                                            'project_files_desc'=>form_error('project_files_desc')
                                            );
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }
    
    /**
    * Delete project_files
    * Method call delete existing project_files of project
    *
    * @access public
    * @param int
    *
    */
    public function delete_project_files($params)
    {
        if ($params != "") {
            $this->projects_model->delete_project_files($params, $this->uid);
        }
    }

    /**
    * form load
    * Get List of data after submittion of AJAX form
    *
    * @access public
    * @param string
    * @param string
    * @param string
    */
    public function form_load($formname, $actionType='', $slug='')
    {
        switch ($formname) {
            case 'project_executives':
                $array_load = $this->projects_model->load_executive($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;
            
            case 'project_organization':
                $array_load = $this->projects_model->load_organization($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;
            
            case 'project_engineering':
                $array_load = $this->projects_model->load_engineering($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;
            
            case 'project_map_point':
                $array_load = $this->projects_model->load_map_point($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;
            
            case 'project_design_issue':
                $array_load = $this->projects_model->load_design_issue($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;
            
            case 'project_environment':
                $array_load = $this->projects_model->load_environment($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;

            case 'project_studies':
                $array_load = $this->projects_model->load_studies($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;

            case 'project_fund_sources':
                $array_load = $this->projects_model->load_fund_sources($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;
            
            case 'project_roi':
                $array_load = $this->projects_model->load_roi($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;
            
            case 'project_critical_participants':
                $array_load = $this->projects_model->load_critical_participants($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;
            
            case 'project_regulatory':
                $array_load = $this->projects_model->load_project_regulatory($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;
            
            case 'participants_public':
                $array_load = $this->projects_model->load_participants_public($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;
            
            case 'participants_political':
                $array_load = $this->projects_model->load_participants_political($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;
            
            case 'participants_companies':
                $array_load = $this->projects_model->load_participants_companies($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;

            case 'participants_owners':
                $array_load = $this->projects_model->load_participants_owners($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;
            
            case 'project_machinery':
                $array_load = $this->projects_model->load_project_machinery($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;
            
            case 'procurement_technology':
                $array_load = $this->projects_model->load_procurement_technology($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;
            
            case 'procurement_services':
                $array_load = $this->projects_model->load_procurement_services($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;
            
            case 'project_files':
                $array_load = $this->projects_model->load_project_files($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;
            
            case 'project_comment':
                $array_load = $this->projects_model->load_project_comment($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;
            
            case 'project_assessment':
                $array_load = $this->projects_model->load_project_assessment($formname, $actionType, $slug, $this->uid);
                $this->load->view("loader", $array_load);
            break;
        
            default:
            redirect('profile/account_settings', 'refresh');
        }
    }
    
    /**
    * Edit Fundamentals
    * Edit fundamentals tab for Project Edit
    *
    * @access public
    * @param string
    */
    public function edit_fundamentals($params)
    {
        $slug = $params;
        $this->pid = $this->projects_model->check_user_project($slug, $this->uid);
        
        $fundamentaldata['slug'] = $slug;
        $fundamentaldata['vtab_position'] = 1;
        
        $fundamentaldata['project'] = $this->projects_model->get_fundamental_data($slug, $this->uid);
        $fundamentaldata['project']['projectdata'] = $this->projects_model->get_project_data($slug, $this->uid);
        $fundamentaldata['main_content'] = 'projects/projects_fundamental';
        

        // get map draw objects
        $fundamentaldata['map_geom'] = $this->projects_model->get_geom($fundamentaldata['project']['pid']);

        $this->breadcrumb->append_crumb(lang('B_PROJECTS'), '/projects');
        $this->breadcrumb->append_crumb($fundamentaldata['project']['projectname'] . ' (edit)', "/projects/$slug");
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        $this->headerdata['title'] = build_title($fundamentaldata['project']['projectname'] . ' (edit)');

        $this->headerdata['header_extra'] .= $this->load->view('projects/leaflet-draw-js', '', true);

        // Render HTML Page from view direcotry
        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('templates/tabcontent', $fundamentaldata);
        $this->load->view('templates/footer', $this->footer_data);
    }
    
    
    /**
    * Edit Financial
    * Edit financial tab for Project Edit
    *
    * @access public
    * @param string
    */
    public function edit_financial($params)
    {
        $slug = $params;
        $this->pid = $this->projects_model->check_user_project($slug, $this->uid);
        
        $financialdata['slug'] = $slug;
        $financialdata['vtab_position'] = 2;
        $financialdata['project'] = $this->projects_model->get_financial_data($slug, $this->uid);
        $financialdata['main_content'] = 'projects/projects_financial';
        
        $this->breadcrumb->append_crumb(lang('B_PROJECTS'), '/projects');
        $this->breadcrumb->append_crumb($financialdata['project']['projectname'] . ' (edit)', "/projects/$slug");
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        $this->headerdata['title'] = build_title($financialdata['project']['projectname'] . ' (edit)');

        // Render the page
        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('templates/tabcontent', $financialdata);
        $this->load->view('templates/footer', $this->dataLang);
    }
    
    /**
    * Edit regulatory
    * Edit regulatory tab for Project Edit
    *
    * @access public
    * @param string
    */
    public function edit_regulatory($params)
    {
        $slug = $params;
        $this->pid = $this->projects_model->check_user_project($slug, $this->uid);
        
        $regulatorydata['slug'] = $slug;
        $regulatorydata['vtab_position'] = 3;
        $regulatorydata['project'] = $this->projects_model->get_regulatory_data($slug, $this->uid);
        $regulatorydata['main_content'] = 'projects/projects_regulatory';
        
        $this->breadcrumb->append_crumb(lang('B_PROJECTS'), '/projects');
        $this->breadcrumb->append_crumb($regulatorydata['project']['projectname'] . ' (edit)', "/projects/$slug");
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        $this->headerdata['title'] = build_title($regulatorydata['project']['projectname'] . ' (edit)');

        // Render the page
        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('templates/tabcontent', $regulatorydata);
        $this->load->view('templates/footer', $this->dataLang);
    }


    /**
    * Edit Participants
    * Edit participants tab for Project Edit
    *
    * @access public
    * @param string
    */
    public function edit_participants($params)
    {
        $slug = $params;
        $this->pid = $this->projects_model->check_user_project($slug, $this->uid);
        
        $participantsdata['slug'] = $slug;
        $participantsdata['vtab_position'] = 4;
        $participantsdata['project'] = $this->projects_model->get_participants_data($slug, $this->uid);
        $participantsdata['main_content'] = 'projects/projects_participants';
        
        $this->breadcrumb->append_crumb(lang('B_PROJECTS'), '/projects');
        $this->breadcrumb->append_crumb($participantsdata['project']['projectname'] . ' (edit)', "/projects/$slug");
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        $this->headerdata['title'] = build_title($participantsdata['project']['projectname'] . ' (edit)');

        // Render the page
        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('templates/tabcontent', $participantsdata);
        $this->load->view('templates/footer', $this->dataLang);
    }

    /**
    * Edit Procurement
    * Edit procurement tab for Project Edit
    *
    * @access public
    * @param string
    */
    public function edit_procurement($params)
    {
        $slug = $params;
        $this->pid = $this->projects_model->check_user_project($slug, $this->uid);
        
        $procurementdata['slug'] = $slug;
        $procurementdata['vtab_position'] = 5;
        $procurementdata['project'] = $this->projects_model->get_procurement_data($slug, $this->uid);
        $procurementdata['main_content'] = 'projects/projects_procurement';
        
        $this->breadcrumb->append_crumb(lang('B_PROJECTS'), '/projects');
        $this->breadcrumb->append_crumb($procurementdata['project']['projectname'] . ' (edit)', "/projects/$slug");
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        $this->headerdata['title'] = build_title($procurementdata['project']['projectname'] . ' (edit)');
        
        // Render the page
        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('templates/tabcontent', $procurementdata);
        $this->load->view('templates/footer', $this->dataLang);
    }

    /**
    * Edit Files
    * Edit files tab for Project Edit
    *
    * @access public
    * @param string
    */
    public function edit_files($params)
    {
        $slug = $params;
        $this->pid = $this->projects_model->check_user_project($slug, $this->uid);
        
        $filesdata['slug'] = $slug;
        $filesdata['vtab_position'] = 6;
        $filesdata['project'] = $this->projects_model->get_files_data($slug, $this->uid);
        $filesdata['main_content'] = 'projects/projects_files';
        
        $this->breadcrumb->append_crumb(lang('B_PROJECTS'), '/projects');
        $this->breadcrumb->append_crumb($filesdata['project']['projectname'] . ' (edit)', "/projects/$slug");
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        $this->headerdata['breadcrumb'] = $filesdata['project']['projectname'] . ' (edit)';

        // Render the page
        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('templates/tabcontent', $filesdata);
        $this->load->view('templates/footer', $this->dataLang);
    }

    /**
     * Project file Uploading function
     * Common function for upload any file in Project Edit Page
     *
     * @param $slug
     * @param $fieldname
     * @param bool $required
     * @return mixed
     */

    public function proj_uploadfiles($slug, $fieldname, $required = true)
    {
        $file = upload_file('/' . PROJECT_IMAGE_PATH, $fieldname, '', $required);

        if ($file['error'] == '') {
            return $file;
        } else {
            $response = array();
            $response["status"]    = "error";
            $response["message"]    = array($fieldname =>$this->upload->display_errors('<label>', '</label>'));
            $response["isload"]    = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
    
            //return false when validation is not satisfied.
            return false;
        }
    }
    
    public function add_comment($params)
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('comment', 'Comment', 'trim|required');
        
        if ($this->form_validation->run() === true) {
            $this->projects_model->add_comment($params);
        } else {
            $response = array();
            $response["status"] = "error";
    
            $response["message"] = array('comment'=>form_error('comment'));
            $response["isload"] = "no";
                        
            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }
    
    public function delete_comment($params)
    {
        $this->projects_model->delete_comment($params);
    }

    // check if current user is project ownwer of is admin
    private function isAdminOrOwner($userid)
    {
        // if ($userid == sess_var('uid') || sess_var('admin_logged_in'))
        if ($userid == sess_var('uid')) {
            return true;
        }
        return false;
    }

    private function set_roi_validation_rules()
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_roi_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('project_roi_percent', 'Percent', 'trim|required|numeric');
        $this->form_validation->set_rules('project_roi_type', 'Type', 'trim|required');
        $this->form_validation->set_rules('project_roi_approach', 'Approach', 'trim|required');
    }

    private function set_design_issue_validation_rules()
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_design_issues_title', 'Title', 'trim|required');
        $this->form_validation->set_rules('project_design_issues_desc', 'Description', 'trim|required');
    }

    private function set_regulatory_validation_rules()
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_regulatory_desc', 'Description', 'trim|required');
    }

    private function set_engineering_validation_rules()
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_engineering_company', 'Company', 'trim|required');
        $this->form_validation->set_rules('project_engineering_role', 'Role', 'trim|required');
        $this->form_validation->set_rules('project_engineering_cname', 'Contact', 'trim|required');
        $this->form_validation->set_rules('project_engineering_challenges', 'Challenges', 'trim|required');
        $this->form_validation->set_rules('project_engineering_innovations', 'Innovations', 'trim|required');
    }

    private function set_environment_validation_rules()
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_environment_title', 'Title', 'trim|required');
        $this->form_validation->set_rules('project_environment_desc', 'Description', 'trim|required');
    }

    private function set_study_validation_rules()
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('project_studies_title', 'Title', 'trim|required');
        $this->form_validation->set_rules('project_studies_desc', 'Description', 'trim|required');
    }

    private function check_sort($value)
    {
        $allowed = array_keys($this->sort_options);
        $default = 1;

        if (in_array($value, $allowed)) {
            return $value;
        } else {
            return $default;
        }
    }

    /**
     * Callback validation rule for an interval
     * Returns true if both start_date and end_date are valid dates
     * and start_date >= end_date, or if either is blank
     *
     * @return bool
     */
    public function valid_period()
    {
        $start = $this->input->post('project_eststart', true);
        $end = $this->input->post('project_estcompletion', true);

        $is_valid = is_valid_period($start, $end, 'm/Y');

        return $is_valid;
    }

    /**
     * Callback validation rule
     * Returns true if Developer OR Sponsor field has value
     * Returns false if both are empty
     *
     * @return bool
     */
    public function isCompleted_developer_sponsor()
    {
        $dev = $this->input->post('project_developer', true);
        $spon = $this->input->post('project_sponsor', true);

        if (empty($dev) && empty($spon)) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * Validation callback
     * Returns true if an argument is in valid MM/YYYY format and between 1900 and 2199
     *
     * @param $value
     * @return bool
     */
    public function valid_monthyear_format($value)
    {
        $regex = "#^[01]\d/(19|20|21)\d{2}$#";
        if (preg_match($regex, $value) || $value == '') {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Validation callback
     * Returns true if an argument contains only alpha (supporting UTF)-numeric characters, underscores, dashes and spaces
     *
     * @param $value
     * @return bool
     */
    public function alpha_dash_space($value)
    {
        $regex = "/^([\pL\s\d_-])+$/u";
        return (! preg_match($regex, $value)) ? false : true;
    }

    private function set_discussion_common_validation_rules()
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('description', 'Description', 'trim|max_length[1024]');
//        $this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain alpha-numeric characters, underscores, dashes and spaces.');
    }

    private function set_create_discussion_validation_rules()
    {
        $this->set_discussion_common_validation_rules();
//        $this->form_validation->set_rules('project_id', 'Project', 'required|integer');
    }

    private function set_update_discussion_validation_rules()
    {
        $this->set_discussion_common_validation_rules();
    }

    private function project_updated_event_data($id)
    {
        // Fetch the project name by project id
        $project = $this->projects_model->find($this->pid, 'projectname');
        $page_analytics = array(
            'event' => array(
                'name' => 'Project Updated',
                'properties' => array(
                    'Project Id' => (int) $id,
                    'Project Name' => $project['projectname'],
                )
            )
        );

        return $page_analytics;
    }
}
