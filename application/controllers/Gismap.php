<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Gismap extends CI_Controller {

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
        // Render the page
        $this->load->model('projects_model');

        $map['map_data'] = $this->projects_model->get_proj_map_data();

        $data = compact(
            'map'
        );

        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('gismap/index', $data);
        $this->load->view('templates/footer', $this->footer_data);



    }

}