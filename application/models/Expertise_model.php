<?php

class Expertise_model extends CI_Model {

	public $search_expertise_query;
	public $_where = false;

	/**
	 * Search Projects
	 * MyVip Map Project Search
	 *
	 * @access	public
     * @param   array
	 * @param	int
     * @param   int
     * @param   boolean
	 * @return	array
	 */
	public function search_experts($filters=array(),$page=1,$limit=10,$excludeCompanies = FALSE)
	{
		$offset = ($page - 1) * $limit;

		$default_filters = array(
			'lat'		=> array('IS NOT NULL', NULL),
			'lng'		=> array('IS NOT NULL', NULL),
			'exp_members.status' => STATUS_ACTIVE,
			);

		// merge defaults and passed through
		$filters = array_merge($default_filters, $filters);

		$sector = isset($filters['sector']) ? $filters['sector'] : false;
		unset($filters['sector']);

		foreach ($filters as $col => $value) {
			if (is_array($value)) {
				$this->db->where("$col $value[0]", $value[1]);
			} else {
				$this->db->where($col, $value);
			}
		}

		if ($sector) {
			$this->db->distinct();
			$this->db->join('exp_expertise_sector', 'exp_expertise_sector.uid = exp_members.uid');
			$this->db->where('exp_expertise_sector.sector', $sector);
		}

        if ($excludeCompanies === TRUE) {
            $this->db->where('exp_members.membertype !=','8');
        }

		if ($this->_where) {
			$this->db->where($this->_where);
		}

		$query = $this->db->select('exp_members.*')->get('exp_members',$limit,$offset);
		$this->search_expertise_query = $this->db->last_query();

		if (! $query->num_rows() > 0) return false;

		$rows = $query->result_array();

		return $rows;

	}

    /**
     * Returns PCI value and a flag whether a PCI meter has to be shown
     *
     * show flag = 1 when
     * noshow date is not defined or less then current date
     * and PCI value < 100
     *
     * @param $member_id
     * @return array
     */
    public function get_pci($member_id)
    {
        $row = $this->db
            ->select('pci')
            ->select('CASE WHEN pci < 100 AND (noshow IS NULL OR noshow < CURRENT_DATE) THEN 1 ELSE 0 END "show"', FALSE)
            ->from('exp_member_pci')
            ->where('member_id', $member_id)
            ->get()
            ->row_array();

        return $row;
    }

    /**
     * Dissmisses the Profile Completeness Index meter dialog
     *
     * VIP-160
     * # When a user clicks Dismiss, the prompt does not reappear for a certain number of days ("silence period")
     * # Number of days to wait is determined based on length of previous silence period, using an exponential scale (double the previous period)
     *   -- If previous period was one day, new period is two days; four > eight; etc.
     * # Max silence period is 32 days. So if previous period was 32 days, so is the new one.
     * # Once user has reached 75+ PCI, min silence period is 8 days.
     *
     * @param $member_id
     */
    public function dismiss_pci($member_id)
    {
        $sql = "
        WITH base AS
        (
          SELECT member_id, pci, COALESCE(noshow - dismissed, 1) silence
            FROM exp_member_pci
           WHERE member_id = ?
             AND dismissed IS NULL OR dismissed <> CURRENT_DATE
        )
        UPDATE exp_member_pci p
           SET noshow = CURRENT_DATE +
                        CASE WHEN b.silence < 16
                             THEN CASE WHEN b.pci >= 75 AND b.silence < 4
                                       THEN 4 ELSE b.silence END * 2
                             ELSE 32 END,
               dismissed = CURRENT_DATE
          FROM base b
         WHERE p.member_id = b.member_id";

        $this->db->query($sql, array($member_id));
    }

    public function myexperts($member_id, $limit, $offset = 0)
    {
        $rows = $this->db
            ->select('m.uid, firstname, lastname, userphoto, title, organization, discipline, country')
            ->select("STRING_AGG(DISTINCT s.sector, ',' ORDER BY s.sector) expert_sector", FALSE)
            ->select("COUNT(*) OVER() row_count", FALSE)
            ->from('exp_member_followers AS f')
            ->join('exp_members AS m', 'f.member_id = m.uid')
            ->join('exp_expertise_sector AS s', "m.uid = s.uid AND s.permission = 'All' AND s.status = " . $this->db->escape(STATUS_ACTIVE), 'left')
            ->where('m.status', STATUS_ACTIVE)
            ->where('membertype', MEMBER_TYPE_MEMBER)
            ->where('f.follower', $member_id)
            ->group_by('m.uid, firstname, lastname, userphoto, title, organization, discipline, country')
            ->order_by('firstname, lastname')
            ->limit($limit, $offset)
            ->get()
            ->result_array();

        return $rows;
    }

    /**
     * Retrieve a record by id
     *
     * @param $id
     * @param null $select
     * @return mixed
     */
    public function find($id, $select = null)
    {
        if (! is_null($select)) {
            $this->db->select($select);
        }

        $row = $this->db
            ->where('uid', (int) $id)
            ->where('status', STATUS_ACTIVE)
            ->get('exp_members')
            ->row_array();

        return $row;
    }

