<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Projects extends CI_Controller {

	//public class variables
	public $headerdata 	= array();
	public $uid			= "";
	public $pid			= "";
	
	/**
	* Constructor
	* Called when the object is created 
	*
	* @access public
	*/
	public function __construct()
	{
		parent::__construct();
		
		if(!sess_var('admin_logged_in'))
		{
			redirect('','refresh');
		}

		
		//Load Profile Model for this controller
		$this->load->model('projects_model');
		
		//load form_validation library for default validation methods
		$this->load->library('form_validation');
		//load breadcrumb library
		$this->load->library('breadcrumb');
		
		//Set Header Data for this page like title,bodyid etc
		$this->headerdata["bodyid"] = "projects";
		$this->headerdata["bodyclass"] = "";
		$this->headerdata["title"] = "Edit Project";
		$this->uid	= sess_var("admin_uid");
		$this->output->enable_profiler(FALSE); 
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
		$this->headerdata["pagejs"]	= array('/themes/js/custom/forms.js',"/themes/js/custom/tables.js",'/themes/js/custom/widgets.js','/themes/js/vip_custom/perpage.js');

	}	
 	
 	/**
	* Index Method 
	* Called when no Method Passed to URL.
	*
	* @access public
	*/
 	public function index()
 	{
 		redirect("/projects/listing","refresh");
	}
 	
 	
 	/**
	* View Method 
	* Load Individual Project Detail Page
	*
	* @access public
	*/
	public function view($params)
	{
	
		$slug = $params;
		$userid	= $this->projects_model->get_uid_from_slug($slug);
		$exist_slug = $this->projects_model->check_project($slug);
		
		
		if (! $exist_slug) {
			redirect('projects/listing', 'refresh');
			exit;
		}
		
		$viewdata = array();
		$viewdata['slug'] = $slug;
		$viewdata['userdata'] = $this->projects_model->get_user_general($userid);
		$viewdata['project']['projectdata'] = $this->projects_model->get_project_data($slug,$userid);
		$viewdata['project']['fundamental'] = $this->projects_model->get_fundamental_data($slug,$userid);
		$viewdata['project']['financial'] = $this->projects_model->get_financial_data($slug,$userid);
		$viewdata['project']['regulatory'] = $this->projects_model->get_regulatory_data($slug,$userid);
		$viewdata['project']['participants'] = $this->projects_model->get_participants_data($slug,$userid);
		$viewdata['project']['procurement'] = $this->projects_model->get_procurement_data($slug,$userid);
		$viewdata['project']['assessment'] = $this->projects_model->get_assessment_data($slug);
		$viewdata['project']['files'] = $this->projects_model->get_files_data($slug,$userid);
		$viewdata['project']['ad'] = $this->projects_model->get_ad_data();
		$viewdata['project']['comment'] = $this->projects_model->get_project_comment($slug,$userid);
		$viewdata['project']['topexperts'] = $this->projects_model->get_top_experts($slug,$userid);
		$viewdata['project']['isaddcomment'] = $userid==$this->uid?TRUE:FALSE;

		$this->breadcrumb->append_crumb('PROJECTS', "/projects");
		$this->breadcrumb->append_crumb($viewdata["project"]['projectdata']["projectname"], "/projects/".$slug."");
		$this->headerdata['breadcrumb'] = $this->breadcrumb->output();
		
		$this->load->view('templates/header',$this->headerdata);
		$this->load->view("templates/leftmenu");
		$this->load->view('projects/projects_view',$viewdata);
		$this->load->view('templates/footer');
	}
	
	/**
	* Create Method 
	* Method call for Project Create form
	*
	* @access public
	*/
	public function create()
	{
		//Session check for the Login Status, if not logged in then redirect to Home page
		//define validation rules
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_users', 'User', 'trim|required');
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		
		$this->breadcrumb->append_crumb('PROJECTS', "/projects");
		$this->breadcrumb->append_crumb("create", "/projects/create");
		$this->headerdata["breadcrumb"] = $this->breadcrumb->output();
		
		$this->load->view('templates/header',$this->headerdata);
		$this->load->view("templates/leftmenu");
		
		if($this->input->post("create_project"))
		{
			if ($this->form_validation->run() === FALSE)
			{
				$this->load->view('projects/projects_create');
			}
			else
			{
				// add_project() method from Projets Model to add project and generate slug
				if($slug = $this->projects_model->add_project())
				{
					redirect("projects/edit/".$slug."","refresh");
				}
				else
				{
					$this->load->view('projects/projects_create');
				}
			}
		}
		else
		{
			$this->load->view('projects/projects_create');
		}
		$this->load->view('templates/footer');
	}
	
	
	/**
	* Edit Method 
	* Method call for Project Edit form
	*
	* @access public
	*/
	public function edit($params)
	{
		$slug = $params;
		$editdata["slug"] = $slug;
		$userid	= $this->projects_model->get_uid_from_slug($params);

		//check if form posted or not to get project data
		if($this->input->post("return") != "")
		{
			$this->form_validation->set_error_delimiters('<label>', '</label>');
			$this->form_validation->set_rules('title_input', 'Description', 'trim|required');
			$this->form_validation->set_rules('project_overview', 'Description', 'trim|required');
			$this->form_validation->set_rules('project_keywords', 'Keywords', 'trim|required');
			$this->form_validation->set_rules('project_country', 'Country', 'required');
			$this->form_validation->set_rules('project_location', 'Location', 'trim|required');
			$this->form_validation->set_rules('project_sector_main', 'Sector', 'required');
			$this->form_validation->set_rules('project_sector_sub', 'Sub-Sector', 'required');
			$this->form_validation->set_rules('project_budget_max', 'Total Budget', 'integer|greater_than[-1]');
			$this->form_validation->set_rules('project_financial', 'Financial Structure', 'required');
			$this->form_validation->set_rules('project_owner', 'Owner', 'trim|required|integer');
            $this->form_validation->set_rules('project_developer', 'Developer', 'trim');
            $this->form_validation->set_rules('project_sponsor', 'Sponsor', 'trim');
            $this->form_validation->set_rules('website', 'Project Website', 'trim|prep_url|max_length[255]');


            if ($this->form_validation->run() === TRUE) {
				$this->projects_model->update_project($slug, $userid);
            } else {
				$response = array();
				$response["status"] 	= "error";
				$response["message"] 	= array('title_input'=>form_error('title_input'),
												'project_overview'=>form_error('project_overview'),
												'project_keywords'=>form_error('project_keywords'),
												'project_country'=>form_error('project_country'),
												'project_location'=>form_error('project_location'),
												'project_sector_main'=>form_error('project_sector_main'),
												'project_sector_sub'=>form_error('project_sector_sub'),
												'project_budget_max'=>form_error('project_budget_max'),
												'project_financial'=>form_error('project_financial')
										 );
				$response["isload"] 	= "no";
							
				////header('Content-type: application/json');
				echo json_encode($response);
				exit;
			}
		}
		$editdata["users"]		= $userid;
		$editdata["project"] 	= $this->projects_model->get_project_data($slug);
		$editdata["assessment"] = $this->projects_model->get_assessment_data($slug);
		$editdata["photoerror"] = '';

		// load all members data from Members Model.
		$this->load->model('members_model');
		$editdata['members'] = 	$this->members_model->get_all_members();

		$this->breadcrumb->append_crumb('PROJECTS', "/projects");
		$this->breadcrumb->append_crumb($editdata["project"]["projectname"]." (edit)", "/projects/".$slug."");
		$this->headerdata["breadcrumb"] = $this->breadcrumb->output();

		// Render the page
		$this->load->view('templates/header',$this->headerdata);
		$this->load->view("templates/leftmenu");
		$this->load->view('projects/projects_edit',$editdata);
		$this->load->view('templates/footer');
	}
	
	/**
	* Listing Method 
	* Method call for Project Listing Page
	*
	* @access public
	*/
	public function view_all_projects($page='')
	{
		$this->headerdata ["bodyid"] = "Profile";
		$this->headerdata ["bodyclass"] = "withvernav";
		$this->headerdata["title"] = "View Projects | ViP Admin";
		$this->headerdata["js"]		= array("/themes/js/plugins/jquery.dataTables.min.js");
		$this->headerdata["pagejs"]	= array("/themes/js/custom/tables.js");

		// load all members data from Members Model.
		$projectlist 	= 	$this->projects_model->get_projects();
		$filter_total	= 	$projectlist['totalproj'];
		
		//collect data from database;
		$data	=	array(
			'main_content'	=>	'projects',
			'proj'				=>	$projectlist['proj'],
			'totalproj'			=>	$filter_total			
		);
		// Render HTML Page from view direcotry
		$this->load->view("templates/header",$this->headerdata);
		$this->load->view("templates/leftmenu");
		$this->load->view('projects/projects_list',$data);
		$this->load->view("templates/footer");

	}
	
	
	/**
	* Delete project_files
	* Method call delete existing project_files of project
	*
	* @access public
	* @param int
	*
	*/
	public function delete_projects()
	{
		$this->projects_model->delete_projects();
	}	
	/**
	* Delete Executive 
	* Method call delete existing executive of project
	*
	* @access public
	* @param int
	*
	*/
	public function delete_executive()
	{
		$this->projects_model->delete_executive();
	}
	
	/**
	* Uplpad Project  Photo method
	* upload project picture and update his/her account detail
	*
	* @access public
	*/
	public function upload_projectphoto($params)
	{
		
		$slug = $params;
		$userid	= $this->projects_model->get_uid_from_slug($slug);
		
		$editdata["slug"] = $slug;
		$photoerror = "";

        $error_delimiters = array('open' => '<label>', 'close' => '</label>');

        $upload = upload_image(PROJECT_IMAGE_PATH, 'project_photo', TRUE, array(
            array('width' => '150', 'height' => '150'),
            array('width' => '80',  'height' => '67'),
            array('width' => '158', 'height' => '132'),
            array('width' => '50',  'height' => '50'),
            array('width' => '198', 'height' => '198')
            ), $error_delimiters
        );

		if($upload['error']=='') {
			$this->projects_model->upload_photo($upload, $slug, $userid);
			return "";
		} else {
			$response = array(
                'status' => 'error',
                'message' => array('project_photo' => $upload['error']),
                'isload' => 'no'
            );

            sendResponse($response);
			return FALSE;
		}
	}
	
	/**
	* Update Project Name
	* update new project name
	*
	* @access public
	* @param string
	*/
	public function updatename($params)
	{
		$userid	= $this->projects_model->get_uid_from_slug($params);
		$this->projects_model->updateprojectname($params,$userid);
	}
	
	
	/**
	* Update Legal Info
	* update new legal info
	*
	* @access public
	* @param string
	*/

	public function add_legal($params)
	{
		$userid	= $this->projects_model->get_uid_from_slug($params);
		$this->projects_model->add_legal($params,$userid);
	}
	
	
	/**
	* Add Executive
	* Add new executive for selected project
	*
	* @access public
	* @param string
	*/
	public function add_executive($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_executives_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_executives_company', 'Company', 'trim|required');
		$this->form_validation->set_rules('project_executives_role', 'Role', 'trim|required');
		$this->form_validation->set_rules('project_executives_email', 'Email', 'trim|required|valid_email');

		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->add_executive($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_executives_name'=>form_error('project_executives_name'),
											'project_executives_company'=>form_error('project_executives_company'),
											'project_executives_role'=>form_error('project_executives_role'),
											'project_executives_email'=>form_error('project_executives_email')
									);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}
	
	/**
	* Update Executive
	* update executive for selected project
	*
	* @access public
	* @param string
	*/
	public function update_executive($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_executives_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_executives_company', 'Company', 'trim|required');
		$this->form_validation->set_rules('project_executives_role', 'Role', 'trim|required');
		$this->form_validation->set_rules('project_executives_email', 'Email', 'trim|required|valid_email');

		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->update_executive($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_executives_name'=>form_error('project_executives_name'),
											'project_executives_company'=>form_error('project_executives_company'),
											'project_executives_role'=>form_error('project_executives_role'),
											'project_executives_email'=>form_error('project_executives_email')
									);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}
	
	
	/**
	* Add Organization
	* Add new organization for selected project
	*
	* @access public
	* @param string
	*/
	public function add_organization($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_organizations_company', 'Company', 'trim|required');
		$this->form_validation->set_rules('project_organizations_role', 'Role', 'trim|required');
		$this->form_validation->set_rules('project_organizations_contact', 'Contact', 'trim|required');
		$this->form_validation->set_rules('project_organizations_email', 'Email', 'trim|required|valid_email');

		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->add_organization($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_organizations_company'=>form_error('project_organizations_company'),
											'project_organizations_role'=>form_error('project_organizations_role'),
											'project_organizations_contact'=>form_error('project_organizations_contact'),
											'project_organizations_email'=>form_error('project_organizations_email')
									);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}
	
	/**
	* Update Organization
	* update organization for selected project
	*
	* @access public
	* @param string
	*/
	public function update_organization($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_organizations_company', 'Company', 'trim|required');
		$this->form_validation->set_rules('project_organizations_role', 'Role', 'trim|required');
		$this->form_validation->set_rules('project_organizations_contact', 'Contact', 'trim|required');
		$this->form_validation->set_rules('project_organizations_email', 'Email', 'trim|required|valid_email');

		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->update_organization($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_organizations_company'=>form_error('project_organizations_company'),
											'project_organizations_role'=>form_error('project_organizations_role'),
											'project_organizations_contact'=>form_error('project_organizations_contact'),
											'project_organizations_email'=>form_error('project_organizations_email')
									);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	* Delete Organization 
	* Method call delete existing organization of project
	*
	* @access public
	* @param int
	*
	*/
	public function delete_organization()
	{
		$this->projects_model->delete_organization();
	}

	/**
	* Add Engineering
	* Add new engineering for selected project
	*
	* @access public
	* @param string
	*/
	public function add_engineering($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_engineering_company', 'Company', 'trim|required');
		$this->form_validation->set_rules('project_engineering_role', 'Role', 'trim|required');
		$this->form_validation->set_rules('project_engineering_cname', 'Contact', 'trim|required');
		$this->form_validation->set_rules('project_engineering_challenges', 'Challenges', 'trim|required');
		$this->form_validation->set_rules('project_engineering_innovations', 'Innovations', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			
			if($file = $this->proj_uploadfiles($params,'project_engineering_schedule',0))
			{	
				$userid	= $this->projects_model->get_uid_from_slug($params);
				$this->projects_model->add_engineering($params,$userid,$file);
			}
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_engineering_company'=>form_error('project_engineering_company'),
											'project_engineering_role'=>form_error('project_engineering_role'),
											'project_engineering_cname'=>form_error('project_engineering_cname'),
											'project_engineering_challenges'=>form_error('project_engineering_challenges'),
											'project_engineering_innovations'=>form_error('project_engineering_innovations')
									);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	* Update engineering
	* update engineering for selected project
	*
	* @access public
	* @param string
	*/
	public function update_engineering($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_engineering_company', 'Company', 'trim|required');
		$this->form_validation->set_rules('project_engineering_role', 'Role', 'trim|required');
		$this->form_validation->set_rules('project_engineering_cname', 'Contact', 'trim|required');
		$this->form_validation->set_rules('project_engineering_challenges', 'Challenges', 'trim|required');
		$this->form_validation->set_rules('project_engineering_innovations', 'Innovations', 'trim|required');

		if($this->form_validation->run() === TRUE)
		{
		
			$hdnuploaded	   = $this->input->post("project_engineering_schedul_hidden");
			if($hdnuploaded != '')
			{
				$filename = array('file_name'=>$hdnuploaded,'error'=>'');
				$userid	= $this->projects_model->get_uid_from_slug($params);
				$this->projects_model->update_engineering($params,$userid,$filename);
			}
			else
			{
				if($file = $this->proj_uploadfiles($params,'project_engineering_schedule',0) )
				{	
					$userid	= $this->projects_model->get_uid_from_slug($params);
					$this->projects_model->update_engineering($params,$userid,$file);
				}
			}

		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_engineering_company'=>form_error('project_engineering_company'),
											'project_engineering_role'=>form_error('project_engineering_role'),
											'project_engineering_cname'=>form_error('project_engineering_cname'),
											'project_engineering_challenges'=>form_error('project_engineering_challenges'),
											'project_engineering_innovations'=>form_error('project_engineering_innovations')
									);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	* Delete engineering 
	* Method call delete existing engineering of project
	*
	* @access public
	* @param int
	*
	*/
	public function delete_engineering()
	{
		$this->projects_model->delete_engineering();
	}
	
	
	/**
	* Add map_point
	* Add new map_point for selected project
	*
	* @access public
	* @param string
	*/
	public function add_map_point($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_map_points_mapname', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_map_points_latitude', 'Latitude', 'trim|required');
		$this->form_validation->set_rules('project_map_points_longitude', 'Longitude', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->add_map_point($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_map_points_mapname'=>form_error('project_map_points_mapname'),
											'project_map_points_latitude'=>form_error('project_map_points_latitude'),
											'project_map_points_longitude'=>form_error('project_map_points_longitude')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	* Update map_point
	* update map_point for selected project
	*
	* @access public
	* @param string
	*/
	public function update_map_point($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_map_points_mapname', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_map_points_latitude', 'Latitude', 'trim|required');
		$this->form_validation->set_rules('project_map_points_longitude', 'Longitude', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->update_map_point($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_map_points_mapname'=>form_error('project_map_points_mapname'),
											'project_map_points_latitude'=>form_error('project_map_points_latitude'),
											'project_map_points_longitude'=>form_error('project_map_points_longitude')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	* Delete map_point
	* Method call delete existing map_points of project
	*
	* @access public
	* @param int
	*
	*/
	public function delete_map_point()
	{
		$this->projects_model->delete_map_point();
	}


	/**
	* Add design_issue
	* Add new design_issue for selected project
	*
	* @access public
	* @param string
	*/
	public function add_design_issue($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_design_issues_title', 'Title', 'trim|required');
		$this->form_validation->set_rules('project_design_issues_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			if($file = $this->proj_uploadfiles($params,'project_design_issues_attachment',0))
			{	
				$userid	= $this->projects_model->get_uid_from_slug($params);
				$this->projects_model->add_design_issue($params,$userid,$file);
			}
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_design_issues_title'=>form_error('project_design_issues_title'),
											'project_design_issues_desc'=>form_error('project_design_issues_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	* Update design_issue
	* update design_issue for selected project
	*
	* @access public
	* @param string
	*/
	public function update_design_issue($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_design_issues_title', 'Title', 'trim|required');
		$this->form_validation->set_rules('project_design_issues_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			
			$hdnuploaded	   = $this->input->post("project_design_issues_attachmen_hidden");
			if($hdnuploaded != '')
			{
				$filename = array('file_name'=>$hdnuploaded,'error'=>'');
				$userid	= $this->projects_model->get_uid_from_slug($params);
				$this->projects_model->update_design_issue($params,$userid,$filename);
			}
			else
			{
				if($file = $this->proj_uploadfiles($params,'project_design_issues_attachment',0) )
				{	
					$userid	= $this->projects_model->get_uid_from_slug($params);
					$this->projects_model->update_design_issue($params,$userid,$file);
				}
			}

		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_design_issues_title'=>form_error('project_design_issues_title'),
											'project_design_issues_desc'=>form_error('project_design_issues_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	* Delete design_issuet
	* Method call delete existing design_issues of project
	*
	* @access public
	* @param int
	*
	*/
	public function delete_design_issue()
	{
		$this->projects_model->delete_design_issue();
	}
	
	
	/**
	* Add environment
	* Add new environment for selected project
	*
	* @access public
	* @param string
	*/
	public function add_environment($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_environment_title', 'Title', 'trim|required');
		$this->form_validation->set_rules('project_environment_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			if($file = $this->proj_uploadfiles($params,'project_environment_attachment',0))
			{	
				$userid	= $this->projects_model->get_uid_from_slug($params);
				$this->projects_model->add_environment($params,$userid,$file);
			}
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_environment_title'=>form_error('project_environment_title'),
											'project_environment_desc'=>form_error('project_environment_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}


	/**
	* Update environment
	* update environment for selected project
	*
	* @access public
	* @param string
	*/
	public function update_environment($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_environment_title', 'Title', 'trim|required');
		$this->form_validation->set_rules('project_environment_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
		
			$hdnuploaded	   = $this->input->post("project_environment_attachmen_hidden");
			if($hdnuploaded != '')
			{
				$filename = array('file_name'=>$hdnuploaded,'error'=>'');
				$userid	= $this->projects_model->get_uid_from_slug($params);
				$this->projects_model->update_environment($params,$userid,$filename);
			}
			else
			{
				if($file = $this->proj_uploadfiles($params,'project_environment_attachment',0) )
				{	
					$userid	= $this->projects_model->get_uid_from_slug($params);
					$this->projects_model->update_environment($params,$userid,$file);
				}
			}

		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_environment_title'=>form_error('project_environment_title'),
											'project_environment_desc'=>form_error('project_environment_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}


	/**
	* Delete environment
	* Method call delete existing environment of project
	*
	* @access public
	* @param int
	*
	*/
	public function delete_environment()
	{
		$this->projects_model->delete_environment();
	}


	/**
	* Add studies
	* Add new studies for selected project
	*
	* @access public
	* @param string
	*/
	public function add_studies($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_studies_title', 'Title', 'trim|required');
		$this->form_validation->set_rules('project_studies_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{		
			if($file = $this->proj_uploadfiles($params,'project_studies_attachment',0))
			{	
				$userid	= $this->projects_model->get_uid_from_slug($params);
				$this->projects_model->add_studies($params,$userid,$file);
			}

		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_studies_title'=>form_error('project_studies_title'),
											'project_studies_desc'=>form_error('project_studies_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}


	/**
	* Update studies
	* update studies for selected project
	*
	* @access public
	* @param string
	*/
	public function update_studies($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_studies_title', 'Title', 'trim|required');
		$this->form_validation->set_rules('project_studies_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			
			$hdnuploaded	   = $this->input->post("project_studies_attachmen_hidden");
			if($hdnuploaded != '')
			{
				$filename = array('file_name'=>$hdnuploaded,'error'=>'');
				$userid	= $this->projects_model->get_uid_from_slug($params);
				$this->projects_model->update_studies($params,$userid,$filename);
			}
			else
			{
				if($file = $this->proj_uploadfiles($params,'project_studies_attachment',0) )
				{	
					$userid	= $this->projects_model->get_uid_from_slug($params);
					$this->projects_model->update_studies($params,$userid,$file);
				}
			}

		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_studies_title'=>form_error('project_studies_title'),
											'project_studies_desc'=>form_error('project_studies_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}


	/**
	* Delete studies
	* Method call delete existing studies of project
	*
	* @access public
	* @param int
	*
	*/
	public function delete_studies()
	{
		$this->projects_model->delete_studies();
	}


	/**
	* Add financial
	* Add new financial info for selected project
	*
	* @access public
	* @param string
	*/
	public function add_financial($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_fs_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_fs_contact', 'Contact Name', 'trim|required');
		$this->form_validation->set_rules('project_fs_role', 'Role', 'trim|required');
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->add_financial($params,$userid);
			exit;
		
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_fs_name'=>form_error('project_fs_name'),
											'project_fs_contact'=>form_error('project_fs_contact'),
											'project_fs_role'=>form_error('project_fs_role')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}


	/**
	* Add fund_sources
	* Add new fund_sources for selected project
	*
	* @access public
	* @param string
	*/
	public function add_fund_sources($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_fund_sources_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_fund_sources_role', 'Role', 'trim|required');
		$this->form_validation->set_rules('project_fund_sources_amount', 'Amount', 'trim|required');
		$this->form_validation->set_rules('project_fund_sources_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->add_fund_sources($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";

			$response["message"] 	= array('project_fund_sources_name'=>form_error('project_fund_sources_name'),
											'project_fund_sources_role'=>form_error('project_fund_sources_role'),
											'project_fund_sources_amount'=>form_error('project_fund_sources_amount'),
											'project_fund_sources_desc'=>form_error('project_fund_sources_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	* Update fund_sources
	* update fund_sources for selected project
	*
	* @access public
	* @param string
	*/
	public function update_fund_sources($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_fund_sources_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_fund_sources_role', 'Role', 'trim|required');
		$this->form_validation->set_rules('project_fund_sources_amount', 'Amount', 'trim|required');
		$this->form_validation->set_rules('project_fund_sources_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->update_fund_sources($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";

			$response["message"] 	= array('project_fund_sources_name'=>form_error('project_fund_sources_name'),
											'project_fund_sources_role'=>form_error('project_fund_sources_role'),
											'project_fund_sources_amount'=>form_error('project_fund_sources_amount'),
											'project_fund_sources_desc'=>form_error('project_fund_sources_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	* Delete fund_sources
	* Method call delete existing fund_sources of project
	*
	* @access public
	* @param int
	*
	*/
	public function delete_fund_sources()
	{
		$this->projects_model->delete_fund_sources();
	}

	
	/**
	* Add ROI
	* Add new ROI for selected project
	*
	* @access public
	* @param string
	*/
	public function add_roi($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_roi_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_roi_percent', 'Percent', 'trim|required|numeric');
		$this->form_validation->set_rules('project_roi_type', 'Type', 'trim|required');
		$this->form_validation->set_rules('project_roi_approach', 'Approach', 'trim|required');
		//$this->form_validation->set_rules('project_roi_keystudy', 'Key Study', 'trim|required');

		if($this->form_validation->run() === TRUE)
		{
			if($file = $this->proj_uploadfiles($params,'project_roi_keystudy',0))
			{	
				$userid	= $this->projects_model->get_uid_from_slug($params);
				$this->projects_model->add_roi($params,$userid,$file);
			}
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";

			$response["message"] 	= array('project_roi_name'=>form_error('project_roi_name'),
											'project_roi_percent'=>form_error('project_roi_percent'),
											'project_roi_type'=>form_error('project_roi_type'),
											'project_roi_approach'=>form_error('project_roi_approach')
											//'project_roi_keystudy'=>form_error('project_roi_keystudy')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	* Update ROI
	* update ROI for selected project
	*
	* @access public
	* @param string
	*/
	public function update_roi($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_roi_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_roi_percent', 'Percent', 'trim|required');
		$this->form_validation->set_rules('project_roi_type', 'Type', 'trim|required');
		$this->form_validation->set_rules('project_roi_approach', 'Approach', 'trim|required');
		//$this->form_validation->set_rules('project_roi_keystudy', 'Key Study', 'trim|required');

		if($this->form_validation->run() === TRUE)
		{
			$hdnuploaded	   = $this->input->post("project_roi_keystud_hidden");
			if($hdnuploaded != '')
			{
				$filename = array('file_name'=>$hdnuploaded,'error'=>'');
				$userid	= $this->projects_model->get_uid_from_slug($params);
				$this->projects_model->update_roi($params,$userid,$filename);
			}
			else
			{
				if($file = $this->proj_uploadfiles($params,'project_roi_keystudy',0) )
				{	
					$userid	= $this->projects_model->get_uid_from_slug($params);
					$this->projects_model->update_roi($params,$userid,$file);
				}
			}
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";

			$response["message"] 	= array('project_roi_name'=>form_error('project_roi_name'),
											'project_roi_percent'=>form_error('project_roi_percent'),
											'project_roi_type'=>form_error('project_roi_type'),
											'project_roi_approach'=>form_error('project_roi_approach')
											//'project_roi_keystudy'=>form_error('project_roi_keystudy')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	* Delete ROI
	* Method call delete existing ROI of project
	*
	* @access public
	* @param int
	*
	*/
	public function delete_roi()
	{
		$this->projects_model->delete_roi();
	}

	
	/**
	* Add critical_participants
	* Add new critical_participant for selected project
	*
	* @access public
	* @param string
	*/
	public function add_critical_participants($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_critical_participants_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_critical_participants_role', 'Role', 'trim|required');
		$this->form_validation->set_rules('project_critical_participants_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->add_critical_participants($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";

			$response["message"] 	= array('project_critical_participants_name'=>form_error('project_critical_participants_name'),
											'project_critical_participants_role'=>form_error('project_critical_participants_role'),
											'project_critical_participants_desc'=>form_error('project_critical_participants_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}


	/**
	* Update critical_participants
	* update critical_participants for selected project
	*
	* @access public
	* @param string
	*/
	public function update_critical_participants($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_critical_participants_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_critical_participants_role', 'Role', 'trim|required');
		$this->form_validation->set_rules('project_critical_participants_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->update_critical_participants($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";

			$response["message"] 	= array('project_critical_participants_name'=>form_error('project_critical_participants_name'),
											'project_critical_participants_role'=>form_error('project_critical_participants_role'),
											'project_critical_participants_desc'=>form_error('project_critical_participants_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}


	/**
	* Delete critical_participants
	* Method call delete existing critical_participants of project
	*
	* @access public
	* @param int
	*
	*/
	public function delete_critical_participants()
	{
		$this->projects_model->delete_critical_participants();
	}


	/**
	* Add regulatory
	* Add new regulatory for selected project
	*
	* @access public
	* @param string
	*/
	public function add_regulatory($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		//$this->form_validation->set_rules('project_regulatory_filename', 'File', 'trim|required');
		$this->form_validation->set_rules('project_regulatory_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
	
			if($file = $this->proj_uploadfiles($params,'project_regulatory_filename',0) )
			{	
				$userid	= $this->projects_model->get_uid_from_slug($params);
				$this->projects_model->add_regulatory($params,$userid,$file);
			}

		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
	
			$response["message"] 	= array('project_regulatory_filename'=>form_error('project_regulatory_filename'),
											'project_regulatory_desc'=>form_error('project_regulatory_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	
	/**
	* Update regulatory
	* update regulatory for selected project
	*
	* @access public
	* @param string
	*/
	public function update_regulatory($params)
	{
		
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_regulatory_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			
			$hdnuploaded	   = $this->input->post("project_regulatory_filenam_hidden");
			if($hdnuploaded != '')
			{
				$filename = array('file_name'=>$hdnuploaded,'error'=>'');
				$userid	= $this->projects_model->get_uid_from_slug($params);
				$this->projects_model->update_regulatory($params,$userid,$filename);
			}
			else
			{
				if($file = $this->proj_uploadfiles($params,'project_regulatory_filename',0) )
				{	
					$userid	= $this->projects_model->get_uid_from_slug($params);
					$this->projects_model->update_regulatory($params,$userid,$file);
				}
			}

		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
	
			$response["message"] 	= array('project_regulatory_filename'=>form_error('project_regulatory_filename'),
											'project_regulatory_desc'=>form_error('project_regulatory_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}
	
	
	/**
	* Delete regulatory
	* Method call delete existing regulatory of project
	*
	* @access public
	* @param int
	*
	*/
	public function delete_regulatory()
	{
		$this->projects_model->delete_regulatory();
	}
	
	
	/**
	* Add participants_public
	* Add new participants_public for selected project
	*
	* @access public
	* @param string
	*/
	public function add_participants_public($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_participants_public_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_participants_public_type', 'Type', 'trim|required');
		$this->form_validation->set_rules('project_participants_public_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->add_participants_public($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
	
			$response["message"] 	= array('project_participants_public_name'=>form_error('project_participants_public_name'),
											'project_participants_public_type'=>form_error('project_participants_public_type'),
											'project_participants_public_desc'=>form_error('project_participants_public_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	
	/**
	* Update participants_public
	* update participants_public for selected project
	*
	* @access public
	* @param string
	*/
	public function update_participants_public($params)
	{
		
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_participants_public_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_participants_public_type', 'Type', 'trim|required');
		$this->form_validation->set_rules('project_participants_public_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->update_participants_public($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
	
			$response["message"] 	= array('project_participants_public_name'=>form_error('project_participants_public_name'),
											'project_participants_public_type'=>form_error('project_participants_public_type'),
											'project_participants_public_desc'=>form_error('project_participants_public_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	
	/**
	* Delete participants_public
	* Method call delete existing participants_public of project
	*
	* @access public
	* @param int
	*
	*/
	public function delete_public_participant()
	{
		$this->projects_model->delete_participants_public();
	}

	
	/**
	* Add participants_political
	* Add new participants_politicalfor selected project
	*
	* @access public
	* @param string
	*/
	public function add_participants_political($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_participants_political_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_participants_political_type', 'Type', 'trim|required');
		$this->form_validation->set_rules('project_participants_political_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->add_participants_political($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
	
			$response["message"] 	= array('project_participants_political_name'=>form_error('project_participants_political_name'),
											'project_participants_political_type'=>form_error('project_participants_political_type'),
											'project_participants_political_desc'=>form_error('project_participants_political_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}
	
	
	/**
	* Update participants_political
	* update participants_politicalfor selected project
	*
	* @access public
	* @param string
	*/
	public function update_participants_political($params)
	{
		
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_participants_political_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_participants_political_type', 'Type', 'trim|required');
		$this->form_validation->set_rules('project_participants_political_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->update_participants_political($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
	
			$response["message"] 	= array('project_participants_political_name'=>form_error('project_participants_political_name'),
											'project_participants_political_type'=>form_error('project_participants_political_type'),
											'project_participants_political_desc'=>form_error('project_participants_political_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	
	/**
	* Delete participants_political
	* Method call delete existing participants_politicalof project
	*
	* @access public
	* @param int
	*
	*/
	public function delete_participants_political()
	{
		$this->projects_model->delete_participants_political();
	}
		
	/**
	* Add participants_companies
	* Add new participants_companies for selected project
	*
	* @access public
	* @param string
	*/
	public function add_participants_companies($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_participants_companies_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_participants_companies_role', 'Type', 'trim|required');
		$this->form_validation->set_rules('project_participants_companies_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->add_participants_companies($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
	
			$response["message"] 	= array('project_participants_companies_name'=>form_error('project_participants_companies_name'),
											'project_participants_companies_role'=>form_error('project_participants_companies_role'),
											'project_participants_companies_desc'=>form_error('project_participants_companies_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}
	

	/**
	* Update participants_companies
	* update participants_companies for selected project
	*
	* @access public
	* @param string
	*/
	public function update_participants_companies($params)
	{
		
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_participants_companies_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_participants_companies_role', 'Type', 'trim|required');
		$this->form_validation->set_rules('project_participants_companies_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->update_participants_companies($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
	
			$response["message"] 	= array('project_participants_political_name'=>form_error('project_participants_political_name'),
											'project_participants_political_role'=>form_error('project_participants_political_role'),
											'project_participants_political_desc'=>form_error('project_participants_political_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	
	/**
	* Delete participants_companies
	* Method call delete existing participants_companies of project
	*
	* @access public
	* @param int
	*
	*/
	public function delete_participants_companies()
	{
		$this->projects_model->delete_participants_companies();
        exit;
	}

	/**
	* Add participants_owners
	* Add new participants_owners for selected project
	*
	* @access public
	* @param string
	*/
	public function add_participants_owners($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_participants_owners_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_participants_owners_type', 'Type', 'trim|required');
		$this->form_validation->set_rules('project_participants_owners_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->add_participants_owners($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
	
			$response["message"] 	= array('project_participants_owners_name'=>form_error('project_participants_owners_name'),
											'project_participants_owners_type'=>form_error('project_participants_owners_type'),
											'project_participants_owners_desc'=>form_error('project_participants_owners_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	
	/**
	* Update participants_owners
	* update participants_owners for selected project
	*
	* @access public
	* @param string
	*/
	public function update_participants_owners($params)
	{
		
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_participants_owners_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_participants_owners_type', 'Type', 'trim|required');
		$this->form_validation->set_rules('project_participants_owners_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->update_participants_owners($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
	
			$response["message"] 	= array('project_participants_owners_name'=>form_error('project_participants_owners_name'),
											'project_participants_owners_type'=>form_error('project_participants_owners_type'),
											'project_participants_owners_desc'=>form_error('project_participants_owners_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}
	
	/**
	* Delete participants_owners
	* Method call delete existing participants_owners of project
	*
	* @access public
	* @param int
	*
	*/
	public function delete_participants_owners()
	{
		$this->projects_model->delete_participants_owners();
	}


	/**
	* Add machinery
	* Add new machinery for selected project
	*
	* @access public
	* @param string
	*/
	public function add_machinery($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_machinery_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_machinery_process', 'Procurement Process', 'trim|required');
		$this->form_validation->set_rules('project_machinery_financial_info', 'Financial Information', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->add_machinery($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
	
			$response["message"] 	= array('project_machinery_name'=>form_error('project_machinery_name'),
											'project_machinery_process'=>form_error('project_machinery_process'),
											'project_machinery_financial_info'=>form_error('project_machinery_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}
	
	/**
	* Update machinery
	* update machinery for selected project
	*
	* @access public
	* @param string
	*/
	public function update_machinery($params)
	{
		
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_machinery_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_machinery_process', 'Procurement Process', 'trim|required');
		$this->form_validation->set_rules('project_machinery_financial_info', 'Financial Information', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->update_machinery($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
	
			$response["message"] 	= array('project_machinery_name'=>form_error('project_machinery_name'),
											'project_machinery_process'=>form_error('project_machinery_process'),
											'project_machinery_financial_info'=>form_error('project_machinery_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}
	
	
	
	/**
	* Delete machinery
	* Method call delete existing machinery of project
	*
	* @access public
	* @param int
	*
	*/
	public function delete_machinery()
	{
		$this->projects_model->delete_machinery();
	}

	/**
	* Add procurement_technology
	* Add new procurement_technology for selected project
	*
	* @access public
	* @param string
	*/
	public function add_procurement_technology($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_procurement_technology_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_procurement_technology_process', 'Procurement Process', 'trim|required');
		$this->form_validation->set_rules('project_procurement_technology_financial_info', 'Financial Information', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->add_procurement_technology($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
	
			$response["message"] 	= array('project_procurement_technology_name'=>form_error('project_procurement_technology_name'),
											'project_procurement_technology_process'=>form_error('project_procurement_technology_process'),
											'project_procurement_technology_financial_info'=>form_error('project_procurement_technology_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}
	
	/**
	* Update procurement_technology
	* update procurement_technology for selected project
	*
	* @access public
	* @param string
	*/
	public function update_procurement_technology($params)
	{
		
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_procurement_technology_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_procurement_technology_process', 'Procurement Process', 'trim|required');
		$this->form_validation->set_rules('project_procurement_technology_financial_info', 'Financial Information', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->update_procurement_technology($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
	
			$response["message"] 	= array('project_procurement_technology_name'=>form_error('project_procurement_technology_name'),
											'project_procurement_technology_process'=>form_error('project_procurement_technology_process'),
											'project_procurement_technology_financial_info'=>form_error('project_procurement_technology_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}
	
	
	
	/**
	* Delete procurement_technology
	* Method call delete existing procurement_technology of project
	*
	* @access public
	* @param int
	*
	*/
	public function delete_procurement_technology()
	{
		$this->projects_model->delete_procurement_technology();
	}


	/**
	* Add procurement_services
	* Add new procurement_services for selected project
	*
	* @access public
	* @param string
	*/
	public function add_procurement_services($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_procurement_services_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_procurement_services_type', 'Type', 'trim|required');
		$this->form_validation->set_rules('project_procurement_services_process', 'Procurement Process', 'trim|required');
		$this->form_validation->set_rules('project_procurement_services_financial_info', 'Financial Information', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->add_procurement_services($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
	
			$response["message"] 	= array('project_procurement_services_name'=>form_error('project_procurement_services_name'),
											'project_procurement_services_type'=>form_error('project_procurement_services_type'),
											'project_procurement_services_process'=>form_error('project_procurement_services_process'),
											'project_procurement_services_financial_info'=>form_error('project_procurement_services_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	
	/**
	* Update procurement_services
	* update procurement_services for selected project
	*
	* @access public
	* @param string
	*/
	public function update_procurement_services($params)
	{
		
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_procurement_services_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('project_procurement_services_type', 'Type', 'trim|required');
		$this->form_validation->set_rules('project_procurement_services_process', 'Procurement Process', 'trim|required');
		$this->form_validation->set_rules('project_procurement_services_financial_info', 'Financial Information', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->update_procurement_services($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
	
			$response["message"] 	= array('project_procurement_services_name'=>form_error('project_procurement_services_name'),
											'project_procurement_services_type'=>form_error('project_procurement_services_type'),
											'project_procurement_services_process'=>form_error('project_procurement_services_process'),
											'project_procurement_services_financial_info'=>form_error('project_procurement_services_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}
	
		
	/**
	* Delete procurement_services
	* Method call delete existing procurement_services of project
	*
	* @access public
	* @param int
	*
	*/
	public function delete_procurement_services()
	{
		$this->projects_model->delete_procurement_services();
	}


	/**
	* Add project_files
	* Add new project_files for selected project
	*
	* @access public
	* @param string
	*/
	public function add_project_files($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_files_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			if($file = $this->proj_uploadfiles($params,'project_files_filename'))
			{	
				$userid	= $this->projects_model->get_uid_from_slug($params);
				$this->projects_model->add_project_files($params,$userid,$file);
			}
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
	
			$response["message"] 	= array(//'project_files_filename'=>form_error('project_files_filename'),
											'project_files_desc'=>form_error('project_files_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}
	
	
	/**
	* Update project_files
	* update project_files for selected project
	*
	* @access public
	* @param string
	*/
	public function update_project_files($params)
	{
		
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_files_desc', 'Description', 'trim|required');
		
		if($this->form_validation->run() === TRUE)
		{
			$hdnuploaded	   = $this->input->post("project_files_filenam_hidden");
			if($hdnuploaded != '')
			{
				$filename = array('file_name'=>$hdnuploaded,'error'=>'','file_size'=>'');
				$userid	= $this->projects_model->get_uid_from_slug($params);
				$this->projects_model->update_project_files($params,$userid,$filename);
			}
			else
			{
				if($file = $this->proj_uploadfiles($params,'project_files_filename') )
				{	
					$userid	= $this->projects_model->get_uid_from_slug($params);
					$this->projects_model->update_project_files($params,$userid,$file);
				}
			}
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
	
			$response["message"] 	= array('project_files_filename'=>form_error('project_files_filename'),
											'project_files_desc'=>form_error('project_files_desc')
											);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}
	
	/**
	* Delete project_files
	* Method call delete existing project_files of project
	*
	* @access public
	* @param int
	*
	*/
	public function delete_project_files()
	{
		$this->projects_model->delete_project_files();
	}
	

	
	/**
	* form load
	* Get List of data after submittion of AJAX form
	*
	* @access public
	* @param string
	* @param string
	* @param string
	*/
	public function form_load()
	{
		$loadtype	= $this->uri->segment(3, 0);
		$slug		= $this->uri->segment(4, 0);
		$actionId	= $this->uri->segment(5, 0);

		$userid	= $this->projects_model->get_uid_from_slug($slug);

		switch($loadtype)
		{
			case 'get_subsector_proj_ddl':
				$secid = $actionId;
				if(!is_numeric($actionId))
				{
					$secid = getsectorid("'".$actionId."'",0);
					//HERE $actionId is not an number its a sector name you have selected
				}
				else
				{
					$secid = $actionId;
				}
				$this->load->view("loader",array('loadtype'=>'get_subsector_proj_ddl','secid'=>$secid));
			    break;

			case 'project_executives':
				$array_load = $this->projects_model->load_executive($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
			    break;
			
			case 'project_organization':
				$array_load = $this->projects_model->load_organization($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
			    break;
			
			case 'project_engineering':
				$array_load = $this->projects_model->load_engineering($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
			    break;
			
			case 'project_map_point':
				$array_load = $this->projects_model->load_map_point($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
			    break;
			
			case 'project_design_issue':
			    $array_load = $this->projects_model->load_design_issue($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
			    break;
			
			case 'project_environment':
			    $array_load = $this->projects_model->load_environment($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
			    break;

			case 'project_studies':
			    $array_load = $this->projects_model->load_studies($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
			    break;

			case 'project_fund_sources':
			    $array_load = $this->projects_model->load_fund_sources($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
			    break;
			
			case 'project_roi':
			    $array_load = $this->projects_model->load_roi($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
			    break;
			
			case 'project_critical_participants':
			    $array_load = $this->projects_model->load_critical_participants($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
			    break;
			
			case 'project_regulatory':
			    $array_load = $this->projects_model->load_project_regulatory($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
			    break;
			
			case 'participants_public':
			    $array_load = $this->projects_model->load_participants_public($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
			    break;
			
			case 'participants_political':
    			$array_load = $this->projects_model->load_participants_political($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
	    		break;
			
			case 'participants_companies':
		    	$array_load = $this->projects_model->load_participants_companies($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
			    break;

			case 'participants_owners':
			    $array_load = $this->projects_model->load_participants_owners($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
			    break;
			
			case 'project_machinery':
			    $array_load = $this->projects_model->load_project_machinery($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
			    break;
			
			case 'procurement_technology':
			    $array_load = $this->projects_model->load_procurement_technology($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
			    break;
			
			case 'procurement_services':
			    $array_load = $this->projects_model->load_procurement_services($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
			    break;
			
			case 'project_files':
			    $array_load = $this->projects_model->load_project_files($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
			    break;
			
			case 'project_comment':
			    $array_load = $this->projects_model->load_project_comment($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
			    break;
			
			case 'project_assessment':
			    $array_load = $this->projects_model->load_project_assessment($loadtype,$actionId,$slug,$userid);
				$this->load->view("loader",$array_load);
			    break;
			
			default:
			    redirect('profile/account_settings','refresh');
		}
	
	}
	
	/**
	* Edit Fundamentals
	* Edit fundamentals tab for Project Edit
	*
	* @access public
	* @param string
	*/
	public function edit_fundamentals($params)
	{
		$slug = $params;		
		
		$fundamentaldata["slug"] = $slug;
		$fundamentaldata["vtab_position"] = 1;
		$fundamentaldata["project"] = $this->projects_model->get_fundamental_data($slug);
		$fundamentaldata["main_content"] = 'projects/projects_fundamental';
		
		$this->breadcrumb->append_crumb('PROJECTS', "/projects");
		$this->breadcrumb->append_crumb($fundamentaldata["project"]["projectname"]." (edit)", "/projects/".$slug."");
		$this->headerdata["breadcrumb"] = $this->breadcrumb->output();
		
		// Render HTML Page from view direcotry
		$this->load->view('templates/header',$this->headerdata);
		$this->load->view("templates/leftmenu");
		$this->load->view('templates/tabcontent',$fundamentaldata);
		$this->load->view('templates/footer');
	}
	
	
	/**
	* Edit Financial
	* Edit financial tab for Project Edit
	*
	* @access public
	* @param string
	*/
	public function edit_financial($params)
	{
		$slug = $params;
				
		$financialdata["slug"] = $slug;
		$financialdata["vtab_position"] = 2;
		$financialdata["project"] = $this->projects_model->get_financial_data($slug);
		$financialdata["main_content"] = 'projects/projects_financial';
		
		$this->breadcrumb->append_crumb('PROJECTS', "/projects");
		$this->breadcrumb->append_crumb($financialdata["project"]["projectname"]." (edit)", "/projects/".$slug."");
		$this->headerdata["breadcrumb"] = $this->breadcrumb->output();
		
		// Render HTML Page from view direcotry
		$this->load->view('templates/header',$this->headerdata);
		$this->load->view("templates/leftmenu");
		$this->load->view('templates/tabcontent',$financialdata);
		$this->load->view('templates/footer');
	}
	
	/**
	* Edit regulatory
	* Edit regulatory tab for Project Edit
	*
	* @access public
	* @param string
	*/
	public function edit_regulatory($params)
	{
		$slug = $params;
		
		
		$regulatorydata["slug"] = $slug;
		$regulatorydata["vtab_position"] = 3;
		$regulatorydata["project"] = $this->projects_model->get_regulatory_data($slug);
		$regulatorydata["main_content"] = 'projects/projects_regulatory';
		
		$this->breadcrumb->append_crumb('PROJECTS', "/projects");
		$this->breadcrumb->append_crumb($regulatorydata["project"]["projectname"]." (edit)", "/projects/".$slug."");
		$this->headerdata["breadcrumb"] = $this->breadcrumb->output();
		
		// Render HTML Page from view direcotry
		$this->load->view('templates/header',$this->headerdata);
		$this->load->view("templates/leftmenu");
		$this->load->view('templates/tabcontent',$regulatorydata);
		$this->load->view('templates/footer');
		
	}


	/**
	* Edit Participants
	* Edit participants tab for Project Edit
	*
	* @access public
	* @param string
	*/
	public function edit_participants($params)
	{
		$slug = $params;
		
		
		$participantsdata["slug"] = $slug;
		$participantsdata["vtab_position"] = 4;
		$participantsdata["project"] = $this->projects_model->get_participants_data($slug);
		$participantsdata["main_content"] = 'projects/projects_participants';
		
		$this->breadcrumb->append_crumb('PROJECTS', "/projects");
		$this->breadcrumb->append_crumb($participantsdata["project"]["projectname"]." (edit)", "/projects/".$slug."");
		$this->headerdata["breadcrumb"] = $this->breadcrumb->output();
		
		// Render HTML Page from view direcotry
		$this->load->view('templates/header',$this->headerdata);
		$this->load->view("templates/leftmenu");
		$this->load->view('templates/tabcontent',$participantsdata);
		$this->load->view('templates/footer');
		
	}

	/**
	* Edit Procurement
	* Edit procurement tab for Project Edit
	*
	* @access public
	* @param string
	*/
	public function edit_procurement($params)
	{
		$slug = $params;
		
		
		
		$procurementdata["slug"] = $slug;
		$procurementdata["vtab_position"] = 5;
		$procurementdata["project"] = $this->projects_model->get_procurement_data($slug);
		$procurementdata["main_content"] = 'projects/projects_procurement';
		
		$this->breadcrumb->append_crumb('PROJECTS', "/projects");
		$this->breadcrumb->append_crumb($procurementdata["project"]["projectname"]." (edit)", "/projects/".$slug."");
		$this->headerdata["breadcrumb"] = $this->breadcrumb->output();
		
		// Render HTML Page from view direcotry
		$this->load->view('templates/header',$this->headerdata);
		$this->load->view("templates/leftmenu");
		$this->load->view('templates/tabcontent',$procurementdata);
		$this->load->view('templates/footer');
		
	}


	/**
	* Edit Files
	* Edit files tab for Project Edit
	*
	* @access public
	* @param string
	*/
	public function edit_files($params)
	{
		$slug = $params;
		
		
		
		$filesdata["slug"] = $slug;
		$filesdata["vtab_position"] = 6;
		$filesdata["project"] = $this->projects_model->get_files_data($slug);
		$filesdata["main_content"] = 'projects/projects_files';
		
		$this->breadcrumb->append_crumb('PROJECTS', "/projects");
		$this->breadcrumb->append_crumb($filesdata["project"]["projectname"]." (edit)", "/projects/".$slug."");
		$this->headerdata["breadcrumb"] = $this->breadcrumb->output();
		
		// Render HTML Page from view direcotry
		$this->load->view('templates/header',$this->headerdata);
		$this->load->view("templates/leftmenu");
		$this->load->view('templates/tabcontent',$filesdata);
		$this->load->view('templates/footer');
	}
	
	/**
	* Project file Uploading function
	* Common function for upload any file in Project Edit Page
	*
	* @access public
	* @param string
	* @param string
	*/

	public function proj_uploadfiles($slug,$fieldname,$required=1)
	{

		

		$file = upload_file(PROJECT_IMAGE_PATH,$fieldname,'',$required);

		if($file['error']=='')
		{
			return $file;
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array($fieldname =>$this->upload->display_errors('<label>','</label>'));
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
	
			//return false when validation is not satisfied.
			return FALSE;	
		}
	}
	
	public function add_comment($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('comment', 'Comment', 'trim|required');
		
		if ($this->form_validation->run() === TRUE)
		{
			 $this->projects_model->add_comment($params);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
	
			$response["message"] 	= array('comment'=>form_error('comment'));
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}
	
	public function delete_comment()
	{
		$this->projects_model->delete_comment();
	}
	
	
	/**
	* Add CG/LA Assessment
	* Add new CG/LA Assessment for selected project
	*
	* @access public
	* @param string
	*/
	public function add_assessment($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_assessment_competitors', 'Competitors', 'trim');
		$this->form_validation->set_rules('project_assessment_drivers', 'Drivers', 'trim');
		$this->form_validation->set_rules('project_assessment_analysis', 'SWOT Analysis', 'trim');

		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->add_assessment($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_assessment_competitors'=>form_error('project_assessment_competitors'),
											'project_assessment_drivers'=>form_error('project_assessment_drivers'),
											'project_assessment_analysis'=>form_error('project_assessment_analysis')
									);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}
	
	/**
	* Update Assessment
	* update assessment for selected project
	*
	* @access public
	* @param string
	*/
	public function update_assessment($params)
	{
		$this->form_validation->set_error_delimiters('<label>', '</label>');
		$this->form_validation->set_rules('project_assessment_competitors', 'Competitors', 'trim');
		$this->form_validation->set_rules('project_assessment_drivers', 'Drivers', 'trim');
		$this->form_validation->set_rules('project_assessment_analysis', 'SWOT Analysis', 'trim');


		if($this->form_validation->run() === TRUE)
		{
			$userid	= $this->projects_model->get_uid_from_slug($params);
			$this->projects_model->update_assessment($params,$userid);
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_assessment_competitors'=>form_error('project_assessment_competitors'),
											'project_assessment_drivers'=>form_error('project_assessment_drivers'),
											'project_assessment_analysis'=>form_error('project_assessment_analysis')															);
			$response["isload"] 	= "no";
						
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}
	
	public function delete_assessment($id)
	{
		$this->projects_model->delete_assessment($id);
	}

}

/* End of file projects.php */
/* Location: ./application/controllers/projects.php */