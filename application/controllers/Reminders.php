<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Reminders extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (logged_in()) redirect('/', 'refresh');

        $languageSession = sess_var('lang');
        get_language_file($languageSession);
        $this->dataLang['lang'] = langGet();

        $this->load->model('reminders_model');
        $this->load->model('members_model');
    }

    /**
     * GET: Show Reset Password page
     * POST: Handle a request to reset a user's password.
     *
     * @param null $token
     */
    public function reset($token = null)
    {
        $error = '';

        // Process POST first
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<label>', '</label>');
            $this->form_validation->set_rules('email', 'Email', 'trim|strtolower|required|valid_email');
            $this->form_validation->set_rules('password', 'New password', 'required|min_length[6]|max_length[16]|matches[password_confirmation]');
            $this->form_validation->set_rules('password_confirmation', 'Password confirmation', 'required|min_length[6]|max_length[16]|matches[password]');

            if ($this->form_validation->run() === TRUE) {
                $email = $this->input->post('email', TRUE);
                $token = $this->input->post('token', TRUE);
                $password = $this->input->post('password', TRUE);
//                $password_confirmation = $this->input->post('password', TRUE);

                $result = $this->reset_password($email, $token, $password);
                if ($result == REMINDER_PASSWORD_RESET) {
                    redirect('/login', 'refresh');
                }

                $error = 'Your email is incorrect or the link you followed has expired.';
            }
        }

        // Render the page
        $page = array(
            'view' => 'reminders/reset_password',
            'title' => build_title('Reset Password'),
            'bodyclass' => 'sign-in',
            'header' => array(),
            'content' => compact('token', 'error'),
            'footer' => array()
        );

        $this->load->view('layouts/default', $page);
    }

    /**
     * GET: Show Forgot Password page
     * POST: Handle a request to send a password reminder mail to the user
     *
     */
    public function remind()
    {
        // Process POST first
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<label>', '</label>');
            $this->form_validation->set_rules('email', 'Email', 'trim|strtolower|required|valid_email');

            if ($this->form_validation->run() === TRUE) {
                $email = $this->input->post('email', TRUE);

                $result = $this->send_reminder($email);

                redirect('/password/remind/sent', 'refresh');
            }
        }

        // Render the page
        $page = array(
            'view' => 'reminders/remind_password',
            'title' => build_title('Reset Password'),
            'bodyclass' => 'sign-in',
            'header' => array(),
            'content' => array(),
            'footer' => array()
        );

        $this->load->view('layouts/default', $page);
    }

    public function remind_sent()
    {
        // Render the page
        $page = array(
            'view' => 'reminders/remind_sent',
            'title' => build_title('Reset Password'),
            'bodyclass' => 'sign-in',
            'header' => array(),
            'content' => array(),
            'footer' => array()
        );

        $this->load->view('layouts/default', $page);
    }

    /**
     * Update the user's record with the new password
     * and send a notification mail
     *
     * @param $email
     * @param $token
     * @param $password
     * @return string
     */
    private function reset_password($email, $token, $password)
    {
        $email = strtolower($email);

        // Make sure that the user exists
        $user = $this->get_user($email);
        if (empty($user)) return REMINDER_INVALID_USER;

        $encrypted = encrypt_password($password);

        // TODO: Consider using a transaction here
        // START TRANSACTION;
        // Make sure that reminder exists and not expired
        $reminder = $this->reminders_model->find($token, $email);
        if (empty($reminder)) return REMINDER_INVALID_TOKEN;

        // Update the user's record with the new password
        $this->members_model->update((int) $user['uid'], $encrypted);

        // TODO: Consider to delete all expired records here
        // Delete the reminder's record
        $this->reminders_model->delete($token);
        // COMMIT;

        // Send the aknowledgement mail to the user
        $mail = $this->compose_reset_mail($user);

        // Send the aknowledgement mail
        $result = SendHTMLMail(null, array($mail['to'], $mail['to_name']), $mail['subject'], $mail['htmlcontent'], null, 'html');

        return REMINDER_PASSWORD_RESET;
    }

    /**
     * Composes a reset message
     *
     * @param $user
     * @return array
     */
    private function compose_reset_mail($user)
    {
        $to = $user['email'];
        if ($user['membertype'] == MEMBER_TYPE_MEMBER) {
            $to_name = $user['firstname'] . ' ' . $user['lastname'];
        } else {
            $to_name = $user['organization'];
        }

        $subject = 'Your password has been successfully changed.';

        $content  = "Your password has been successfully changed.\n";
        $content .= "Click the link below to login to your " . SITE_NAME . " account.\n\n";
        $url = base_url() . 'login';
        $content .= "<a href=\"$url\">Log in</a>";

        $htmlcontent = simple_mail_content($content);

        return compact('to', 'to_name', 'subject', 'htmlcontent');
    }

    /**
     * Generate a new reminder token and send a remainder email to the user
     *
     * @param $email
     * @return string
     */
    private function send_reminder($email)
    {
        $email = strtolower($email);

        $user = $this->get_user($email);

        if (empty($user)) return REMINDER_INVALID_USER;

        // Generate a new reminder token
        $token = reminder_token($email);
        $created_at = date('Y-m-d H:i:s');
        $ip =
        $reminder = compact('email', 'token', 'created_at');

        // Save the reminder record in the database
        $this->reminders_model->create($reminder);

        // Compose a reminder mail
        $mail = $this->compose_reminder_mail($user, $reminder);

        // Send the reminder mail
        $result = SendHTMLMail(null, array($mail['to'], $mail['to_name']), $mail['subject'], $mail['htmlcontent'], null, 'html');

        return REMINDER_SENT;
    }

    /**
     * Composes a reminder message
     *
     * @param $user
     * @param $reminder
     * @return array
     */
    private function compose_reminder_mail($user, $reminder)
    {
        $to = $user['email'];
        if ($user['membertype'] == MEMBER_TYPE_MEMBER) {
            $to_name = $user['firstname'] . ' ' . $user['lastname'];
        } else {
            $to_name = $user['organization'];
        }

        $subject = 'Changing your ' . SITE_NAME . ' password';

        $content  = "We received a request to update the password associated with this email address.\n";
        $content .= "Click the link below. You'll be taken to a secure page where you can change your password.\n\n";
        $url = base_url() . 'password/reset/' . $reminder['token'];
        $content .= "<a href=\"$url\">Change your password</a>";
        $content .= "\n\n This link will expire on " . date('m/d/Y H:i:s', $this->reminder_expires($reminder['created_at'])) . '.';

        $htmlcontent = simple_mail_content($content);

        return compact('to', 'to_name', 'subject', 'htmlcontent');
    }

    /**
     * Given the created_at timestamp returns the expiration time for the reminder
     *
     * @param string $created_at
     * @return int
     */
    private function reminder_expires($created_at)
    {
        return strtotime($created_at) + REMINDER_EXPIRES;
    }

    /**
     * Retrieves a user record by email
     *
     * @param $email
     * @return mixed
     */
    private function get_user($email)
    {
        $email = strtolower($email);

        $user = $this->members_model->find_by_email($email, 'uid,email,firstname,lastname,membertype');

        return $user;
    }

}