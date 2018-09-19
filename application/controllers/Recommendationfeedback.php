<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class RecommendationFeedback extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

    }

    public function store(int $recipientUserId, string $recommendationType, int $targetId, string $uporDown)
    {
        $this->load->model('recommendationfeedback_model');
        $this->recommendationfeedback_model->store($recipientUserId, $recommendationType, $targetId, $uporDown);
        $this->session->set_flashdata('alert', [
        	'class' => 'alert-success',
        	'message' => 'Thanks for your feedback!'
        ]);
        redirect("/expertise/{$targetId}");
    }
}

