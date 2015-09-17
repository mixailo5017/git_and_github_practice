<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Discussions extends CI_Controller
{
    public $sess_uid;
    public $sess_logged_in;
    public $headerdata = array();

    public function __construct()
    {
        parent::__construct();

        // Session check for the Login Status, if not logged in then redirect to Home page
        if (! sess_var('admin_logged_in')) {
            redirect('', 'refresh');
        }

        // Load model for this controller
        $this->load->model('discussions_model');

        //Set Header Data for this page like title,bodyid etc
        $this->sess_uid = sess_var('admin_uid');
    }

    public function members($id, $action, $member_id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
            ! in_array($action, array('allow', 'deny'))) return;

        if ($this->discussions_model->$action($id, $member_id)) {
            $response = array(
                'status' => 'success',
                'msgtype' => 'success',
                'msg' => 'The access to the discussion has been successfully ' . ($action == 'allow' ? 'allowed' : 'denied') . '.'
            );
        } else {
            $response = array(
                'status' => 'error',
                'msgtype' => 'error',
                'msg' => "Error while trying to {$action} the comment."
            );
        }

        sendResponse($response);
        exit;
    }

    public function edit($id)
    {
        // Process POST request first
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->load->library('form_validation');
            $this->set_update_validation_rules();

            if ($this->form_validation->run() === TRUE) {

                $input = $this->input->post(NULL, TRUE);

                if ($this->discussions_model->update($id, $input)) {
                    redirect('discussions', 'refresh');
                }
            }
        }

        $discussion = $this->discussions_model->find($id);
        $experts = $this->discussions_model->experts($id, true);

        $headers = array(
            'bodyid' => '',
            'bodyclass' => 'withvernav',
            'title' => 'Discussions | GViP Admin',
            'js' => array(
                '/themes/js/plugins/chosen.jquery.min.js',
                '/themes/js/plugins/jquery.alerts.js',
                '/themes/js/plugins/jquery.dataTables.min.js',
            ),
            'pagejs' => array('/themes/js/custom/tables.js')
        );

        // Render the page
        $this->load->view('templates/header', $headers);
        $this->load->view('templates/leftmenu');
        $this->load->view('discussions/edit', compact('discussion', 'experts'));
        $this->load->view('templates/footer');
    }

    public function index()
    {
        $headers = array(
            'bodyid' => 'store',
            'bodyclass' => 'withvernav',
            'title' => 'Discussions | GViP Admin',
            'js' => array(
                '/themes/js/plugins/jquery.dataTables.min.js',
                '/themes/js/plugins/chosen.jquery.min.js',
                '/themes/js/plugins/jquery.alerts.js'
            ),
            'pagejs' => array('/themes/js/custom/tables.js')
        );

        $rows = $this->discussions_model->all(0, 0, array('deleted' => true));
        $projects = array('' => 'Select a project') + $this->discussions_model->projects_list(false);

        // Render the page
        $this->load->view('templates/header', $headers);
        $this->load->view('templates/leftmenu');
        $this->load->view('discussions/index', compact('rows', 'projects'));
        $this->load->view('templates/footer');
    }

    public function create()
    {
        // Process POST request first
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->load->library('form_validation');
            $this->set_create_validation_rules();

            if ($this->form_validation->run() === TRUE) {

                $input = $this->input->post(NULL, TRUE);

                if ($id = $this->discussions_model->create($input)) {
                    redirect("discussions/edit/$id", 'refresh');
                }
            }
        }

        // Then load the view
        $headers = array(
            'bodyid' => '',
            'bodyclass' => 'withvernav',
            'title' => 'Add New Discussion | GViP Admin',
            'js' => array(
//                '/themes/js/plugins/jquery.dataTables.min.js',
                '/themes/js/plugins/chosen.jquery.min.js',
                '/themes/js/plugins/jquery.alerts.js'
            ),
//            'pagejs' => array('/themes/js/custom/tables.js')
        );

        // Fetch necessary data
        $projects = array('' => 'Select a project') + $this->discussions_model->projects_list(true);

        // Render the page from views
        $this->load->view('templates/header', $headers);
        $this->load->view('templates/leftmenu');
        $this->load->view('discussions/create', compact('projects'));
        $this->load->view('templates/footer');
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
        return (! preg_match($regex, $value)) ? FALSE : TRUE;
    }

    private function set_common_validation_rules()
    {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('description', 'Description', 'trim|max_length[1024]');
//        $this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain alpha-numeric characters, underscores, dashes and spaces.');
    }

    private function set_create_validation_rules()
    {
        $this->set_common_validation_rules();
        $this->form_validation->set_rules('project_id', 'Project', 'required|integer');
    }

    private function set_update_validation_rules()
    {
        $this->set_common_validation_rules();
    }
}