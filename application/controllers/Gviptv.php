<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Gviptv extends CI_Controller
{

    //public class variables
    protected $headerdata = array();
    protected $footer_data = array();

    public function __construct()
    {

        parent::__construct();

        $languageSession = sess_var('lang');
        get_language_file($languageSession);

        // Load breadcrumb library
        $this->load->library('breadcrumb');

        // Set Header Data for this page like title,bodyid etc
        $this->headerdata['bodyid'] = 'forum';
        $this->headerdata['bodyclass'] = '';
        $this->headerdata['title'] = build_title(lang('forums'));

        $this->output->enable_profiler(FALSE);

        $this->footer_data['lang'] = langGet();

        //Load model for this controller
        $this->load->model('gviptv_model');
    }

    public function index()
    {

        // Load a list of all forums from the model.
        $rows = $this->gviptv_model->all();

        // Render the page
        $this->load->view('gviptv/header');
        $this->load->view('gviptv/index', array(
            'main_content' => 'rows',
            'rows' => $rows,
        ));
        $this->load->view('templates/footer', $this->footer_data);

    }
    
    public function view($id){

        //get project from id
        $model = $this->gviptv_model;
        $details  = $model->find($id);

        if (empty($details)){
            show_404();
        }

        // Load a list of all forums from the model.
        $rows = $this->gviptv_model->all();

        // Render the page
        $this->load->view('gviptv/header');

        $this->load->view('gviptv/single_page', array(
            'main_content' => 'rows',
            'rows' => $rows,
            'details' => $details,
        ));
        $this->load->view('templates/footer', $this->footer_data);

    }
}

