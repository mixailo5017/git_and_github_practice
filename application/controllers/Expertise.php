<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Expertise extends CI_Controller {

	public $sess_uid;
	public $sess_logged_in;
	public $headerdata = array();
	public $dataLang = array();

    private $sort_options;

    public function __construct()
	{
		parent::__construct();
		
		$languageSession = sess_var('lang');
		get_language_file($languageSession);
		$this->dataLang['lang'] = langGet();

        // If the user is not logged in then redirect to the login page
        auth_check();

		//load form_validation library for default validation methods
		$this->load->library('form_validation');
		//load breadcrumb library
		$this->load->library('breadcrumb');
	
		//Load the default model for this controller
		$this->load->model('expertise_model');
		
		//Set Header Data for this page like title,bodyid etc
		$this->sess_uid	 = sess_var('uid');
		$this->headerdata['bodyid'] = 'expertise';
		$this->headerdata['bodyclass'] = 'no-breadcrumbs';
		$this->headerdata['title'] = build_title(lang('expertise'));

        $this->sort_options = array(
            1 => lang('SortAlphabetically'),
            2 => lang('SortMostRelevant'),
            3 => lang('SortRecentlyJoinedFirst'),
            4 => lang('HighestRatedFirst'),
	    5 => 'Random',

        );
	}

    public function ratings($member_id)
    {
        $member_id = (int) $member_id;

        $this->load->model('ratings_model');
        $ratings = $this->ratings_model->ratings($member_id);

        sendResponse($ratings);
        exit;
    }

    public function rate($member_id)
    {
        // Process only if it is POST
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return false;

        $member_id = (int) $member_id;
        $rated_by = (int) sess_var('uid');

        if ($member_id == $rated_by) {
            sendResponse(array(
                'status' => 'error',
                'error' => 'You can\'t rate yourself.'
            ));
            exit;
        }

        $ratings = $this->input->post('ratings', TRUE);

        // Calculate overall rating value for analytics
        // so that we don't make a db call
        $overall = count($ratings) > 0 ? round(array_sum($ratings) / count($ratings), 1) : 0.0;

        $this->load->model('ratings_model');
        $result = $this->ratings_model->create(compact('member_id', 'rated_by', 'ratings'));
        // TODO: check for error

        // Recalculate and return new averages
        $averages = $this->ratings_model->ratings($member_id);

        // Retrieve first and last names of rated member for Analytics
        $this->load->model('members_model');
        $expert = $this->members_model->find($member_id, 'firstname,lastname');
        // Analytics
        $page_analytics = array(
            'event' => array(
                'name' => 'Rating Submitted',
                'properties' => array(
                    'Rated Expert Id' => $member_id,
                    'Rated Expert Name' => $expert['firstname'] . ' ' . $expert['lastname'],
                    'Rating Overall' => $overall
                )
            )
        );

        sendResponse(array(
            'status' => 'success',
            'ratings' => $averages,
            'analytics' => $page_analytics
        ));
        exit;
    }

    /**
     * Makes a relationship between a member (expert) and the currently logged in user (member)
     * User (member) follows other member
     *
     * @return bool
     */
    public function follow() {
        $this->load->model('members_model');
        $model = $this->members_model;

        $follower_id = (int) sess_var('uid');
        $following_id = (int) $this->input->post('id', TRUE);

        if (! $result = $model->follow($following_id, $follower_id)) {
            sendResponse(array('status' => 'error'));
            exit;
        }

        $response = array('status' => 'success');

        if ($this->input->post('return_follows', TRUE) == '1') {
            $response = array_merge($result, array('follows' => $model->follows($following_id)));
        }

        // Following (Expert) name is not available to us at this point;
        // therefore we need to fetch it explicitly
        // TODO: Revisit and extract logic to reuse between follow and unfollow methods
        $following = $model->find($following_id, 'uid, email, membertype, firstname, lastname, userphoto, organization, title');
        $following['fullname'] = $following['membertype'] == MEMBER_TYPE_EXPERT_ADVERT ? $following['organization'] : $following['firstname'] . ' ' . $following['lastname'];

        $follower = $model->find($follower_id, 'uid, email, membertype, firstname, lastname, userphoto, organization, title');
        $follower['fullname'] = $follower['membertype'] == MEMBER_TYPE_EXPERT_ADVERT ? $follower['organization'] : $follower['firstname'] . ' ' . $follower['lastname'];

        // Notify the user that he is now being followed
        $this->notify_follow($following, $follower);

        // Analytics
        $page_analytics = array(
            'event' => array(
                'name' => 'Expert Followed',
                'properties' => array(
                    'Expert Id' => $following_id,
                    'Expert Name' => $following['fullname']
                )
            )
        );
        $response['analytics'] = $page_analytics;

        sendResponse($response);
        exit;
    }

    /**
     * Deletes a relationship between a member (expert) and the currently logged in user (member)
     * User (member) unfollows other member
     *
     * @return bool
     */
    public function unfollow() {
        $this->load->model('members_model');
        $model = $this->members_model;

        $userid = (int) sess_var('uid');
        $id = (int) $this->input->post('id', TRUE);

        if (! $result = $model->unfollow($id, $userid)) {
            sendResponse(array('status' => 'error'));
            exit;
        }

        $response = array('status' => 'success');

        if ($this->input->post('return_follows', TRUE) == '1') {
            $response['follows'] = $model->follows($id);
        }

        // Analytics
        // Expert name is not available to us at this point;
        // therefore we need to fetch it explicitly
        // TODO: Revisit and extract logic to reuse between follow and unfollow methods
        $expert = $model->find($id, 'membertype, firstname, lastname, organization');
        $expert_name = $expert['membertype'] == MEMBER_TYPE_EXPERT_ADVERT ? $expert['organization'] : $expert['firstname'] . ' ' . $expert['lastname'];
        $page_analytics = array(
            'event' => array(
                'name' => 'Expert Unfollowed',
                'properties' => array(
                    'Expert Id' => $id,
                    'Expert Name' => $expert_name
                )
            )
        );
        $response['analytics'] = $page_analytics;

        sendResponse($response);
        exit;
    }

    /**
     * Show a page displaying a specific expert
     *
     * @param $userid
     */
    public function view($userid)
	{
        $userid = (int) $userid;

		$users = $this->expertise_model->get_user($userid);
		if (empty($users)) show_404();
        unset($users['password']);

        if (! in_array((int) $users['membertype'], array(MEMBER_TYPE_MEMBER, MEMBER_TYPE_EXPERT_ADVERT))) show_404();

		$expertise = $this->expertise_model->get_expertise($userid);
		$myexpertise = $this->expertise_model->get_expertise_sector_subsector($userid);
		$education = $this->expertise_model->get_education($userid);

        // TODO: Revisit the logic
		if ($users['membertype'] == MEMBER_TYPE_EXPERT_ADVERT) {
            $page_category = 'Lightning';
            $fullname = $users['organization'];
			$project = $this->expertise_model->get_organization_projects($userid);
            $breadcrumb_title = lang('B_EXPERT_ADVERTS');
            $uri_segment = 'companies';	
	    $view = 'expertise/organization_view';

		} else {
            $page_category = 'Expert';
            $fullname = $users['firstname'] . ' ' . $users['lastname'];
            $project = $this->expertise_model->get_projects($userid);
            $breadcrumb_title = lang('B_EXPERTISE');
            $uri_segment = 'expertise';
            $view = 'expertise/expertise_view';

		}

		$isexpert		= 	check_is_topexpert($userid);
		$seats 			= 	$this->expertise_model->get_seats($userid);
		$case_studies	= 	$this->expertise_model->get_case_studies($userid, '', '1');
		$org_info		= 	$this->expertise_model->get_org_info($userid);

		$data =	compact(
			'users',
			'expertise',
			'education',
			'project',
			'isexpert',
			'seats',
			'case_studies',
			'org_info',
			'myexpertise'
		);

        // Expert specific data
        if ($users['membertype'] == MEMBER_TYPE_MEMBER) {

            $this->load->model('members_model');
            $data['isfollowing'] = $this->members_model->isfollowing($userid, $this->sess_uid);

            // Fetch ratings for the expert
            $this->load->model('ratings_model');
            $data['ratings'] = $this->ratings_model->ratings($userid);
            $data['rated_by_me'] = $this->ratings_model->exists($userid, $this->sess_uid);
        }

		$this->breadcrumb->append_crumb($breadcrumb_title, "/$uri_segment");
        $this->breadcrumb->append_crumb($fullname, $users['uid']);
		$this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        $this->headerdata['title'] = build_title($fullname);

        // Provide page analitics data for Segment Analitics
        $this->headerdata['page_analytics'] = array(
            'category' => $page_category,
            'properties' => array(
                'Target Id' => $userid,
                'Target Name' => $fullname
            )
        );

        // Render the page
		$this->load->view('templates/header', $this->headerdata);
		$this->load->view($view, $data);
		$this->load->view('templates/footer', $this->dataLang);
	}

	public function index()
	{
        $limit = view_check_limit($this->input->get_post('limit', TRUE));
        $offset	= $this->input->get_post('per_page', TRUE);
        if (empty($offset)) {
            $offset = 0;
        }
        $sort = $this->check_sort($this->input->get_post('sort', TRUE));

        $filter = array(
            'country' => $this->input->get_post('country', TRUE),
            'sector' => $this->input->get_post('sector', TRUE),
            'subsector' => $this->input->get_post('subsector', TRUE),
            'discipline' => $this->input->get_post('discipline', TRUE),
            'searchtext' => $this->input->get_post('searchtext', TRUE),
        );
        array_walk($filter, function(&$value, $key) {
            $value = $value ? : '';
        });

        $users = $this->expertise_model->get_filter_user_list2($limit, $offset, $filter, MEMBER_TYPE_MEMBER, $sort);
		$total = $users['filter_total'];

        $sector_data = sector_subsectors();
        $subsectors = array();
        if (! empty($subsector)) {
            if (isset($sector_data[$subsector])) {
                $subsectors = $sector_data[$subsector];
            }
        }

        $config = array(
            'base_url' => '/expertise/?' . http_build_query(array_merge($filter, compact('sort', 'limit'))),
            'total_rows' => $total,
            'per_page' => $limit,
            'num_links' => 1,
            'next_link' => lang('Next') . '  ' . '&gt;',
            'prev_link' => '&lt;' . '  ' . lang('Prev'),
            'first_link' => lang('First'),
            'last_link' =>  lang('Last'),
            'page_query_string' => TRUE
        );
        $this->load->library('pagination');
        $this->pagination->initialize($config);

		$data = array(
			'users'        => $users['filter'],
            'sectors'      => array_keys($sector_data),
            'subsectors'   => $subsectors,
            'all_subsectors'   => $sector_data,
			'filter_total' => $total,
            'filter'	   => $filter,
            'sort_options' => $this->sort_options,
            'sort'         => $sort,
            'limit'        => $limit,
			'paging'       => $this->pagination->create_links(),
			'page_from'    => $offset + 1,
			'page_to'      => ($offset + $limit <= $total) ? $offset + $limit : $total
		);

        $this->breadcrumb->append_crumb(lang('B_EXPERTISE'), '/expertise');
		$this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        // Analytics
        // Check if we have any serach filters setup
        if (count(array_filter($filter)) > 0) {
            $event_properties = array(
                'Category' => 'Expert',
                'Found' => $total
            );
            foreach ($filter as $key => $value) {
                if (! empty($value)) {
                    $event_properties[ucfirst($key)] = $value;
                }
            }

            $this->headerdata['page_analytics'] = array(
                'event' => array(
                    'name' => 'Searched',
                    'properties' => $event_properties
                )
            );
        }

        // Render the page
		$this->load->view('templates/header', $this->headerdata);
		$this->load->view('expertise/index', $data);
		$this->load->view('templates/footer', $this->dataLang);
	}
	
	public function send_message()
	{
		$this->expertise_model->send_model_mail();
	}

    private function check_sort($value)
    {
        $allowed = array_keys($this->sort_options);
        $default = 5;

        if (in_array($value, $allowed)) {
            return $value;
        } else {
            return $default;
        }
    }

    private function notify_follow($following, $follower)
    {
        $follower['photo_src'] = "plink/101/{$follower['uid']}";

        $view_data = array(
            'view' => 'follow_notification', // name of a view for the content section
            'content' => compact('following', 'follower')
        );

        // Render the email from the template
        $content = $this->load->view('email/layout', $view_data, TRUE);

        $subject = 'You have a new follower';

        return email(array($following['email'], $following['fullname']), $subject, $content, array(ADMIN_EMAIL, ADMIN_EMAIL_NAME));
    }
}
