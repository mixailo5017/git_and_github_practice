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
		$this->load->view('templates/header',$this->headerdata);
		$this->load->view('templates/leftmenu');	
		$this->load->view('reports/gapi',$data);
		$this->load->view('templates/footer');	
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
		
		$this->load->view('templates/header',$this->headerdata);
		$this->load->view('templates/leftmenu');	
		$this->load->view('reports/setting',$data);
		$this->load->view('templates/footer');	
	}
	
	
}

/* End of file googleapi.php */
/* Location: ./backend/controllers/googleapi.php */