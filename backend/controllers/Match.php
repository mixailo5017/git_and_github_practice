<?php

//if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Match extends CI_Controller{

    /* private */
    private $member_project_lib;
    private $project_project_lib;
    private $member_member_lib;
    private $project_lib;
    private $member_lib;

    private $ci;

    /* public */
    public $headerdata = array();

    public function __construct()
    {
        parent::__construct();

        /* Load Libraries, Models and Helpers */
        /*
        $this->member_project_lib   = new Matches_lib(MEMBER_PROJECT_TYPE);
        $this->project_project_lib  = new Matches_lib(PROJECT_PROJECT_TYPE);
        $this->member_member_lib    = new Matches_lib(MEMBER_MEMBER_TYPE);
        $this->member_member_lib    = new Matches_lib(MEMBER_MEMBER_TYPE);
        */
        $this->project_lib  = new Matches_lib(PROJECT_TYPE);
        $this->member_lib   = new Matches_lib(MEMBER_TYPE);


        $this->load->model("projects_model");
        $this->load->helper("html");

        //Set Header Data for this page like title,bodyid etc
        $this->headerdata["bodyid"] = "match";
        $this->headerdata["bodyclass"] = "";
        $this->headerdata["title"] = "Match Score Demo";

        $this->headerdata["js"]	= array(
            //"/themes/js/plugins/jquery.validate.min.js",
            //"/themes/js/plugins/jquery.tagsinput.min.js",
            //"/themes/js/plugins/charCount.js",
            "/themes/js/plugins/ui.spinner.min.js",
            "/themes/js/plugins/chosen.jquery.min.js",
            //"/themes/js/plugins/jquery.dataTables.min.js",
            //"/themes/js/plugins/jquery.bxSlider.min.js",
            //"/themes/js/plugins/jquery.slimscroll.js"
        );

        $this->headerdata["pagejs"]	= array(
            '/themes/js/custom/match_demo.js',
        );
    }

    public function index()
    {
        /* Process Queue Items */
        //$this->cli_process_queue_items();
        echo "test";
    }

    public function test_mm()
    {
        $mem1 = 509;
        $mem2 = 222;
        $this->member_lib->generate_member_member_scores($mem1,$mem2);
    }

    /**
     * NEW :: MEMBER PROJECT
     */
    public function process_member_project_scores()
    {
        //grab all project ids. process against all project ids
        $this->project_lib->cli_calculate_member_project_scores_from_project_id();
    }

    /**
     * NEW :: PROJECT PROJECT
     */
    public function process_project_project_scores()
    {
        $this->project_lib->cli_calculate_project_project_scores();
    }

    /**
     * NEW :: MEMBER MEMBER
     */
    public function process_member_member_scores()
    {
        $this->member_lib->cli_member_member_score_calculate();
    }


    public function demo()
    {
        $this->load->view("templates/header",$this->headerdata);
        $this->load->view("templates/leftmenu");

        $projects = $this->projects_model->get_projects();
        $projectList = array();
        if(isset($projects['proj'])) $projectList = $projects['proj'];

        $data = array(
            'projectList'   => $projectList,
            'pid'           => '',
        );


        //if this is a post, get the top experts and send them to the template
        if($this->input->post("match_score"))
        {
            $pid = $this->input->post("projects-select");

            if($pid)
            {
                $experts = $this->member_project_lib->get_top_experts_for_project($pid);
                $data['topExperts'] = $experts;
                $data['pid'] = $pid;
            }
        }

        $this->load->view("Match/demo_proj_exp",$data);

        $this->load->view('templates/footer');
    }

    /**
     * This function gets called from cli mode by the cron scheduler.
     * @access public
     */
    public function cli_process_member_project_queue_items()
    {
        //$this->member_project_lib->cli_process_queue_items();
    }

    public function cli_process_project_project_queue_items()
    {
        //$this->project_project_lib->cli_process_queue_items();
    }

    public function cli_process_member_member_queue_items()
    {
        //@todo - $this->member_member_lib->cli_process_queue_items(); Find out what 'Key Executives' means
    }




    /*
   public function queue_initial_project_project_pairs()
   {
       /* Project Project Score Matching //
       echo "Start initial project project calculations";
       $count = 0;
       $this->project_project_lib->cli_initial_project_project_score_calculate();
       (is_cli()) ? PHP_EOL : "";
       echo "End project project calculations are done";
       /* End Project project score matching //
   }*/

    /*
    public function queue_initial_member_project_pairs()
    {
        /* Member Project Score Matching //
        echo "Start initial member project calculations";
        $count = 0;
        $this->member_project_lib->cli_initial_member_project_score_calculate();
        (is_cli()) ? PHP_EOL : "";
        echo "End member project calculations are done" . PHP_EOL;
        /* End Member project score matching //
    }*/

    /*
    public function queue_initial_member_member_pairs()
    {
        /* MEMBER MEMBER Score Matching //
        echo "Start initial member project calculations";
        $count = 0;
        $this->member_member_lib->cli_initial_member_member_score_calculate();
        (is_cli()) ? PHP_EOL : "";
        echo "End member project calculations are done" . PHP_EOL;
        /* End member member score matching //
    }
    */

}

