<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Discussions extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $languageSession = sess_var('lang');
        get_language_file($languageSession);
        $this->dataLang['lang'] = langGet();

        // If the user is not logged in then redirect to the login page
        auth_check();

        //Load the model
        $this->load->model('discussions_model');
    }

    /**
     * Post a comment to a discussion
     * @param $id
     * @return bool
     */
    public function post($id)
    {
        // Don't process the request if it's not a POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;
        // Grab all the input
        $input = $this->input->post(NULL, TRUE);
        // TODO: Implement validation
        $data = array(
            'discussion_id' => (int) $id,
            'author' => (int) $input['author'], //sess_var('uid')
            'created_at' => date('Y-m-d H:i:s'),
            'content' => $input['content'],
            'reply_to' => isset($input['reply_to']) ? (int) $input['reply_to'] : null
        );

        if (! $result = $this->discussions_model->create_post($data)) {
            sendResponse(array(
                'status' => 'error'
            ));
            exit;
        }

        // Notify discussion participants
        $this->notify($data);

        sendResponse(array(
            'status' => 'success'
        ));

        exit;
    }

    public function replies($discussion_id) {
        $discussion_id = (int) $discussion_id;

        $replies = $this->discussions_model->replies($discussion_id);
        $this->preprocess_feed($replies);

        $response = array(
            'status' => 'success',
            'updates' => $this->load->view('discussions/_replies', compact('replies'), true),
            'update_count' => count($replies)
        );
        sendResponse($response);

        return true;
    }

    public function feed($discussion_id, $last_id = 0) {
        $discussion_id = (int) $discussion_id;
        $last_id = (int) $last_id;

        $feed = $this->discussions_model->feed($discussion_id, $last_id, MAX_UPDATES);
        $count = count($feed);
        $total = ($count > 0) ? (int) $feed[0]['row_count'] : 0;

        $this->preprocess_feed($feed);

        $user = array(
            'id' => sess_var('uid'),
            'photo' => expert_image(sess_var('userphoto'), 43)
        );

        $response = array(
            'status' => 'success',
            'updates' => $this->load->view('discussions/_feed', compact('feed', 'user', 'discussion_id'), true),
            'count' => $count,
            'more_count' => $total - $count
        );
        sendResponse($response);

        return true;
    }

    private function preprocess_feed(&$feed) {
        $image_size = 43;
        $now = time();

        array_walk($feed, function(&$item, $key) use ($image_size, $now) {
            $item['ago'] = time_ago($now, strtotime($item['created_at']), 'ago');
            $item['author_photo'] = expert_image($item['author_photo'], $image_size);
            $item['author_url'] = '/expertise/' . $item['author'];
        });
    }

    /**
     * Send an email notification to
     *  1. all participants except the author of the post
     *  2. don't forget to include the project owner
     * @param $data
     * @return bool
     */
    private function notify($data)
    {
        $from_id = $data['author'];
        $from_photo = "plink/101/$from_id";

        // Retrieve the discussion info
        $discussion = $this->discussions_model->find($data['discussion_id'], 'id, title, project_id');

        // grab all participants
        $participants = $this->discussions_model->experts($data['discussion_id'], false, true);

        foreach ($participants as $key => $recipient) {
            // Skip the author
            if ($recipient['id'] == $from_id) {
                $sender = $recipient;
                unset($participants[$key]); // remove author from participants
                break;
            }
        }

        $from_name = $sender['expert_name'];
        $message = nl2br(auto_link($data['content'], 'url', TRUE));
        $view_data = compact('from_id', 'from_name', 'from_photo', 'discussion', 'message');

        // Render the email from the template
        $content  = $this->load->view('email/_header', null, TRUE);
        $content .= $this->load->view('email/_new_discussion_comment', $view_data, TRUE);
        $content .= $this->load->view('email/_footer', null, TRUE);

        $subject = 'A new comment was added to the discussion "' . $discussion['title'] . '"';

        foreach ($participants as $recipient) {
            // Ignore email errors for now
            $result = email(array($recipient['email'], $recipient['expert_name']), $subject, $content, array(ADMIN_EMAIL, ADMIN_EMAIL_NAME));
        }
    }

}