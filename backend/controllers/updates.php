<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Updates extends CI_Controller
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
        $this->load->model('updates_model');

        //Set Header Data for this page like title,bodyid etc
        $this->sess_uid = sess_var('admin_uid');
    }

    public function show($id)
    {
        $update = $this->updates_model->find($id);

        $headers = array(
            'bodyid' => '',
            'bodyclass' => 'withvernav',
            'title' => 'Manage Feed | GViP Admin',
            'js' => array(
                '/themes/js/plugins/chosen.jquery.min.js',
                '/themes/js/plugins/jquery.alerts.js'
            ),
        );

        $this->load->view('templates/header', $headers);
        $this->load->view('templates/leftmenu');
        $this->load->view('updates/show', compact('update'));
        $this->load->view('templates/footer');
    }


    /**
     * Retrive a list of all entries.
     *
     */
    public function index()
    {
        $filter = array(
            'id' => '',
            'author_id' => '',
            'project_id' => '',
            'created_at' => '',
        );

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = $this->input->post(NULL, TRUE);

            if ($input !== FALSE) {
                $filter = array_merge($filter, array_intersect_key($input, $filter));
            }
            if (!empty($filter['created_at'])) {
                $filter['created_at'] = format_date($filter['created_at'], 'Y-m-d', 'm/d/Y');
            }
        }

        $headers = array(
            'bodyid' => 'store',
            'bodyclass' => 'withvernav',
            'title' => 'Comment Feed | GViP Admin',
            'js' => array(
                '/themes/js/plugins/jquery.dataTables.min.js',
                '/themes/js/plugins/chosen.jquery.min.js',
                '/themes/js/plugins/jquery.alerts.js'
            ),
            'pagejs' => array('/themes/js/custom/tables.js')
        );
        //$this->headerdata = $headers;

        // Load a list of all entries from the model.
        $filtered = array_filter($filter);
        if (empty($filtered)) {
            $rows = array();
        } else {
            $filter['deleted'] = true; // Include deleted records
            $rows = $this->updates_model->all($filter);
        }
        $authors = array('' => 'Select an author') + $this->updates_model->authors_list();
        $projects = array('' => 'Select a project') + $this->updates_model->projects_list();

        // Render the page
        $this->load->view('templates/header', $headers);
        $this->load->view('templates/leftmenu');
        $this->load->view('updates/index', compact('rows', 'authors', 'projects', 'filter'));
        $this->load->view('templates/footer');
    }

    /**
     * Restore an entry(ies) by id
     * @param $id
     */
    public function restore($id)
    {
        $this->delete_restore($id, 'restore');
    }

    /**
     * Soft delete an entry(ies) by id
     * @param $id
     */
    public function delete($id)
    {
        $this->delete_restore($id, 'delete');
    }

    /**
     * @param $id
     * @param $action
     */
    private function delete_restore($id, $action)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($id)) return;

        $id = (int) $id;

        if ($this->updates_model->{$action}($id)) {
            $response = array(
                'status' => 'success',
                'msgtype' => 'success',
                'msg' => "The comment {$action}d successfully."
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
}