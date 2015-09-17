<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum extends CI_Controller {

	/**
	 * Profile controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/admin.php/members
	 * So any other public methods not prefixed with an underscore will
	 * map to /admin.php/members/<method_name>
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
		$this->load->model('forum_model');
		
		//Set Header Data for this page like title,bodyid etc
		$this->sess_uid	 = sess_var('admin_uid');
		
	}

	public function config() {
		$CI =& get_instance();
		$CI->config->set_item( 'global_xss_filtering', FALSE );
	}

	/**
	* Index Method 
	* Called when no Method Passed to URL.
	*
	* @access public
	*/
	public function index()
	{
		redirect('forum/edit_forum','refresh');
	}
	
	public function edit_forum($params='')
	{
		$this->headerdata["bodyid"] 		= "Forum";
		$this->headerdata["bodyclass"] 		= "withvernav";
		$this->headerdata["title"] 			= "Edit Forum | ViP Admin";
		$this->headerdata["js"]				= array(
												"/themes/js/plugins/jquery.validate.min.js",
												"/themes/js/plugins/jquery.tagsinput.min.js",
												"/themes/js/plugins/charCount.js",
												"/themes/js/plugins/ui.spinner.min.js",
												"/themes/js/plugins/chosen.jquery.min.js",
												"/themes/js/plugins/jquery.dataTables.min.js",
												"/themes/js/plugins/jquery.bxSlider.min.js",
												"/themes/js/plugins/jquery.slimscroll.js"
												);
		$this->headerdata["pagejs"]	= array('/themes/js/custom/forms.js',"/themes/js/custom/tables.js",'/themes/js/custom/widgets.js');
		
		$this->headerdata["conditionalcss"]	= array(
												"[if IE 9]"=>"css/style.ie9.css",
												"[if IE 8]"=>"css/style.ie8.css"
										    	);
		$this->headerdata["conditionaljs"]	= array("[if lt IE 9]"=>"http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js");
		
	
		if($params != "")
		{
			$this->headerdata["title"] = "Edit Fourm | VIP Admin";
			$data = array(
				"headertitle" => "Edit Forum"
			);

		}
		else
		{
			$this->headerdata["title"] = "Edit Forum| VIP Admin";
			$data = array(
				"headertitle" => "Edit Forum"
			);
		}
		
		$forum_experties = array();
		$forum_projects = array();
		$forum_experties = $this->forum_model->get_all_users_checkbox();
		$forum_projects = $this->forum_model->get_projects_checkbox();
		$forum_detail 	= $this->forum_model->get_forum_detail();
			
		$data	= array('forum_experties'=>$forum_experties,'forum_projects'=>$forum_projects,'forum_detail'=>$forum_detail);
		
		
		$this->load->view("templates/header",$this->headerdata);
		$this->load->view("templates/leftmenu");
		$this->load->view('forum/edit_forum',$data);
		$this->load->view("templates/footer");

	}
	
	function update_forum()
	{
		//load form_validation library for default validation methods
		//$this->load->library('form_validation');
		
		if($result	=	$this->forum_model->update_forum())
		{
			redirect('/forum/edit_forum','refresh');
		}			
	
	}
	
	
/**
	* Uplpad User Photo method
	* upload profile picture and upldate his/her account detail
	*
	* @access public
	*/
	public function upload_banner()
	{
		
		$upload_banner = upload_image('/'.FORUM_IMAGE_PATH,'banner_filename',TRUE,array(array('width'=>'560','height'=>'69')));
		
		if($upload_banner['error']=='')
		{
			if($result	= $this->forum_model->upload_banner($upload_banner))
			{
				redirect('/forum/edit_forum','refresh');
			}

		}
		else
		{
			$this->edit_forum();
			return FALSE;
		}
			
	}
	
}



/* End of file forum.php */
/* Location: ./backend/controllers/profile.php */
?>