<?php
class Expertadvert_model extends CI_Model {

    public $search_expert_advert_query;
    public $_where = false;


    /**
     * Returns the id of the randomly picked Ligtning (ExpertAdvert) company
     * @return int|null
     */
    public function get_random() {
        $result = $this->db
            ->select('uid')
            ->from('exp_members')
            ->where('membertype', MEMBER_TYPE_EXPERT_ADVERT)
            ->where('status', STATUS_ACTIVE)
            ->order_by('uid', 'random')
            ->limit(1)
            ->get()
            ->row_array();

        return empty($result) ? null : $result['uid'];
    }

    /*
     * Get Account Details of logged in user
     *
     * @access public
     * @param int
     * @return array
     */
    public function get_user($userid){
        $this->db->where("uid ='".$userid."' AND status ='1'");
        $query_user = $this->db->get('exp_members');
        if ($query_user->num_rows() > 0)
        {
            foreach($query_user->result_array() as $row)
            {

                if($row['membertype'] == '8')
                {
                    $imgurl = $row["userphoto"]!=""?$row["userphoto"]:"placeholder_organization.png";
                    $imgpath = $row["userphoto"]!=""?USER_IMAGE_PATH:USER_NO_IMAGE_PATH;
                    $row["userphoto"]	  = $imgurl;
                    $row["userphotoPath"] = $imgpath;

                    $result_user	=	$row;
                }
            }

            return $result_user;
        }
    }

    /*
     * Get the total number of users in this group (expert adverts)
     *
     * @access public
     * @param none
     * @return int
     */
    public function get_user_total(){
        $this->db_where('exp_members.status','1');
        $this->db_where('exp_members.membertype','8');

        return $this->db->count_all_results('exp_members');
    }


    /*
     * Get users that belong to the Expert Adverts Group (Groupd Id = 8)
     *
     * @access public
     * @param int
     * @param int
     * @return array
     */
    public function get_users($perpage=FALSE,$limit=0){
        $this->db->where('status','1');
        $this->db->where('membertype','8');

        if($perpage !== FALSE){
            $query_user_list = $this->db->get('exp_members',$perpage,$limit);
        }
        else
        {
           $query_user_list = $this->db->get('exp_members');
        }

        $results_user_list = array();
        if($query_user_list->num_rows() > 0){
            foreach($query_user_list->result_array() as $row){
                $imgurl  = $row["userphoto"]!=""?$row["userphoto"]:"profile_image_placeholder.png";
                $imgpath = $row["userphoto"]!=""?USER_IMAGE_PATH:USER_NO_IMAGE_PATH;

                $row["userphoto"]	  = $imgurl;
                $row["userphotoPath"] = $imgpath;

                $results_user_list[]	=	$row;
            }
        }

        return $results_user_list;
    }


    /**
     * Get paginated list of adverts with filters applied
     * This method should be used instead of get_filtered_user_list()
     * @param $limit
     * @param int $offset
     * @param array $filter
     * @param null $sort
     * @return mixed
     */
    public function get_filter_user_list2($limit, $offset = 0, $filter = array(), $sort = null) {
        // We delegate to expertise_model, so we load this model first
        $this->load->model('expertise_model');
        return $this->expertise_model->get_filter_user_list2($limit, $offset, $filter, MEMBER_TYPE_EXPERT_ADVERT, $sort);
    }

