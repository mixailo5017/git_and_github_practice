<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller
{
    private $user;

    public function __construct()
    {
        parent::__construct();

        // TODO: Revisit
        if (! sess_var('admin_logged_in') || ! sess_var('admin_uid')) redirect('');

        $this->get_user(true);

        if (empty($this->user) || $this->user['membertype'] != MEMBER_TYPE_ADMIN) show_404();
    }

    public function index()
    {
        // Process POST first
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<label>', '</label>');

            if ($this->input->post('update') !== null) {
                $update = $this->validate_update();
            } elseif ($this->input->post('reset') !== null) {
                $update = $this->validate_reset_password();
            }
            if (! empty($update)) {
                $this->members_model->update($this->user['uid'], $update);
                // Retrieve updated user record
                $this->get_user();
            }
        }

        $headers = array(
            'bodyid' => '',
            'bodyclass' => 'withvernav',
            'title' => 'My Profile | GViP Admin',
            'js' => array(
                '/themes/js/plugins/chosen.jquery.min.js',
                '/themes/js/plugins/jquery.alerts.js',
                '/themes/js/plugins/jquery.dataTables.min.js',
            ),
            'pagejs' => array('/themes/js/custom/tables.js')
        );

        // Render the page
        $this->load->view('templates/header', $headers);
        $this->load->view('templates/leftmenu');
        $this->load->view('profile/index', array('user' => $this->user));
        $this->load->view('templates/footer');
    }

    private function validate_update()
    {
        $this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
        $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');

        if (! $this->form_validation->run()) return false;

        return array_intersect_key($this->input->post(NULL, TRUE), array_flip(array('firstname', 'lastname')));
    }

    private function validate_reset_password()
    {
        $this->form_validation->set_rules('current_password', 'Current Password', 'required|callback_valid_password',
            ['valid_password' => 'Please ensure you enter the correct current password.']
        );
        $this->form_validation->set_rules('password', 'New Password', 'min_length[6]|max_length[16]|matches[password_confirmation]');
        $this->form_validation->set_rules('password_confirmation', 'Verify Password', 'min_length[6]|max_length[16]|matches[password]');
        $this->form_validation->set_rules('email', 'Email', 'trim|strtolower|valid_email|is_unique[exp_members.email]');
        // Set custom validation error message for unique email rule
        $this->form_validation->set_message('is_unique', 'There is already an account associated with that email address.');

        if (! $this->form_validation->run()) return false;

        $update = array();

        $email = $this->input->post('email', TRUE);
        if (! empty($email)) $update['email'] = $email;

        $password = $this->input->post('password', TRUE);
        if (! empty($password)) {
            $update = array_merge($update, encrypt_password($password));
        }

        return $update;
    }

    public function valid_password($password)
    {
        $credentials = compact('password');
        $result = $this->auth->has_valid_credentials($this->user, $credentials);
        return $result;
    }

    private function get_user($with_credentials = false)
    {
        $select = 'uid,firstname,lastname,email,membertype';
        if ($with_credentials) $select .= ',password,salt';

        $id = (int) sess_var('admin_uid');
        $this->load->model('members_model');
        // TODO: Revisit and extract admins into its own model
        $this->user = $this->members_model->find($id, $select);
        if (! empty($this->user)) {
            $this->user['uid'] = (int) $this->user['uid'];
            $this->user['membertype'] = (int) $this->user['membertype'];
        }
    }
}