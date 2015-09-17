<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {

	public $headerdata = array();
	
	public function __construct()
	{
		parent::__construct();
		
		//Session check for the Login Status, if already logged in redirect to Dashboard page
		if (sess_var('admin_logged_in')) {
			redirect('dashboard', 'refresh');
		}
		
		//Set Header Data for this page like title,bodyid etc
		$this->headerdata['bodyclass'] = 'loginpage';
		$this->headerdata['title'] = 'GViP Admin';
		
		//Load Home Model for this controller 
		$this->load->model('index_model');
	}

    /**
     * Default view
     */
    public function index()
	{
        $login_failed = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->validate()) {
                if ($this->login_check()) {
                    redirect('dashboard', 'refresh');
                } else {
                    $login_failed =	true;
                }
            }
        }

		$headerdata = $this->headerdata;
		// Render the page
		$this->load->view('index', compact('headerdata', 'login_failed'));
	}


    private function validate()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="loginmsg">','</div>');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[32]');

        if ($this->form_validation->run() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    private function login_check()
	{
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);

        return $this->index_model->validate_login($username, $password);
	}
}
