<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Members extends CI_Controller {

	public $sess_uid;
	public $sess_logged_in;
	public $headerdata = array();

	public function __construct()
	{
		parent::__construct();

		//Session check for the Login Status, if not logged in then redirect to Home page
		if (! sess_var('admin_logged_in')) {
			redirect('', 'refresh');
		}

		//Load Profile Model for this controller
		$this->load->model('members_model');

		//Set Header Data for this page like title,bodyid etc
		$this->sess_uid	 = sess_var('admin_uid');
	}

	/**
	 * Return a count and a list of projects for a given member in JSON format
	 * @param int $member_id
     */
	public function projects($member_id)
	{
		$member_id = (int) $member_id;

		$this->load->model('projects_model');
		$projects = $this->projects_model->member_projects($member_id, 'pid, projectname');

		$reponse = array(
			'project_count' => count($projects),
			'projects' => $projects
		);

		sendResponse($reponse);
		exit;
	}

//	public function index()
//	{
//		redirect('members/view_all_members', 'refresh');
//	}
//
	public function export()
	{
		// Process POST first
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<label>', '</label>');
			$this->form_validation->set_rules('fields', 'Fields', 'required');
			$this->form_validation->set_rules('format', 'Format', 'required');
			$this->form_validation->set_rules('new_line', 'New line character', 'required');

			if ($this->form_validation->run() !== FALSE) {
				$fields = $this->input->post('fields', TRUE);
				$format = $this->input->post('format', TRUE);
				$new_line = ($this->input->post('new_line', TRUE) == 'rn') ? "\r\n" : "\n";

				$this->export_csv($fields, $format, $new_line);
			}
		}

		$header = array(
			'bodyid' => '',
			'bodyclass' => 'withvernav',
			'title' => 'Export Members | GViP Admin',
			'js'=> array(
//				'/themes/js/plugins/jquery.dataTables.min.js',
				'/themes/js/plugins/chosen.jquery.min.js',
				'/themes/js/plugins/jquery.alerts.js'
			),
//			'pagejs' => array('/themes/js/custom/tables.js')
		);

		$fields = array(
			'_all' => 'All',
			'uid' => 'User ID',
			'firstname' => 'First Name',
			'lastname' => 'Last Name',
			'email' => 'Email',
			'title' => 'Title',
			'organization' => 'Organization',
			'country' => 'Country',
			'userphoto' => 'Photo',
			'pci' => 'PCI',
			'registerdate' => 'Join Date',
			'discipline' => 'Discipline',
			'annualrevenue' => 'Annual Revenue',
			'totalemployee' => 'Total Employees',
			'public_status' => 'Org Structure',
			'address' => 'Address',
			'state' => 'State',
			'postal_code' => 'Postal Code',
			'rating_overall' => 'Overall Rating',
			'rating_count' => 'Number of Ratings'
		);
		$default_fields = array('firstname', 'lastname', 'email');

		$this->load->view('templates/header', $header);
		$this->load->view('templates/leftmenu');
		$this->load->view('members/export', compact('fields', 'default_fields'));
		$this->load->view('templates/footer');
	}

	private function export_csv($fields, $format, $new_line)
	{
		$delimiter = ($format == 'tsv') ? "\t" : ',';
		$csv = $this->members_model->export_csv($fields, array(), $delimiter, $new_line);
		$filename = 'members_' . date('Y-m-j') . '.csv';

		if ($format == 'tsv') {
			// Excel for Mac does not currently support UTF-8
			// To fix this we convert encoding to UTF-16LE
			// And add UTF-16LE byte order mark (BOM)
			$csv = chr(255) . chr(254) . mb_convert_encoding($csv, 'UTF-16LE', 'UTF-8');
		}

		header('Content-Encoding: UTF-8');
		header('Content-type: text/csv; charset=UTF-8');
		header("Content-Disposition: attachment; filename={$filename}");
		header('Cache-control: private, max-age=0, no-cache');

		echo $csv;
		exit;
	}

	/**
	* Members CSV Download
	*
	*/
	public function members_csv()
	{
		$query = $this->db
			->select('firstname, lastname, email') // fields to use
			->where('membertype', MEMBER_TYPE_MEMBER) // normal members only
			->get('exp_members');

		$this->load->dbutil();

		$filename = 'members_export_' . date('Y-m-j') . '.csv';


		header('Content-Encoding: UTF-8');
		header('Content-type: text/csv; charset=UTF-8');
		header("Content-Disposition: attachment; filename={$filename}");
		//echo "\xEF\xBB\xBF"; // UTF-8 BOM

		echo $this->dbutil->csv_from_result($query);

		exit;
	}

	/**
	* View all members method
	* Retrive all members registered into system.
	*
	* @access public
	*/
	public function index($membertype = null)
	{
		$this->headerdata ["bodyid"] = "Profile";
		$this->headerdata ["bodyclass"] = "withvernav";
		$this->headerdata["title"] = "View Members | ViP Admin";
		$this->headerdata["js"]		= array(
			"/themes/js/plugins/jquery.dataTables.min.js",
			"/themes/js/plugins/chosen.jquery.min.js",
			"/themes/js/plugins/jquery.alerts.js"
			);
		$this->headerdata["pagejs"]	= array("/themes/js/custom/tables.js");

		// load all members data from Members Model.
		$members = $this->members_model->get_members($membertype);

		// Render the page
		$this->load->view('templates/header', $this->headerdata);
		$this->load->view('templates/leftmenu');
		$this->load->view('members/members_list', compact('members'));
		$this->load->view('templates/footer');
	}

	/**
	* Delete a member(s)
	*/
