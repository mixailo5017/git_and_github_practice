<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Inbox extends CI_Controller
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

        //load expertise model
        $this->load->model('expertise_model');

        auth_check();

        $userid = (int) sess_var('uid');

        if ($userid != (int) sess_var('uid')){
            show_404();
        }
    }

    /**
     * Index Method
     * Called when no Method Passed to URL.
     *
     * @access public
     */
    public function index()
    {
        $userid = (int) sess_var('uid');
        redirect('inbox/'.$userid,'refresh');
    }

    public function view()
    {

        $userid = (int) sess_var('uid');
        $message = $this->expertise_model->get_user_messages($userid);
        $message['userid'] = $userid;
        $message['issent'] = false;

        $data =	compact(
            'message'
        );

        // Render the page
        $this->load->view('inbox/header');
        $this->load->view('inbox/index', $data);
        $this->load->view('templates/footer', $this->footer_data);

    }

    public function message_view($messageid)
    {
        $userid = (int) sess_var('uid');
        $message = $this->expertise_model->get_user_messages($userid, $messageid);
        $message['userid'] = $userid;

        $data =	compact(
            'message'
        );

        // Render the page
        $this->load->view('inbox/header');
        $this->load->view('inbox/message_view', $data);
        $this->load->view('templates/footer', $this->footer_data);

    }

    public function sent_view()
    {

        $userid = (int) sess_var('uid');
        $message = $this->expertise_model->get_sent_messages($userid);
        $message['userid'] = $userid;
        $message['issent'] = true;

        $data = array(
            'message' => $message
        );

        // Render the page
        $this->load->view('inbox/header');
        $this->load->view('inbox/index', $data);
        $this->load->view('templates/footer', $this->footer_data);
    }
}
