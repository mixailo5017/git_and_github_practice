<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class PublicProfiles extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Load language file
        get_language_file('english');

    }

    public function projects($slug)
    {
        if (! PROJECT_PROFILES_ENABLED) show_404();

        // Cache this page for PUBLIC_PROFILE_TTL minutes
        //$this->output->cache(PUBLIC_PROFILE_TTL);

        $this->load->model('projects_model');
        $project = $this->projects_model->find_public($slug);

        // If a project doesn't exist show 404
        if (empty($project)) show_404();

        $project['photo_src'] = project_image($project['projectphoto'], 198);
        $project_executive['photo_src'] = expert_image('', 198);

        $page = array(
            'view' => 'project',
            'title' => build_title($project['projectname']),
            'header' => array(),
            'content' => compact('project', 'project_executive'),
            'footer' => array()
        );

        $this->load->view('public/layout', $page);
    }
}