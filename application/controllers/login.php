<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $languageSession = sess_var('lang');
        get_language_file($languageSession);
        $this->dataLang['lang'] = langGet();

        // Remember the intended uri
        $intended = $this->input->get_post('r');
        if (! empty($intended)) {
            $this->session->set_flashdata('intended', $intended);
        }
    }

    /**
     * GET: Show login form
     * POST: Handle login request
     *
     */
    public function index()
    {
        if ($this->auth->check()) redirect_after_login();

        $error = '';

        // Process POST first
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->try_login()) {
                $this->clear_signup();
                redirect_after_login();
            }

            $error = lang('LoginFailed');
        }

        // Render the page
        $page = array(
            'view' => 'home/login',
            'title' => build_title('Login'),
            'bodyclass' => 'sign-in',
            'header' => array(),
            'content' => compact('error'),
            'footer' => array()
        );

        $this->load->view('layouts/default', $page);
    }

    public function impersonate($id)
    {
        $id = (int) $id;

        // Impersonation only allowed if logged in as an administrator
        if (! sess_var('admin_logged_in') || sess_var('admin_type') != '1') {
            show_404();
        }

        if ($id != $this->auth->id()) {
            if (! $this->auth->login_by_id($id)) {
                $this->auth->logout();
                show_error('Forbidden', 403, 'Forbidden');
            }
        }
        $this->clear_signup();

        redirect_after_login();
    }

    private function try_login()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('email', lang('Email'), 'trim|strtolower|required|valid_email');
        $this->form_validation->set_rules('password', lang('Password'), 'required|min_length[6]|max_length[16]');
        $this->form_validation->set_rules('remember', '', '');

        if ($this->form_validation->run() === FALSE) return;

        // Gather the input
        $credentials = array(
            'email' => $this->input->post('email', TRUE),
            'password' => $this->input->post('password', TRUE)
        );
        $remember = $this->input->post('remember', TRUE);

        return $this->auth->attempt($credentials, $remember);
    }

    private function clear_signup()
    {
        $this->load->model('signup_model');
        $this->signup_model->clear();
    }
}