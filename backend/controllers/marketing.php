<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marketing extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/admin.php/welcome
	 *	- or -
	 * 		http://example.com/admin.php/welcome/index
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /admin.php/welcome/<method_name>
	 */
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

		// Session check for the Login Status, redirect to Account Settings Page
		// unless logged in as an admin user 
		// OR controller is being run from the command line
		if(!sess_var('admin_logged_in') && !$this->input->is_cli_request())
		{
			redirect('','refresh');
		}

		// load model
		$this->load->model('members_model');
		$this->load->model('projects_model');

		//Set Header Data for this page like title,bodyid etc
		$this->headerdata["bodyclass"] = "withvernav";
		$this->headerdata["title"] = "Weekly Email | GViP Admin";
	}

	/**
	 * Shows index page allowing user to create the HTML for an email
	 * 
	 * @return HTML
	 */
	public function index()
	{
		$data['headertitle'] = $this->headerdata['title'];

		

		$this->load->view('templates/header', $this->headerdata);
		$this->load->view('templates/leftmenu');
		$this->load->view('marketing/index', $data);
		$this->load->view('templates/footer');
	}

	/**
	 * Generate HTML to send out as an email, using the weekly email template
	 * @return HTML Shows a page from which the HTML can be copied to clipboard
	 */
	public function generatehtml()
	{
		$data['headertitle'] = $this->headerdata['title'];

		$expertURLs = array_filter($this->input->post('experts') ?: []);
		$projectURLs = array_filter($this->input->post('projects') ?: []);
		
		if (count($expertURLs) < 4 || count($projectURLs) < 4) {
			$data['error'] = "You didn't include enough experts/projects! Please go back and ensure all fields are completed.";
		}

		$expertsData = [];
		$requiredExpertFields = "uid, firstname, lastname, title, organization";
		foreach ($expertURLs as $expertURL) {
			if (!preg_match('/\d+$/', $expertURL, $matches)) continue;
			$uid = (int) $matches[0];
			$expertsData[] = $this->members_model->find($uid, $requiredExpertFields);
		}
		var_dump($expertsData); die;

		$this->load->view('templates/header', $this->headerdata);
		$this->load->view('templates/leftmenu');
		$this->load->view('marketing/generatehtml', $data);
		$this->load->view('templates/footer');
	}
}