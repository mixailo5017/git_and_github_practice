<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

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


		// check migrations
		$this->load->library('migration');

		// load image helper
		//$this->load->helper('img_helper'); //autoload

		// load activity model
		$this->load->model('activity_log_model');

		// force back down
		//$d = $this->migration->version(0); echo "<pre>"; var_dump( $d ); exit;
		if ( ! $this->migration->current())
		{
			show_error($this->migration->error_string());
		}


		//Set Header Data for this page like title,bodyid etc
		$this->headerdata["bodyclass"] = "withvernav";
		$this->headerdata["title"] = "Dashboard | GViP Admin Interface";
		$this->headerdata["js"]		= array("/themes/js/plugins/jquery.flot.min.js","/themes/js/plugins/jquery.flot.resize.min.js","/themes/js/plugins/jquery.slimscroll.js");
		$this->headerdata["pagejs"]	= array("/themes/js/custom/dashboard.js");
		$this->headerdata["conditionaljs"]	= array("[if lte IE 8]"=>"/themes/js/plugins/excanvas.min.js");
	}

	public function index()
	{
		$this->load->model('members_model');
		$data = $this->members_model->get_dashboard_members();

		// new activity log
		$data['project_updates']	= $this->activity_log_model->get_project_updates(10);
		$data['new_projects']		= $this->activity_log_model->get_new_projects(10);
		$data['member_updates']		= $this->activity_log_model->get_member_updates(10);

		$this->load->view('templates/header',$this->headerdata);
		$this->load->view('templates/leftmenu');
		$this->load->view('dashboard',$data);
		$this->load->view('templates/footer');
	}

	/**
	* Logout Method
	*
	* @access public
	*/
	public function logout()
	{
		$this->session->sess_destroy();
		redirect("",'refresh');
	}

}

/* End of file welcome.php */
/* Location: ./backend/controllers/welcome.php */