	/**
	 * Get Account Details of loged in user
	 *
 	 * @param int $userid
	 * @return array
	 */
	public function get_user($userid){

        $query_user = $this->db
            ->where('uid', $userid)
            ->where('status', STATUS_ACTIVE)
		    ->get('exp_members');

		if ($query_user->num_rows() > 0)
		{
			foreach($query_user->result_array() as $row)
			{

				if($row['membertype'] == '8')
				{
					$imgurl = $row["userphoto"]!=""?$row["userphoto"]:"placeholder_organization.png";
					$imgpath = $row["userphoto"]!=""?USER_IMAGE_PATH:USER_NO_IMAGE_PATH;

				}
				else
				{
					$imgurl = $row["userphoto"]!=""?$row["userphoto"]:"profile_image_placeholder.png";
					$imgpath = $row["userphoto"]!=""?USER_IMAGE_PATH:USER_NO_IMAGE_PATH;

				}
				$row["userphoto"]	  = $imgurl;
				$row["userphotoPath"] = $imgpath;

				$result_user	=	$row;
			}

			return $result_user;
		}
	}

	/**
	 * Get total number of users (Experts)
	 *
	 * @return int
	 */
	public function get_user_total() {
		return $this->db
            ->from('exp_members')
            ->where('status', STATUS_ACTIVE)
		    ->where('membertype', MEMBER_TYPE_MEMBER)
            ->count_all_results();
	}
    public function get_sme_experts_list($project_id, $exclude = array(), $limit, $offset = null) {
        $sql = "
        SELECT m.uid, firstname, lastname, title, organization, userphoto, country, discipline,
               STRING_AGG(DISTINCT s.sector, ',' ORDER BY s.sector) expert_sector,
               COUNT(*) OVER () row_count
          FROM exp_member_project_scores r JOIN exp_members m
            ON r.member_id = m.uid LEFT JOIN exp_expertise_sector s
            ON m.uid = s.uid AND s.permission = 'All' AND s.status = ?
         WHERE r.project_id = ?
           AND m.status = ?
           AND m.totalemployee = '1-50'
           AND m.annualrevenue < 15
           AND m.public_status <> 'open'";
        $bindings = array(STATUS_ACTIVE, $project_id, STATUS_ACTIVE);

        if (! empty($exclude)) {
            $sql .= ' AND r.member_id NOT IN(' . rtrim(str_repeat('?,', count($exclude)), ',') . ')';
            $bindings = array_merge($bindings, $exclude);
        }

        $sql .= " GROUP BY m.uid, firstname, lastname, title, organization, userphoto, country, discipline, score_sum";

        $sql .= " ORDER BY score_sum DESC LIMIT ?";
        array_push($bindings, $limit);

        if (! is_null($offset)) {
            $sql .= " OFFSET ?";
            array_push($bindings, $offset);
        }

        $result = $this->db
            ->query($sql, $bindings)
            ->result_array();

        return $result;
    }

    public function get_sme_experts($project_id, $exclude = array(), $limit = 5, $out_of = 15) {
        $sql = "
        SELECT *
          FROM
        (
            SELECT uid, firstname, lastname, title, organization, userphoto,
                   COUNT(*) OVER () row_count
              FROM exp_member_project_scores s JOIN exp_members m
                ON s.member_id = m.uid
             WHERE s.project_id = ?
               AND m.status = ?
               AND m.totalemployee = '1-50'
               AND m.annualrevenue < 15
               AND m.public_status <> 'open'";
        $bindings = array($project_id, STATUS_ACTIVE);

        if (! empty($exclude)) {
            $sql .= ' AND s.member_id NOT IN(' . rtrim(str_repeat('?,', count($exclude)), ',') . ')';
            $bindings = array_merge($bindings, $exclude);
        }

        $sql .= "
             ORDER BY s.score_sum DESC
             LIMIT ?
        ) q
          ORDER BY RANDOM()
          LIMIT ?";
        array_push($bindings, $out_of, $limit);

        $result = $this->db
            ->query($sql, $bindings)
            ->result_array();

        return $result;
    }

    public function get_global_experts_list($project_id, $exclude = array(), $limit, $offset = null) {
        $sql = "
        SELECT m.uid, firstname, lastname, title, organization, userphoto, country, discipline,
               STRING_AGG(DISTINCT s.sector, ',' ORDER BY s.sector) expert_sector,
               COUNT(*) OVER () row_count
          FROM exp_member_project_scores r JOIN exp_members m
            ON r.member_id = m.uid LEFT JOIN exp_expertise_sector s
            ON m.uid = s.uid AND s.permission = 'All' AND s.status = ?
         WHERE r.project_id = ?
           AND m.status = ?
           AND (m.totalemployee <> '1-50' OR
                m.annualrevenue >= 15)";
        $bindings = array(STATUS_ACTIVE, $project_id, STATUS_ACTIVE);

        if (! empty($exclude)) {
            $sql .= ' AND r.member_id NOT IN(' . rtrim(str_repeat('?,', count($exclude)), ',') . ')';
            $bindings = array_merge($bindings, $exclude);
        }

        $sql .= " GROUP BY m.uid, firstname, lastname, title, organization, userphoto, country, discipline, score_sum";

        $sql .= " ORDER BY score_sum DESC LIMIT ?";
        array_push($bindings, $limit);

        if (! is_null($offset)) {
            $sql .= " OFFSET ?";
            array_push($bindings, $offset);
        }

        $result = $this->db
            ->query($sql, $bindings)
            ->result_array();

        return $result;
    }

