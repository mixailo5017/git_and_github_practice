<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller
{
    //
    private $steps = array('start', 'edit', 'pickphoto', 'confirm');

    private $titles = array(
        'start'     => 'Create Account : Get Started',
        'edit'      => 'Create Account : Add Profile Info',
        'pickphoto' => 'Create Account : Upload Photo',
        'confirm'   => 'Create Account : Confirmation',
    );

    public function __construct()
    {
        parent::__construct();

        if ($this->auth->check()) redirect_after_login();

        $languageSession = sess_var('lang');
        get_language_file($languageSession);
        $this->dataLang['lang'] = langGet();

        $this->load->model('signup_model');

        $this->load->library('linkedin');
    }

    /**
     * LinkedIn redirect handler
     *
     */
    public function linkedin_authorized()
    {
        $error = 'Sorry. Something went wrong while trying to get your profile data from LinkedIn';

        try {
            $profile = $this->linkedin->profile();

            if (empty($profile)) $this->redirect_with_error('/signup/start', $error);

            $data = array(
                'firstname' => isset($profile['firstName']) ? $profile['firstName'] : '',
                'lastname' => isset($profile['lastName']) ? $profile['lastName'] : '',
                'email' => isset($profile['emailAddress']) ? $profile['emailAddress'] : '',
            );

            if (isset($profile['location']['country']['code']))
                $data['country'] = country_name($profile['location']['country']['code']);

            if (isset($profile['positions']) && ! empty($profile['positions']['_total'])) {
                $positions = $profile['positions']['values'];

                if (isset($positions[0]['company']) && isset($positions[0]['company']['name']))
                    $data['organization'] = $positions[0]['company']['name'];

                if (isset($positions[0]['title']) && isset($positions[0]['title']))
                    $data['title'] = $positions[0]['title'];
            }

            $this->signup_model->update($data);

            $this->log_linkedin_data($data, $profile);

            redirect('/signup/edit');

        } catch (Exception $e) {
            // TODO: Revisit and make it more specific if necessary
            $this->redirect_with_error('/signup/start', $error);
        }
    }

    public function index($step = 'start')
    {
        // If it's a wrong URI show 404
        if (! in_array($step, $this->steps)) show_404();

        $data = array();

        // Grab an error message from sesion flash data
        $error = $this->session->flashdata('error');
        if ($error) $data['error'] = $error;

        $signup = $this->signup_model->get();

        // Process POST first
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Flash error message will be overwritten here with the one that came with the POST request
            // May need to address this
            $result = $this->{"post_$step"}($signup);
            if (! empty($result) && is_array($result))
                $data = array_merge($data, $result);
        }

        // Validate signup data so that if someone goes directly to
        // /pickphoto without filling out all fields on /edit page
        // we redirect them to /start page
        if ($step == 'pickphoto' || $step == 'confirm') {
            if (! $this->signup_model->validate_required()) redirect('/signup/start');
        }

        $this->page($step, $signup, $data);
    }

    private function post_start($signup)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('is_developer', 'Are you a project developer', 'required');

        if (! $this->form_validation->run()) return;

        $linkedin = $this->input->post('linkedin', TRUE) !== false;
        $is_developer = $this->input->post('is_developer', TRUE) == '1';

        $this->signup_model->update(compact('linkedin', 'is_developer'));

        if ($linkedin) {
            $this->linkedin->authorize();
            exit;
        }

        redirect('/signup/edit');
    }

    private function post_edit($signup)
    {
        // Validation goes here
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<label>', '</label>');
        $this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
        $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('organization', 'Organization', 'trim|required');
        $this->form_validation->set_rules('public_status', 'Org Structure', 'trim|required');
        $this->form_validation->set_rules('discipline', 'Discipline', 'trim|required');
        $this->form_validation->set_rules('sub-sector[]', 'Sector(s)', 'required');
        $this->form_validation->set_rules('country', 'Country', 'trim|required');
        $this->form_validation->set_rules('city', 'City', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|strtolower|required|valid_email|is_unique[exp_members.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[16]|matches[password_confirmation]');
        $this->form_validation->set_rules('password_confirmation', 'Password confirmation', 'required|min_length[6]|max_length[16]|matches[password]');
        // Set custom validation error message for unique email rule
        $this->form_validation->set_message('is_unique', 'There is already an account with that email address.');

        if (! $this->form_validation->run()) return;

        // Grab the input
        $input = array_intersect_key($this->input->post(NULL, TRUE), $this->signup_model->get_fields());
        // Save the state
        $this->signup_model->update($input);

        // TODO: Branch logic here for LinkedIn
        redirect('/signup/pickphoto');
    }

    public function post_pickphoto($signup)
    {
  
        $input = $this->input->post(NULL, TRUE);
        
        if (! empty($input['remove_photo'])) {
            $userphoto = '';
            // Delete the photo file
            if (! empty($signup['userphoto'])) {
                $file = $_SERVER['DOCUMENT_ROOT'] . SIGNUP_IMAGE_PATH . $signup['userphoto'];
                if (file_exists($file)) unlink($file);
                // array_map('unlink', glob("some/dir/*.txt")
                
            }
        }

        if (! empty($input['upload_photo']) || ! empty($input['next'])) {
            $upload = upload_image(SIGNUP_IMAGE_PATH, 'fd-file', false);

            if (! empty($upload['error'])) {
                return array('error' => $upload['error']);
            }

            $userphoto = $upload['file_name'];

            $this->signup_model->update(compact('userphoto'));

            
        }

        // If it is not Skip update the model
        if (empty($input['skip_photo'])) $this->signup_model->update(compact('userphoto'));
      
        redirect('/signup/pickphoto');
    }


    public function post_confirm($signup)
    {
        //$signup = $this->signup_model->get();
        $is_developer = $signup['is_developer'];

        $expert = array_diff_key($signup, array_flip(array('linkedin', 'is_developer', 'sub-sector')));

        $expert['membertype'] = MEMBER_TYPE_MEMBER;
        $expert['status'] = STATUS_ACTIVE;
        $expert['registerdate'] = date('Y-m-d H:i:s');
        $expert['registerip'] = $this->input->ip_address();

        $encrypted = encrypt_password($expert['password']);
        $expert['password'] = $encrypted['password'];
        $expert['salt'] = $encrypted['salt'];

        $select2 = array();

        foreach ($signup['sub-sector'] as $key => $value) {
            $pieces = explode(':', $signup['sub-sector'][$key]);
            $select2[$key] = array(
                'sector' => $pieces[0],
                'subsector' => $pieces[1]
            );
        }

        $this->load->model('members_model');
        $new_id = $this->members_model->create($expert, $is_developer);
        $this->members_model->add_sector($select2, $new_id);

        // Move photo file
        if (! empty($expert['userphoto'])) {
            $from = $_SERVER['DOCUMENT_ROOT'] . SIGNUP_IMAGE_PATH . $expert['userphoto'];
            $to = $_SERVER['DOCUMENT_ROOT'] . USER_IMAGE_PATH . $expert['userphoto'];
            if (file_exists($from)) rename($from, $to);
        }

        // Analytics
        $this->load->model('expertise_model');
        $pci = $this->expertise_model->get_pci($new_id);

        // Compose the anaylytics data
        $page_analytics = array(
//            'alias' => true,
            'user_properties' => array(
                'createdAt' => $expert['registerdate'],
                'firstName' => $expert['firstname'],
                'lastName' => $expert['lastname'],
                'email' => $expert['email'],
                'Title' => $expert['title'],
                'Organization Structure' => $expert['public_status'],
                'Discipline' => $expert['discipline'],
                'Sector(s)' => $signup['sub-sector'],
                'User Country' => $expert['country'],
                'User City' => $expert['city'],
                'Organization' => $expert['organization'],
                'Profile Completion Index' => (int) $pci['pci'],
//                'Rating Overall Average' => 0.0,
            ),
            'event' => array(
                'name' => 'Signed Up',
                'properties' => array(
                    'id' => $new_id,
                    'User Name' => $expert['firstname'] . ' ' . $expert['lastname']
                )
            )
        );
        // By default the "Project Developer" gets pending value
        if ($is_developer) $page_analytics['user_properties']['Project Developer'] = 'pending';

        // Set analytics data before redirect
        $this->session->set_flashdata('page_analytics', $page_analytics);

        // Clear session signup data
        $this->signup_model->clear();

        $this->auth->login_by_id($new_id);
        redirect_after_login();
    }

    private function page($step, $signup, $data = array())
    {
        $content = compact('signup');
        if (! is_null($data) && is_array($data)) $content = array_merge($content, $data);

        // Render the page
        $page = array(
            'view' => "signup/$step",
            'title' => build_title($this->titles[$step]),
            'bodyclass' => 'onboarding',
            'header' => array(),
            'content' => $content,
            'footer' => array()
        );

        if ($step == 'pickphoto') {
            $page['styles'] = array(
                'lib/filedrop.css',
                'lib/cropper.min.css',
                'lib/tipsy.css'
            );
            $page['scripts'] = array(
                'filedrop.min.js',
                'lib/filedrop-min.js',
                'lib/cropper.min.js',
                'lib/jquery.tipsy.js'
            );
        }

        if ($step == 'edit') {
            $page['styles'] = [];
            $page['scripts'] = [];
        }

        $this->load->view('layouts/default', $page);
    }

    private function redirect_with_error($redirect, $error)
    {
        $this->session->set_flashdata('error', $error);
        redirect($redirect);
    }


    /**
     * AJAX endpoint to upload a user's photo
     *
     * @param string $action
     * @return array
     */
    public function photo($action = '')
    {

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            sendResponse(array(
                'status' => 'error',
                'error' => 'Invalid request.'
            ));
            exit;
        }

        $signup = $this->signup_model->get();

        $response = array('status' => 'success');

        if ($action == 'upload') {

            $upload = upload_image(SIGNUP_IMAGE_PATH, 'fd-file', false);    

            if (! empty($upload['error'])) {
                $this->sendResponse(array(
                    'status' => 'error',
                    'error' => $upload['error']
                ));
                exit;
            }

            $userphoto = $upload['file_name'];
            $base_url = rtrim(base_url(), '/');
            $response['original'] = $base_url . SIGNUP_IMAGE_PATH . $userphoto;
            $response['preview'] = $base_url . safe_image(SIGNUP_IMAGE_PATH, $userphoto, USER_NO_IMAGE_PATH . USER_IMAGE_PLACEHOLDER, array('max' => 198));

            // Remove previously uploaded image if any
            $this->remove_current_photo($signup);
        }

        if ($action == 'remove') {
            $this->remove_current_photo($signup);
            $userphoto = '';
        }

        // Update the model
        $this->signup_model->update(compact('userphoto'));

        $this->sendResponse($response);
        exit;
    }

    private function sendResponse($response)
    {
        if (isset($_POST['fd-callback']) && $_POST['fd-callback']) {
            $callBack = $_POST['fd-callback'];
            $response = json_encode($response);
            header('Content-Type: text/html; charset=utf-8');
            echo '<!DOCTYPE html><html><head></head><body><script type="text/javascript">',
                "try{window.top.$callBack($response)}catch(e){};</script></body></html>";
            exit;
        }
        sendResponse($response);
    }

    private function remove_current_photo($signup)
    {
        if (! empty($signup['userphoto'])) {
            $file = $_SERVER['DOCUMENT_ROOT'] . SIGNUP_IMAGE_PATH . $signup['userphoto'];
            if (file_exists($file)) unlink($file);
            // array_map('unlink', glob("some/dir/*.txt")
        }
    }

    private function log_linkedin_data($data, $profile)
    {
        // Return if log table doesn't exist
        if (! $this->db->table_exists('exp_linkedin_log')) return;

        try {
            $email = $data['email'];

            $picture_url = (isset($profile['pictureUrls']['values'][0])) ? $profile['pictureUrls']['values'][0] : null;
            $payload = json_encode($profile);

            $this->db
                ->set(compact('email', 'picture_url', 'payload'))
                ->insert('exp_linkedin_log');
        } catch (Exception $e) {
            log_message('error', 'log_linkedin_data: ' . $e->getCode() . ' - ' . $e->getMessage());
        }
    }
}