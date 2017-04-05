<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {

    private $member_project_lib;
	
	//default class variables
	public $sess_uid;
	public $sess_logged_in;
	public $headerdata = array();
	public $dataLang 	= array();
	
	public function __construct()
	{
		parent::__construct();
		
		$languageSession = sess_var('lang');
		get_language_file($languageSession);
		$this->dataLang['lang'] = langGet();

        // If the user is not logged in then redirect to the login page
        auth_check();
		
		//Load encrypt
		$this->load->library('encrypt');
		
		//Load array helper
		$this->load->helper(array('form', 'url'));
		
		//load form_validation library for default validation methods
		$this->load->library('form_validation');
	
		//Load Profile Model for this controller
		$this->load->model('profile_model');
		
		//Set Header Data for this page like title,bodyid etc
		$this->sess_uid	 = sess_var('uid');
		$this->headerdata['bodyid'] = 'Profile';
		$this->headerdata['bodyclass'] = 'no_breadcrumbs';
		$this->headerdata['title'] = build_title(lang('MyProfile'));
		
		//action type for ajax event in eductaion
		$actionType = $this->uri->segment(2, 0);
		$actionId	= $this->uri->segment(3, 0);

        // TODO: Revisit this logic to use array of events
        // deffered through flashdata
        $page_analytics = $this->session->flashdata('page_analytics');
        //var_dump($page_analytics);
        if (! empty($page_analytics)) {
            $this->headerdata['page_analytics'] = $page_analytics;
        }
    }

	/**
	* Index Method 
	* Called when no Method Passed to URL.
	*
	* @access public
	*/
	public function index()
	{
		redirect('profile/account_settings','refresh');
	}


    /**
     * Returns JSON with the information about current user's Profile Completeness Index
     */
    public function pci()
    {
        $this->load->model('expertise_model');
        $pci = $this->expertise_model->get_pci($this->sess_uid);

        sendResponse($pci);
        exit;
    }

    public function dismiss_pci()
    {
        $input = $this->input->post('dismiss_pci', TRUE);
        if ($input == 'dismiss_pci') {
            $this->load->model('expertise_model');
            $this->expertise_model->dismiss_pci($this->sess_uid);
        }

        sendResponse(array());
        exit;
    }

	/**
	* Logout Method 
	*
	* @access public
	*/
	public function logout()
	{
        logout();
	}
	
	/**
	* Account Settings method
	* Retrive all data from logged in user.
	*
	* @access public
	*/
	public function account_settings()
	{
		//print_r($this->session->all_userdata());
		// load get_user(),get_expertise() and get_education() and get_project() methods from Profile Model.
		$result			=	$this->profile_model->get_user($this->sess_uid);
		$expertise		=	$this->profile_model->get_expertise();
		$education_data	=   $this->profile_model->get_education();
        $pci = array();

		if (sess_var('usertype') == MEMBER_TYPE_EXPERT_ADVERT) {
			$project_data = $this->profile_model->get_org_projects($this->sess_uid);
		} else {
			$project_data = $this->profile_model->get_projects($this->sess_uid);
            $this->load->model('expertise_model');
            $pci = $this->expertise_model->get_pci($this->sess_uid);
		}
		$sector_data = $this->profile_model->get_expert_sectors();

		// collect data from database;
		$data = array(
			'main_content' => 'users',
			'users'	       => $result,
			'expertise'    => $expertise,
			'education'    => $education_data,
			'project'      => $project_data,
			'sector'       => $sector_data,
			'usertype'     => sess_var('usertype'),
            'pci'          => $pci
		);
		
		// Render the page
		$this->load->view('templates/header', $this->headerdata);
		$this->load->view('profile/account_settings', $data);
		$this->load->view('templates/footer', $this->dataLang);
	}
	
	
	
	/**
	* my_projects
	* Retrive all data from logged in user.
	*
	* @access public
	*/
	public function my_projects()
	{
		//print_r($this->session->all_userdata());
		// load get_user(),get_expertise() and get_education() and get_project() methods from Profile Model.
		$proj_link_data = array();
		
		$result			=	$this->profile_model->get_user($this->sess_uid);
		if(sess_var('usertype')=='8')
		{
			$project_data	=   $this->profile_model->get_org_projects($this->sess_uid);
		}
		else
		{
			$project_data	=   $this->profile_model->get_projects($this->sess_uid);
		}
		$sector_data	=   $this->profile_model->get_expert_sectors();
		$proj_link_data	=   $this->profile_model->get_project_links($this->sess_uid);
		
		//collect data from database;
		$data	=	array(
			'main_content'	=>	'users',
			'users'			=>	$result,
			'project'		=>  $project_data,
			'usertype'		=>  sess_var('usertype'),
			'projectlink'	=>  $proj_link_data
		);
		
		// Render HTML Page from view direcotry
		$this->load->view('templates/header', $this->headerdata);
		$this->load->view('profile/my_projects', $data);
		$this->load->view('templates/footer', $this->dataLang);
	}

	
	
		/**
	* Account Settings method
	* Retrive all data from logged in user.
	*
	* @access public
	*/
	public function account_settings2()
	{
		// load get_user(),get_expertise() and get_education() and get_project() methods from Profile Model.
		$result			=	$this->profile_model->get_user($this->sess_uid);
		$expertise		=	$this->profile_model->get_expertise();
		$education_data	=   $this->profile_model->get_education();
		if(sess_var('usertype')=='8')
		{
			$project_data	=   $this->profile_model->get_org_projects($this->sess_uid);
		}
		else
		{
			$project_data	=   $this->profile_model->get_projects($this->sess_uid);
		}
		$sector_data	=   $this->profile_model->get_expert_sectors();
		
		//collect data from database;
		$data	=	array(
			'main_content'	=>	'users',
			'users'			=>	$result,
			'expertise'		=>  $expertise,
			'education'		=>  $education_data,
			'project'		=>  $project_data,
			'sector'		=>  $sector_data,
			'usertype'		=>  sess_var('usertype')
		);
		
		// Render HTML Page from view direcotry
		$this->load->view("templates/header",$this->headerdata);
		$this->load->view('profile/account_settings_global',$data);
		$this->load->view('templates/footer',$this->dataLang);
	}

	
	/**
	* Update method
	* Retrieve all data from logged in user.
	*
	* @access public
	*/
	public function update()
	{	
		//define validation rules
		//$usertype = $this->input->post('hdn_member_usertype');
		$usertype = sess_var('usertype');
		
		$this->form_validation->set_error_delimiters('<label>', '</label>'); 
		if ($usertype != MEMBER_TYPE_EXPERT_ADVERT)
		{
			$this->form_validation->set_rules('member_first_name', 'First Name', 'trim|required');
			$this->form_validation->set_rules('member_last_name', 'Last Name', 'trim|required');
			$this->form_validation->set_rules('member_title', 'Title', 'trim|required');
			$this->form_validation->set_rules('member_organization', 'Member Organization', 'trim|required');
		}
		else
		{
			$this->form_validation->set_rules('member_organization', 'Organization Name', 'trim|required');
			$this->form_validation->set_rules('member_phone', 'Phone', 'trim|required');		
		}
		
		if ($this->form_validation->run() === FALSE)
		{
			//ajax response for error massages
			$response = array();
			$response["status"] 	= "error";
			
			if ($usertype != '8')
			{
				$response["message"] = array(
                    'member_first_name' => form_error('member_first_name'),
					'member_last_name' => form_error('member_last_name'),
					'member_title' => form_error('member_title')
                );
			}
			else
			{
				$response["message"] = array(
                    'member_organization' => form_error('member_organization'),
                    'member_phone' => form_error('member_phone')
                );
			}
			
			$response["isload"] 		= "no";
			//$response["isredirect"] 	= true;
						
			header('Content-type: application/json');
			echo json_encode($response);

			//return false when validation is not satisfied.
			return FALSE;
		}
		else
		{
			//call update_user() method from Profile Model
			$result	=	$this->profile_model->update_user();
		}
	}
	
	
	/**
	* send_invite_seats method
	* send_invite_seats data from expertise
	*
	* @access public
	*/
	public function send_invite_seats()
	{	
		//define validation rules
		$invt = $this->input->post('hdn_inviteno', TRUE);
		
		$this->form_validation->set_error_delimiters('<label>', '</label>'); 
		//$this->form_validation->set_rules('first_name_'.$invt, 'First Name', 'required');
		//$this->form_validation->set_rules('last_name_'.$invt, 'Last Name', 'required');
		$this->form_validation->set_rules('email_'.$invt, 'Email', 'trim|strtolower|required|valid_email');
		
		$email = $this->input->post("email_$invt", TRUE);
		
		if ($this->form_validation->run() === FALSE) {
			$response = array(
                'status' => 'error',
                'message' => form_error("email_$invt"),
                'isload' => 'no',
            );

			sendResponse($response);
            exit;
		} else {
			
			if ($this->email_exists($email)) {
				if ($this->check_associate_seat($email)) {
					$response = array(
                        'status' => 'custom_error',
                        'message' => 'User is already assigned to an organization.',
    					'isload' => 'no',
                    );

                    sendResponse($response);
                    exit;
				}

				if ($this->check_varifiedUser_seat($email))	{
					$response = array(
                        'status' => 'custom_error',
					    'message' => 'User is not verified or not active yet.',
					    'isload' =>  'no',
                    );

                    sendResponse($response);
                    exit;
				} else {
					//not realate to anywhere and send invitation.
					$result	= $this->profile_model->send_member_invite_seat($invt, sess_var('uid'));
				}
			} else 	{
					//create new user and send invitation
				$result	= $this->profile_model->send_invite_seat($invt, sess_var('uid'));
			}
		}
	}
	
	/**
	* resend_invite_seats method
	* resend_invite_seats data from expertise
	*
	* @access public
	*/
	public function resend_invite_seats($params)
	{
		if (sess_var('usertype') != MEMBER_TYPE_EXPERT_ADVERT) {
			redirect(index_page(), 'refresh');
		}
		$result	= $this->profile_model->resend_invite_seat($params, sess_var('uid'));
	}
	
	/**
	* remove_seats method
	* remove_seats data from expertise
	*
	* @access public
	*/
	public function remove_seats($params)
	{
		if (sess_var('usertype') != MEMBER_TYPE_EXPERT_ADVERT)
		{
			redirect(index_page(), 'refresh');
		}

		$result	=$this->profile_model->remove_seat($params, sess_var('uid'));
		//redirect('profile/edit_seats','refresh');
	}
	
	
	/**
	* Uplpad User Photo method
	* upload profile picture and upldate his/her account detail
	*
	* @access public
	*/
	public function upload_userphoto()
	{
        $id = (int) sess_var('uid');

        $error_response = array(
            'status' => 'error',
            'isload' => 'no'
        );

        $error_delimiters = array('open' => '<label>', 'close' => '</label>');
		$upload = upload_image(USER_IMAGE_PATH, 'photo_filename', true, array(), $error_delimiters);

		if ($upload['error'] !== '') {
            $error_response['message'] = array('photo_filename' => $upload['error']);
            sendResponse($error_response);
            exit;
        }

        if (! $this->profile_model->update_photo($id, $upload['file_name'])) {
            $error_response['message'] = lang('ErrorwhileupdatingProfilepicture');
            sendResponse($error_response);
            exit;
        }

		// Update user photo in the session
		$this->session->set_userdata('userphoto', $upload['file_name']);

		// Build the response
        $response = array(
            'status' => 'success',
            'message' => lang('Profilepictureupdatedsuccessfully'),
            'isload' => 'no',
            'imgpath' => expert_image($upload['file_name'], 150),
            'headerimgpath' => expert_image($upload['file_name'], 27, array('rounded_corners' => array('all', '2'))),
        );

        // Analytics
        $analytics = $this->photo_updated_event_data($id);
        $response['analytics'] = $analytics;

        sendResponse($response);
        exit;
	}
	
	/**
	* Upload video url method
	* upload profile picture and upldate account detail
	*
	* @access public
	*/
	public function upload_uservideo()
	{
		//define validation rules
		$this->form_validation->set_error_delimiters('<label>', '</label>'); 
		$this->form_validation->set_rules('member_video', 'Video Url', 'required');
		if ($this->form_validation->run() === FALSE)
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('member_video'=>form_error('member_video'));
			$response["isload"] 	= "no";
						
			header('Content-type: application/json');
			echo json_encode($response);

			//return false when validation is not satisfied.
			return FALSE;	
		}
		else
		{
			$this->profile_model->upload_video();
		}
		
	}	
	
	/**
	* update expertise
	* update expertise and upldate his/her account detail
	*
	* @access public
	*/
	public function update_expertise()
	{
		$this->profile_model->update_expertise();
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
		
		if ($this->form_validation->run() === FALSE)
		{
			//ajax response for error massages
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('education_university'=>form_error('education_university'),
											'education_degree'=>form_error('education_degree'),
											'education_major'=>form_error('education_major')
										);
			$response["isload"] 	= "no";
						
			header('Content-type: application/json');
			echo json_encode($response);

			//return false when validation is not satisfied.
			return FALSE;	
		}
		else
		{
			$this->profile_model->add_education();
		}
	}
	
	/**
	* Add education
	* add expertise and upldate his/her account detail
	*
	* @access public
	*/
	public function add_expert_sector()
	{
		//define validation rules
		$this->form_validation->set_error_delimiters('<label>', '</label>'); 
		$this->form_validation->set_rules('member_sector', 'Sector', 'required');
		$this->form_validation->set_rules('member_sub_sector', 'Sub Sector', 'required');
		if ($this->form_validation->run() === FALSE)
		{
			//ajax response for error massages
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_sector_main'=>form_error('member_sector'),
											'project_sector_sub'=>form_error('member_sub_sector')
										);
			$response["remove"] 	= true;
			$response["isload"] 	= "no";
			//$response["loadurl"] 	= "/projects/form_load/expertise_sector_form/view/";
		
						
			header('Content-type: application/json');
			echo json_encode($response);

			//return false when validation is not satisfied.
			return FALSE;	
		}
		else
		{
			
			$numberofsector = $this->input->post('hdn_expert_sector_number');
			
			$this->profile_model->add_expert_sector($numberofsector);
		}
	}


	/**
	* Update education
	* update expertise and upldate his/her account detail
	*
	* @access public
	*/
	public function update_expert_sector($params)
	{
		//define validation rules
		$this->form_validation->set_error_delimiters('<label>', '</label>'); 
		$this->form_validation->set_rules('member_sector', 'Sector', 'required');
		$this->form_validation->set_rules('member_sub_sector', 'Sub Sector', 'required');
		
		if ($this->form_validation->run() === FALSE)
		{
			//ajax response for error massages
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_sector_main'.$params.''=>form_error('member_sector'),
											'dynamicSubsector_'.$params.''=>form_error('member_sub_sector')
										);
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["loadurl"] 	= "/projects/form_load/expertise_sector_form/view/";
		
						
			header('Content-type: application/json');
			echo json_encode($response);

			//return false when validation is not satisfied.
			return FALSE;	
		}
		else
		{
			$this->profile_model->update_expert_sector($params,$this->sess_uid);
		}
	}
	
	/**
	* Delete expertise sector
	* update expertise sector from account detail
	*
	* @access public
	*/

	public function delete_expert_sector()
	{
			$edu_secid = $this->uri->segment(3, 0);
			$this->profile_model->delete_expert_sector($edu_secid);
	}
	
	/**
	* update education
	* update expertise and upldate his/her account detail
	*
	* @access public
	*/
	public function update_education()
	{
		$edu_editid = $this->uri->segment(3, 0);
		//define validation rules
		//define validation rules
		$this->form_validation->set_error_delimiters('<label>', '</label>'); 
		$this->form_validation->set_rules('education_university', 'University', 'required');
		$this->form_validation->set_rules('education_degree', 'Degree', 'required');
		$this->form_validation->set_rules('education_major', 'Major', 'required');
		
		if ($this->form_validation->run() === FALSE)
		{
			//ajax response for error massages
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('education_university'.$edu_editid.''=>form_error('education_university'),
											'education_degree'=>form_error('education_degree'),
											'education_major'=>form_error('education_major')
										);
			$response["isload"] 	= "no";
						
			header('Content-type: application/json');
			echo json_encode($response);

			//return false when validation is not satisfied.
			return FALSE;	
		}
		else
		{
			$this->profile_model->update_education($edu_editid);
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
			$edu_delid = $this->uri->segment(3, 0);
			$this->profile_model->delete_education($edu_delid);
	}

	/**
	* form load
	* chage after submittion of education form
	*
	* @access public
	*/

	public function form_load($formname,$actionType='',$actionId='')
	{
		switch($formname)
		{
			case 'expertise_education_form':
				$array_load = $this->profile_model->load_education($formname,$actionType,$actionId);
				$this->load->view("loader",$array_load);
			break;
			case 'expertise_sector_form':
				$array_load = $this->profile_model->load_expertsector($formname,$actionType,$actionId);
				$this->load->view("loader",$array_load);
			break;
			
			case 'get_subsector_ddl':
				$secid = $this->uri->segment(4, 0);
				$this->load->view("loader",array('formname'=>'get_subsector_ddl','secid'=>$secid));
			break;
			
			default:
			redirect('profile/account_settings','refresh');				
		}
	
	}
	
	/**
	* account setting email
	* load second verticle tab in profile module
	*
	* @access public
	*/
	
	public function account_settings_email()
	{
		//load encript library for password encryption
		$this->load->library('encrypt');

		// load get_user() methods from Profile Model.
		$user			=	$this->profile_model->get_user($this->sess_uid);
		//collect data from database;
		$user['password'] = $this->encrypt->decode($user['password']);

		$data	=	array(
			'main_content'	=>	'users',
			'users'			=>	$user,
			'usertype'		=>  sess_var('usertype')
		);
		// Render HTML Page from view direcotry
		$this->load->view('templates/header', $this->headerdata);
		$this->load->view('profile/account_settings_email', $data);
		$this->load->view('templates/footer', $this->dataLang);

	}
	
	/**
	* update email
	* update email after registration
	*
	* @access public
	*/
	public function update_email()
	{
		// Load encript library for password encryption
		$this->load->library('encrypt');

		// Define validation rules
		$this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('es_username', lang('CurrentEmail'), 'trim|strtolower|required|valid_email|is_unique[exp_members.email]');
		$this->form_validation->set_rules('es_password', lang('CurrentPassword'), 'required|min_length[6]|max_length[16]');
		// Set custom validation error message for unique email rule
		$this->form_validation->set_message('is_unique', lang('EmailNotUnique'));

        // Perform validation against input
		if ($this->form_validation->run() === FALSE) {
            sendResponse(array(
                'status' => 'error',
                'message' => array(
					'es_username' => form_error('es_username'),
                    'es_password' => form_error('es_password')
				),
                'isload' => 'no'
            ));
			return FALSE;
		}

        $uid = sess_var('uid');
        $password = $this->input->post('es_password', TRUE);
        $new_email = $this->input->post('es_username', TRUE);

        // Call model's update method providing input
        $result = $this->profile_model->update_email($uid, $password, $new_email);
        if ($result > 0) {
            sendResponse(array(
                'status' => 'success',
                'message' => lang('Emailupdatedsuccessfully'),
                'remove' => true,
                'isreset' => 'yes',
                'isredirect' => 'yes'
            ));
            return TRUE;
        } else {
            switch ($result) {
                case -1: // Passwords dont match
                    $message = array('es_password' => lang('PasswordDoesntMatchOriginal'));
                    break;
                case -2: // Error while updating
                    $message = array('es_username' => lang('ErrorwhileupdatingEmail'));
                    break;
            }
            sendResponse(array(
                'status' => 'error',
                'message' => $message,
                'isload' => 'no'
            ));
            return FALSE;
        }
	}
	

	/**
	* Update password for the current user from Account Settings page
	*
	* @return boolean
	*/
	
	public function update_password()
	{
		//load encript library for password encryption
		$this->load->library('encrypt');

		//define validation rules
		$this->form_validation->set_error_delimiters('<label>', '</label>'); 
		$this->form_validation->set_rules('ps_currentpass', lang('CurrentPassword'), 'required|min_length[6]|max_length[32]');
		$this->form_validation->set_rules('ps_newpassword', lang('NewPassword'), 'required|min_length[6]|max_length[16]|matches[ps_confpassword]');
		$this->form_validation->set_rules('ps_confpassword', lang('ConfirmPassword'), 'required|min_length[6]|max_length[16]|matches[ps_newpassword]');

		// Perform validation against input
		if ($this->form_validation->run() === FALSE)
		{
            sendResponse(array(
                'status' => 'error',
                'message' => array(
                    'ps_currentpass'  => form_error('ps_currentpass'),
                    'ps_newpassword'  => form_error('ps_newpassword'),
                    'ps_confpassword' => form_error('ps_confpassword')
                ),
                'isload' => 'no'
            ));
			exit;
		}

        $uid = sess_var('uid');
        $old_password = $this->input->post('ps_currentpass');
        $new_password = $this->input->post('ps_newpassword');

        // Call model's update method providing input
        $result = $this->profile_model->update_password($uid, $old_password, $new_password);
        if ($result > 0) {
            sendResponse(array(
                'status' => 'success',
                'message' => lang('Passwordupdatedsuccessfully'),
                'remove' => true,
                'isreset' => 'yes'
            ));
            return TRUE;
        } else {
            switch ($result) {
                case -1: // Passwords dont match
                    $message = array('ps_currentpass' => lang('PasswordDoesntMatchOriginal'));
                    break;
                case -2: // Error while updating
                    $message = array('es_username' => lang('ErrorwhileupdatingPassword'));
                    break;
            }
            sendResponse(array(
                'status' => 'error',
                'message' => $message,
                'isload' => 'no'
            ));
            exit;
        }
	}
	
	public function welcome()
	{
        $id = (int) sess_var('uid');

        $error = false;
        // Process POST request first
        // Upload member's photo
        // TODO: Revisit and extract the common logic for welcome and upload_userphoto methods
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if the post_max_size vaue is exceded
            if (! $error = is_post_msize_exceeded()) {

                $error_delimiters = array('open' => '<label>', 'close' => '</label>');
                $upload = upload_image('/' . USER_IMAGE_PATH, 'photo_filename', true, array(
                        array('width' => '198','height' => '198'),
                        array('width' => '150','height' => '150'),
                        array('width' => '50', 'height' => '50'),
                        array('width' => '39', 'height' => '39'),
                    ), $error_delimiters
                );

                if ($upload['error'] == '') {
                    // Update the photo for the current member (user)
                    $this->profile_model->update_photo($id, $upload['file_name']);

                    // Analytics
                    $page_analytics = $this->photo_updated_event_data($id);
                    // Set flash data before redirect
                    $this->session->set_flashdata('page_analytics', $page_analytics);

                    // Redirect to My Profile Edit page
                    redirect('/profile/account_settings', 'refresh');
                } else {
                    $error = $upload['error'];
                }

            }
        }

        $user = $this->profile_model->get_user($this->sess_uid);

        // Provide page analitics data for Segment Analitics
//        $this->headerdata['page_analytics'] = array(
//            'alias' => true,
//            'user_properties' => array(
//                'createdAt' => format_date($user['registerdate'], 'Y-m-d H:i:s'),
//                'firstName' => $user['firstname'],
//                'lastName' => $user['lastname'],
//                'email' => $user['email'],
//                'Organization' =>$user['organization']
//            ),
//            'event' => array(
//                'name' => 'Signed Up',
//                'properties' => array(
//                    'id' => $this->sess_uid,
//                    'User Name' => trim($user['firstname'] . ' ' . $user['lastname'])
//                )
//            )
//        );

		// Render the page
		$this->load->view('templates/header', $this->headerdata);
		$this->load->view('profile/welcome', compact('user', 'error'));
		$this->load->view('templates/footer', $this->dataLang);
	}
	
	public function edit_seats()
	{
		if (sess_var('usertype') != MEMBER_TYPE_EXPERT_ADVERT) {
			redirect(index_page(),'refresh');
		}

		$data = array();
		//print_r($this->session->all_userdata());
		//load encript library for password encryption
		$this->load->library('encrypt');
		
		$result			=	$this->profile_model->get_user($this->sess_uid);
		$seats			= 	$this->profile_model->get_seats($this->sess_uid);
		
		//collect data from database;
		$data	=	array(
			'main_content'	=>	'users',
			'users'			=>	$result,
			'usertype'		=>  sess_var('usertype'),
			'seats'			=> $seats
		);
		// Render HTML Page from view direcotry
		$this->load->view("templates/header",$this->headerdata);
		$this->load->view('profile/edit_seats',$data);
		$this->load->view('templates/footer',$this->dataLang);

	}
	
	public function edit_case_studies()
	{
		if(sess_var('usertype')!='8')
		{
			redirect(index_page(),'refresh');
		}

		$data = array();
		//print_r($this->session->all_userdata());
		//load encript library for password encryption
		$this->load->library('encrypt');
		$case_studies= array();
		
		$result					=	$this->profile_model->get_user($this->sess_uid);
		$case_studies			= 	$this->profile_model->get_case_studies($this->sess_uid,'');
		
		//collect data from database;
		$data	=	array(
			'main_content'		=>	'users',
			'users'				=>	$result,
			'usertype'			=>  sess_var('usertype'),
			'case_studies'		=> 	$case_studies
		);
		// Render HTML Page from view direcotry
		$this->load->view("templates/header",$this->headerdata);
		$this->load->view('profile/edit_case_studies',$data);
		$this->load->view('templates/footer',$this->dataLang);

	}
	public function update_case_study($params='')
	{
		
		if(sess_var('usertype') == '8')
		{
			$cno = $this->input->post('hdn_caseno');
			
			$this->form_validation->set_rules('case_name_'.$cno, 'Name', 'required');
			$this->form_validation->set_error_delimiters('<label>', '</label>'); 
		
			$case_userphoto['error'] = '';
			$case_userphoto['file_name'] = $this->input->post("photo_filenam_hidden");
			if(isset($_FILES['photo_filename']) && $_FILES['photo_filename']["name"] != "")
			{
				$case_userphoto = upload_image('/'.USER_IMAGE_PATH,'photo_filename',TRUE,array(array('width'=>'150','height'=>'150')));
			}
		    
				
			if ($this->form_validation->run() === FALSE || $case_userphoto['error'] != '')
			{
				//ajax response for error massages
				$response = array();
				$response["status"] 	= "error";
				$response["message"] 	= array('case_name_'.$cno 	=>	form_error('case_name_'.$cno),'comment_'.$cno=> $this->upload->display_errors('<label>','</label>'));
				$response["isload"] 	= "no";
				//$response['photoerror'] = $case_userphoto['error'];
				
				header('Content-type: application/json');
				echo json_encode($response);
	
				//return false when validation is not satisfied.
				return FALSE;
			}
			else
			{
				$this->profile_model->update_case_study($case_userphoto,'',$cno);
			}
	    
		}
	}
	
	
	public function view_case_studies($params = 0, $cstudyid = false)
	{

		if (!$params || !$cstudyid) {
			show_404();
		}

		$data = array();
		//print_r($this->session->all_userdata());
		//load encript library for password encryption
		$this->load->library('encrypt');
		$case_studies= array();
		
		$userid = $params;

		$result					=	$this->profile_model->get_user($userid);

		if (!$result) {
			show_404();
		}
		
		$case_studies			= 	$this->profile_model->get_case_studies($userid,'','1');
//		$current_case			=	$this->profile_model->get_case_studies($userid,$cstudyid,'1');
		$seats					= 	$this->profile_model->get_seats($userid);

        // Instead of hitting the database twice just to fetch the current case lets get it from the result array
        $current_key = 0;
        $current_case = null;
        if (count($case_studies) > 0) {
            // Try to find the current case study
            // If can't fint default to the first one
            foreach ($case_studies as $key => $case_study) {
                if ($case_study['casestudyid'] == $cstudyid) {
                    $current_key = $key;
                    break;
                }
            }
            $current_case = $case_studies[$current_key];
        }

        if(sess_var('usertype')=='8') {
			$projects	=   $this->profile_model->get_org_projects($userid);
		} else {
			$projects	=   $this->profile_model->get_projects($userid);
		}
		
		//collect data from database;
		$data	=	array(
			'main_content'		=>	'users',
			'users'				=>	$result,
			'usertype'			=>  sess_var('usertype'),
			'topexpert'			=> 	$seats,
			'case_studies'		=> 	$case_studies,
			'project'			=>  $projects,
//			'currentcase'		=>  $current_case[0]
			'currentcase'		=>  $current_case
		);
		// Render HTML Page from view direcotry
		$this->load->view("templates/header",$this->headerdata);
		$this->load->view('profile/view_case_studies',$data);
		$this->load->view('templates/footer',$this->dataLang);

	}
	
	public function delete_case_studies($params)
	{
		if(sess_var('usertype')!='8')
		{
			redirect(index_page(),'refresh');
		}
		else
		{
			$case_studyid = $params;
			$this->profile_model->delete_case_studies($case_studyid);
		}
	}

	
	
	
