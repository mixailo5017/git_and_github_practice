<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expertadvert extends CI_Controller {

	//public class variables
	public $headerdata 	= array();
	public $uid			= '';
	public $pid			= '';
	public $dataLang 	= array();

    private $sort_options;
	
	public function __construct()
	{
		parent::__construct();
		
		$languageSession = sess_var('lang');
		get_language_file($languageSession);
		$this->dataLang['lang'] = langGet();

        // If the user is not logged in then redirect to the login page
        auth_check();

		//Load Profile Model for this controller
		$this->load->model('expertadvert_model');
		
		//load breadcrumb library
		$this->load->library('breadcrumb');
		
		//Set Header Data for this page like title,bodyid etc
		$this->headerdata['bodyid'] = 'myvip';
		$this->headerdata['bodyclass'] = '';
		$this->headerdata['title'] = build_title(lang('Lightning'));

		$this->uid	= sess_var('uid');

        $this->sort_options = array(
            1 => lang('SortAlphabetically'),
        );
    }
 	
    public function index()
    {
        $limit = view_check_limit($this->input->get_post('limit', TRUE));
        $offset		= $this->input->get_post('per_page', TRUE);
        if (empty($offset)) {
            $offset = 0;
        }
        $sort = $this->check_sort($this->input->get_post('sort', TRUE));
        //$sort = 1;

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

        $users = $this->expertadvert_model->get_filter_user_list2($limit, $offset, $filter, MEMBER_TYPE_EXPERT_ADVERT, $sort);
        $total = $users['filter_total'];

        $sector_data = sector_subsectors();
        $subsectors = array();
        if (! empty($subsector)) {
            if (isset($sector_data[$subsector])) {
                $subsectors = $sector_data[$subsector];
            }
        }

        $config = array(
            'base_url' => '/companies/?' . http_build_query(array_merge($filter, compact('sort', 'limit'))),
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

        $this->breadcrumb->append_crumb(lang('B_EXPERT_ADVERTS'), '/companies');
        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();

        // Render HTML Page from view direcotry
        $this->load->view('templates/header', $this->headerdata);
        $this->load->view('expertadvert/index', $data);
        $this->load->view('templates/footer', $this->dataLang);
    }

// 	public function index()
// 	{
// 		$viewdata = array();
//
// 		$viewdata['uid'] = $this->uid;
//
// 		$this->load->view('templates/header', $this->headerdata);
//		$this->load->view('expertadvert/expertadvert_view', $viewdata);
//		$this->load->view('templates/footer', $this->dataLang);
//	}
	
	public function view_casestudy()
	{
		$viewdata = array();
 		
 		$viewdata['uid'] = $this->uid;
 		
 		$this->load->view('templates/header', $this->headerdata);
		$this->load->view('expertadvert/view_casestudy', $viewdata);
		$this->load->view('templates/footer', $this->dataLang);
	}
	
	public function edit_casestudy()
	{
		$viewdata = array();
 		
 		$viewdata['uid'] = $this->uid;
 		
 		$this->load->view('templates/header', $this->headerdata);
		$this->load->view('expertadvert/edit_casestudy', $viewdata);
		$this->load->view('templates/footer', $this->dataLang);
	}
	
	public function view_profile()
	{
		$viewdata = array();
 		
 		$viewdata['uid'] = $this->uid;
 		
 		$this->load->view('templates/header', $this->headerdata);
		$this->load->view('expertadvert/view_profile', $viewdata);
		$this->load->view('templates/footer', $this->dataLang);
	}
	
	public function edit_seats()
	{
		$viewdata = array();
 		
 		$viewdata['uid'] = $this->uid;
 		
 		$this->load->view('templates/header', $this->headerdata);
		$this->load->view('expertadvert/edit_seats', $viewdata);
		$this->load->view('templates/footer', $this->dataLang);
	}

//    public function listing(){
//        $this->load->library('pagination');
//
//        $perpage		=	12;
//
//        $country	= $this->input->get_post('member_country', TRUE);
//        $sector		= $this->input->get_post('member_sectors', TRUE);
//        $discipline	= $this->input->get_post('member_discipline', TRUE);
//        $page		= $this->input->get_post('per_page', TRUE);
//        $searchtext = $this->input->get_post('search_text', TRUE);
//
//        $filterby = array_filter(compact('country', 'sector', 'discipline', 'searchtext'));
//
//        $filter_users =	$this->expertadvert_model->get_filtered_user_list2($perpage, $page, $country, $sector, $discipline, $searchtext);
//        $filter_total = $filter_users['filter_total'];
//
//        $config['base_url'] = '/expertadvert/list/'.
//            '?member_country='    . urlencode($country) .
//            '&member_sector='     . urlencode($sector) .
//            '&member_discipline=' . urlencode($discipline) .
//            '&search_text='       . urlencode($searchtext);
//        $config['total_rows'] 	= $filter_total;
//        $config['per_page'] 	= $perpage;
//        $config['next_link'] 	= lang('Next') . ' ' . '&gt;';
//        $config['prev_link'] 	= '&lt;' . ' ' . lang('Prev');
//        $config['first_link']	= FALSE;
//        $config['last_link'] 	= FALSE;
//        $config['page_query_string'] = TRUE;
//
//        $this->pagination->initialize($config);
//
//        $pages = $page != ''?$page:0;
//        $page_from	= ($filter_total < 1)?0:($pages + 1);
//        $page_to 	= (($pages + $perpage) <= $filter_total)?($pages + $perpage):$filter_total;
//
//        $data = array(
//            'main_content'		=>	'users',
//            'users'				=>	$filter_users['filter'],
//            'filter_total'		=>	$filter_total,
//            'filter_by'		 	=>	$filterby, //$filter_users['filter_by'],
//            'expertadvertpagging'	=>  $this->pagination->create_links(),
//            'page_from'			=>  $page_from,
//            'page_to'			=>  $page_to
//        );
//
//        $this->breadcrumb->append_crumb(lang('B_EXPERT_ADVERTS'), "/companies");
//        $this->headerdata['breadcrumb'] = $this->breadcrumb->output();
//
//        // Render HTML Page from view direcotry
//        $this->load->view('templates/header', $this->headerdata);
//        $this->load->view('expertadvert/listview', $data);
//        $this->load->view('templates/footer', $this->dataLang);
//    }
    private function check_sort($value) {
        $allowed = array_keys($this->sort_options);
        $default = 1;

        if (in_array($value, $allowed)) {
            return $value;
        } else {
            return $default;
        }
    }
}
