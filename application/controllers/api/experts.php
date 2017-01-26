<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Experts extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // If the user is not logged in then redirect to the login page
        auth_check();

        //Load the default model for this controller
        // $this->load->model('projects_model');
        
        $this->uid = (int) sess_var('uid');
    }

    /**
     * API method for dealing with forums that the expert is attending
     * @param  int $expert_id uid of expert
     * @param  int $forum_id  id of forum
     * @return json            
     */
    public function forums($expert_id, $forum_id = null) {
    	
    	$this->load->model('forums_model');

    	if (! $this->checkExpertIdIsAuthenticatedUser((int) $expert_id) ) {
    		$response = resp('error', 'You are not allowed to alter other users.');
    		return sendResponse($response);
    	}

    	if ($forum_id === null) {
	    	$response = resp('error', 'If you want to associate an expert with a forum, you need to provide a forum ID.');
    		return sendResponse($response);	
    	}

    	if ($this->input->server('REQUEST_METHOD') == 'GET') {
    		$response = resp('error', 'Method to retrieve forums not yet implemented.');
    		return sendResponse($response);
    	}

    	// Now try adding the member to the forum
    	if ($this->registerMemberForForum($expert_id, $forum_id)) {
	    	$response = resp('success', 'Thank you for registering. You will receive a confirmation email shortly.');
	    	$response["analytics"] = [
                "event" => [
                    "name" => "Register to Attend Clicked",
                    "properties" => [
                        "Forum Id" => $forum_id,
                        "Forum Name" => $this->forums_model->find($forum_id, 'title')
                    ]
                ]
            ];
			return sendResponse($response);
		}

		// Failed to do anything
		$response = resp('error', 'Sorry, but we weren\'t able to register you. Please contact support and we\'ll get it sorted right away.');
		return sendResponse($response);


    }

    /**
     * check whether an expert ID matches the authenticated user
     * @param  int $expert_id ID to check
     * @return bool            [description]
     */
    private function checkExpertIdIsAuthenticatedUser($expert_id) {
    	if ($expert_id != $this->uid) {
    		return false;
    	}

    	return true;
    }

    private function registerMemberForForum($expert_id, $forum_id) {
    	$result = $this->forums_model->delete_members($forum_id, $expert_id) &&
		    	  $this->forums_model->add_members($forum_id, $expert_id);
		return $result;
    }

}