    /**
     * !!! DEPRECATED !!!
     * Method get_filtered_user_list2() should be used instead
     *
     * Get a paginated and filtered user list  from the  Expert Adverts Group (Groupd Id = 8)
     *
     * @access public
     * @param int
     * @param int
     * @param string
     * @param string
     * @param string
     * @param string
     * @return array
     */
    public function get_filtered_user_list($perpage,$limit=0,$country='',$sector='',$discipline='',$searchtext=''){
        $searchtext_array 		= explode(' ',strtolower($searchtext));
        $searchtext_array_cnt 	= count($searchtext_array);

        $filterby = array();
        $this->db->where('exp_members.status', '1');
        $this->db->where('exp_members.membertype', '8');

        if($country != '')	$this->db->where('country', $country);
        if($sector != '')  $this->db->where('sector', $sector);
        if($discipline != '') $this->db->where('discipline', $discipline);
        if(trim($searchtext) != ''){
            foreach($searchtext_array as $k=>$v)
            {
                if(isset($v)&& $v!='')
                {
                    $where_likec = "(LOWER(organization) LIKE '%".trim($v)."%')";
                    $this->db->where($where_likec);

                }
            }
        }

        //@todo remove the old (commented out) query
        //$query_filter_usertotal = $this->db->get('exp_members');
        $this->db->from("exp_members");
        $query_filter_usertotal = $this->db->count_all_results();

        if($country != '')
        {
            $this->db->where('country', $country);
            $filterby['country'] = $country;
        }
        if($discipline != '')
        {
            $this->db->where('discipline', $discipline);
            $filterby['discipline'] = $discipline;
        }
        if(trim($searchtext) != ''){
            foreach($searchtext_array as $k=>$v)
            {
                if(isset($v)&& $v!='')
                {
                    $where_like = "(LOWER(organization) LIKE '%".trim($v)."%')";
                    $this->db->where($where_like);
                }
            }
            $filterby['searchtext'] = $searchtext;
        }
        if($sector != '')
        {

            $this->db->join('exp_expertise_sector','exp_expertise_sector.uid = exp_members.uid', 'inner');

            $this->db->where_in('exp_expertise_sector.sector', $sector);
            //$this->db->group_by("exp_members.uid");
            $filterby['sector'] = $sector;
        }

        $this->db->distinct();
        $this->db->select('exp_members.*');
        $this->db->where('exp_members.status', '1');
        $this->db->where('exp_members.membertype', '8');
        $this->db->order_by('organization', 'asc');
        $query_userlist = $this->db->get('exp_members',$perpage,$limit);

        //load the expertise model, since we are re-using some of those components.
        $this->load->model("expertise_model",'expertiseModel');

        if ($query_userlist->num_rows() > 0)
        {
            $mysector = array();
            foreach($query_userlist->result_array() as $row)
            {
                $imgurl  = $row["userphoto"]!=""?$row["userphoto"]:"profile_image_placeholder.png";
                $imgpath = $row["userphoto"]!=""?USER_IMAGE_PATH:USER_NO_IMAGE_PATH;
                $mysector = $this->expertiseModel->get_expertise_mysector($row["uid"]);

                $row["userphoto"]	  = $imgurl;
                $row["userphotoPath"] = $imgpath;
                $row["expert_sector"]		  = $mysector;
                $result_userlist["filter"][]	=	$row;
            }
            if($sector != "") {
                $result_userlist["filter_total"] = $query_userlist->num_rows();
            } else {
                $result_userlist["filter_total"] = $query_filter_usertotal;//$query_filter_usertotal->num_rows();
            }
            $result_userlist["filter_by"]	= $filterby;

            return $result_userlist;
        }
        else
        {
            $result_userlist["filter_total"] = 0;
            $result_userlist["filter"] 	 = array();
            $result_userlist["filter_by"]= $filterby;

            return $result_userlist;
        }
    }

    /**
     * Search Companies
     * MyVip Map Project Search
     *
     * @access	public
     * @param   array
     * @param	int
     * @param   int
     * @param   boolean
     * @return	array
     */
    public function search_companies($filters=array(),$page=1,$limit=10)
    {
        $offset = ($page - 1) * $limit;

        $default_filters = array(
            'lat'		                => array('IS NOT NULL',NULL),
            'lng'		                => array('IS NOT NULL',NULL),
            'exp_members.status'	    => '1',
            'exp_members.membertype'    => '8',
        );

        // merge defaults and passed through
        $filters = array_merge($default_filters,$filters);

        $sector = isset($filters['sector']) ? $filters['sector'] : false;
        unset($filters['sector']);

        foreach( $filters as $col => $value )
        {
            if( is_array($value) )
            {
                $this->db->where("$col $value[0]", $value[1]);
            }
            else
            {
                $this->db->where($col, $value);
            }
        }

        if( $sector )
        {
            $this->db->distinct();
            $this->db->join('exp_expertise_sector','exp_expertise_sector.uid = exp_members.uid');
            $this->db->where('exp_expertise_sector.sector',$sector);
        }


        if( $this->_where )
        {
            $this->db->where($this->_where);
        }

        $qry = $this->db->select('exp_members.*')->get('exp_members');
        $this->search_expert_advert_query = $this->db->last_query();

        if( ! $qry->num_rows() > 0 ) return false;

        $data = $qry->result_array();

        return $data;

    }
}
?>