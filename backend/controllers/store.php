<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Store extends CI_Controller
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
        $this->load->model('store_items_model');

        // load form_validation library for default validation methods
        // TODO: move to methods where it's actualy needed (store, update...)
//        $this->load->library('form_validation');

        //Set Header Data for this page like title,bodyid etc
        $this->sess_uid = sess_var('admin_uid');
    }

    /**
     * Retrive a list of all entries.
     *
     */
    public function index()
    {
        $headers = array(
            'bodyid' => 'store',
            'bodyclass' => 'withvernav',
            'title' => 'View Store Items | GViP Admin Interface',
            'js' => array(
                '/themes/js/plugins/jquery.dataTables.min.js',
                '/themes/js/plugins/chosen.jquery.min.js',
                '/themes/js/plugins/jquery.alerts.js'
            ),
            'pagejs' => array('/themes/js/custom/tables.js')
        );
        //$this->headerdata = $headers;

        // Load a list of all entries from the model.
        $rows = $this->store_items_model->all();

        // Render HTML Page from views
        $this->load->view('templates/header', $headers);
        $this->load->view('templates/leftmenu');
        $this->load->view('store/index', compact('rows'));
        $this->load->view('templates/footer');
    }

    /**
     * Delete entry(ies) by id(s)
     *
     */
    public function destroy() {
        $ids = $this->input->get('delids');

        if (count($ids) > 0) {
            if ($this->store_items_model->delete($ids)) {
                sendResponse(array(
                    'status' => 'success',
                    'msgtype' => 'success',
                    'msg' => 'Store item(s) deleted successfully'
                ));
            } else {
                sendResponse(array(
                    'status' => 'success',
                    'msgtype' => 'error',
                    'msg' => 'Error while deleting store item(s).'
                ));
            }
        }
    }

    /**
     * Create a new entry
     *
     */
    public function create() {
        // Process updates first
        if ($this->input->post('submit')) {

            $this->set_validation_rules();

            if ($this->form_validation->run() === TRUE) {
                $now = date('Y-m-d H:i:s');
                $data = array(
                    'title' => $this->input->post('title'),
                    'url' => $this->input->post('url'),
                    'created_at' => $now,
                    'updated_at' => $now
                );
                if ($id = $this->store_items_model->create($data)) {
                    redirect("store/edit/$id", 'refresh');
                }
            }
        }

        // Then load the view
        $headers = array(
            'bodyid' => 'store',
            'bodyclass' => 'withvernav',
            'title' => 'Add New Store Item | GViP Admin Interface',
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
        $this->load->view('store/create');
        $this->load->view('templates/footer');
    }

    /**
     * Edit the specified entry by id
     * @param $id
     */
    public function edit($id) {
        // Convert $id to integer
        $id = (int) $id;

        // Process updates first
        if ($this->input->post('submit')) {
            $update = $this->input->post('update');
            // Grab all input and remove submit
            $input = array_diff_key($this->input->post(NULL, TRUE), array(
                'submit' => null,
                'update' => null
            ));

            switch ($update) {
                case 'general':
                    $this->update($id, $input);
                    break;
                case 'photo':
                    $this->upload_image($id, $update);
                    break;
            }
        }

        $headers = array(
            'bodyid' => 'store',
            'bodyclass' => 'withvernav',
            'title' => 'Edit Store Item | GViP Admin Interface',
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
                '/themes/js/custom/tables.js',
                '/themes/js/custom/widgets.js'
            )
        );

        // Fetch necessary data
        $details  = $this->store_items_model->find($id);

        // Render the page
        $this->load->view('templates/header', $headers);
        $this->load->view('templates/leftmenu');
        $this->load->view('store/edit', compact('details'));
        $this->load->view('templates/footer');
    }

    /**
     * Update the specified entry
     *
     * @param int $id
     * @param array $input
     * @return bool
     */
    private function update($id, $input) {
        $this->set_validation_rules();

        if ($this->form_validation->run() === TRUE) {
            // Convert empty strings to NULLs
            $input = array_map(function($value) {
                return $value === '' ? null : $value;
            }, $input);

            $this->store_items_model->update($id, $input);
            redirect("/store/edit/$id", 'refresh');
        }
    }

    /**
     * Upload an image and update the specified entry
     *
     * @param string $id
     * @return mixed
     */
    private function upload_image($id) {
        $sizes = array(
            array('width' => '50', 'height' => '50'),
        );

        $image = upload_image(STORE_IMAGE_PATH, 'photo_filename', TRUE, $sizes, '');
//dd($image);
        if ($image['error'] == '') {
            $this->store_items_model->update($id, array('photo' => $image['file_name']));
            redirect("/store/edit/$id", 'refresh');
        }
    }

    /**
     * Set validation rules for update and create methods
     *
     */
    private function set_validation_rules() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('url', 'URL', 'trim|required');
    }

}