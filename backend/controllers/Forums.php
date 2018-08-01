<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forums extends CI_Controller {

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
		$this->load->model('forums_model');

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
            'title' => 'View Forums | GViP Admin Interface',
            'js' => array(
                '/themes/js/plugins/jquery.dataTables.min.js',
                '/themes/js/plugins/chosen.jquery.min.js',
                '/themes/js/plugins/jquery.alerts.js'
            ),
            'pagejs' => array('/themes/js/custom/tables.js')
        );
        //$this->headerdata = $headers;

        // Load a list of all forums from the model.
        $rows = $this->forums_model->all();
        $categories = $this->forums_model->categories();

        // Render HTML Page from views
        $this->load->view('templates/header', $headers);
        $this->load->view('templates/leftmenu');
        $this->load->view('forums/index', array(
            'main_content' => 'rows',
            'rows' => $rows,
            'categories' => flatten_assoc($categories, 'name', 'name')
        ));
        $this->load->view('templates/footer');
    }

    /**
     * Delete forum(s) entry(ies) by id(s)
     *
     */
    public function destroy() {
        $ids = $this->input->get('delids');

        if (count($ids) > 0) {
            if ($this->forums_model->delete($ids)) {
                sendResponse(array(
                    'status' => 'success',
                    'msgtype' => 'success',
                    'msg' => 'Forum(s) deleted successfully'
                ));
            } else {
                sendResponse(array(
                    'status' => 'success',
                    'msgtype' => 'error',
                    'msg' => 'Error while deleting forum(s).'
                ));
            }
        }
    }

    /**
     * Create a new forum entry
     *
     */
    public function create() {
        // Process updates first
        if ($this->input->post('submit')) {

            $this->set_create_validation_rules();

            if ($this->form_validation->run() === TRUE) {
                $now = date('Y-m-d H:i:s');
                $data = array(
                    'title' => $this->input->post('title'),
                    'category_id' => $this->input->post('category_id'),
                    'start_date' => null,
                    'end_date' => null,
                    'is_featured' => '0',
                    'status' => '0',
                    'created_at' => $now,
                    'updated_at' => $now
                );
                if ($id = $this->forums_model->create($data)) {
                    redirect("forums/edit/$id", 'refresh');
                }
            }
        }

        // Then load the view
        $headers = array(
            'bodyid' => 'Forums',
            'bodyclass' => 'withvernav',
            'title' => 'Add New Forum | GViP Admin Interface',
            'js' => array(
                '/themes/js/plugins/jquery.dataTables.min.js',
                '/themes/js/plugins/chosen.jquery.min.js',
                '/themes/js/plugins/jquery.alerts.js'
            ),
            'pagejs' => array('/themes/js/custom/tables.js')
        );

        // Fetch necessary data
        $categories = flatten_assoc($this->forums_model->categories(), 'id', 'name');

        // Render the page from views
        $this->load->view('templates/header', $headers);
        $this->load->view('templates/leftmenu');
        $this->load->view('forums/create', array('categories' => $categories));
        $this->load->view('templates/footer');
    }

    /**
     * Edit a specified forum entry by id
     * @param $id
     * @param string $selectedtab
     */
    public function edit($id, $selectedtab = '') {
        // Convert $id to integer
        $id = (int)$id;

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
                case 'banner':
                    $this->upload_image($id, $update);
                    break;
                case 'projects':
                    $this->update_projects($id, $input);
                    break;
                case 'experts':
                    $this->update_experts($id, $input);
                    break;
            }
        }

            $headers = array(
//            'bodyid' => 'Forums',
            'bodyclass' => 'withvernav',
            'title' => 'Edit Forum | GViP Admin Interface',
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
        $model = $this->forums_model;
        $details  = $model->find($id);
        $projects = $model->all_projects($id);
        $experts  = $model->all_members($id);
        $categories = flatten_assoc($model->categories(), 'id', 'name');

        $data = array(
            'details' => $details,
            'projects' => $projects,
            'experts' => $experts,
            'categories' => $categories,
        );

        // Render the page
        $this->load->view('templates/header', $headers);
        $this->load->view('templates/leftmenu');
        $this->load->view('forums/edit', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Update a specified forum entry
     *
     * @param int $id
     * @param array $input
     * @return bool
     */
    private function update($id, $input) {
        $this->set_update_validation_rules();

        if ($this->form_validation->run() === TRUE) {
            // Ensure we have required flags set
            $input['status'] = (isset($input['status'])) ? '1' : '0';
            $input['is_featured'] = (isset($input['is_featured'])) ? '1' : '0';

            // Convert empty strings to NULLs
            $input = array_map(function($value) {
                return $value === '' ? null : $value;
            }, $input);
            // Reformat dates
            if (! is_null($input['start_date'])) {
                $input['start_date'] = format_date($input['start_date'], 'Y-m-d');
            }
            if (! is_null($input['end_date'])) {
                $input['end_date'] = format_date($input['end_date'], 'Y-m-d');
            }
            // Decode iframe tags from html entities
            if (isset($input['content']) && ! is_null($input['content'])) {
                $input['content'] = decode_iframe($input['content']);
            }

            $this->forums_model->update($id, $input);
            redirect('/forums/edit/' . $id, 'refresh');
        }
    }

    /**
     * Upload an image and update a specified forum entry
     *
     * @param string $id
     * @param string $image_type
     * @return mixed
     */
    private function upload_image($id, $image_type) {
        $allowed_types = array('photo', 'banner');

        if (! in_array($image_type, $allowed_types)) {
            return FALSE;
        }

        $sizes = array(
            'photo' => array(
                array('width'=>'396','height'=>'396'),
                array('width'=>'198','height'=>'198')
            ),
            'banner' => array(
                array('width'=>'600','height'=>'69')
            )
        );

        $image = upload_image(FORUM_IMAGE_PATH, 'photo_filename', TRUE, $sizes[$image_type]);

        if ($image['error'] == '') {
            $this->forums_model->update($id, array($image_type => $image['file_name']));
            redirect("/forums/edit/$id/#images", 'refresh');
        }
    }

    /**
     * Update a list of projects accosiated with the forum
     *
     * @param $id
     * @param $input
     */
    private function update_projects($id, $input) {
        // Convert the type of project ids from string to integer
        $data = array_map(function($value) {
            return (int) $value;
        }, $input['projects']);

        $this->forums_model->sync_projects($id, $data);
        redirect("/forums/edit/$id/#projects", 'refresh');
    }

    /**
     * Update a list of members(experts) accosiated with (attending) the forum
     *
     * @param int $id
     * @param array $input
     */
    private function update_experts($id, $input) {
        // Convert the type of member (expert) ids from string to integer
        $data = array_map(function($value) {
            return (int) $value;
        }, $input['members']);

        $this->forums_model->sync_members($id, $data);

        redirect("/forums/edit/$id/#experts", 'refresh');
    }

    /**
     * Callback validation rule for an interval
     * Returns true if both start_date and end_date are valid dates
     * and start_date >= end_date
     *
     * @return bool
     */
    public function valid_period() {
        $start = $this->input->post('start_date', TRUE);
        $end = $this->input->post('end_date', TRUE);

        if ($start === false && $end === false) {
            return true;
        }

        $is_valid = is_valid_period($start, $end);

        return $is_valid;
    }

    /**
     * Validation callback
     * Returns true if an argument contains only alpha (supporting UTF)-numeric characters, underscores, dashes and spaces
     *
     * @param $value
     * @return bool
     */
    public function alpha_dash_space($value) {
        $regex = "/^([\pL\s\d_-])+$/u";
        return (! preg_match($regex, $value)) ? FALSE : TRUE;
    }

    /**
     * Set validation rules for update and create methods
     *
     */
    private function set_common_validation_rules() {
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('title', 'Title', 'trim|required|callback_alpha_dash_space');
        $this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain alpha-numeric characters, underscores, dashes and spaces.');
    }

    /**
     * Set validation rules for forum update method
     *
     */
    private function set_update_validation_rules() {
        $this->set_common_validation_rules();

        $this->form_validation->set_rules('start_date', 'Start Date', 'trim|callback_valid_period');
        $this->form_validation->set_rules('end_date', 'End Date', 'trim');
        $this->form_validation->set_rules('category_id', 'Category Id', 'required|integer'); // Needed for set_value to work properly
        $this->form_validation->set_rules('register_url', 'Register URL', 'trim');
        $this->form_validation->set_rules('meeting_url', 'Meeting URL', 'trim');
        $this->form_validation->set_rules('venue', 'Venue', 'trim');
        $this->form_validation->set_rules('status', ' Forum enabled', ''); // Needed for set_value to work properly
        $this->form_validation->set_rules('is_featured', 'Featured forum', ''); // Needed for set_value to work properly
        $this->form_validation->set_rules('venue_url', 'Venue URL', 'trim');
        $this->form_validation->set_rules('venue_address', 'Venue Address', 'trim');
        $this->form_validation->set_rules('venue_lat', 'Venue Latitude', 'trim|decimal|greater_than[-91]|less_than[91]');
        $this->form_validation->set_rules('venue_lng', 'Venue Longitude', 'trim|decimal|greater_than[-181]|less_than[181]');
        $this->form_validation->set_rules('content', 'Forum Description', 'trim');

        $this->form_validation->set_message('valid_period', 'Forum dates are invalid.');
    }

    /**
     * Set validation rules for forum create method
     *
     */
    private function set_create_validation_rules() {
        $this->set_common_validation_rules();

        $this->form_validation->set_rules('category_id', 'Category Id', 'required|integer');
    }
}