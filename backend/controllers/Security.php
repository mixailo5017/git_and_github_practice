<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Security extends CI_Controller {

	/**
	 * Security controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/admin.php/security
	 * So any other public methods not prefixed with an underscore will
	 * map to /admin.php/security/<method_name>
	 */
	
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
		if(!sess_var('admin_logged_in'))
		{
			redirect('','refresh');
		}
		
		//Load Profile Model for this controller
		$this->load->model('security_model');
		
		//Set Header Data for this page like title,bodyid etc
		$this->sess_uid	 = sess_var('admin_uid');
		
	}

	/**
	* Index Method 
	* Called when no Method Passed to URL.
	*
	* @access public
	*/
	public function index()
	{
		redirect('security/banning','refresh');
	}
	
	public function banning()
	{
		$this->headerdata ["bodyid"] 		= "Profile";
		$this->headerdata ["bodyclass"] 	= "withvernav";
		$this->headerdata["title"] 			= "User Banning | GViP Admin Interface";
		$this->headerdata["js"]				= array(
												"/themes/js/plugins/jquery.validate.min.js",
												"/themes/js/plugins/jquery.tagsinput.min.js",
												"/themes/js/plugins/charCount.js",
												"/themes/js/plugins/ui.spinner.min.js",
												"/themes/js/plugins/chosen.jquery.min.js"
												);
		$this->headerdata["pagejs"]			= array("/themes/js/custom/forms.js");
		
		$this->headerdata["conditionalcss"]	= array(
												"[if IE 9]"=>"css/style.ie9.css",
												"[if IE 8]"=>"css/style.ie8.css"
										    	);
		$this->headerdata["conditionaljs"]	= array("[if lt IE 9]"=>"http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js");
		$this->headerdata["title"] = "User Banning | GViP Admin Interface";

		$data = array();
		$data["headertitle"] = "User Banning";
		
		$data["status"] = FALSE;
		if($this->input->post("update") != ""){
			$data["status"] = $this->security_model->update_banning_data();
		}
		
		$data["security"] = $this->security_model->get_banning_data();

		
		$this->load->view("templates/header",$this->headerdata);
		$this->load->view("templates/leftmenu");
		$this->load->view('security/banning',$data);
		$this->load->view("templates/footer");
	}
	
	public function throttling()
	{
		$this->headerdata ["bodyid"] 		= "Profile";
		$this->headerdata ["bodyclass"] 	= "withvernav";
		$this->headerdata["title"] 			= "Throttling Configuration | GViP Admin Interface";
		$this->headerdata["js"]				= array(
												"/themes/js/plugins/jquery.validate.min.js",
												"/themes/js/plugins/jquery.tagsinput.min.js",
												"/themes/js/plugins/charCount.js",
												"/themes/js/plugins/ui.spinner.min.js",
												"/themes/js/plugins/chosen.jquery.min.js"
												);
		$this->headerdata["pagejs"]			= array("/themes/js/custom/forms.js");
		
		$this->headerdata["conditionalcss"]	= array(
												"[if IE 9]"=>"css/style.ie9.css",
												"[if IE 8]"=>"css/style.ie8.css"
										    	);
		$this->headerdata["conditionaljs"]	= array("[if lt IE 9]"=>"http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js");
		$this->headerdata["title"] = "Throttling Configuration | GViP Admin Interface";

		$data = array();
		$data["headertitle"] = "Throttling Configuration";
		
		$data["status"] = FALSE;
		if($this->input->post("update") != ""){
			$data["status"] = $this->security_model->update_throttling_data();
		}
		
		$data["throttling"] = $this->security_model->get_throttling_data();
		
		$this->load->view("templates/header",$this->headerdata);
		$this->load->view("templates/leftmenu");
		$this->load->view('security/throttling',$data);
		$this->load->view("templates/footer");
	}


}

/* End of file security.php */
/* Location: ./backend/controllers/security.php */
?>