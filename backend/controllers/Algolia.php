<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Algolia extends CI_Controller {

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
		$this->load->model('algolia_model');

		//Set Header Data for this page like title,bodyid etc
		$this->headerdata["bodyclass"] = "withvernav";
		$this->headerdata["title"] = "Algolia | GViP Admin";
	}

	/**
	 * Shows index page allowing user to run export functions
	 * If POST contains a value for 'update', perform export function
	 * 
	 * @return HTML
	 */
	public function index()
	{
		$data['headertitle'] = $this->headerdata['title'];

		$data["status"] = false;
		$indexToUpdate = $this->input->post("update");
		if($indexToUpdate){
			$data["status"] = $this->updateAlgolia($indexToUpdate);
		}

		$this->load->view('templates/header',$this->headerdata);
		$this->load->view('templates/leftmenu');
		$this->load->view('algolia/index',$data);
		$this->load->view('templates/footer');
	}

	/**
	 * Updates either the experts or the projects index in Algolia
	 * @param  [string] $indexToUpdate either 'projects' or 'experts'
	 * @return [mixed]                returns 'projects' or 'experts' if update is successful, otherwise false
	 */
	public function updateAlgolia($indexToUpdate)
	{
		switch ($indexToUpdate) {
			case 'experts':
				return $this->algolia_model->save_all_experts();
			case 'projects':
				return $this->algolia_model->save_all_projects();
		}

		return false;
	}
	
}