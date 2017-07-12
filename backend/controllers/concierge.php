<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Concierge extends CI_Controller {

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
		$this->load->model('concierge_model');

		$this->load->helpers('text_helper');

		//Set Header Data for this page like title,bodyid etc
		$this->headerdata["bodyclass"] = "withvernav";
		$this->headerdata["title"] = "Concierge | GViP Admin Interface";
	}


	public function index()
	{
		redirect('concierge/questions','refresh');
	}

	public function questions()
	{
		// set dataTable to use dynamic tables
		$this->headerdata["js"]		= array('/themes/js/plugins/jquery.dataTables.min.js');
		$this->headerdata["pagejs"]	= array('/themes/js/custom/tables.js');

		$data['questions'] = $this->concierge_model->get();

		$this->load->view('templates/header',$this->headerdata);
		$this->load->view('templates/leftmenu');
		$this->load->view('concierge/concierge_list',$data);
		$this->load->view('templates/footer');
	}

	public function question($id=false,$action=false)
	{
		if( in_array($action, array('archive','unarchive') ) )
		{
			$this->concierge_model->$action($id);
		}

		$data['question'] = $this->concierge_model->get($id);

		$this->load->view('templates/header',$this->headerdata);
		$this->load->view('templates/leftmenu');
		$this->load->view('concierge/concierge_detail',$data);
		$this->load->view('templates/footer');
	}

	public function archive_questions()
	{

		$delids = $this->input->get("delids");

		// stop if no ids
		if( ! count($delids) > 0) exit;

		if( $this->concierge_model->archive_many($delids) )
		{
			$response = resp('success','Questions Archived Successfully','yes');
		}
		else
		{
			$response = resp('error','Something went wrong','no');
		}

		die_json($response);

	}
}

/* End of file welcome.php */
/* Location: ./backend/controllers/welcome.php */