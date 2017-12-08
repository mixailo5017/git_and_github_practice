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
	 * @return void Shows a page from which the HTML can be copied to clipboard
	 */
	public function generatehtml()
	{
		$data['headertitle'] = $this->headerdata['title'];
		$data['errors'] = [];

		$expertURLs = array_filter($this->input->post('experts') ?: []);
		$projectURLs = array_filter($this->input->post('projects') ?: []);
		
		if (count($expertURLs) < 4 || count($projectURLs) < 4) {
			$data['errors'][] = "You didn't include enough experts/projects! Please go back and ensure all fields are completed.";
		}

		$expertsData = array_filter($this->get_experts_data_for_email($expertURLs));
		
		if (count($expertsData) < 4) {
			$data['errors'][] = "Not all the experts appear to exist in the database. Please check you copied the URLs correctly!";
		}
		foreach ($expertsData as &$expert) {
			$expert['imageURL'] = "https://www.gvip.io" . expert_image($expert['userphoto'], 120);
		}

		$projectsData = array_filter($this->get_projects_data_for_email($projectURLs));
		if (count($projectsData) < 4) $data['errors'][] = "Not all the projects appear to exist in the database. Please check you copied the URLs correctly!";
		foreach ($projectsData as &$project) {
			$project['imageURL'] = "https://www.gvip.io" . project_image($project['projectphoto'], 120);
		}

		$data = array_merge(compact('expertsData', 'projectsData'), $data);

		$this->load->view('templates/header', $this->headerdata);
		$this->load->view('templates/leftmenu');
		$this->load->view('marketing/generatehtml', $data);
		$this->load->view('templates/footer');
	}

	/**
	 * Fetch from DB the experts data required for the weekly email template
	 * @param  array $expertURLs Array of strings containing URLs for expert profile pages
	 * @return array             Array of associative arrays each containing info on an expert
	 */
	private function get_experts_data_for_email($expertURLs)
	{
		$expertsData = [];
		$requiredExpertFields = "uid, firstname, lastname, title, organization, userphoto";
		
		// TODO: Consider implementing (or finding) a new method to retrieve all rows in a single query
		foreach ($expertURLs as $expertURL) {
			if (!preg_match('/\d+$/', $expertURL, $matches)) continue;
			$uid = (int) $matches[0];
			$expertsData[] = $this->members_model->find($uid, $requiredExpertFields);
		}

		return $expertsData;
	}

	/**
	 * Fetch from DB the projects data required for the weekly email template
	 * @param  array $projectURLs Array of strings containing URLs for project profile pages
	 * @return array             Array of associative arrays each containing info on a project
	 */
	private function get_projects_data_for_email($projectURLs)
	{
		$projectsData = [];
		$requiredProjectFields = "slug, projectname, projectphoto";

		// TODO: Consider implementing (or finding) a new method to retrieve all rows in a single query
		foreach ($projectURLs as $projectURL) {
			if (!preg_match('/[^\/]+$/', $projectURL, $matches)) continue;
			$slug = $matches[0];
			$projectsData[] = $this->projects_model->find_from_slug($slug, $requiredProjectFields);
		}

		return $projectsData;
	}
}