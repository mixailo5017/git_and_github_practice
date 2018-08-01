<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	protected $headerdata = array();
	protected $dataLang = array();

	public function __construct()
	{
		parent::__construct();

		// Check and execute any outstanding migrations
		$this->load->library('migration');
		if (! $this->migration->current()) {
			show_error($this->migration->error_string());
		}

		$languageSession = sess_var('lang');
		get_language_file($languageSession);
		$this->dataLang['lang'] = langGet();

		// if already logged in redirect to MyViP dashboard
		if ($this->auth->check()) redirect_after_login();

		// Load the default model for this controller
		$this->load->model('home_model');

		// TODO: Deprecate the rest
		//
		// Set Header Data for this page like title,bodyid etc
		$this->headerdata['bodyid'] = 'home';
		$this->headerdata['bodyclass'] = 'no-breadcrumbs';
		$this->headerdata['title'] = build_title(lang('InfrastructureProfessionalsNetwork'));
	}

	/**
	* Show home (landing page)
	*
	*/
	public function index()
	{
		// Get counters
		// TODO: Implement caching for counters
		// benedmunds/codeigniter-cache (https://github.com/benedmunds/codeigniter-cache)
		$counters = $this->home_model->get_counters();

		// Render the page
		$page = array(
			'view' => 'home/home',
			'title' => build_title(lang('InfrastructureProfessionalsNetwork')),
			'bodyclass' => 'home',
			'header' => array(),
			'content' => compact('counters'),
			'footer' => array()
		);

		$this->load->view('layouts/default', $page);
	}

	// TODO: Revisit and depricate all methods below
	//
	public function verification()
	{
		// load get_user() methods from Profile Model.
		$data["result"] = array();
		$data["result"]["data"] = array();
		$data["result"]["stage"] = "verify";
		
		$data["result"]["data"]["status"] = "success";
		$data["result"]["data"]["msg"] = $this->dataLang['lang']['thankscheckemailverification'];

		//collect data from database;
		
		// Render HTML Page from view direcotry
		$this->load->view("templates/header",$this->headerdata);
		$this->load->view('verification',$data);
		$this->load->view('templates/footer',$this->dataLang);

	}

	public function bypassverification()
	{
		// load get_user() methods from Profile Model.
		$data["result"] = array();
		$data["result"]["data"] = array();
		$data["result"]["stage"] = "verify";
		
		$data["result"]["data"]["status"] = "success";
		$data["result"]["data"]["msg"] = $this->dataLang['lang']['loginusingnewaccount'];

		//collect data from database;
		
		// Render HTML Page from view direcotry
		$this->load->view("templates/header",$this->headerdata);
		$this->load->view('bypassverification',$data);
		$this->load->view('templates/footer',$this->dataLang);
	}
	
	public function verifyaccount($params)
	{
		
		//load encript library for password encryption
		$this->load->library('encrypt');

		$data["result"] = array();
		$email = $this->home_model->verified_account($params);
		if($email == "success")
		{
			$data["result"]["stage"] = "reset";
			$data["result"]['data']["status"] = "success";
			$data["result"]['data']["msg"] = $this->dataLang['lang']['pendingapproval'];
		}
		elseif($email == "waiting")
		{
			$data["result"]["stage"] = "reset";
			$data["result"]['data']["status"] = "error";
			$data["result"]['data']["msg"] = $this->dataLang['lang']['alreadyverified'];
		}
		else
		{
			$data["result"]["stage"] = "reset";
			$data["result"]['data']["status"] = "error";
			$data["result"]['data']["msg"] = $this->dataLang['lang']['couldnotfinduserdb'];
		}
		
		$this->load->view('templates/header',$this->headerdata);
		$this->load->view('verification',$data);
		$this->load->view('templates/footer',$this->dataLang);
	}
	
	public function seat_accept_account($params)
	{
		//load encript library for password encryption
		$this->load->library('encrypt');

		$data["result"] = array();
		$email = $this->home_model-> seat_accept_account($params);

		if ($email == "success")
		{
			$data["result"]["stage"] = "reset";
			$data["result"]['data']["status"] = "success";
			$data["result"]['data']["msg"] = lang('confirming_associate');
		} else {
			$data["result"]["stage"] = "reset";
			$data["result"]['data']["status"] = "error";
			$data["result"]['data']["msg"] = lang('couldnotfinduserdb');
		}
		
		$this->load->view('templates/header', $this->headerdata);
		$this->load->view('verification', $data);
		$this->load->view('templates/footer', $this->dataLang);
	}
	
	public function seat_member_account($params)
	{
		//load encript library for password encryption
		$this->load->library('encrypt');

		$data["result"] = array();
		$email = $this->home_model->seat_member_account($params);
		if($email == "success")
		{
			$data["result"]["stage"] = "reset";
			$data["result"]['data']["status"] = "success";
			
			$data["result"]['data']["msg"] = $this->dataLang['lang']['confirming_associate'];
		}
		else
		{
			$data["result"]["stage"] = "reset";
			$data["result"]['data']["status"] = "error";
			$data["result"]['data']["msg"] = $this->dataLang['lang']['couldnotfinduserdb'];
		}
		
		$this->load->view('templates/header',$this->headerdata);
		$this->load->view('verification',$data);
		$this->load->view('templates/footer',$this->dataLang);
	}
}
