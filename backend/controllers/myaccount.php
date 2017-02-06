<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Myaccount extends CI_Controller {

//    private $member_project_lib;
	
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
		
			//Load array helper
		$this->load->helper(array('form', 'url'));
		
		//load form_validation library for default validation methods
		$this->load->library('form_validation');
	
		//Load Profile Model for this controller
		$this->load->model('myaccount_model');
		
		//Set Header Data for this page like title,bodyid etc
		$this->sess_uid	 = sess_var('admin_uid');
		$this->headerdata["bodyid"] = "Profile";
		$this->headerdata["bodyclass"] = "no-breadcrumbs";
		$this->headerdata["title"] = "Edit Member Profile";
		$this->headerdata["js"]	= array(
			"/themes/js/plugins/jquery.validate.min.js",
			"/themes/js/plugins/jquery.tagsinput.min.js",
			"/themes/js/plugins/charCount.js",
			"/themes/js/plugins/ui.spinner.min.js",
			"/themes/js/plugins/chosen.jquery.min.js",
			"/themes/js/plugins/jquery.dataTables.min.js",
			"/themes/js/plugins/jquery.bxSlider.min.js",
			"/themes/js/plugins/jquery.slimscroll.js"
			);
		$this->headerdata["pagejs"]	= array(
            '/themes/js/custom/forms.js',
            '/themes/js/custom/tables.js',
            '/themes/js/custom/widgets.js',
            '/themes/js/vip_custom/perpage.js',
            '/themes/js/vip_custom/myaccount.js'
        );
		//action type for ajax event in eductaion
	}

	/**
	* Index Method 
	* Called when no Method Passed to URL.
	*
	* @access public
	*/
	public function index()
	{
		redirect('myaccount/'.$this->sess_uid.'','refresh');
	}
	
	public function view($params, $selectedtab='')
	{
        $id = (int) $params;
        if (! $id) show_404();

        // Process POST first
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<label>', '</label>');

            if ($this->input->post('reset') !== false) {
                $update = $this->validate_reset();
            }

            if (! empty($update)) {
                $this->load->model('members_model');
                $this->members_model->update($id, $update);
            }
            $selectedtab = 'account';
        }


        // load get_user(),get_expertise() and get_education() and get_project() methods from Profile Model.
		$result			= $this->myaccount_model->get_user($id);
		$expertise		= $this->myaccount_model->get_expertise($id);
		$education_data	= $this->myaccount_model->get_education($id);
		$project_data	= $this->myaccount_model->get_projects($id);
		$sector_data	= $this->myaccount_model->get_expert_sectors($id);

		$non_experts	= $this->myaccount_model->get_non_expert_members($id, true);
		$seats			= $this->myaccount_model->get_seats($id);
		$seats 			= isset($seats['approved']) ? $seats['approved'] : false;
		
		
		//echo "<pre>"; var_dump( $seats ); exit;
		

		//collect data from database;
		$accountdata = array(
			'main_content'	=> 'users',
			'users'			=> $result,
			'expertise'		=> $expertise,
			'education'		=> $education_data,
			'project'		=> $project_data,
			'sector'		=> $sector_data,
			'selectedtab'	=> $selectedtab,
			'non_experts'	=> $non_experts,
			'seats'			=> $seats
		);

		$this->load->view("templates/header",$this->headerdata);
		$this->load->view("templates/leftmenu");
		$this->load->view('myaccount/myaccount',$accountdata);
		$this->load->view("templates/footer");
	}
	
	/**
	* Update method
	* Retrive all data from logged in user.
	*
	* @access public
	*/
	
	public function update()
	{
	
		$userid	 	= $this->uri->segment(3, 0);
		$usertype = $this->input->post('hdn_member_usertype');
		
		$this->form_validation->set_error_delimiters('<label>', '</label>'); 
		if($usertype != '8')
		{
			$this->form_validation->set_rules('member_first_name', 'First Name', 'required');
			$this->form_validation->set_rules('member_last_name', 'Last Name', 'required');
			$this->form_validation->set_rules('member_title', 'Tht title field', 'required');
			$this->form_validation->set_rules('member_organization', 'Member Organization', 'trim|required');		
		}
		else
		{
			$this->form_validation->set_rules('member_organization', 'Organization Name', 'trim|required');
			$this->form_validation->set_rules('member_phone', 'Phone', 'trim|required');		
		}
		
		if ($this->form_validation->run() === FALSE)
		{
			//return false when validation is not satisfied.
			$this->view($userid);
			return FALSE;
		}
		else
		{
			if($result	= $this->myaccount_model->update_user($userid))
			{
				redirect('/myaccount/'.$userid,'refresh');
			}
		}
	}


	/**
	* update_seats method
	* 	assign users to expert seats
	*
	* @access public
	*/
	public function update_seats()
	{
		$userid	 	= $this->uri->segment(3, 0);

	
		$seats = $this->input->post('seats');

		$saved = $this->myaccount_model->update_seats($userid,$seats);
		
		//http://vip.dev/admin.php/myaccount/483#expert-advert-seats
		redirect("/myaccount/{$userid}#expert-advert-seats",'refresh');
	}

	/**
	* Uplpad User Photo method
	* upload profile picture and upldate his/her account detail
	*
	* @access public
	*/
	public function upload_userphoto()
	{
		$userid	 	= $this->uri->segment(3, 0);

		$upload_userphoto = upload_image('/'.USER_IMAGE_PATH,'photo_filename',TRUE,array(array('width'=>'150','height'=>'150'),array('width'=>'284','height'=>'284'),array('width'=>'138','height'=>'138'),array('width'=>'198','height'=>'198'),array('width'=>'50','height'=>'50'),array('width'=>'39','height'=>'39'),array('width'=>'27','height'=>'27')));
		
		if($upload_userphoto['error']=='')
		{
			if($result	= $this->myaccount_model->upload_photo($userid,$upload_userphoto))
			{
				redirect('/myaccount/'.$userid,'refresh');
			}

		}
		else
		{
			$this->view($userid);
			return FALSE;
		}
	}

	/**
    * Delete User Photo method
    *
    * @access public
    */
    public function delete_userphoto()
    {
        $userid         = $this->uri->segment(3, 0);

	    $update_data = array('userphoto' => '');

        $this->db->where('uid', $userid);

        if($str = $this->db->update('exp_members', $update_data)) {
        	
	        return redirect('/myaccount/'.$userid,'refresh');
	    }
    }
    
	/**
	* update expertise
	* update expertise and upldate his/her account detail
	*
	* @access public
	*/
	public function update_expertise($params)
	{
		$userid	 	= $this->uri->segment(3, 0);
		
		if($result	=	$this->myaccount_model->update_expertise($userid))
		{
            //schedule project expert match score update after a profile update
            //$this->load->library("member_project_lib");
            //$this->member_project_lib->schedule_proj_expert_pairs_from_expert_uid($userid);

			redirect('/myaccount/'.$userid.'#tabs-2','refresh');
		}	
	}
	/**
	* Add education
	* update expertise and upldate his/her account detail
	*
	* @access public
	*/
	public function add_education()
	{
		//define validation rules
		$this->form_validation->set_error_delimiters('<label>', '</label>'); 
		$this->form_validation->set_rules('education_university', 'University', 'required');
		$this->form_validation->set_rules('education_degree', 'Degree', 'required');
		$this->form_validation->set_rules('education_major', 'Major', 'required');
		$userid = $this->input->post('hdn_userid');
		
		if ($this->form_validation->run() === FALSE)
		{
			//return false when validation is not satisfied.
			$this->view($userid,'4');
			return FALSE;
		}
		else
		{
			//call add_user() method from Home Model
			if($result	=	$this->myaccount_model->add_education($userid))
			{
				redirect('/myaccount/'.$userid.'#tabs-4','refresh');
			}			
				
		}	
	}
	public function update_education()
	{
		$userid	 	= $this->uri->segment(3, 0);
		$edu_editid = $this->uri->segment(4, 0);
		
		$this->form_validation->set_error_delimiters('<label>', '</label>'); 
		$this->form_validation->set_rules('education_university', 'University', 'required');
		$this->form_validation->set_rules('education_degree', 'Degree', 'required');
		$this->form_validation->set_rules('education_major', 'Major', 'required');
		//$userid = $this->input->post('hdn_userid');
		
		if ($this->form_validation->run() === FALSE)
		{
			//return false when validation is not satisfied.
			$this->view($userid,'4');
			return FALSE;
		}
		else
		{
			//call add_user() method from Home Model
			if($result	= $this->myaccount_model->update_education($userid,$edu_editid))
			{
				redirect('/myaccount/'.$userid.'#tabs-4','refresh');
			}			
				
		}	
		
	}


	
	/**
	* Delete education
	* update expertise and Delete his/her account detail
	*
	* @access public
	*/

	public function delete_education()
	{
		$this->myaccount_model->delete_education();
	}


	/**
	* Update expert
	* update expertise and upldate his/her account detail
	*
	* @access public
	*/
	public function update_expert_sector($params)
	{
			$userid	 	= $this->uri->segment(3, 0);
			$sec_editid = $this->uri->segment(4, 0);

		//define validation rules
		$this->form_validation->set_error_delimiters('<label>', '</label>'); 
		$this->form_validation->set_rules('member_sector', 'Sector', 'required');
		$this->form_validation->set_rules('member_sub_sector', 'Sub Sector', 'required');
		
		
		if ($this->form_validation->run() === FALSE)
		{
			//return false when validation is not satisfied.
			$this->view($userid,'3');
			return FALSE;
		}
		else
		{
			//call add_user() method from Home Model
			if($result	=	$this->myaccount_model->update_expert_sector($userid,$sec_editid))
			{
				redirect('/myaccount/'.$userid.'#tabs-3','refresh');
			}			
				
		}	
	}
	
	
	/**
	* Add education
	* add expertise and upldate his/her account detail
	*
	* @access public
	*/
	public function add_expert_sector($params)
	{
			$userid	 	= $this->uri->segment(3, 0);

		//define validation rules
		$this->form_validation->set_error_delimiters('<label>', '</label>'); 
		$this->form_validation->set_rules('member_sector', 'Sector', 'required');
		$this->form_validation->set_rules('member_sub_sector', 'Sub Sector', 'required');
		if ($this->form_validation->run() === FALSE)
		{
			//return false when validation is not satisfied.
			$this->view($userid,'3');
			return FALSE;
		}
		else
		{
			//call add_user() method from Home Model
			$numberofsector = $this->input->post('hdn_expert_sector_number');
			if($result	=	$this->myaccount_model->add_expert_sector($userid,$numberofsector))
			{
				redirect('/myaccount/'.$userid.'#tabs-3','refresh');
			}			
				
		}			
	}

	/**
	* Delete expertise sector
	* update expertise sector from account detail
	*
	* @access public
	*/

	public function delete_expert_sector($params)
	{
	    $sectionid 	= $this->uri->segment(4, 0);
		$userid	 	= $this->uri->segment(3, 0);
		$this->myaccount_model->delete_expert_sector($userid,$sectionid);
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
		$userid		= $this->uri->segment(4, 0);
		$actionId	= $this->uri->segment(5, 0);

		switch($loadtype)
		{
			case 'education_edit':
				$array_load = $this->myaccount_model->load_education($userid,$loadtype,$actionId);
				$this->load->view("loader",$array_load);
				
			break;

			case 'sector_edit':
				$array_load = $this->myaccount_model->load_expertsector($userid,$loadtype,$actionId);
				$this->load->view("loader",$array_load);
			break;
			
			case 'get_subsector_ddl':
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
				$this->load->view("loader",array('loadtype'=>'get_subsector_ddl','secid'=>$secid));
			break;
			
		}
	}

    // TODO: Deprecate and get rid of this and views/myaccount/settings
	public function settings()
	{
		$this->headerdata["title"] = "Edit Profile Settings";

		$accountdata = array();
		$result			=	get_logged_userinfo(sess_var("admin_uid"));
		
		//collect data from database;
		$accountdata 	=	array(
			'main_content'	=>	'users',
			'users'			=>	$result,
		);

		
		$this->load->view("templates/header",$this->headerdata);
		$this->load->view("templates/leftmenu");
		$this->load->view('myaccount/settings',$accountdata);
		$this->load->view("templates/footer");
	}

    // TODO: Deprecate and get rid of this
	/**
	* update email
	* update email after registration
	*
	* @access public
	*/
	public function update_email()
	{
		//define validation rules
		$this->form_validation->set_error_delimiters('<label style="float:none; padding:0px; color:red; width:100%">', '</label>'); 
		$this->form_validation->set_rules('es_username', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('es_password', 'Password', 'required|min_length[6]|max_length[16]|alpha_numeric');
		
		if ($this->form_validation->run() === FALSE)
		{
			//ajax response for error massages
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('es_username'=>form_error('es_username'),
											'es_password'=>form_error('es_password')
									);
			$response["isload"] 	= "no";
						
			header('Content-type: application/json');
			echo json_encode($response);

			//return false when validation is not satisfied.
			return FALSE;	

		}
		else
		{
			$this->myaccount_model->update_email();
		}		
	}


    // TODO: Deprecate and get rid of this
	/**
	* update password
	* chage existing password and set new password
	*
	* @access public
	*/
	public function update_password()
	{
		//load encript library for password encryption
		$this->load->library('encrypt');

		//define validation rules
		$this->form_validation->set_error_delimiters('<label style="float:none; padding:0px; color:red; width:100%">', '</label>'); 
		$this->form_validation->set_rules('ps_currentpass','Current Password', 'required|min_length[6]|max_length[32]');
		$this->form_validation->set_rules('ps_newpassword', 'New Password', 'required|min_length[6]|max_length[32]|matches[ps_confpassword]');
		$this->form_validation->set_rules('ps_confpassword', 'Password Confirmation', '');

		
		if ($this->form_validation->run() === FALSE)
		{
			//ajax response for error massages
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('ps_currentpass'=>form_error('ps_currentpass'),
											'ps_newpassword'=>form_error('ps_newpassword'),
											'ps_confpassword'=>form_error('ps_confpassword')
									);
			$response["isload"] 	= "no";
						
			header('Content-type: application/json');
			echo json_encode($response);

			//return false when validation is not satisfied.
			return FALSE;	
	

		}
		else
		{
			$this->myaccount_model->update_password();
		}	
	}
	
	
	function update_expadvert($param)
	{
		//load form_validation library for default validation methods
		$this->load->library('form_validation');
		
		$userid	 	= $param;
		$usertype = $this->input->post('hdn_member_usertype');

		//define validation rules
		$this->form_validation->set_error_delimiters('<label class="error w400">', '</label>'); 
		
		$this->form_validation->set_rules('expadvert_organizationname', 'Organization Name', 'required');
		$this->form_validation->set_rules('expadvert_number_of_seat', 'Number Of Seat', 'required|numeric');
		$this->form_validation->set_rules('expadvert_license_cost', 'License Cost', 'required');
		$this->form_validation->set_rules('expadvert_license_no', 'License Number', 'required|alpha_numeric');
		$this->form_validation->set_rules('expadvert_license_cname', 'Account Contact Name', 'required');
		$this->form_validation->set_rules('expadvert_license_cemail', 'Account Contact Email', 'required|valid_email');
		
		if ($this->form_validation->run() === FALSE)
		{
			//return false when validation is not satisfied.
			//ajax response for error massages
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('expadvert_organizationname'=>form_error('expadvert_organizationname'),
											'expadvert_number_of_seat'=>form_error('expadvert_number_of_seat'),
											'expadvert_license_cost'=>form_error('expadvert_license_cost'),
											'expadvert_license_no'=>form_error('expadvert_license_no'),
											'expadvert_license_cname'=>form_error('expadvert_license_cname'),
											'expadvert_license_cemail'=>form_error('expadvert_license_cemail')
										);
			$response["isload"] 	= "no";
						
			header('Content-type: application/json');
			echo json_encode($response);
			return FALSE;
		}
		else
		{
			if($result	= $this->myaccount_model->update_expadvert($userid))
			{
				redirect('/myaccount/'.$userid,'refresh');
			}
		}	

	}

    // Validate and collect input for Reset Password and (or) email tab
    private function validate_reset()
    {
        $this->form_validation->set_rules('password', 'New Password', 'min_length[6]|max_length[16]|matches[password_confirmation]');
        $this->form_validation->set_rules('password_confirmation', 'Verify Password', 'min_length[6]|max_length[16]|matches[password]');
        $this->form_validation->set_rules('email', 'Email', 'trim|strtolower|valid_email|is_unique[exp_members.email]');
        // Set custom validation error message for unique email rule
        $this->form_validation->set_message('is_unique', 'There is already an account with that email address.');

        if (! $this->form_validation->run()) return false;

        $update = array();

        $email = $this->input->post('email', TRUE);
        if (! empty($email)) $update['email'] = $email;

        $password = $this->input->post('password', TRUE);
        if (! empty($password)) {
            $update = array_merge($update, encrypt_password($password));
        }

        return $update;
    }


}
