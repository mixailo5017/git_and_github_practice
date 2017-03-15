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

		//Session check for the Login Status, if logged in redirect to Account Settings Page
		if(!sess_var('admin_logged_in'))
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
		$data['experts'] = $this->algolia_model->get_all_experts();
		$data['headertitle'] = $this->headerdata['title'];

		$data["status"] = false;
		if($this->input->post("update") != ""){
			$data["status"] = $this->algolia_model->save_all_experts(); // Call model update method here, once implemented
		}

		$this->load->view('templates/header',$this->headerdata);
		$this->load->view('templates/leftmenu');
		$this->load->view('algolia/index',$data);
		$this->load->view('templates/footer');
	}

	/**
	 * @return array All expert data formatted for export to Algolia
	 */
	public function experts()
	{
		var_dump($this->algolia_model->get_all_experts());
	}

	/**
	 * @return array All project data formatted for export to Algolia
	 */
	public function projects()
	{
		var_dump($this->algolia_model->get_all_projects());
	}
	
}