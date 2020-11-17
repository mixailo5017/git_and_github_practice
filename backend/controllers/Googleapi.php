<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GoogleApi extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/admin.php/googleapi
	 *	- or -  
	 * 		http://example.com/admin.php/googleapi/index
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /admin.php/googleapi/<method_name>
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
		
		$this->load->model("googleapi_model");
		
		$this->headerdata["bodyclass"] = "withvernav";
		$this->headerdata["title"] = "Google Analytics | GViP Admin Interface";
		//$this->headerdata["js"]		= array("/themes/js/plugins/jquery.flot.min.js","/themes/js/plugins/jquery.flot.resize.min.js","/themes/js/plugins/jquery.slimscroll.js");
		$this->headerdata["js"]		= array("/themes/js/plugins/jquery.slimscroll.js");
		$this->headerdata["conditionaljs"]	= array("[if lte IE 8]"=>"/themes/js/plugins/excanvas.min.js");
	}
 	
 	public function index()
 	{
 		redirect("googleapi/reports","refresh");
 	}
	 
	public function reports()
	{
		$data = array();
		$data["setting"] = $this->googleapi_model->get_ga_data();
		$data["headertitle"] = "Google Analytics";

		$this->loadViews('reports/gapi', $data);
	}
	
	public function setting()
	{
		$data = array();
		$data["headertitle"] = "Google Analytics Setting";
		
		$data["status"] = FALSE;
		if($this->input->post("update") != ""){
			$data["status"] = $this->googleapi_model->update_ga_data();
		}
		
		$data["setting"] = $this->googleapi_model->get_ga_data();
		
		$this->loadViews('reports/setting', $data);
	}

	public function projects()
	{
		$data['headertitle'] = 'Project Recency';

		$data['averageRecency'] = $this->googleapi_model->averageRecency();
		$data['recencyBuckets'] = $this->googleapi_model->recencyGroupings();

		$this->loadViews('reports/projects', $data);
	}
	
	/**
	 * Loads the various views required to compose the page
	 * @param  string $viewName Name of the view to load for the main part of the page
	 * @param  array  $data     Data to feed to the main view
	 */
	private function loadViews(string $viewName, array $data)
	{
		$this->load->view('templates/header',$this->headerdata);
		$this->load->view('templates/leftmenu');	
		$this->load->view($viewName, $data);
		$this->load->view('templates/footer');	
	}
	
}

/* End of file googleapi.php */
/* Location: ./backend/controllers/googleapi.php */