/*	public function send_message()
	{
		$this->profile_model->send_model_mail();
	}
*/
	
	public function accept_projExpadv($param1,$param2)
	{
		if(sess_var('usertype')!='8')
		{
			redirect(index_page(),'refresh');
		}
		else
		{
			$this->profile_model->accept_projExpadv($param1,$param2);
		}
	}
	
	public function reject_projExpadv($param1,$param2)
	{
		if(sess_var('usertype')!='8')
		{
			redirect(index_page(),'refresh');
		}
		else
		{
			$this->profile_model->reject_projExpadv($param1,$param2);
		}
	}
	
	
	private function email_exists($email)
	{
	  $this->db->where('email', $email);
   	  //$this->db->where('status', '1');	  
	  
	  $query = $this->db->get('exp_members');
	  if( $query->num_rows() > 0 ){ 
	  	return TRUE; 
	  } else { return FALSE; }
	}
	
	
	private function check_associate_seat($email)
	{
	  $this->db->join('exp_invite_experts','exp_invite_experts.uid = exp_members.uid', 'inner');
 	  $this->db->from('exp_members');
	  $this->db->where('exp_members.email', $email);
	  $this->db->where('exp_invite_experts.status','1');
	  $this->db->where('exp_members.membertype','5');
	  
	  $query = $this->db->get();
	  if( $query->num_rows() > 0 ){ return TRUE; } else { return FALSE; }
	}
	
	private function check_varifiedUser_seat($email)
	{
	 	$this->db->where('email', $email);
   	 	$this->db->where('status', '0');
   	 	$this->db->where('status', '2');
	  
	 	$query = $this->db->get('exp_members');
	  	if( $query->num_rows() > 0 )
	  	{ 
	  		return TRUE; 
	  	}
	  	else
	  	{
	  	 	return FALSE; 
	  	}
	}
	
	
	public function add_project_link()
	{
	
		//define validation rules
		$this->form_validation->set_error_delimiters('<label>', '</label>'); 
		$this->form_validation->set_rules('project_name', 'Project Name', 'required');
		$this->form_validation->set_rules('project_link', 'Project Link', 'required');
		if ($this->form_validation->run() === FALSE)
		{
			//ajax response for error massages
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_name'=>form_error('project_name'),
											'project_link'=>form_error('project_link')
										);
			$response["remove"] 	= true;
			$response["isload"] 	= "no";
			//$response["loadurl"] 	= "/projects/form_load/expertise_sector_form/view/";
		
						
			header('Content-type: application/json');
			echo json_encode($response);

			//return false when validation is not satisfied.
			return FALSE;	
		}
		else
		{			
			$this->profile_model->add_project_link();
		}

	}
	
	public function update_project_link($params)
	{
		//define validation rules
		$this->form_validation->set_error_delimiters('<label>', '</label>'); 
		$this->form_validation->set_rules('project_name', 'Project Name', 'required');
		$this->form_validation->set_rules('project_link', 'Project Link', 'required');
		if ($this->form_validation->run() === FALSE)
		{
			//ajax response for error massages
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= array('project_name'=>form_error('project_name'),
											'project_link'=>form_error('project_link')
										);
			$response["remove"] 	= true;
			$response["isload"] 	= "no";	
						
			header('Content-type: application/json');
			echo json_encode($response);

			//return false when validation is not satisfied.
			return FALSE;	
		}
		else
		{
			$this->profile_model->update_project_link($params,$this->sess_uid);
		}

	}
	
	public function delete_projlink($params)
	{
		$this->profile_model->delete_projlink($params,$this->sess_uid);
	}

    private function photo_updated_event_data($id)
    {
        $id = (int) $id;
        // Fetch PCI
        $this->load->model('expertise_model');
        $pci = $this->expertise_model->get_pci($id);
        $page_analytics = array(
            'id' => $id,
            'user_properties' => array(
                'Profile Picture Updated' => date('Y-m-d H:i:s'),
                'Profile Completion Index' => (int) $pci['pci']
            )
        );

        return $page_analytics;
    }
}
