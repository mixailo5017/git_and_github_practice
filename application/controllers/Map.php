<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Map extends CI_Controller {

	protected  $headerdata = array();
    protected  $footer_data = array();

	public function __construct()
	{
		parent::__construct();

		$languageSession = sess_var('lang');
		get_language_file($languageSession);

        // If the user is not logged in then redirect to the login page
        auth_check();

		//Set Header Data for this page like title,bodyid etc
		$this->headerdata['bodyid'] = 'map_search';
		$this->headerdata['bodyclass'] = '';
		$this->headerdata['title'] = build_title(lang('Map'));

		// Set Footer Data
		$this->footer_data['lang'] = langGet();
	}

	public function index()
	{
        $map = $this->initialize_map();
        $this->footer_data['footer_extra'] = $this->load->view('map/_footer_extra', compact('map'), true);

        // Render the page
		$this->load->view('templates/header', $this->headerdata);
        $this->load->view('templates/_map_assets', '');
        $this->load->view('templates/_map_templates', '');
        $this->load->view('map/index');
		$this->load->view('templates/footer', $this->footer_data);
	}

    private function initialize_map() {
        // session data
        $session_map = false;

        if (isset($this->session->userdata['map']) &&
            isset($this->session->userdata['map']['zoom'])) {

            $m = $this->session->userdata['map'];

            $session_map = array(
                'zoom'		=> $m['zoom'],
                'lat'		=> $m['lat'],
                'lng'		=> $m['lng'],
                'searchtype'=> ($m['type'] == 'myprojects') ? 'projects' : $m['type'],
                'filters'	=> isset($m['filters']) ? $m['filters'] : false,
                'forum'		=> false
            );

            foreach ($session_map as $key => $value) {
                if ($value == false) unset($session_map[$key]);
            }
        }

        return json_encode($session_map);
    }
}