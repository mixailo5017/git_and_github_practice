<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Plink extends CI_Controller
{
    /*
     * /plink/{type}/{id}
     *
     * Types:
     * 10x Email realated
     * 101 member small photo 33px width
     *
     */

    protected $types = array(
        '101' => 'email_member_small',
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function index($type = null, $id = null)
    {
        if (empty($type) || empty($this->types[$type]) || empty($id)) {
            $this->send_404();
        }

        $method = $this->types[$type];

        if (! method_exists($this, $method)) {
            $this->send_404();
        }

        $this->{$method}($id);
    }

    protected function email_member_small($id) {
        $this->load->model('expertise_model');

        $member = $this->expertise_model->find((int) $id, 'membertype, userphoto');
        if (empty($member)) {
            $this->send_404();
        }

        if ($member['membertype'] == MEMBER_TYPE_MEMBER) {
            $src = expert_image($member['userphoto'], 33, array('rounded_corners' => array('all', 0)));
        } else {
            $src = company_image($member['userphoto'], 33, array('rounded_corners' => array('all', 0)));
        }
        $ext = pathinfo($src, PATHINFO_EXTENSION);

        $this->output
            ->set_content_type($ext)
            ->set_output(file_get_contents(rtrim(FCPATH, '/') . $src));
    }

    private function send_404()
    {
        $this->output->set_status_header(404)->set_output('');
        exit;
    }
}