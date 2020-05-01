<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Stimulus extends CI_Controller
{

    //public class variables
    protected $headerdata = array();
    protected $footer_data = array();

    public function __construct()
    {

        parent::__construct();

        // If the current user doesn't have access to the forum show 404
        if (!in_array(Auth::id(), INTERNAL_USERS)) {
            if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != 'gvip' || !in_array($_SERVER['PHP_AUTH_PW'], PWD_FOR_STIM)) {
                header('WWW-Authenticate: Basic realm="MyProject"');
                header('HTTP/1.0 401 Unauthorized');
                die('Access Denied');
            }
        }

        $languageSession = sess_var('lang');
        get_language_file($languageSession);

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

        $filter = array(
            'country' => $this->input->get_post('country', true),
            'sector' => $this->input->get_post('sector', true),
            'stage' => $this->input->get_post('stage', true),
            'searchtext' => $this->input->get_post('searchtext', true)
        );
        array_walk($filter, function (&$value, $key) {
            $value = $value ? : '';
        });

        // Fetch projects and members (experts) accociated with the forum
        $projects = $model->projects($id, 'pid, slug, projectname, projectphoto, p.sector, p.country, p.lat, p.lng, p.totalbudget, p.sponsor, p.stage, p.subsector, p.location, p.description', array('p.id' => 'random'), 500, 0, true, $filter);
        $members = $model->get_members_for_forum_homepage($id);

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
            'forums_by_categories' => $forums_by_categories,
            'filter'       => $filter,

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
        $this->load->view('stimulus/header_stimulus', $this->headerdata);
        $this->load->view('stimulus/show', $data);
        $this->load->view('templates/footer', $this->footer_data);
    }

    /**
     * Display a paginated list of all projects associated with the forum
     *
     * @param $id
     */
    public function projects($id) {

        // If the user is not logged in then redirect to the login page
        auth_check();

        $perpage =	12;
        $page = $this->input->get_post('per_page', TRUE);

        $details = $this->forums_model->find($id, 'f.id, title');

        if (empty($details)) {
            redirect('stimulus', 'refresh');
            exit;
        }

        $rows = $this->forums_model->projects($id, 'pid, slug, projectname, projectphoto, p.country, p.sector, stage', null, $perpage, $page, true);
        $total = (count($rows) > 0) ? $rows[0]['row_count'] : 0;

        $config = array (
            'base_url'   => "/stimulus/projects/$id?",
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

        $this->breadcrumb->append_crumb(lang('B_FORUMS'), '/stimulus');
        $this->breadcrumb->append_crumb($details['title'], '/stimulus/' . $details['id']);
        $this->breadcrumb->append_crumb(lang('B_PROJECTS'), '/stimulus/projects/' . $details['id']);
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        $this->headerdata['title'] = build_title(lang('ForumProjects'));

        // Render the page
        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('stimulus/projects', $data);
        $this->load->view('templates/footer', $this->footer_data);

    }

}