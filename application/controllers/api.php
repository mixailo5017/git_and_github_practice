<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {

	//public class variables
	public $headerdata 	= array();
	public $uid			= "";
	public $pid			= "";
	public $dataLang 	= array();

	private $init = false;
	private $forum = false;

    /**
	* Constructor
	* Called when the object is created
	*
	* @access public
	*/
	public function __construct()
	{
		parent::__construct();

		$languageSession = sess_var("lang");
		get_language_file($languageSession);
		$this->dataLang['lang'] = langGet();

        // If the user is not logged in then redirect to the login page
        auth_check();

		//Load Profile Model for this controller
		//$this->load->helper('img_helper'); // autoload
		$this->load->model('projects_model');
		$this->load->model('expertise_model');
        $this->load->model('forums_model');
        $this->load->model('expertadvert_model');

		//load form_validation library for default validation methods
		$this->load->library('form_validation');
		//load breadcrumb library
		$this->load->library('breadcrumb');

		//Set Header Data for this page like title,bodyid etc
        // TODO: What page??? It's an API pvm
//		$this->headerdata['bodyid'] = 'projects';
//		$this->headerdata['bodyclass'] = '';
//		$this->headerdata['title'] = 'GViP';
		$this->uid = sess_var('uid');
		$this->output->enable_profiler(FALSE);

		// set up some cache for map
		if (! sess_var('map')) {
			$this->session->set_userdata(array('map' => array()));
		}

        // alias the session data with cache variable.
		$this->cache =& $this->session->userdata['map'];

		$this->init = (bool) $this->input->post('init', false);
	}


    /**
     * Map Search
     *    api used to return searchs to map for projects and experts makers/info
     *
     * @param string $type
     */
	public function map_search($type = 'both')
	{
		$default = json_decode('{
		    "north": "42.261049162113856",
		    "east": "-72.861328125",
		    "south": "36.27085020723905",
		    "west": "-80.43090820312499"
		}', true);

		$bounds	= $this->input->post('bounds',$default);

		$filters = array(
			"lat < {$bounds['north']}"	=> null,
			"lat > {$bounds['south']}"	=> null,
			"lng > {$bounds['west']}"	=> null,
			"lng < {$bounds['east']}"	=> null
			);

		$projects   = array();
		$experts    = array();
        $companies  = array();
        $myprojects = array();

		$limit = 999;

		// basic search filters
		$this->cache['zoom']	= $this->input->post('zoom',false);
		$this->cache['lat']		= $this->input->post('lat', false);
		$this->cache['lng']		= $this->input->post('lng', false);
		$this->cache['type']	= $this->input->post('type',false);

		// forum only search
		$this->forum = (bool) ($this->input->get_post('forum') === 'true' ? true : $this->forum);

        if ($type == 'both' || $type == 'projects') {
			$projects = $this->search_projects($filters, $limit);
		}

		if ($type == 'both' || $type == 'experts') {
			$experts = $this->search_experts($filters, $limit);
		}

        if ($type == 'both' || $type == 'companies') {
            $companies = $this->search_companies($filters, $limit);
        }

        if ($type == 'both' || $type == 'myprojects') {
            $myprojects = $this->search_projects($filters, $limit, true);
        }

        $return_data = compact('projects', 'experts', 'companies', 'myprojects');

		$this->session->sess_write();

//		die(json_encode($return_data));
        sendResponse($return_data);
        exit;
	}

	private function search_experts($filters, $limit = 10)
	{
		$experts = array();

		$expertise_filters = $filters;
		$expertise_options = array(
			'sector'			=> $this->input->get_post('sector'),
			'discipline'		=> $this->input->get_post('expert_discipline'),
			'country'			=> $this->input->get_post('country'),
			// 'cost'				=> $this->input->get_post('cost'),
		);

		foreach ($expertise_options as $k => $v) {
			if ($v != false) {
				$expertise_filters[$k] = $v;
			}
		}

		$revenue = $this->input->get_post('revenue', -1);
		if ($revenue) {
			switch ($revenue) {
				case '0':
					$expertise_filters["annualrevenue < 2.5"]	= null;
					break;
				case 2.5:
					$expertise_filters["annualrevenue >= 2.5 AND annualrevenue < 5"]		= null;
					break;
				case 5:
					$expertise_filters["annualrevenue >= 5 AND annualrevenue < 15"]	= null;
					break;
				case 15:
					$expertise_filters["annualrevenue >= 15 AND annualrevenue < 50"]	= null;
					break;
				case 50:
					$expertise_filters["annualrevenue >= 50 AND annualrevenue < 200"]	= null;
					break;
				case 200:
					$expertise_filters["annualrevenue >= 200"]	= null;
					break;
				default:
					break;
			}
		}


		if ($this->init && isset($this->cache['expertise_filters'])) {
			$expertise_filters = $this->cache['expertise_filters'];
		} else {
			$this->cache['filters'] = array_merge($expertise_filters,array('revenue'=>$revenue));
		}

		// check for forum only  
		if( $this->forum === true ) {
            $forum_id = $this->input->get_post('forum_id', 0);
            $member_ids = implode(',', flatten_assoc($this->forums_model->members($forum_id, 'uid'), null, 'uid'));
            $member_ids = ($member_ids != '') ? $member_ids : '-1';

            //$getExp = $this->forum_model->get_forum_experts();
			//$this->db->where_in("uid",explode(",",$getExp['experts']));
			//$this->expertise_model->_where = "uid in ({$getExp['experts']})";
            $this->expertise_model->_where = "uid in ($member_ids)";
		}

        $excludeCompanies = TRUE;
		$experts_data	= $this->expertise_model->search_experts($expertise_filters, 1, $limit, $excludeCompanies);


		if ($experts_data) {
			foreach( $experts_data as $i => $row ) {
				$expert = array(
					'p_id'				=> $row['uid'],
					'p_lat'				=> $row['lat'],
					'p_lng'				=> $row['lng'],
					'p_name'			=> trim($row['firstname'] . ' ' . $row['lastname']),
					'p_title'			=> $row['title'],
					'p_discipline'		=> $row['discipline'],
					'p_total_employee'	=> $row['totalemployee'],
					//'p_stage'			=> $row['stage'],
					'p_type'			=> $row['status'],
					'p_location'		=> trim($row['city'] .' '. $row['state'] .' '. $row['country']),
					'p_budget'			=> format_budget($row['annualrevenue']),
					//'p_date_range'	 => trim($row['eststart'] .' '. $row['estcompletion']),
					'p_link'			=> '/expertise/'.$row['uid'],
					'p_image_small'		=> '',
					'p_image_big'		=> '',
					//'p_stage_class		 => 'ps_'.project_stage_class($row['stage']),
					'p_type_class'		=> '',
					'p_sector_class'	=> ''. project_sector_class($row['sector']),
					'p_sectors'			=> '{}',
					'p_organization'			=> $row['organization'],
					);

				if ($row['membertype'] == MEMBER_TYPE_EXPERT_ADVERT) {
					$expert['p_name'] = $row['organization'];
					$expert['p_title'] = '';
				}

				if (true || $row['userphoto'] != '') {
					$expert['p_image_small']	= expert_image($row["userphoto"],32 );
					$expert['p_image_circle']	= expert_image($row["userphoto"],32,array('bg_color'=>'','save_type'=>'png','rounded_corners' => array( 'all','16' )) );
					$expert['p_image_big']		= expert_image($row["userphoto"],85);
				}

				$exp = $this->expertise_model->get_expertise_sector_subsector($row['uid']);
				if ($exp) {
					$sectors = array();
					foreach ($exp as $k => $v) {
						$sectors[project_sector_class($v['sector'])] = $v['sector'];
					}
					// add them
					$expert['p_sectors'] = json_encode($sectors);
				}
				$experts[] = $expert;
			}
		}
		return $experts;
	}

	private function search_projects($filters, $limit = 10, $only_myprojects = false)
	{
		$projects = array();

		$project_filters = $filters;
		$project_options = array(
			'sector' => $this->input->get_post('sector'),
			'stage'	=> $this->input->get_post('project_stage'),
			'country' => $this->input->get_post('country'),
		);

		foreach ($project_options as $k => $v) {
			if ($v != false) {
				$project_filters[$k] = $v;
			}
		}

		$totalbudget = $this->input->get_post('budget',-1);

		if ($totalbudget !== false) {
			switch ($totalbudget) {
				case '0':
					$project_filters["totalbudget < 50"] = null;
					break;
				case 50:
					$project_filters["totalbudget >= 50 AND totalbudget < 500"] = null;
					break;
				case 500:
					$project_filters["totalbudget >= 500 AND totalbudget < 1000"]	= null;
					break;
				case 1000:
					$project_filters["totalbudget >= 1000"]	= null;
					break;
				default:
					break;
			}
		}


		if ($this->init && isset($this->cache['project_filters'])) {
			$project_filters = $this->cache['project_filters'];
		} else {
			$this->cache['filters'] = array_merge( $project_filters, array('budget'=>$totalbudget));
		}

		// check for forum only   isforum
		if ($this->forum === true) {
            $forum_id = $this->input->get_post('forum_id', 0);
            $project_ids = implode(',', flatten_assoc($this->forums_model->projects($forum_id, 'pid'), null, 'pid'));
            $project_ids = ($project_ids != '') ? $project_ids : '-1'; //dd($project_ids);

            $this->projects_model->_where = "pid in ($project_ids)";
		}

        if ($only_myprojects) {
            $project_ids = implode(',', flatten_assoc($this->projects_model->all_my_projects($this->uid), null, 'id'));
            $project_ids = ($project_ids != '') ? $project_ids : '-1'; //dd($project_ids);
            $this->projects_model->_where = "pid in ($project_ids)";
        }

		$projects_data	= $this->projects_model->search_projects($project_filters, 1, $limit);

		if ($projects_data) {
			foreach ($projects_data as $i => $row) {
				$project = array(
					'p_id'				=> $row['pid'],
					'p_lat'				=> $row['lat'],
					'p_lng'				=> $row['lng'],
					'p_title'			=> $row['projectname'],
					'p_slug'			=> $row['slug'],
					'p_stage'			=> lang($row['stage']) != '' ? lang($row['stage']) : $row['stage'],
					'p_sector'			=> $row['sector'],
					'p_subsector'		=> $row['subsector'] == 'Other' ? $row['subsector_other'] : $row['subsector'],
					'p_keywords'		=> $row['keywords'],
					'p_sponsor'			=> $row['sponsor'],
					'p_location'		=> trim($row['location'] .' '. $row['country']),
					'p_budget'			=> format_budget($row['totalbudget']),
					'p_date_start'		=> $row['eststart'] == '1111-11-11' ? '' :  $row['eststart'],
					'p_date_end'		=> $row['estcompletion'] == '1111-11-11' ? '' :  $row['estcompletion'],
					'p_link'			=> '/projects/'.$row['slug'],
					'p_image_small'		=> '',
					'p_image_big'		=> '',
					'p_stage_class'		=> 'ps_'.project_stage_class($row['stage']),
					'p_type_class'		=> '',
					'p_sector_class'	=> ''. project_sector_class($row['sector']),
					'p_developer'	    => ''. $row['developer'],
                    'p_image_small'     => project_image($row['projectphoto'], 50),
                    'p_image_big'       => project_image($row['projectphoto'], 85),
				);

//				if ($row['projectphoto'] != '') {
//					$project['p_image_small']	= project_image($row['projectphoto'],50);
//					$project['p_image_big']		= project_image($row['projectphoto'],85);
//				} else {
//					$project['p_image_small']	= project_image(PROJECT_NO_IMAGE_PATH,50);
//					$project['p_image_big']		= project_image(PROJECT_NO_IMAGE_PATH,85);
//				}

				$project = array_map('htmlspecialchars', $project);

				$projects[] = $project;
			}
		}

		return $projects;
	}

    /*
     * These are expert adverts (membertype = 8).
     * @param array
     * @param int
     * @return array
     */
    private function search_companies($filters,$limit=10)
    {
        $experts = array();

        $expertise_filters = $filters;
        $expertise_options = array(
            'sector'			=> $this->input->get_post('sector'),
            'discipline'		=> $this->input->get_post('expert_discipline'),
            'country'			=> $this->input->get_post('country'),
            // 'cost'				=> $this->input->get_post('cost'),
        );


        foreach( $expertise_options as $k => $v )
        {
            if( $v != false ){
                $expertise_filters[$k] = $v;
            }
        }

        $revenue = $this->input->get_post('revenue',-1);
        if ( $revenue )
        {
            switch($revenue)
            {
                case '0':
                    $expertise_filters["annualrevenue < 2.5"]	= null;
                    break;
                case 2.5:
                    $expertise_filters["annualrevenue >= 2.5 AND annualrevenue < 5"]		= null;
                    break;
                case 5:
                    $expertise_filters["annualrevenue >= 5 AND annualrevenue < 15"]	= null;
                    break;
                case 15:
                    $expertise_filters["annualrevenue >= 15 AND annualrevenue < 50"]	= null;
                    break;
                case 50:
                    $expertise_filters["annualrevenue >= 50 AND annualrevenue < 200"]	= null;
                    break;
                case 200:
                    $expertise_filters["annualrevenue >= 200"]	= null;
                    break;
                default:
                    break;
            }
        }


        if( $this->init && isset($this->cache['expertise_filters']) )
        {
            $expertise_filters = $this->cache['expertise_filters'];
        }
        else
        {
            $this->cache['filters'] = array_merge($expertise_filters,array('revenue'=>$revenue));
        }

        // check for forum only
        if( $this->forum === true )
        {
            $forum_id = $this->input->get_post('forum_id', 0);
            $member_ids = implode(',', flatten_assoc($this->forums_model->members($forum_id, 'uid'), null, 'uid'));
            $member_ids = ($member_ids != '') ? $member_ids : '-1';

            //$getExp = $this->forum_model->get_forum_experts();
            //$this->db->where_in("uid",explode(",",$getExp['experts']));
            //$this->expertise_model->_where = "uid in ({$getExp['experts']})";
            $this->expertise_model->_where = "uid in ($member_ids)";
        }

        $excludeCompanies = TRUE;
        $experts_data	= $this->expertadvert_model->search_companies($expertise_filters,1,$limit);


        if( $experts_data )
        {
            foreach( $experts_data as $i => $row )
            {
                $expert = array(
                    'p_id'				=> $row['uid'],
                    'p_lat'				=> $row['lat'],
                    'p_lng'				=> $row['lng'],
                    'p_name'			=> trim($row['firstname'] . ' ' . $row['lastname']),
                    'p_title'			=> $row['title'],
                    'p_discipline'		=> $row['discipline'],
                    'p_total_employee'	=> $row['totalemployee'],
                    //'p_stage'			=> $row['stage'],
                    'p_type'			=> $row['status'],
                    'p_location'		=> trim($row['city'] .' '. $row['state'] .' '. $row['country']),
                    'p_budget'			=> format_budget($row['annualrevenue']),
                    //'p_date_range'	 => trim($row['eststart'] .' '. $row['estcompletion']),
                    'p_link'			=> '/expertise/'.$row['uid'],
                    'p_image_small'		=> '',
                    'p_image_big'		=> '',
                    //'p_stage_class		 => 'ps_'.project_stage_class($row['stage']),
                    'p_type_class'		=> '',
                    'p_sector_class'	=> ''. project_sector_class($row['sector']),
                    'p_sectors'			=> '{}',
                    'p_organization'			=> $row['organization'],
                );

                if( $row['membertype'] == 8 )
                {
                    $expert['p_name'] = $row['organization'];
                    $expert['p_title'] = '';
                }

                if( true || $row['userphoto'] != '')
                {
                    $expert['p_image_small']	= expert_image($row["userphoto"],32 );
                    $expert['p_image_circle']	= expert_image($row["userphoto"],32,array('bg_color'=>'','save_type'=>'png','rounded_corners' => array( 'all','16' )) );
                    $expert['p_image_big']		= expert_image($row["userphoto"],85);
                }

                $exp = $this->expertise_model->get_expertise_sector_subsector($row['uid']);
                if( $exp )
                {
                    $sectors = array();
                    foreach( $exp as $k => $v )
                    {
                        $sectors[project_sector_class($v['sector'])] = $v['sector'];
                    }
                    // add them
                    $expert['p_sectors'] = json_encode($sectors);
                }

                $experts[] = $expert;

            }

        }
        return $experts;
    }

	public function concierge_question()
	{
		$msg = $this->input->get('message', TRUE);

		// if not blank
		if (! $msg) {
            $response = resp('error', 'Error');
            sendResponse($response);
            return;
        }

        $this->load->model('concierge_model');
        $this->concierge_model->message = $msg;
        $added = $this->concierge_model->save();

        if (! $added) {
            $error = $this->concierge_model->get_errors();
            $response = resp('error', $error);
            sendResponse($response);
            return;
        }

        $email	= $this->concierge_model->email;

        $from_id = sess_var('uid');
        $from_name	= $this->concierge_model->name;
        $from_photo = "plink/101/$from_id";

        $view_data = array_merge(compact('from_id', 'from_name', 'from_photo'), array('message' => nl2br($this->concierge_model->message)));

        // Render the email from the template
        $content  = $this->load->view('email/_header', null, TRUE);
        $content .= $this->load->view('email/_member', $view_data, TRUE);
        $content .= $this->load->view('email/_footer', null, TRUE);

//        if (! SendHTMLMail($sender['email'], $recipient['email'], $subject, $content, null, 'html')) {
        if (! email(CONCIERGE_EMAIL, CONCIERGE_EMAIL_TITLE, $content, array($email, $from_name))) {
            $response = resp('error', 'Error');
            sendResponse($response);
            return;
        }

//        $body 	= "A new question was just added by {$name} ({$email})\n\n\n";
//        $body 	.= "\n---------------------------\n\n";
//        $body 	.= $this->concierge_model->message;
//        $body 	.= "\n\n---------------------------";
//
//        $send = SendHTMLMail(null, CONCIERGE_EMAIL, CONCIERGE_EMAIL_TITLE, $body, $email);
//
//		die_json($response);

        $response = resp('success', 'Question has been added');
        sendResponse($response);
    }

	public function remove_map_session()
	{
		unset($this->session->userdata['map']);
		die('done');
	}

    public function geocode()
    {
        $address = $this->input->get_post('address', false);
        if (empty($address)) {
            die_json('Address string is empty');
            exit;
        }
        $this->load->library('mapquest');
        $response = $this->mapquest->geocode(urlencode($address));
        //$response = json_decode($response);
        die_json($response);
    }

	/**
	 * This is a get request that accepts a lat/lng and then
	 * returns the full JSON object that comes back from maqquest
	 * 
	 * @return object Full mapquest reverse geocode object
	 */
	public function reverse_geocode() {

		$this->load->library('mapquest');

		$response = $this->mapquest->reverse_geocode($this->input->get_post('lat', false). ',' . $this->input->get_post('lng', false))->json_raw;

		$response = json_decode($response);

		die_json($response);

	}

	public function batch_geocode()
	{

		$e = $this->geocode_experts();

		//if( !$e ) die('geocode_experts!');;

		$p = $this->geocode_projects();

		//if( !$p ) die('geocode_projects!');

		die('done!');
	}

	private function geocode_experts()
	{
		return $this->expertise_model->batch_geocode();

		die('$this->expertise_model->batch_geocode()');
	}

	private function geocode_projects()
	{
		return $this->projects_model->batch_geocode();

		die('$this->expertise_model->batch_geocode()');
	}
}
