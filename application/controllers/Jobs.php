<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Jobs extends CI_Controller {

    //public class variables
    public $headerdata 	= array();
    public $uid			= '';
    public $dataLang 	= array();

    public function __construct() {

        parent::__construct();

        $languageSession = sess_var('lang');
        get_language_file($languageSession);
        $this->dataLang['lang'] = langGet();

        // If the user is not logged in then redirect to the login page
        auth_check();

        //load breadcrumb library
        $this->load->library('breadcrumb');

        //Set Header Data for this page like title,bodyid etc
        $this->uid = sess_var('uid');

        $this->headerdata = array(
            'bodyid' => 'jobs',
            'bodyclass' => '',
            'title' => build_title(lang('jobs'))
        );

    }

    public function index() {

        $this->load->model('projects_model');
        $allProj['map_data'] = $this->projects_model->get_all_proj_data();


        $this->load->model('forums_model');
        $model = $this->forums_model;
        $stimMap['map_data'] = $projects = $model->projects(37, 'pid, slug, projectname, projectphoto, p.sector, p.country, p.lat, p.lng, p.totalbudget, p.sponsor, p.stage, p.subsector, p.location, p.description', array('p.id' => 'random'), 700, 0, true);

        $data = array(
            'allProj' => $allProj['map_data'],
            'model_obj' => $this->projects_model,
            'stimMap'   => $stimMap['map_data']
        );


        $this->breadcrumb->append_crumb(lang('B_JOBS'), '/jobs');
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();


        // Render HTML Page from view direcotry
        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('jobs/index', $data);
        $this->load->view('templates/footer',$this->dataLang);


    }
}
