<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gviptv extends CI_Controller {

    //default class variables
    public $sess_uid;
    public $sess_logged_in;
    public $headerdata = array();

    /**
     * Constructor
     * Called when the object is created
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        //Session check for the Login Status, if not logged in then redirect to Home page
        if(!sess_var('admin_logged_in')) {
            redirect('', 'refresh');
        }

        //Load model for this controller
        $this->load->model('gviptv_model');

        //load form_validation library for default validation methods
        $this->load->library('form_validation');

        //Set Header Data for this page like title,bodyid etc
        $this->sess_uid	 = sess_var('admin_uid');

    }

    /**
     * Retrive a list of all forums.
     *
     */
    public function index()
    {
        $headers = array(
            'bodyid' => 'Forums',
            'bodyclass' => 'withvernav',
            'title' => 'View GVIP TV Videos | GViP Admin Interface',
            'js' => array(
                '/themes/js/plugins/jquery.dataTables.min.js',
                '/themes/js/plugins/chosen.jquery.min.js',
                '/themes/js/plugins/jquery.alerts.js'
            ),
            'pagejs' => array('/themes/js/custom/tables.js')
        );
        //$this->headerdata = $headers;

        // Load a list of all forums from the model.
        $rows = $this->gviptv_model->all();

        // Render HTML Page from views
        $this->load->view('templates/header', $headers);
        $this->load->view('templates/leftmenu');
        $this->load->view('gviptv/index', array(
            'main_content' => 'rows',
            'rows' => $rows,
        ));
        $this->load->view('templates/footer');
    }

    /**
     * Delete forum(s) entry(ies) by id(s)
     *
     */
    public function destroy($id) {

            if ($this->gviptv_model->delete($id)) {
                redirect("gviptv", 'refresh');
            } else {
                sendResponse(array(
                    'status' => 'success',
                    'msgtype' => 'error',
                    'msg' => 'Error while deleting GViP TV Video.'
                ));
            }
    }

    /**
     * Create a new forum entry
     *
     */
    public function create() {
        // Process updates first
        if ($this->input->post('submit')) {

                $now = date('Y-m-d H:i:s');
                $data = array(
                    'link' => $this->input->post('link'),
                    'thumbnail' => $this->input->post('thumbnail'),
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'category' => $this->input->post('category_id'),
                    'created_at' => $now
                );
                if ($id = $this->gviptv_model->create($data)) {
                    redirect("gviptv", 'refresh');
                }
        }

        // Then load the view
        $headers = array(
            'bodyid' => 'gviptv',
            'bodyclass' => 'withvernav',
            'title' => 'Add New GViP TV Video | GViP Admin Interface',
            'js' => array(
                '/themes/js/plugins/jquery.dataTables.min.js',
                '/themes/js/plugins/chosen.jquery.min.js',
                '/themes/js/plugins/jquery.alerts.js'
            ),
            'pagejs' => array('/themes/js/custom/tables.js')
        );

        // Render the page from views
        $this->load->view('templates/header', $headers);
        $this->load->view('templates/leftmenu');
        $this->load->view('gviptv/create');
        $this->load->view('templates/footer');
    }

    /**
     * Edit a specified forum entry by id
     * @param $id
     */
    public function edit($id) {
        // Convert $id to integer
        $id = (int)$id;


        if ($this->input->post('submit')) {

            $now = date('Y-m-d H:i:s');
            $input = array(
                'link' => $this->input->post('link'),
                'thumbnail' => $this->input->post('thumbnail'),
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'category' => $this->input->post('category'),
                'created_at' => $now
            );
            $this->update($id, $input);

        }

        $headers = array(
//            'bodyid' => 'Forums',
            'bodyclass' => 'withvernav',
            'title' => 'Edit GViP TV Video | GViP Admin Interface',
            'js' => array(
                '/themes/js/plugins/jquery.validate.min.js',
                '/themes/js/plugins/jquery.tagsinput.min.js',
                '/themes/js/plugins/charCount.js',
                '/themes/js/plugins/ui.spinner.min.js',
                '/themes/js/plugins/chosen.jquery.min.js',
                '/themes/js/plugins/jquery.dataTables.min.js',
                '/themes/js/plugins/jquery.bxSlider.min.js',
                '/themes/js/plugins/jquery.slimscroll.js'
            ),
            'pagejs' => array(
                '/themes/js/custom/forms.js',
//                '/themes/js/custom/tables.js',
                '/themes/js/custom/widgets.js'
            )
        );

        // Fetch necessary data
        $model = $this->gviptv_model;
        $details  = $model->find($id);

        $data = array(
            'details' => $details
        );

        // Render the page
        $this->load->view('templates/header', $headers);
        $this->load->view('templates/leftmenu');
        $this->load->view('gviptv/edit', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Update a specified forum entry
     *
     * @param int $id
     * @param array $input
     */
    private function update($id, $input) {

            $this->gviptv_model->update($id, $input);
            redirect('/gviptv/edit/' . $id, 'refresh');
    }

}