//	public function delete()
//	{
//        $id = $this->input->get('delids');
//        if (empty($id)) {
//            return;
//        }
//
//        if ($this->members_model->delete($id)) {
//            $response = array(
//                'status' => 'success',
//                'msgtype' => 'success',
//                'msg' => 'Member(s) deleted successfully.'
//            );
//        } else {
//            $response = array(
//                'status' => 'error',
//                'msgtype' => 'error',
//                'msg' => 'Error while trying to delete member(s).'
//            );
//        }
//
//        sendResponse($response);
//        exit;
//	}

    /**
     * Restore (undelete) a member account
     * @param $id
     */
    public function restore($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($id)) {
            return;
        }
        $id = (int) $id;
        if ($this->members_model->enable($id)) {
            $response = array(
                'status' => 'success',
                'msgtype' => 'success',
                'msg' => 'Member(s) restored successfully.'
            );
        } else {
            $response = array(
                'status' => 'error',
                'msgtype' => 'error',
                'msg' => 'Error while trying to restore the member.'
            );
        }

        sendResponse($response);
        exit;
    }

    /**
     * Disable a member account
     * @param $id
     */
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($id)) {
            return;
        }

        $id = (int) $id;
        if ($this->members_model->disable($id)) {
            $response = array(
                'status' => 'success',
                'msgtype' => 'success',
                'msg' => 'Member(s) deleted successfully.'
            );
        } else {
            $response = array(
                'status' => 'error',
                'msgtype' => 'error',
                'msg' => 'Error while trying to delete the member.'
            );
        }

        sendResponse($response);
        exit;
    }

	/**
	* Manage Group Method
	* Retrive all member group.
	*
	* @access public
	*/
	public function manage_group()
	{
		$this->headerdata ["bodyid"] = "Profile";
		$this->headerdata ["bodyclass"] = "withvernav";
		$this->headerdata["title"] = "View Members | ViP Admin";
		$this->headerdata["js"]		= array("/themes/js/plugins/jquery.dataTables.min.js");
		$this->headerdata["pagejs"]	= array("/themes/js/custom/tables.js");

		// load member group data from Members Model.
		$membergroupdata			=	$this->members_model->get_member_group();

		//collect data from database;
		$data	=	array(
			'main_content'	=>	'member_group',
			'member_group'	=>	$membergroupdata,
		);
		// Render HTML Page from view direcotry
		$this->headerdata["title"] = "Member Groups | VIP Admin";
		$this->load->view("templates/header",$this->headerdata);
		$this->load->view("templates/leftmenu");
		$this->load->view('members/manage_group',$data);
		$this->load->view("templates/footer");
	}

	/**
	* Delete Member Group
	* Delete Members group.
	*
	* @access public
	*/
	public function delete_group()
	{
		$this->members_model->delete_group();
	}

	/**
	* Verify Member
	* Verify Members.
	*
	* @access public
	*/
	public function approve($params='')
	{
		if($params)
		{
			if($this->members_model->approve_request($params))
			{
				redirect('/members','refresh');
			}
		}
	}

	public function deny($params='')
	{
		if($params)
		{
			if($this->members_model->deny_request($params))
			{
				redirect('/members','refresh');
			}
		}
	}


	public function edit_member_group($params='')
	{
		$this->headerdata ["bodyid"] = "Profile";
		$this->headerdata ["bodyclass"] = "withvernav";
		$this->headerdata["title"] = "View Members | ViP Admin";
		$this->headerdata["js"]		= array("/themes/js/plugins/jquery.dataTables.min.js");
		$this->headerdata["pagejs"]	= array("/themes/js/custom/tables.js");

		if($params != "")
		{
			$this->headerdata["title"] = "Edit Member Group | VIP Admin";
			$gd = $this->members_model->get_member_group_detail($params);
			$data_group['group_data'] = $gd['0'];
			$data_group['headertitle'] = 'Edit Member Group';
		}
		else
		{
			$this->headerdata["title"] = "Add Member Group | VIP Admin";
			$data_group['headertitle'] = 'Add Member Group';
		}


		$this->load->view("templates/header",$this->headerdata);
		$this->load->view("templates/leftmenu");
		$this->load->view('members/edit_member_group',$data_group);
		$this->load->view("templates/footer");

	}


   public function new_member($params='')
	{
		$this->headerdata ["bodyid"] 		= "Profile";
		$this->headerdata ["bodyclass"] 	= "withvernav";
		$this->headerdata["title"] 			= "View Members | ViP Admin";
		$this->headerdata["js"]				= array(
												"/themes/js/plugins/jquery.validate.min.js",
												"/themes/js/plugins/jquery.tagsinput.min.js",
												"/themes/js/plugins/charCount.js",
												"/themes/js/plugins/ui.spinner.min.js",
												"/themes/js/plugins/chosen.jquery.min.js"
												);
		$this->headerdata["pagejs"]			= array("/themes/js/custom/forms.js","/themes/js/vip_custom/form_validation.js");

		$this->headerdata["conditionalcss"]	= array(
												"[if IE 9]"=>"css/style.ie9.css",
												"[if IE 8]"=>"css/style.ie8.css"
										    	);
		$this->headerdata["conditionaljs"]	= array("[if lt IE 9]"=>"http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js");


		if($params != "")
		{
			$this->headerdata["title"] = "Edit Member Profile | VIP Admin";
			$data = array(
				"headertitle" => "Edit Member Profile"
			);

		}
		else
		{
			$this->headerdata["title"] = "Register New Member| VIP Admin";
			$data = array(
				"headertitle" => "Register New Member"
			);
		}


		$this->load->view("templates/header",$this->headerdata);
		$this->load->view("templates/leftmenu");
		$this->load->view('members/new_member',$data);
		$this->load->view("templates/footer");

	}



	function add_member()
	{
		//load form_validation library for default validation methods
		$this->load->library('form_validation');

		//define validation rules
		$this->form_validation->set_error_delimiters('<label class="error w400">', '</label>');
		$this->form_validation->set_rules('member_first_name', 'First Name', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|is_unique[exp_members.email]');
		$this->form_validation->set_rules('member_organization', 'Member Organization', 'trim|required');
		$this->form_validation->set_rules('register_password', 'Password', 'required|min_length[6]|max_length[32]');

		if ($this->form_validation->run() === FALSE)
		{
			//return false when validation is not satisfied.
			$this->new_member();
			return FALSE;
		}
		else
		{
			//call add_user() method from Home Model
			if($result	=	$this->members_model->add_user())
			{
				redirect('/members','refresh');
			}



		}

	}



	public function new_expert_advert($params='')
	{
		$this->headerdata["bodyid"] 		= "Profile";
		$this->headerdata["bodyclass"] 		= "withvernav";
		$this->headerdata["title"] 			= "View Members | ViP Admin";
		$this->headerdata["js"]				= array(	"/themes/js/plugins/jquery.validate.min.js",
														"/themes/js/plugins/jquery.tagsinput.min.js",
														"/themes/js/plugins/charCount.js",
														"/themes/js/plugins/ui.spinner.min.js",
														"/themes/js/plugins/chosen.jquery.min.js"
												);
		$this->headerdata["pagejs"]			= array("/themes/js/vip_custom/myaccount.js");

		$this->headerdata["conditionalcss"]	= array(
												"[if IE 9]"=>"css/style.ie9.css",
												"[if IE 8]"=>"css/style.ie8.css"
										    	);
		$this->headerdata["conditionaljs"]	= array("[if lt IE 9]"=>"http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js");


		if($params != "")
		{
			$this->headerdata["title"] = "Edit Member Profile | VIP Admin";
			$data = array(
				"headertitle" => "Edit Member Profile"
			);

		}
		else
		{
			$this->headerdata["title"] = "Register New Organization| VIP Admin";
			$data = array(
				"headertitle" => "Register New Organization"
			);
		}


		$this->load->view("templates/header",$this->headerdata);
		$this->load->view("templates/leftmenu");
		$this->load->view('members/new_expert_advert',$data);
		$this->load->view("templates/footer");

	}

	function add_expadvert()
	{
		//load form_validation library for default validation methods
		$this->load->library('form_validation');


		//define validation rules
		$this->form_validation->set_error_delimiters('<label class="error w400">', '</label>');

		$this->form_validation->set_rules('expadvert_organizationname', 'Organization Name', 'required');
		$this->form_validation->set_rules('expadvert_number_of_seat', 'Number Of Seat', 'required|numeric');
		$this->form_validation->set_rules('expadvert_license_cost', 'License Cost', 'required');
		$this->form_validation->set_rules('expadvert_license_no', 'License Number', 'required|alpha_numeric');
		$this->form_validation->set_rules('expadvert_license_cname', 'Account Contact Name', 'required');
		$this->form_validation->set_rules('expadvert_license_cemail', 'Account Contact Email', 'required|is_unique[exp_members.email]|valid_email');

		if ($this->form_validation->run() === FALSE)
		{
			//return false when validation is not satisfied.
			$this->new_expert_advert();
			return FALSE;
		}
		else
		{
			//call add_user() method from Home Model
			if($result	=	$this->members_model->add_expadvert())
			{
				redirect('/members','refresh');
			}
		}
	}

	public function update_member_group($params)
	{
	//load form_validation library for default validation methods
		$this->load->library('form_validation');


		//define validation rules
		$this->form_validation->set_error_delimiters('<label class="error w400">', '</label>');

		$this->form_validation->set_rules('group_title', 'Group Name', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			//return false when validation is not satisfied.
			$this->update_member_group();
			return FALSE;
		}
		else
		{
			//call add_user() method from Home Model
			if($result	=	$this->members_model->update_member_group($params))
			{
				redirect('/members/manage_group','refresh');
			}



		}	}

}

/* End of file members.php */
/* Location: ./backend/controllers/profile.php */
?>