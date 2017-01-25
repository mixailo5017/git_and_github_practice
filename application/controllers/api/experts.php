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
    	
    	if (! $this->checkExpertIdIsAuthenticatedUser((int) $expert_id) ) {
    		$response = resp('error', 'You are not allowed to alter other users.');
    		return sendResponse($response);
    	}

    	if ($this->input->server('REQUEST_METHOD') == 'GET') {
    		$response = resp('error', 'Method to retrieve forums not yet implemented.');
    		return sendResponse($response);
    	}

    	if ($forum_id === null) {
	    	$response = resp('error', 'If you want to associate an expert with a forum, you need to provide a forum ID.');
    		return sendResponse($response);	
    	}


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

}