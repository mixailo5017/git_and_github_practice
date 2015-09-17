<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Updates extends CI_Controller
{
    public function __construct() {
        parent::__construct();

        $languageSession = sess_var('lang');
        get_language_file($languageSession);
        $this->dataLang['lang'] = langGet();

        // If the user is not logged in then redirect to the login page
        auth_check();

        //Load the model
        $this->load->model('updates_model');
    }

    /**
     * @param $update_id
     */
    public function replies($update_id) {
        $update_id = (int) $update_id;

        $updates = $this->updates_model->replies($update_id);
        $this->preprocess_replies($updates);

        $response = array(
            'status' => 'success',
            'updates' => $this->load->view('updates/_replies', compact('updates'), true),
            'update_count' => count($updates)
        );
        sendResponse($response);
        exit;
    }

    /**
     * Returns JSON formatted array of updates for the Project profile page
     * @param $project_id
     * @param int $last_id
     */
    public function project($project_id, $last_id = 0) {
        $project_id = (int) $project_id;
        $last_id = (int) $last_id;

        $updates = $this->updates_model->project_feed($project_id, $last_id, MAX_UPDATES);
        $count = count($updates);
        $total = ($count > 0) ? (int) $updates[0]['row_count'] : 0;

        $this->preprocess_project_updates($updates);

        $user = array(
            'id' => sess_var('uid'),
            'photo' => expert_image(sess_var('userphoto'), 43)
        );

        $response = array(
            'status' => 'success',
            'updates' => $this->load->view('updates/_project_feed', compact('updates', 'user', 'project_id'), true),
            'count' => $count,
            'more_count' => $total - $count
        );
        sendResponse($response);
        exit;
    }

    /**
     * Returns JSON formatted array of updates for MyVip page
     * @param int $last_id
     */
    public function myvip($last_id = 0) {
        $last_id = (int) $last_id;

        $updates = $this->updates_model->myvip_feed(sess_var('uid'), $last_id, MAX_UPDATES);
        $count = count($updates);
        $total = ($count > 0) ? (int) $updates[0]['row_count'] : 0;

        $this->preprocess_myvip_updates($updates);

        $response = array(
            'status' => 'success',
            'updates' => $this->load->view('updates/_myvip_feed', compact('updates'), true),
            'count' => $count,
            'more_count' => $total - $count
        );
        sendResponse($response);
        exit;
    }

    /**
     * Post an update
     * @param string $type project
     * @param int $id Id of the target (project, expert...) object
     */
    public function post($type, $id) {

        switch ($type) {
            case 'project':
                $target_type = PROJECT_UPDATE;
                break;
            default:
                sendResponse(array('status' => 'error'));
                exit;
        }

        $input = $this->input->post(NULL, TRUE);
        // TODO: Implement validation
        $data = array(
            'author' => (int) $input['author'], //sess_var('uid')
            'target_type' => $target_type,
            'target_id' => (int) $id,
            'type' => (int) $input['type'],
            'created_at' => date('Y-m-d H:i:s'),
            'content' => $input['content'],
            'reply_to' => isset($input['reply_to']) ? (int) $input['reply_to'] : null
        );

        if (! $result = $this->updates_model->create($data)) {
            sendResponse(array('status' => 'error'));
            exit;
        }

        $response = array(
            'status' => 'success'
        );

        if ($target_type = PROJECT_UPDATE) {
            // Send a notification to the project owner
            $this->notify($data);

            // Analytics
            // Project name is not available to us at this point;
            // therefore we need to fetch it explicitly
            $pid = $data['target_id'];
            $this->load->model('projects_model');
            $project = $this->projects_model->find($pid, 'projectname');

            $page_analytics = array(
                'event' => array(
                    'name' => 'Comment Posted',
                    'properties' => array(
                        'Category' => 'Project',
                        'Project Id' => $pid,
                        'Project Name' => $project['projectname']
                    )
                )
            );
            $response['analytics'] = $page_analytics;
        }

        sendResponse($response);
        exit;
    }

    /**
     * Send an email notification to the project owner that a comment has been posted on their project
     * @param $data
     * @return bool
     */
    private function notify($data)
    {
        $from_id = $data['author'];

        $this->load->model('expertise_model');
        // Retrieve the sender information from the database
        $sender = $this->expertise_model->find($from_id, 'email, firstname, lastname, userphoto, membertype, organization'); //var_dump('Sender', $sender);
        if (empty($sender)) return false;

        if ($sender['membertype'] == MEMBER_TYPE_MEMBER) {
            $from_name = $sender['firstname'] . ' ' . $sender['lastname'];
        } else {
            $from_name = $sender['organization'];
        }
        $from_photo = "plink/101/$from_id";

        // Retrieve the project info
        $this->load->model('projects_model');
        $project = $this->projects_model->find($data['target_id'], 'pid, uid, projectname, projectphoto');
        if (empty($project)) return false;

        $to_id = $project['uid']; // Recipient is the project owner

        // Retrieve the recipient information from the database
        $recipient = $this->expertise_model->find($to_id, 'email, firstname, lastname, userphoto, membertype, organization');
        if (empty($recipient)) return false;

        if ($recipient['membertype'] == MEMBER_TYPE_MEMBER) {
            $to_name = $recipient['firstname'] .  ' ' . $recipient['lastname'];
        } else {
            $to_name = $recipient['organization'];
        }

        $message = nl2br(auto_link($data['content'], 'url', TRUE));

        $view_data = compact('from_id', 'from_name', 'from_photo', 'project', 'message');

        // Render the email from the template
        $content  = $this->load->view('email/_header', null, TRUE);
        $content .= $this->load->view('email/_new_comment', $view_data, TRUE);
        $content .= $this->load->view('email/_footer', null, TRUE);

        $subject = 'You have a new comment on your project';

        return email(array($recipient['email'], $to_name), $subject, $content, array(ADMIN_EMAIL, ADMIN_EMAIL_NAME));
    }

    private function preprocess_myvip_updates(&$updates) {
        $image_size = 43;
        $now = time();

        array_walk($updates, function(&$item, $key) use ($image_size, $now) {
            $item['ago'] = time_ago($now, strtotime($item['created_at']), 'ago');
            switch ($item['target_type']) {
                case PROJECT_UPDATE:
                    switch ($item['type']) {
                        case UPDATE_TYPE_STATUS:
                        case UPDATE_TYPE_PROFILE:
                            $item['author'] = $item['target'];
                            $item['author_name'] = $item['target_name'];
                            $item['author_photo'] = project_image($item['target_photo'], $image_size);
                            $item['author_url'] = '/projects/' . $item['author'];
                            break;
                        case UPDATE_TYPE_NEWPROJECT:
                            $item['author_photo'] = expert_image($item['author_photo'], $image_size);
                            $item['author_url'] = '/expertise/' . $item['author'];
                            $item['target_url'] = '/projects/' . $item['target'];
                            break;
                        }
                    break;
                case MEMBER_UPDATE:
                    $item['author_photo'] = expert_image($item['author_photo'], $image_size);
                    $item['author_url'] = '/expertise/' . $item['author'];
                    break;
            }
        });
    }

    private function preprocess_project_updates(&$updates) {
        $image_size = 43;
        $now = time();

        array_walk($updates, function(&$item, $key) use ($image_size, $now) {
            $item['ago'] = time_ago($now, strtotime($item['created_at']), 'ago');
            switch ($item['target_type']) {
                case PROJECT_UPDATE:
                    if ($item['type'] == UPDATE_TYPE_COMMENT) {
                        $item['author_photo'] = expert_image($item['author_photo'], $image_size);
                        $item['author_url'] = '/expertise/' . $item['author'];
                    } else {
                        $item['author'] = $item['target'];
                        $item['author_name'] = $item['target_name'];
                        $item['author_photo'] = project_image($item['target_photo'], $image_size);
                        $item['author_url'] = '/projects/' . $item['author'];
                    }
                    $item['target_url'] = '/projects/' . $item['target'];
                    break;
            }
        });
    }

    private function preprocess_replies(&$updates) {
        $image_size = 43;
        $now = time();

        array_walk($updates, function(&$item, $key) use ($image_size, $now) {
            $item['ago'] = time_ago($now, strtotime($item['created_at']), 'ago');
            switch ($item['target_type']) {
                case PROJECT_UPDATE:
                    $item['author_photo'] = expert_image($item['author_photo'], $image_size);
                    $item['author_url'] = '/expertise/' . $item['author'];
                    $item['target_url'] = '/projects/' . $item['target'];
                    break;
            }
        });
    }


}