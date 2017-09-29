<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller {

    protected $headerdata = array();
    protected $dataLang = array();

    public function __construct()
    {
        parent::__construct();

        $languageSession = sess_var('lang');
        get_language_file($languageSession);
        $this->dataLang['lang'] = langGet();

    }

    public function index($page)
    {
        if (! method_exists($this, $page)) show_404();

        $this->{$page}();
    }

    public function language()
    {
        // Process POST first
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('language', 'Language', 'trim|strtolower|required');

            if (! $this->form_validation->run()) {
                $error = form_error('language');

                $response = compact('error');
                sendResponse($response);
                exit;
            }

            $language = $this->input->post('language', TRUE);
            App::language($language);
        }

        $language = App::language();
        $response = compact('language');

        sendResponse($response);
        exit;
    }

    private function sitemap()
    {
        // Cache this page for PUBLIC_PROFILE_TTL minutes
         $this->output->cache(SITEMAP_TTL);

        $urls = array(
            array('loc' => base_url('/')),
            array('loc' => base_url('/login')),
            array('loc' => base_url('/signup')),
            array('loc' => base_url('/terms')),
            array('loc' => base_url('/privacy')),
        );

        if (PROJECT_PROFILES_ENABLED) {
            $this->load->model('projects_model');
            $project_urls = $this->projects_model->sitemap(base_url() . 'p/');
            $urls = array_merge($urls, $project_urls);
        }

        $this->output->set_content_type('application/xml');
        // Render the sitemap
        $this->load->view('pages/sitemap', compact('urls'));
    }

    private function terms()
    {
        $page = array(
            'view' => 'pages/terms',
            'title' => build_title(lang('TermsOfService')),
            'bodyclass' => '',
            'header' => array(),
            'content' => array(),
            'footer' => array()
        );

        $this->load->view('layouts/default', $page);
    }

    private function privacy()
    {
        $page = array(
            'view' => 'pages/privacy',
            'title' => build_title(lang('PrivacyPolicy')),
            'bodyclass' => '',
            'header' => array(),
            'content' => array(),
            'footer' => array()
        );

        $this->load->view('layouts/default', $page);
    }

    private function brazilfaq()
    {
        $page = array(
            'view' => 'pages/brazilfaq',
            'title' => build_title(lang('BrazilFAQ')),
            'bodyclass' => '',
            'header' => array(),
            'content' => array(),
            'footer' => array()
        );

        $this->load->view('layouts/default', $page);
    }

    private function privatemeetings()
    {
        // If the user is authenticated, append user's details to the meeting URL query string
        // Of course, only do this if the info is not there already (no infinite loops please)
        if (Auth::check() && !$this->input->get('email')) {
            $authenticatedUserID = Auth::id();
            $this->load->model('expertise_model');
            $authenticatedUser = $this->expertise_model->get_user($authenticatedUserID);
            
            $name = trim($authenticatedUser['firstname'].' '.$authenticatedUser['lastname']);
            $parameters = [
                'name'    => $name,
                'email'   => $authenticatedUser['email'],
                'company' => $authenticatedUser['organization']
            ];
            if ($this->input->get()) {
                $parameters = array_merge($this->input->get(), $parameters);
            }
            $queryString = http_build_query($parameters);
            redirect(current_url().'?'.$queryString);
        }

        $forumID = $this->uri->segment(2);

        $page = array(
            'view' => 'pages/privatemeetings',
            'title' => build_title(lang('ForumBookMeeting')),
            'bodyclass' => '',
            'header' => array(),
            'content' => compact('forumID'),
            'footer' => array()
        );

        $this->load->view('layouts/default', $page);
    }

    public function error_404()
    {
        show_404();
    }
}