    public function get_global_experts($project_id, $exclude = array(), $limit = 5, $out_of = 15) {
        $sql = "
        SELECT *
          FROM
        (
            SELECT uid, firstname, lastname, title, organization, userphoto,
                   COUNT(*) OVER () row_count
              FROM exp_member_project_scores s JOIN exp_members m
                ON s.member_id = m.uid
             WHERE s.project_id = ?
               AND m.status = ?
               AND (m.totalemployee <> '1-50' OR
                    m.annualrevenue >= 15)";
        $bindings = array($project_id, STATUS_ACTIVE);

        if (! empty($exclude)) {
            $sql .= ' AND s.member_id NOT IN(' . rtrim(str_repeat('?,', count($exclude)), ',') . ')';
            $bindings = array_merge($bindings, $exclude);
        }

        $sql .= "
             ORDER BY s.score_sum DESC
             LIMIT ?
        ) q
          ORDER BY RANDOM()
          LIMIT ?";
        array_push($bindings, $out_of, $limit);

        $result = $this->db
            ->query($sql, $bindings)
            ->result_array();

        return $result;
    }

    public function get_key_executives($projects, $exclude = array(), $limit = 3) {
        $top_per_project = 10;
        $distinct_overall = 20;

        $sql = "
        SELECT uid, firstname, lastname, title, organization, userphoto
        FROM
        (
            SELECT DISTINCT member_id, rn
              FROM
            (
                SELECT project_id, member_id,
                       ROW_NUMBER() OVER (PARTITION BY project_id ORDER BY score_sum DESC) rn
                  FROM exp_member_project_scores
                 WHERE project_id IN(";
        $sql .= rtrim(str_repeat('?,', count($projects)), ',') . ')';
        $bindings = $projects;

        if (! empty($exclude)) {
            $sql .= ' AND member_id NOT IN(' . rtrim(str_repeat('?,', count($exclude)), ',') . ')';
            $bindings = array_merge($bindings, $exclude);
        }

        $sql .= ") q
             WHERE rn <= ?
             ORDER BY rn
             LIMIT ?
        ) o JOIN exp_members m
            ON o.member_id = m.uid
         WHERE m.status = ?
         ORDER BY RANDOM()
         LIMIT ?";
        array_push($bindings, $top_per_project, $distinct_overall, STATUS_ACTIVE, $limit);

        $result = $this->db
            ->query($sql, $bindings)
            ->result_array();

        return $result;
    }

    /**
     * Returns an array of $limit member experts chosen at random from the $out_of
     * most recently joined experts who have profile photos.
     *
     * @param array $exclude An array of memeber ids to exclude from the result set
     * @param int $limit
     * @param int $out_of
     * @return array
     */
    public function get_new_experts($exclude = array(), $limit = 3, $out_of = 50) {
        $sql = "
        SELECT *
          FROM
        (
            SELECT uid, firstname, lastname, title, organization, userphoto
              FROM exp_members
             WHERE membertype = ?
               AND status = ?
               AND userphoto IS NOT NULL
               AND userphoto <> ''";
        $bindings = array(MEMBER_TYPE_MEMBER, STATUS_ACTIVE);

        if (! empty($exclude)) {
            $sql .= ' AND uid NOT IN(' . rtrim(str_repeat('?,', count($exclude)), ',') . ')';
            $bindings = array_merge($bindings, $exclude);
        }

        $sql .= " ORDER BY registerdate DESC
            LIMIT ?
        ) q
         ORDER BY RANDOM()
         LIMIT ?";
        array_push($bindings, $out_of, $limit);

        $result = $this->db
            ->query($sql, $bindings)
            ->result_array();

        return $result;
    }

    /**
     * Get paginated list of users with filters applied
     *
     * @param int $limit How many records to return starting from offset
     * @param int $offset How many records to skip
     * @param array $filter country|discipline|sector|searchtext
     * @param int $member_type
     * @param int|null $sort Prerefined sort order (1, 2, 3)
     * @return array
     */
    public function get_filter_user_list2($limit, $offset = 0, $filter = array(), $member_type = MEMBER_TYPE_MEMBER, $sort = null) {

        $this->db->from('exp_members');
        if (! empty($filter['country'])) {
            $this->db->where('exp_members.country', $filter['country']);
        }
        if (! empty($filter['discipline'])) {
            $this->db->where('exp_members.discipline', $filter['discipline']);
        }
        if (! empty($filter['sector'])) {
            $where = " exp_members.uid IN (SELECT DISTINCT uid FROM exp_expertise_sector WHERE permission = 'All' AND status = " .
                $this->db->escape(STATUS_ACTIVE) .
                " AND sector = " . $this->db->escape($filter['sector']);
            if (! empty($filter['subsector'])) {
                $where .= " AND subsector = " . $this->db->escape($filter['subsector']);
            }
            $where .= ")";

            $this->db->where($where, null, FALSE);
        }

        if (! empty($filter['searchtext'])) {
            $terms = split_terms2($filter['searchtext']);

            $columns = array(
                'organization',
                'country',
            );
            if ($member_type == MEMBER_TYPE_MEMBER) {
                $columns = array_merge($columns, array(
                    'firstname',
                    'lastname',
                    'title'
                ));
            }

            $where = where_like2($columns, $terms);
            $this->db->where($where, null, FALSE);
        }

        $this->db
            ->join('exp_expertise_sector', "exp_members.uid = exp_expertise_sector.uid AND exp_expertise_sector.permission = 'All' AND exp_expertise_sector.status = " . $this->db->escape(STATUS_ACTIVE), 'left')
            ->join('exp_member_ratings', 'exp_member_ratings.member_id = exp_members.uid', 'left')
            ->join('exp_member_rating_details', 'exp_member_ratings.id = exp_member_rating_details.rating_id', 'left')
            ->where('exp_members.status', STATUS_ACTIVE)
            ->where('exp_members.membertype', $member_type)
            ->select('exp_members.uid, membertype, firstname, lastname, title, organization, userphoto, country, discipline')
            ->select("STRING_AGG(DISTINCT exp_expertise_sector.sector, ',' ORDER BY exp_expertise_sector.sector) expert_sector, COUNT(*) OVER() total_rows", FALSE)
            ->select('COALESCE(ROUND(AVG(exp_member_rating_details.rating), 1), 0.0) rating_overall, COUNT(DISTINCT exp_member_ratings.rated_by) rating_count', FALSE)
            ->group_by('exp_members.uid, membertype, firstname, lastname, title, organization, userphoto, country, discipline');

        if ($member_type == MEMBER_TYPE_MEMBER) {
            switch ($sort) {
		case 5: // Random
                    $this->db
                        ->order_by('firstname', 'RANDOM');
                    break;
                case 4: // High ranked first
                    $this->db
                        ->order_by('rating_overall DESC, rating_count DESC, firstname, lastname');
                    break;
                case 3: // Most recent first
                    $this->db
                        ->order_by('registerdate DESC, firstname, lastname');
                    break;
                case 2: // Most relevant
                    $this->db
                        ->select("CASE WHEN COALESCE(userphoto, '') = '' THEN 0 ELSE 1 END has_photo", FALSE)
                        ->order_by('has_photo DESC, registerdate DESC, firstname, lastname');
                    break;
                default: // Alphabetically
                    $this->db->order_by('firstname, lastname');
            }
        } else {
            $this->db->order_by('organization');
        }

        $rows = $this->db
            ->limit($limit, $offset)
            ->get()
            ->result_array();

        $result = array(
            'filter_total' => count($rows) > 0 ? (int) $rows[0]['total_rows'] : 0,
            'filter' => $rows
        );
//echo '<pre>'; print_r($this->db->last_query()); echo '</pre>';
        return $result;
    }

    /**
     * Get User list
     * TODO: Get rid of this method. Use get_filter_user_list2 instead.
     *
     * @access    public
     * @param $perpage
     * @param int $limit
     * @param string $country
     * @param string $sector
     * @param string $discipline
     * @param string $searchtext
     * @internal param $none
     * @return    array
     */
	public function get_filter_user_list($perpage, $limit = 0, $country = '', $sector = '', $discipline = '', $searchtext = '') {

		$searchtext_array 		= explode(' ',strtolower($searchtext));
		$searchtext_array_cnt 	= count($searchtext_array);

		$filterby = array();

		if ($country != '')	$this->db->where('country', $country);
		if ($sector != '')  $this->db->where('sector', $sector);
		if ($discipline != '') $this->db->where('discipline', $discipline);
		if (trim($searchtext) != '') {
			foreach ($searchtext_array as $k => $v) {
				if (isset($v) && $v != '') {
					//$this->db->like('firstname', trim($v)); $this->db->or_like('lastname', trim($v));
					$where_likec = "( LOWER(firstname) LIKE '%".trim($v)."%' OR LOWER(lastname) LIKE '%".trim($v)."%' )";
					$this->db->where($where_likec);
				}
			}
		}

		$query_filter_usertotal = $this->db
        	->where('exp_members.status', '1')
		    ->where('exp_members.membertype', '5')
            ->get('exp_members');


		if ($country != '') {
			$this->db->where('country', $country);
			$filterby['country'] = $country;
		}
		if ($discipline != '') {
			$this->db->where('discipline', $discipline);
			$filterby['discipline'] = $discipline;
		}
		if (trim($searchtext) != '') {
			foreach($searchtext_array as $k=>$v) {
				if(isset($v) && $v != '') {
					//$this->db->like('firstname', trim($v)); $this->db->or_like('lastname', trim($v));
					$where_like = "( LOWER(firstname) LIKE '%".trim($v)."%' OR LOWER(lastname) LIKE '%".trim($v)."%' )";
					$this->db->where($where_like);
				}
			}
			$filterby['searchtext'] = $searchtext;
		}
		if ($sector != '') {
			$this->db->join('exp_expertise_sector','exp_expertise_sector.uid = exp_members.uid', 'inner');
			$this->db->where_in('exp_expertise_sector.sector', $sector);
			//$this->db->group_by("exp_members.uid");
			$filterby['sector'] = $sector;
		}

		$this->db->distinct();
		$this->db->select('exp_members.*');
		$this->db->where('exp_members.status', '1');
		$this->db->where('exp_members.membertype', '5');
		$this->db->order_by('firstname', 'asc');
		$query_userlist = $this->db->get('exp_members',$perpage,$limit);

		if ($query_userlist->num_rows() > 0)
		{
			$mysector = array();
			foreach ($query_userlist->result_array() as $row) {
				$imgurl  = $row["userphoto"]!=""?$row["userphoto"]:"profile_image_placeholder.png";
				$imgpath = $row["userphoto"]!=""?USER_IMAGE_PATH:USER_NO_IMAGE_PATH;
				$mysector = $this->get_expertise_mysector($row["uid"]);

				$row["userphoto"]	  = $imgurl;
				$row["userphotoPath"] = $imgpath;
				$row["expert_sector"]		  = $mysector;
				$result_userlist["filter"][]	=	$row;
			}
			if($sector != "") {
				$result_userlist["filter_total"] = $query_userlist->num_rows();
			} else {
				$result_userlist["filter_total"] = $query_filter_usertotal->num_rows();
			}
			$result_userlist["filter_by"]	= $filterby;

			return $result_userlist;
		} else {
			$result_userlist["filter_total"] = 0;
			$result_userlist["filter"] 	 = array();
			$result_userlist["filter_by"]= $filterby;

			return $result_userlist;
		}
	}

    /**
     * Get User list
     *
     * @param $perpage
     * @param int $limit
     * @return    array
     */
	public function get_user_list($perpage, $limit = 0) {

		$this->db->where('status', '1');
		$this->db->where('membertype', '5');

		$query_userlist = $this->db->get('exp_members',$perpage,$limit);
		if ($query_userlist->num_rows() > 0)
		{
			foreach($query_userlist->result_array() as $row)
			{
				$imgurl  = $row["userphoto"]!=""?$row["userphoto"]:"profile_image_placeholder.png";
				$imgpath = $row["userphoto"]!=""?USER_IMAGE_PATH:USER_NO_IMAGE_PATH;

				$row["userphoto"]	  = $imgurl;
				$row["userphotoPath"] = $imgpath;

				$result_userlist[]	=	$row;
			}

			return $result_userlist;
		}
	}

	/**
	 * Get Account Details of loged in user
	 *
	 * @access	public
	 * @param	int
	 * @return	array
	 */
	public function get_user_photo($userid){

		$this->db->where('uid', $userid);
		$this->db->select('userphoto');
		$query_userphoto = $this->db->get('exp_members');
		if ($query_userphoto->num_rows() > 0)
		{
			foreach($query_userphoto->result_array() as $row)
			{
				$result_userphoto	=	$row;
			}

			return $result_userphoto;
		}
		else
		{
			return FALSE;
		}
	}


	/**
	 * Get Education
	 * @return	array
	 */
	public function get_education($userid,$eduid = '')
	{
		$this->db->where('uid', $userid);
		$this->db->where('status', "1");
		if(isset($eduid)&& $eduid != 0)
		{
			$this->db->where('educationid', $eduid);
		}
		$query_education = $this->db->get('exp_education');
		if ($query_education->num_rows() > 0)
		{
			foreach($query_education->result_array() as $row)
			{
				$result_education[]	=	$row;
			}

			return $result_education;
		}
	}


	/**
	 * Get expertise
	 * @return	array
	 */
	public function get_expertise($userid)
	{
		$this->db->where('uid', $userid);

		$query_expertise = $this->db->get('exp_expertise');
		if ($query_expertise->num_rows() > 0)
		{
			foreach($query_expertise->result_array() as $row)
			{
				$result_expertise	=	$row;
			}

			return $result_expertise;
		}
	}


	/**
	 * Get projects
	 * (get user projects)
	 *
	 * @access	public
	 * @param	int
	 * @return	array
	 */
	public function get_projects($userid)
	{
		//retrive user's project information from db
		$this->db->select("pid,uid,projectname,slug,projectphoto,country,sector,subsector,stage,location");
		$this->db->where('isdeleted','0');
		$this->db->where('uid', $userid);
		$query_project = $this->db->get('exp_projects');

		$totalproj = $query_project->num_rows();
		$projectdata["totalproj"] = $totalproj;

		foreach($query_project->result_array() as $row)
		{
			$projectdata["proj"][] = $row;
		}
		return $projectdata;
	}


	/**
	 * Get expertise sector
	 * @return	array
	 */
	public function get_expertise_mysector($userid)
	{
		$this->db->distinct('sector');
		$this->db->select('sector');
		$this->db->where('uid', $userid);
		$this->db->where('permission', 'All');
		$this->db->where('status', '1');
		$expert_sectors = array();
		$query_expertise = $this->db->get('exp_expertise_sector');
		foreach($query_expertise->result_array() as $row)
		{
			$expert_sectors[] = $row['sector'];
		}
		$imp_result_export = implode(",",$expert_sectors);
		//$query_expertise->free_result();
		return $imp_result_export;
	}



	/**
	 * Get expertise sectors and subsectors
	 * @return	array
	 */
	public function get_expertise_sector_subsector($userid)
	{
		$rows = $this->db
            //->distinct('sector')
		    ->select('sector, subsector, id')
            ->where('uid', $userid)
		    ->where('permission', 'All')
		    ->where('status', STATUS_ACTIVE)
            ->order_by('sector')
            ->order_by('subsector')
            ->get('exp_expertise_sector')
            ->result_array();

		$sectors = array();
		foreach($rows as $row)  {
			$sectors[$row['id']]['sector'] = $row['sector'];
			$sectors[$row['id']]['subsector'] = $row['subsector'];
		}

		return $sectors;
	}

	/**
	 * Get get_seats
     * TODO: Revisit and rewrite hopefully with one query to the database
	 *
	 * @access	public
	 * @param	int
	 * @return	array
	 */
	public function get_seats($userid)
    {
        $invites = $this->db
            ->where('orgid', (int) $userid)
            ->get('exp_invite_experts')
            ->result_array();

        $seats = array(
            'pending' => array(),
            'approved' => array()
        );

        foreach ($invites as $invite) {
            // TODO: Revisit and specify only needed columns!
            $user = $this->db
                ->where('uid', $invite['uid'])
                ->where('status', STATUS_ACTIVE)
                ->get('exp_members')
                ->row_array();

            if (! empty($user)) {
                // TODO: Revisit and get rid of this peace. expert_image() function should take care of it
                $imgurl = $user['userphoto'] != '' ? $user['userphoto'] : 'profile_image_placeholder.png';
                $imgpath = $user['userphoto'] != '' ? USER_IMAGE_PATH : USER_NO_IMAGE_PATH;
                $user['userphoto']	  = $imgurl;
                $user['userphotoPath'] = $imgpath;
                // ---

                if ($user['status'] == STATUS_PENDING) {
                    $seats['pending'][] = $user;
                }
                if ($user['status'] == STATUS_ACTIVE) {
                    $seats['approved'][] = $user;
                }
            }
        }

        return $seats;
	}

	/**
	 * Get List of expert sectors of user
	 * @return	array
	 */
	public function get_case_studies($userid,$cstudyid='',$status = '0')
	{
		$this->db->where('uid',$userid);
		if($cstudyid)
		{
			$this->db->where('casestudyid',$cstudyid);
		}
		if($status == '1')
		{
			$this->db->where('status',$status);
		}
		//$this->db->where('status', '1');
		$query = $this->db->get('exp_case_studies');
		if ($query->num_rows() > 0)
		{
			foreach($query->result_array() as $row)
			{
				$getcasestudies[]	=	$row;
			}

			return $getcasestudies;
		}
	}

	public function get_org_info($userid)
	{
		$this->db->where("uid",$userid);
		$this->db->where("status","1");

		$org = array();
		$query = $this->db->get("exp_invite_experts");
		if($query->num_rows() > 0) {
			$org = $query->row_array();
		} else {
			$org["orgid"] = 0;
		}
		return $org;
	}

	public function get_organization_projects($userid)
	{
		$this->db->join('exp_projects as ep','exs.projid = ep.pid', 'left');
		$this->db->select("pid,ep.uid,projectname,slug,projectphoto,country,ep.sector,ep.subsector,stage,location");
		$this->db->where('exs.isdeleted','0');
		$this->db->where('ep.isdeleted','0');
		$this->db->where('exs.status','1');
		$this->db->where('exs.orgid', $userid);
		$query_project = $this->db->get('exp_proj_expertadvert as exs');

		$totalproj = $query_project->num_rows();
		$projectdata["totalproj"] = $totalproj;

		foreach($query_project->result_array() as $row)
		{
			$projectdata["proj"][] = $row;
		}
		return $projectdata;
	}
	
	
	    /**
     * Get List of expert sectors of user
     * this one works better than the above one 
     * @return	array
     */
    public function get_organization_projects_data($userid)
    {
        $this->db->select("a.pid, a.uid, projectname,slug,projectphoto,country,a.sector,a.subsector,stage,location,lat,lng, description, sponsor, description, totalbudget");
        $this->db->from('exp_projects a');
        $this->db->join('exp_invite_experts b', 'b.uid=a.uid', 'left');
        $this->db->where('a.isdeleted','0');
        $this->db->where('b.existance','1');
        $this->db->where('b.orgid', $userid);
	$this->db->or_where('a.uid', $userid);
        $query_project = $this->db->get();

        $totalproj = $query_project->num_rows();
        $projectdata["totalproj"] = $totalproj;

        foreach($query_project->result_array() as $row)
        {
            $projectdata["proj"][] = $row;
        }
        return $projectdata;
    }

    /**
     * Send an email message to a member
     * TODO: Extract sending response to the client logic out!
     */
    public function send_model_mail()
	{
        $subject = $this->input->post('model_esubject', TRUE);
        $message = $this->input->post('model_emessage', TRUE);
        $to_id   = (int) $this->input->post('hdn_to', TRUE);
        $from_id = (int) sess_var('uid');

        $error_response = array(
            'status' => 'error',
            'message' => lang('ErrorwhilesendingMessage'),
            'isload' => 'no'
        );

        // Retrieve the sender information from the database
        $sender = $this->find($from_id, 'email, firstname, lastname, userphoto, membertype, organization'); //var_dump('Sender', $sender);
        if (empty($sender)) {
            sendResponse($error_response);
        }

        if ($sender['membertype'] == MEMBER_TYPE_MEMBER) {
            $from_name = $sender['firstname'] . ' ' . $sender['lastname'];
        } else {
            $from_name = $sender['organization'];
        }
        $from_photo = "plink/101/$from_id";

        // Retrieve the recipient information from the database
        $recipient = $this->find($to_id, 'email, firstname, lastname, userphoto, membertype, organization');
        if (empty($recipient)) {
            sendResponse($error_response);
            return;
        }

        if ($recipient['membertype'] == MEMBER_TYPE_MEMBER) {
            $to_name = $recipient['firstname'] .  ' ' . $recipient['lastname'];
        } else {
            $to_name = $recipient['organization'];
        }

        $view_data = array_merge(compact('from_id', 'from_name', 'from_photo'), array('message' => nl2br($message)));

        // Render the email from the template
        $content  = $this->load->view('email/_header', null, TRUE);
        $content .= $this->load->view('email/_member', $view_data, TRUE);
        $content .= $this->load->view('email/_footer', null, TRUE);

        $result = email(array($recipient['email'], $to_name), $subject, $content, array($sender['email'], $from_name . ' via ' . SITE_NAME));
        if (! $result) {
            sendResponse($error_response);
            return;
        }

        // Save the message in the database
        $data = array(
            'msgfrom'		=> $from_id,
            'msgto' 		=> $to_id,
            'msgsubject'	=> $subject,
            'msgmessage'	=> $message,
            'msgdatetime'	=> date('Y-m-d H:i:s')
        );
        if (! $this->db->insert('exp_model_email', $data)) {
            sendResponse($error_response);
            return;
        }

        $analytics = array(
            'event' => array(
                'name' => 'Message Sent',
                'properties' => array(
                    'Recipient Id' => $to_id,
                    'Recipient Name' => $to_name
                )
            )
        );

        $response = array(
            'status' => 'success',
            'message' => lang('MessageSendsuccessfully'),
            'isload' => 'no',
            'isreload' => 'yes',
            'analytics' => $analytics
        );
        sendResponse($response);
	}
	
	
	/**
     * Get List of messages sent to user
     * @return	array
     */
    public function get_user_messages($userid, $messageid=null)
    {
        $this->db->select("a.msgid, a.msgfrom, a.msgto, a.msgsubject, a.msgmessage, a.msgdatetime, b.uid, b.firstname, b.lastname, b.userphoto, b.membertype, b.organization, b.email");
        $this->db->from('exp_model_email a');
        $this->db->join('exp_members b', 'b.uid=a.msgfrom', 'left'); //from
        //$this->db->where('a.isdeleted','0');
        $this->db->where('b.status','1');
        if ($messageid != null){
            $this->db->where('a.msgid', $messageid);
        }
        else {
            $this->db->where('a.msgto', $userid);
        }
        $this->db->order_by('a.msgdatetime', 'DESC');
        $query_messages = $this->db->get();

        $totalmessages = $query_messages->num_rows();

        $messagedata["totalmessages"] = $totalmessages;

        foreach($query_messages->result_array() as $row)
        {
            $messagedata["msg"][] = $row;
        }
        return $messagedata;

    }

    /**
     * Get List of messages sent to user
     * @return	array
     */
    public function get_sent_messages($userid, $messageid=null)
    {
        $this->db->select("a.msgid, a.msgfrom, a.msgto, a.msgsubject, a.msgmessage, a.msgdatetime, b.uid, b.firstname, b.lastname, b.userphoto, b.membertype, b.organization, b.email");
        $this->db->from('exp_model_email a');
        $this->db->join('exp_members b', 'b.uid=a.msgto', 'left');
        //$this->db->where('a.isdeleted','0');
        $this->db->where('b.status','1');
        $this->db->where('a.msgfrom', $userid);
        if ($messageid != null){
            $this->db->where('a.msgid', $messageid);
        }
        $this->db->order_by('a.msgdatetime', 'DESC');
        $query_messages = $this->db->get();

        $totalmessages = $query_messages->num_rows();

        $messagedata["totalmessages"] = $totalmessages;

        foreach($query_messages->result_array() as $row)
        {
            $messagedata["msg"][] = $row;
        }
        return $messagedata;

    }

    /**
     * Most likely it used once for one time data population
     * batch_geocode
     *
     * @param int $batch_size
     * @param bool $clear
     * @return boolean|string
     */
    public function batch_geocode($batch_size = 30, $clear = true)
    {

        if( $clear )
        {
            // log something
            // 		all members gecode has been reset = geocode field = null
            $this->db->update('exp_members',array('geocode'=>NULL));
        }

        $qry = $this->db->where("geocode IS NULL")->get('exp_members');

        $c = $qry->num_rows();

        if( ! $c > 0 )
        {
            // log something
            // 	no empty geocode values = nothing to do
            return false;
        }

        $this->load->library('mapquest');

        // init batch array
        $batch_array = $qry->result_array();

        // make location strings
        foreach($qry->result() as $i => $row )
        {

            $location = trim($row->address) . ' ' . trim($row->city) . ' ' . trim($row->state) . ' ' . trim($row->postal_code) . ' ' . trim($row->country);
            $location = trim($location);

            if( $location == '' )
            {
                $this->db->where('uid',$row->uid)->update('exp_members',array('geocode'=>'[]'));
                unset($batch_array[$i]);
            }

            $batch_array[$i]['loc_string'] = urlencode($location);

        }

        echo "<pre>"; var_dump( $batch_array ); exit;


        // create groups for each batch call
        $groups = array_chunk($batch_array, $batch_size);



        // do each batch call with the group
        foreach ($groups as $i => $group)
        {

            $locations = array_map( function($v){ return $v['loc_string']; }, $group);

            $json = $this->mapquest->batch_geocode($locations)->json_obj;

            if( ! isset($json->results) )
            {
                // log something
                // 		bad group or bad reponse = goto next group
                continue;
            }

            foreach($json->results as $i => $row)
            {

                // dump it
                // echo "<pre>"; var_dump( $row->locations ); exit;

                // create insert obj
                $insert_data = array();
                $insert_data['geocode'] = json_encode($row);

                if( isset($row->locations[0]) && isset($group[$i]['uid']) )
                {
                    //	echo "<pre>"; var_dump( $json ); exit;
                    $loc1 = $row->locations[0];
                    $insert_data['lat'] = $loc1->latLng->lat;
                    $insert_data['lng'] = $loc1->latLng->lng;

                }
                $uid = $group[$i]['uid'];

                if( urlencode($row->providedLocation->location) == $group[$i]['loc_string'] )
                {
                    $all_updates[$uid] = $insert_data;
                    //echo "<pre>"; var_dump( $insert_data, $uid ); exit;
                    $this->db->where('uid',$uid)->update('exp_members',$insert_data);
                }
            }

            // dump  all updates by group
            // echo "<pre>"; var_dump( $all_updates ); exit;

        }

        echo "<pre>"; var_dump( $all_updates ); exit;
    }

    /**
     * old_batch_geocode
     *
     * @return boolean|string
     */
    public function old_batch_geocode()
    {
        //$qry = $this->db->update('exp_projects',array('geocode' => NULL, 'lat' => NULL, 'lng' => NULL) );
        //echo "<pre>"; var_dump( $qry ); exit;

        $this->load->library('mapquest');

        $qry = $this->db->where("geocode IS NULL")->get('exp_members',50);


        foreach($qry->result() as $i => $row )
        {

            $location = trim($row->city . ' ' . $row->state . ' ' . $row->country);

            if( $location == '' )
            {
                $this->db->where('uid',$row->uid)->update('exp_members',array('geocode'=>'[]'));
                continue;
            }

            $location = urlencode($location);

            $data = $this->mapquest->geocode($location)->json_raw;

            // create insert obj
            $insert_data = array();
            $insert_data['geocode'] = $data;

            $json = $this->mapquest->geocode($location)->json_obj;
            if( $json && count($json->results) > 0 && count($json->results[0]->locations) > 0 )
            {
                //	echo "<pre>"; var_dump( $json ); exit;
                $loc1 = $json->results[0]->locations[0];
                $insert_data['lat'] = $loc1->latLng->lat;
                $insert_data['lng'] = $loc1->latLng->lng;

            }
            //echo "<pre>"; var_dump( $insert_data ); exit;

            $this->db->where('uid',$row->uid)->update('exp_members',$insert_data);

            sleep(2);
        }
    }
}
