<?php
use Carbon\Carbon;

class Projects_model extends CI_Model {

	public $search_project_query;
	public $_where = false;

    public function sitemap($segment = '')
    {
        // Outputs a string of as many comma-separated question marks as there are elements in INTERNAL_USERS
        $in_string = str_replace(' ', ',', trim(str_repeat("? ", count(INTERNAL_USERS))));

        $sql = "
        SELECT ? || p.slug loc
          FROM exp_projects p JOIN exp_members m
            ON p.uid = m.uid
         WHERE p.isdeleted = ?
           AND m.status = ?
           AND p.uid IN (" . $in_string . ")
        ";
        $bindings = array(
            $segment,
            '0',
            STATUS_ACTIVE
        );

        $bindings = array_merge($bindings, INTERNAL_USERS); // Project should not belong to a real project developer

        $rows = $this->db
            ->query($sql, $bindings)
            ->result_array();

        return $rows;
    }

    /**
     * Retrive details for public project profile
     * @param string $slug public slug
     */
    public function find_public($slug)
    {

        $sql = "
        SELECT p.pid project_id, p.projectphoto, p.projectname, p.description,
               p.country, p.location, p.stage, p.sector, p.subsector
          FROM exp_projects p JOIN exp_members m
            ON p.uid = m.uid
         WHERE p.slug = ?
           AND p.isdeleted = ?
           AND m.status = ?
         LIMIT 1";

        $bindings = array(
            $slug,
            '0', // Project should be in a not deleted state
            STATUS_ACTIVE // Project owner should be active
        );

        $row = $this->db
            ->query($sql, $bindings)
            ->row_array();

        return $row;
    }

    /**
     * Returns an array of all (paginated) of my projects
     * @param $member_id
     * @param string $scope Allowed values: all, own, follow
     * @param int|null $limit
     * @param int|null $offset
     * @param int|bool $row_count
     * @return bool|array
     */
    public function all_my_projects($member_id, $scope = 'all', $limit = null, $offset = null, $row_count = false) {
        if (is_null($member_id)) {
            return false;
        }
        $select = array(
            'own'    => "SELECT p.pid id, slug, 1 is_my, projectname, projectphoto, p.country, p.sector, stage",
            'follow' => "SELECT p.pid id, slug, 0 is_my, projectname, projectphoto, p.country, p.sector, stage",
            'all'    => "SELECT       id, slug,   is_my, projectname, projectphoto, country, sector, stage"
        );
        $body['own'] = "
             FROM exp_projects p JOIN exp_members m
               ON p.uid = m.uid
            WHERE p.uid = ?
              AND p.isdeleted = ?
              AND m.status = ?";
        $body['follow'] = "
             FROM exp_project_followers f JOIN exp_projects p
               ON f.project_id = p.pid JOIN exp_members m
               ON p.uid = m.uid
            WHERE f.follower = ?
              AND p.isdeleted = ?
              AND m.status = ?";

        $bindings = array(
            $member_id,
            '0', // Project should not be in a deleted state
            STATUS_ACTIVE, // Project owner should be active (not deleted)
        );

        $body['all'] = " FROM ({$select['own']} {$body['own']} UNION ALL {$select['follow']} {$body['follow']}) q";

        // If fetching ALL projects then add one more binding for $member_id
        if ($scope == 'all') {
            $bindings[] = $member_id;
            $bindings[] = '0';
            $bindings[] = STATUS_ACTIVE;
        }

        $sql = $select[$scope];
        if ($row_count) {
            $sql .= ", COUNT(*) OVER () AS row_count";
        }
        $sql .= $body[$scope];

        $sql .= " ORDER BY is_my DESC, projectname";

        if (! is_null($limit)) {
            $offset = is_null($offset) ? 0 : (int) $offset;
            $sql .= " LIMIT ? OFFSET ?";
            $bindings[] = $limit;
            $bindings[] = $offset;
        }

        $result = $this->db
            ->query($sql, $bindings)
            ->result_array();

        return $result;
    }

    /**
     * Returns an array of my projects (projects that a user owns and follows)
     *
     * @param int $member_id
     * @param int $limit Default 3.
     * @return bool|array
     */
    public function my_projects($member_id, $limit = 3) {
        if (is_null($member_id)) {
            return false;
        }

        // Projects that I own
        $sql = "
        SELECT p.pid id, 1 is_my, projectname, projectphoto, sector, stage, totalbudget
          FROM exp_projects p
         WHERE p.uid = ?
           AND p.isdeleted = '0'
         ORDER BY RANDOM()
         LIMIT $limit
        ";
        $owned = $this->db
            ->query($sql, array($member_id))
            ->result_array();

        $owned_count = count($owned);
        if ($owned_count == $limit) {
            return $owned;
        }

        // Projects that I follow
        $sql = "
        SELECT p.pid id, 0 is_my, projectname, projectphoto, p.sector, stage, totalbudget
          FROM exp_project_followers f JOIN exp_projects p
            ON f.project_id = p.pid JOIN exp_members m
            ON p.uid = m.uid
         WHERE f.follower = ?
           AND p.isdeleted = '0'
           AND m.status = ?
         ORDER BY RANDOM()
         LIMIT ?
        ";
        $followed = $this->db
            ->query($sql, array($member_id, STATUS_ACTIVE, $limit - $owned_count))
            ->result_array();

        if (count($followed) == 0) {
            return $owned;
        } else {
            return array_merge($owned, $followed);
        }
    }

    public function similar_projects2($id, $limit = 3)
    {
        $sql = "
        SELECT q.id, projectname, projectphoto, sector, stage, totalbudget
          FROM
        (
            SELECT CASE WHEN project_id_1 = ? THEN project_id_2 ELSE project_id_1 END id
              FROM exp_project_project_scores s JOIN exp_projects p1
                ON s.project_id_1 = p1.pid JOIN exp_projects p2
                ON s.project_id_2 = p2.pid JOIN exp_members m1
                ON p1.uid = m1.uid JOIN exp_members m2
                ON p2.uid = m2.uid
             WHERE ? IN(project_id_1, project_id_2)
               AND p1.isdeleted = ?
               AND p2.isdeleted = ?
               AND m1.status = ?
               AND m2.status = ?
             ORDER BY score_sum DESC
             LIMIT ?
         ) q JOIN exp_projects p
             ON q.id = p.pid
         ";

        $pid = (int) $id; // Ensure that $pid is of type int
        $bindings = array(
            $id, $id,
            '0', '0', // Projects should not be in a deleted state
            STATUS_ACTIVE, STATUS_ACTIVE, // Project owners should be active (not deleted)
            (int) $limit
        );

        $rows = $this->db
            ->query($sql, $bindings)
            ->result_array();

        return $rows;

    }

    /**
     * Expects an array of project ids and returns the top similar project for each of those projects. The return array is keyed
     * by the project id.
     * TODO: Revisit and replace it with one query so that we can remove the dependency on Matches_lib
     *
     * @param array $ids
     * @return array
     */
    public function similar_projects($ids)
    {
        // Return an empty array if no ids are provided
        if (empty($ids) || ! is_array($ids)) {
            return array();
        }

//        $index = 1;
//        $sql = "WITH values (id, position) AS (VALUES";
//        foreach ($ids as $id) {
//            $sql .= " ($id, $index),";
//        }
//        $sql = rtrim($sql, ',') . ')';
//
//        $sql .= "SELECT
//        FROM exp_project_project_scores s JOIN
//        "
        $project_project_matches_lib = new Matches_lib(PROJECT_TYPE);
        //$sp_ids should be in the form of $sp_ids[my_project_id]=similar_project_id
        $sp_ids = $project_project_matches_lib->get_top_project_for_each($ids);

        // Return an empty array if no matches have been found
        if (empty($sp_ids)) {
            return array();
        }

        // Fetch similar projects data
        $similar = $this->db
            ->select('pid id, projectname, projectphoto, sector, stage, totalbudget')
            ->from('exp_projects')
            ->where_in('pid', $sp_ids)
            ->get()
            ->result_array();

        /*...this is going to get complicated. So we want to match the order of the similar projects as it is in the $ids array.
            The trick is without doing a double loop */
        $ordered = array();
        $flipped_myproject_id_map = array_flip($ids); //result: $flipped_ids[myproject_id] = $key_we_need
        $flipped_matched_id_map = array_flip($sp_ids); //result: $flipped_matched_id_map[similar_project_id]=my_project_id
        foreach($similar as $key => $project_data) {
            $order_id = $flipped_myproject_id_map[$flipped_matched_id_map[$project_data['id']]];
            $ordered[$order_id] = $project_data;
        }
        ksort($ordered);

        return $ordered;
    }

    /**
     * Follow the project
     *
     * @param int $project_id
     * @param int $follower It's uid from exp_members
     * @return bool
     */
    public function follow($project_id, $follower) {
        if (is_null($project_id) || is_null($follower)) {
            return false;
        }

        // BEGIN TRANSACCTION
        $this->db->trans_start();

        $this->unfollow($project_id, $follower);
        $result = $this->db
            ->set(array(
                'project_id' => $project_id,
                'follower' => $follower,
                'created_at' => date('Y-m-d H:i:s')
            ))
            ->insert('exp_project_followers');

        // COMMIT
        $this->db->trans_complete();
        $this->db->trans_off(); // TODO: Revisit this

        if ($this->db->trans_status() === FALSE) {
            return false;
        }

        return true;
    }

    /**
     * Unfollow the project
     *
     * @param int $project_id
     * @param int $follower It's uid from exp_members
     * @return bool
     */
    public function unfollow($project_id, $follower) {
        if (is_null($project_id) || is_null($follower)) {
            return false;
        }

        $result = $this->db
            ->where('project_id', $project_id)
            ->where('follower', $follower)
            ->delete('exp_project_followers');

        if ($result === FALSE) {
            return false;
        }

        return true;
    }

    /**
     * Check if the member (user) is following a specific project
     *
     * @param int $project_id
     * @param int $follower It's uid from exp_members
     * @return bool
     */
    public function isfollowing($project_id, $follower) {
        if (is_null($project_id) || is_null($follower)) {
            return false;
        }

        $result = $this->db
            ->where('project_id', $project_id)
            ->where('follower', $follower)
            ->count_all_results('exp_project_followers');

        return ($result > 0);
    }

	/**
	 * batch_geocode
	 *
	 * @access	public
	 * @return	boolean/string
	 */
	public function batch_geocode($slug=FALSE)
	{
		//$qry = $this->db->update('exp_projects',array('geocode' => NULL, 'lat' => NULL, 'lng' => NULL) );
		//echo "<pre>"; var_dump( $qry ); exit;

		$this->load->library('mapquest');

		//$qry = $this->db->where("geocode IS NULL")->get('exp_projects');
        if( $slug )
        {
            $qry = $this->db->where('slug',$slug)->get('exp_projects',50);
        }
        else
        {
            $qry = $this->db->where("geocode IS NULL")->get('exp_projects',50);
        }

		foreach($qry->result() as $i => $row )
		{

			$location = trim($row->location . ' ' . $row->country);

			$location = urlencode($location);

			$data = $this->mapquest->geocode($location)->json_raw;

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


			$this->db->where('pid',$row->pid)->update('exp_projects',$insert_data);

			sleep(2);
		}
	}

    /**
     * Updates a project's location based on the give location string
     * @param string $slug Project slug.
     * @param string $uid The project owner's uid
     * @param string $location An address including the country
     * @param string $country The location country
     * @return bool
     */
    public function update_proj_geocode_from_location($slug="",$uid="",$location="",$country="")
    {

        if( empty($slug) || empty($uid) || empty($location) )
        {
            return FALSE;
        }

        $this->load->library('mapquest');
        $qry = $this->db->select("location,country")
            ->from("exp_projects")
            ->where('slug',$slug)
            ->where("uid",$uid)
            ->get();

        foreach($qry->result() as $i => $row )
        {

            //$location = trim($row->location . ' ' . $row->country);

            //no updates necessary if the existing location and country are the same
            if($row->location === trim($location) && $row->country === trim($country)) return true;

            $location = urlencode($location);

            $data = $this->mapquest->geocode($location)->json_raw;

            // create insert obj
            $insert_data = array();
            $insert_data['geocode'] = $data;

            //$json = $this->mapquest->geocode($location)->json_obj;
            $json = json_decode($data);

            if( $json && count($json->results) > 0 && count($json->results[0]->locations) > 0 )
            {
                //echo "<pre>"; var_dump( $json ); exit;
                $loc1 = $json->results[0]->locations[0];
                $insert_data['lat'] = $loc1->latLng->lat;
                $insert_data['lng'] = $loc1->latLng->lng;

            }
            //echo "<pre>"; var_dump( $row->pid, $insert_data ); exit;

            $update = $this->db->where('slug',$slug)->where("uid",$uid)->update('exp_projects',$insert_data);
        }
    }

	/**
	 * get geometry for map
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function get_geom($pid)
	{	
		$where = array('proj_id'=>$pid);
		$data = $this->db->select("id,proj_id,poly_name,geojson,color,extra, ST_AsGeoJSON(geom) AS geom")
				->where($where)
				->get('exp_proj_map_draw');

		$return = array();
		
		if( $data->num_rows() > 0 )
		{	
			foreach( $data->result_array() as $key => $row )  
			{
				$row['geojson'] = json_decode($row['geojson'],true);

				$row['geom'] = json_decode($row['geom'],true);
				
				$return[] = $row;
			}
		}

		//echo "<pre>"; var_dump( $return ); exit;
		

		return $return;
		
	}


	/**
	 * add new geometry 
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function save_geom($pid, $gid, $data = false)
	{

		if( $gid == 'null' )
		{
			$gid = false;
		}

		$where = [
            'proj_id' => $pid,
            'id'      => $gid
        ];

		$current = false;

		if( $gid )
		{
			// check if id exists
			$current = $this->db->where($where)->get('exp_proj_map_draw')->num_rows();
		}

		// encode json data
		$data['geojson'] = json_encode($data['geojson']);
        $geom = $data['geom'];
        unset($data['geom']);

        $this->db->set($data);
        $this->db->set('geom', "ST_GeomFromGeoJSON(" . $this->db->escape($geom) . ")", false);

		if( $current )
		{
            $this->db->where($where);
            $saved = $this->db->update('exp_proj_map_draw');
		}
		else
		{
			$this->db->set('proj_id', $pid);
			$saved = $this->db->insert('exp_proj_map_draw');
			$gid = $this->db->insert_id();
		}

		if( $saved )
		{
			$response["status"] 	= "success";
			$response["message"] 	= 'Item saved.';
		}
		else 
		{
			$response["status"] 	= "error";
			$response["message"] 	= 'Item not saved.';
		}

		$response['gid'] = $gid;
		$response['proj_id'] = $pid;
		$response['current'] = $current;
		$response['last_query'] = $this->db->last_query();

		return $response;
	}

	/**
	 * delete geometry 
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function delete_geom($pid, $gid)
	{

		// check if id exists
		$deleted = $this->db->where('proj_id',$pid)->where('id',$gid)->delete('exp_proj_map_draw');
		
		$response["pid"] 		= $pid;
		$response["gid"] 		= $gid;

		if( $deleted )
		{
			$response["status"] 	= "success";
			$response["message"] 	= 'Item deleted.';
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= 'Item not deleted.';
		}

		return $response;
	}

	/**
	 * update location
	 * (update locations)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function update_location($slug,$uid)
	{
		$updateprojectarr = array(
			"location"			=> $this->input->post("project_location"),
			"lat"				=> $this->input->post("project_lat"),
			"lng"				=> $this->input->post("project_lng"),
			"geocode"			=> $this->input->post("project_geocode"),
		);

		if ($this->db->update("exp_projects", $updateprojectarr, array("slug" => $slug, "uid" => $uid)) )
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['ProjectLocationupdatesuccessfully'];
		}
		else 
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdateProjectLocation'];
		}

		return $response;
	}

	/**
	 * Builds a nice city/state, could be rolled into the get project data function.
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function get_city_state($slug,$uid)
	{

		$qryproj = $this->db->get_where("exp_projects",array("slug"=>$slug,"uid"=>$uid));
		$projectarr = $qryproj->row_array();
		$qryproj->free_result();

		if ($projectarr['geocode'])
		{
			$geocode = json_decode($projectarr['geocode'], true);
			
			if (count($geocode['results'][0]['locations']) > 0) {
				$geocode = $geocode['results'][0]['locations'][0];
				$prettylocation = "";

				if ($geocode['adminArea5'] != "")
				{
					$prettylocation = $geocode['adminArea5'];
				}

				// uncomment if you want to add in county.
				// if ($geocode['adminArea4'] != "")
				// {
				// 	if ($prettylocation != "")
				// 	{
				// 		$prettylocation = $prettylocation . ", ";
				// 	}
				// 	$prettylocation = $prettylocation . $geocode['adminArea4'];
				// }

				if ($geocode['adminArea3'] != "")
				{
					if ($prettylocation != "")
					{
						$prettylocation = $prettylocation . ", ";
					}
					$prettylocation = $prettylocation . $geocode['adminArea3'];
				}
			} else {
				$prettylocation = "";
			}
		} else {
			$prettylocation = "";
		}

		return $prettylocation;
	}

	/**
	 * Update Sector Values
	 *
	 * @access	public
	 * @return	boolean/string
	*/
	public function update_sector_values()
	{
		return false;
		die('update_sector_values start');
		$qry = $this->db->get('exp_sectors');

		if( $qry->num_rows() === 0 ) return false;


		foreach( $qry->result() as $i => $row )
		{
			$label = $row->sectorname;
			$value = $row->sectorvalue;



			$new_value = url_title($value,'_',true);


			echo "<strong>{$label}</strong>: {$value} | {$new_value}<br>";

			//$this->db->where('sectorid',$row->sectorid)->update('exp_sectors',array('sectorvalue'=>$new_value));
		}

		die('update_sector_values');
	}

    /**
     * Add New Project
     *
     * @access    public
     * @param $member_id
     * @param $projectname
     * @param null $orgid
     * @return    boolean/string
     */
	public function add_project($member_id, $projectname, $orgid = null)
	{
		$slug = $this->create_slug($projectname, 'exp_projects');

		$data = array(
            'uid'         => $member_id,
            'projectname' => $projectname,
            'slug'        => $slug,
            'isforum'     => '0', //sess_var('isforum'), // Most likely not used anymore
            'entry_date'  => time()
        );

		if (! $this->db->insert('exp_projects', $data)) {
            return FALSE;
        }
        $pid = $this->db->insert_id();

        if (! is_null($orgid) && $orgid > 0) {
            $data = array(
                'ownerid'   => $member_id,
                'projid'    => $pid,
                'orgid'     => $orgid,
                'status'    => STATUS_INACTIVE,
                'isdeleted' => '0'
            );
            if (! $this->db->insert('exp_proj_expertadvert', $data)) {
                return FALSE;
            }
        }

        return $pid;
	}

	/**
	 * Create slug
	 *
	 * @access	public
	 */
	public function create_slug($string, $table)
	{
	    $slug = url_title($string);
	    $slug = strtolower($slug);
	    $i = 0;
	    $params = array ();
	    $params['slug'] = $slug;

	    while ($this->db->where($params)->get($table)->num_rows())
	    {
	        if (!preg_match ('/-{1}[0-9]+$/', $slug ))
	        {
	            $slug .= '-' . ++$i;
	        }
	        else
	        {
	            $slug = preg_replace ('/[0-9]+$/', ++$i, $slug );
	        }
	        $params ['slug'] = $slug;
	    }
	    return $slug;
	}


    public function all($limit, $offset = 0, $filter = array(), $sort = null)
    {
        $this->db
            ->select('p.pid, p.uid, projectname, slug, projectphoto, p.country, p.sector, p.subsector, stage, totalbudget, o.government_level')
            ->select('COUNT(*) OVER () row_count', FALSE)
            ->from('exp_projects p')
            ->join('exp_members o', 'p.uid = o.uid')
            ->where('isdeleted', '0')
            ->where('o.status', STATUS_ACTIVE)
            ->where_in('o.membertype', array(MEMBER_TYPE_MEMBER, MEMBER_TYPE_EXPERT_ADVERT));

        if (! empty($filter['stage'])) {
            $this->db->where('stage', $filter['stage']);
        }

        if (! empty($filter['sector'])) {
            $this->db->where('p.sector', $filter['sector']);

            if (!empty($filter['subsector'])) {
                $this->db->where('p.subsector', $filter['subsector']);
            }
        }

        if (! empty($filter['country'])) {
            $this->db->where('p.country', $filter['country']);
        }

        if (! empty($filter['searchtext'])) {
            $terms = split_terms2($filter['searchtext']);

            $columns = array(
                'projectname',
                'p.country',
                'p.description',
            );
            $where = where_like2($columns, $terms);
            $this->db->where($where, null, FALSE);
        }

       switch ($sort) {
           case 1: // alphabetical by projectname
               $this->db->order_by('projectname');
               break;
           default: // Most recently updated first (option 2, default)
               $this->db
                    ->join("(SELECT t1.pid, t1.last_date
                            FROM log_projects AS t1
                            LEFT OUTER JOIN log_projects AS t2
                              ON t1.pid = t2.pid 
                                AND (t1.last_date < t2.last_date 
                                 OR (t1.last_date = t2.last_date AND t1.log_id < t2.log_id))
                            JOIN exp_projects proj 
                              ON (proj.pid = t1.pid) 
                            WHERE t2.pid IS NULL
                              AND proj.isdeleted = '0'
                            ORDER BY t1.last_date DESC) AS update_dates", 'p.pid = update_dates.pid', 'left outer')
                   ->order_by('(CASE WHEN last_date IS NULL THEN 1 ELSE 0 END)')
                   ->order_by('last_date DESC');
               break;
           
       }
        
        $rows = $this->db
            ->limit($limit, $offset)
            ->get()
            ->result_array();

        return $rows;

    }

    /**
	 * Get projects
	 * (get user projects)
	 *
	 * @access	public
	 * @param	int
	 * @return	array
	 */
	public function get_projects($uid,$perpage,$limit =0,$stage ='',$sector ='',$country ='',$searchtext ='',$cost='')
	{
		$filterby = array();
		if($stage != '')  { $this->db->where('stage', $stage);}
		if($sector != '')  { $this->db->where('sector', $sector);}
		if($country != '')	{ $this->db->where('country', $country); }
		if($searchtext != ''){ $this->db->like('LOWER(projectname)', strtolower($searchtext)); }

		$this->db->where('isdeleted', '0');
	 	$this->db->select("pid,uid,projectname,slug,projectphoto,country,sector,stage,fundamental_legal,location,developer,sponsor,totalbudget,lat,lng");
		$query_filter_qryproj = $this->db->get('exp_projects');

		if($stage != '')
		{
			$this->db->where('stage', $stage);
			$filterby['stage'] = $stage;
		}
		if($sector != '')
		{
			$this->db->where('sector', $sector);
			$filterby['sector'] = $sector;
		}
		if($country != '')
		{
			$this->db->where('country', $country);
			$filterby['country'] = $country;
		}
		if($cost != '')
		{
			// $this->db->where('country', $country);
			// $filterby['country'] = $country;
		}

		if($searchtext != ''){ $this->db->like('LOWER(projectname)', strtolower($searchtext));	$filterby['searchtext'] = $searchtext; }

		$this->db->where('isdeleted','0');

		$this->db->select("pid,uid,projectname,slug,projectphoto,country,sector,stage,fundamental_legal,location,description,developer,sponsor,totalbudget,lat,lng");

		$qryproj  = $this->db->get('exp_projects',$perpage,$limit);


		$totalproj = $query_filter_qryproj->num_rows();


		if ($totalproj > 0)
		{
			foreach($qryproj->result_array() as $row)
			{
				// WHAT IS THIS?
				$imgurl = $row["projectphoto"];
				$row["projectphoto"] = $imgurl;

				// Add easy to use class name for sector
				$row["sectorclass"] = url_title($row['sector'],'_',true);

				$projectdata["proj"][] = $row;
			}
			$projectdata["totalproj"] 		= $totalproj;
			$projectdata["filter_by"]		= $filterby;

			$this->projectdata = $projectdata;

			return $projectdata;
		}
		else
		{
			$projectdata["totalproj"]	 = 0;
			$projectdata["proj"] 	 	 = array();
			$projectdata["filter_by"]	 = $filterby;

			$this->projectdata = $projectdata;

			return $projectdata;
		}

	}


    /**
     * Search Projects
     * MyVip Map Project Search
     *
     * @param array $filters
     * @param int $page
     * @param int $limit
     * @return    array
     */
	public function search_projects($filters = array(), $page = 1, $limit = 10)
	{
		$offset = ($page - 1) * $limit;

		$default_filters = array(
			'lat'       => array('IS NOT NULL', NULL),
			'lng'       => array('IS NOT NULL', NULL),
			'isdeleted' => '0',
			);

		// merge defaults and passed through
		$filters = array_merge($default_filters, $filters);

        // TODO: !!! Revisit and deal with filters
        // Currently they are being set all over the place
		foreach ($filters as $col => $value) {
			if (is_array($value)) {
				$this->db->where("p.$col $value[0]", $value[1]);
			} else {
				$this->db->where("p.$col", $value);
			}
		}

		if ($this->_where) {
			$this->db->where($this->_where);
		}

		$query = $this->db
            ->select('p.*') // TODO: Revisit and explicitely set columns
            ->from('exp_projects p')
            ->join('exp_members m', 'p.uid = m.uid')
            ->where('m.status', STATUS_ACTIVE)
            ->limit($limit, $offset)
            ->get();
		$this->search_project_query = $this->db->last_query();

		if (! $query->num_rows() > 0) return false;

		$rows = $query->result_array();

		return $rows;
	}

	/**
     * TODO What does this method is doing here??? It should've been in expertise_model
	 * Get user General detail
	 * (get user projects)
	 *
	 * @access	public
	 * @param	int
	 * @return	array
	 */
	public function get_user_general($uid)
	{
        $row = $this->db
            ->select('uid,title,firstname,lastname,email,organization,membertype,discipline,sector,subsector,subsector_other,country,city,state,userphoto,forum_attendee,status')
            ->where('uid', (int) $uid)
            ->where('status', STATUS_ACTIVE)
            ->get('exp_members')
            ->row_array();

        return $row;
	}


	/*
	 * Check User Project
	 * Check whether this project is created by logged in user or not
	 *
	 * @access 	public
	 * @param 	string
	 * @return 	projectid
	*/
	public function check_user_project($slug,$uid)
	{
		$this->db->select("pid");
		$qrycheck = $this->db->get_where("exp_projects",array("uid"=>$uid,"slug"=>$slug,"isdeleted"=>"0"));
		if($qrycheck->num_rows() > 0)
		{
			$objproject = $qrycheck->row_array();
			$pid = $objproject["pid"];
			return $pid;
		}
		else
		{
			return "";
		}
	}


	/*
	 * Check User Project existance
	 * Check whether this project is created or not
	 *
	 * @access 	public
	 * @param 	string
	 * @return 	boolean
	*/
	public function check_project($slug, $return_data = false)
	{
		$this->db->select("pid,projectname");
		$qrycheck = $this->db->get_where("exp_projects",array("slug"=>$slug,"isdeleted"=>"0"));
		if($qrycheck->num_rows() > 0)
		{
			if( $return_data === true )
			{
				return $qrycheck->row();
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	/*
	 * Check uid from slug
	 *
	 *
	 * @access 	public
	 * @param 	string
	 * @return 	boolean
	*/
	public function get_uid_from_slug($slug)
	{
		$this->db->select("uid");
		$qrycheck = $this->db->get_where("exp_projects",array("slug"=>$slug,"isdeleted"=>"0"));
		if($qrycheck->num_rows() > 0)
		{
			$objproject = $qrycheck->row_array();
			$uid2 = $objproject["uid"];
			return $uid2;
		}
		else
		{
			return "";
		}
	}

	/*
	 * Find pid from slug
	 * @param string $slug
	 * @return string
	*/
	public function get_pid_from_slug($slug)
	{
        $result = $this->db
            ->select('pid')
            ->where('slug', $slug)
            ->where('isdeleted', '0')
            ->limit(1)
            ->get('exp_projects')
            ->row_array();

        if (isset($result['pid'])) {
            return $result['pid'];
        } else {
            return '';
        }
	}

    /**
     * Returns a slug given a pid of the project or an empty string if the project could not be found
     *
     * @param int $pid
     * @return string
     */
    public function get_slug_from_pid($pid) {
        $result = $this->db
            ->select('slug')
            ->where('pid', $pid)
            ->get('exp_projects')
            ->row_array();

        if (isset($result['slug'])) {
            return $result['slug'];
        } else {
            return '';
        }
    }

    /*
     * Update Project
     * Update project data submited from Project Information Tab
     *
     * @access 	public
     * @param 	string
     * @param 	int
     * @return 	array
    */
	public function update_project($slug, $uid)
	{

        $location = $this->input->post('project_location', TRUE);
        $country = $this->input->post('project_country', TRUE);
        $eststart = $this->input->post('project_eststart', TRUE);
        $estcompletion = $this->input->post('project_estcompletion', TRUE);
        
        // Clean dates ready to feed into DateFormat. 1111-11-11 should be fed into DateFormat as blank, 
        // and month-only dates should be padded using the first day of the month (so 04/2016 becomes 04/01/2016)
        if ($eststart != "1111-11-11" && $eststart != "" ) {
            if (strlen($eststart) == 7) {
                $eststart = substr_replace($eststart, "01/", 3, 0);
            }
        } else {
            $eststart = "";
        }
        if ($estcompletion != "1111-11-11" && $estcompletion != "") {
            if (strlen($estcompletion) == 7) {
                $estcompletion = substr_replace($estcompletion, "01/", 3, 0);
            }
        } else {
            $estcompletion = "";
        }

        // If budget value is empty or equals to 0 set it explicitly to NULL
        // otherwise convert it to int
        $budget = $this->input->post('project_budget_max', TRUE);
        if ($budget == '' || $budget == '0') {
            $budget = null;
        } else {
            $budget = (int) $budget;
        }

		$update = array(
			'description' 			=> $this->input->post('project_overview', TRUE),
			'keywords'				=> $this->input->post('project_keywords', TRUE),
			'country'				=> $country,
			'location'				=> $location,
			'sector'				=> $this->input->post('project_sector_main', TRUE),
			'subsector'				=> $this->input->post('project_sector_sub', TRUE),
			'subsector_other'		=> $this->input->post('project_sector_sub_other', TRUE),
			'totalbudget'			=> $budget,
			'financialstructure'	=> $this->input->post('project_financial', TRUE),
			'financialstructure_other' => $this->input->post('project_fs_other', TRUE),
			'project_meta_permissions' => $this->input->post('project_meta_permissions', TRUE),
			'stage'					=> $this->input->post('project_stage', TRUE),
			'eststart'				=> DateFormat($eststart, DATEFORMATDB, FALSE),
			'estcompletion'			=> DateFormat($estcompletion, DATEFORMATDB, FALSE),
            'stage_elaboration'     => $this->input->post('project_stage_elaboration', TRUE),
			'developer'		        => $this->input->post('project_developer', TRUE),
			'sponsor'		        => $this->input->post('project_sponsor', TRUE),
            'website'		        => $this->input->post('website', TRUE),
		);

        $lat = $this->input->post('project_lat', TRUE);
        $lng = $this->input->post('project_lng', TRUE);
        $geocode = $this->input->post('project_geocode', TRUE);

        //if we got lat,lng and geocode details, they are from a pin drop on a map, otherwise we'll see if we need to update the location.
        if ($lat && $lng && $geocode) {
            $update['lat'] = $lat;
            $update['lng'] = $lng;
            $update['geocode'] = $geocode;
        } else {
            $this->update_proj_geocode_from_location($slug, $uid, $location, $country);
        }


		$this->db
            ->where(array('slug' => $slug, 'uid' => $uid))
		    ->update('exp_projects', $update);

		$update['projectname'] = $this->input->post('title_input_hidden', TRUE);

		$update['executive'] = $this->get_executives($slug, $uid);
		$update['organization'] = $this->get_organizations($slug, $uid);

		return $update;
	}

    /**
     * Retrieving a record  by primary key
     *
     * @param int $id
     * @param null $select
     * @return array
     */
    public function find($id, $select = null) {
        if (! is_null($select)) {
            $this->db->select($select);
        }

        $row = $this->db
            ->where('pid', (int) $id)
            ->where('isdeleted', '0')
            ->from('exp_projects')
            ->get()
            ->row_array();

        return $row;
    }

	/*
	 * Get Project Data
	 * Get Requested Project data when user eneter into edit mode
	 *
	 * @access 	public
	 * @param 	string
	 * @param 	int
	 * @return 	array
	*/
	public function get_project_data($slug,$uid)
	{
		$qryproj = $this->db->get_where("exp_projects",array("slug"=>$slug,"uid"=>$uid));
		$projectarr = $qryproj->row_array();
		$qryproj->free_result();

		$projectarr["executive"] = $this->get_executives($slug,$uid);
		$projectarr["organization"] = $this->get_organizations($slug,$uid);
        $projectarr["last_updated"] = $this->get_last_updated($slug, $uid);

		return $projectarr;
	}


	/*
	 * Get common Project Data
	 * Get Requested Project data when user eneter into edit mode
	 *
	 * @access 	public
	 * @param 	string
	 * @param 	string,int
	 * @return 	array
	*/
	public function get_common_data($slug,$uid)
	{
		$this->db->select(array('pid','stage','uid','projectname','slug','fundamental_legal'));
		$qryproj = $this->db->get_where("exp_projects",array("slug"=>$slug,"uid"=>$uid));
		$projectarr = $qryproj->row_array();
		$qryproj->free_result();

		return $projectarr;
	}

	 /**
	 * Get Project assessment
	 * (get projct assessment)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	array
	 */
	public function get_project_assessment ($slug)
	{
		//$this->db->order_by("assessmentdate", "desc");
		$qryexec = $this->db->order_by( "id", "desc")->get_where("exp_proj_assessment",array("slug"=>$slug));
		$projectarr = $qryexec->result_array();
		$qryexec->free_result();

		return $projectarr;
	}


    /**
     * Update Project Picture in Project Details & Edit
     * TODO: Revisit and either change this implementation to use pid instead of slug
     * or implement another method that does that.
     * @param $file
     * @param $slug
     * @param $uid
     * @return boolean
     */
	public function upload_photo($file, $slug, $uid)
	{
		$update = array('projectphoto' => $file['file_name']);

		$this->db->where(array('uid'=> $uid,'slug'=>$slug));
		if (! $this->db->update('exp_projects', $update)) {
            return false;
        }

        return true;
	}


	/**
	 * Update Project name
     * TODO: Revisit and either change this implementation to use pid instead of slug
     * or implement another method that does that.
	 *
	 * @access	public
	 * @param	int
	 * @return	bool
	 */
	public function updateprojectname($slug, $uid)
	{
		$response = array();
		$update_data = array(
			'projectname' => $this->input->post("title_input")
		);
		$this->db->where(array('uid'=> $uid,'slug'=>$slug));

		if($this->db->update('exp_projects', $update_data))
		{
			$response["issubmit"] = TRUE;
			$response["formname"] = "project_form";
		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * Add Legal
	 *
	 * @access	public
	 * @param	int
	 * @return	array
	 */
	public function add_legal($slug,$uid)
	{
		$response = array();
		$update_data = array(
			'fundamental_legal' => $this->input->post("project_legal")
		);
		$this->db->where(array('uid'=> $uid,'slug'=>$slug));

		if($this->db->update('exp_projects', $update_data))
		{
			$response["issubmit"] = FALSE;
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Projectinfoupdatesuccessful'];
			$response["remove"] 	= true;
			$response["isload"] 	= "no";
			//$response["isreset"] 	= "yes";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdatingProfile'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

    /**
     * Add Procurement Process
     *
     * @access  public
     * @param   int
     * @return  array
     */
    public function add_procurement_process($slug,$uid)
    {
        $response = array();
        $update_data = array(
            'procurement_criteria' => $this->input->post("project_auction_criteria"),
            'procurement_date' => DateFormat($this->input->post('project_auction_date', TRUE), DATEFORMATDB, FALSE)
        );
        $this->db->where(array('uid'=> $uid,'slug'=>$slug));

        if($this->db->update('exp_projects', $update_data))
        {
            $response["issubmit"] = FALSE;
            $response["status"]     = "success";
            $response["message"]    = $this->dataLang['lang']['Projectinfoupdatesuccessful'];
            $response["remove"]     = true;
            $response["isload"]     = "no";
            //$response["isreset"]  = "yes";
            //$response["loaddata"]     = $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
        }
        else
        {
            $response["status"]     = "error";
            $response["message"]    = $this->dataLang['lang']['ErrorwhileupdatingProfile'];
            $response["remove"]     = true;

        }
        //header('Content-type: application/json');
        echo json_encode($response);
    }

	/**
	 * Get executive
	 * (get executives)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	array
	 */
	public function get_executives($slug,$uid)
	{
		$qryexec = $this->db->get_where("exp_proj_executive",array("slug"=>$slug,"uid"=>$uid));
		$execarr = $qryexec->result_array();
		$qryexec->free_result();

		return $execarr;
	}


	/**
	 * Add executive
	 * (add executives)
	 *
	 * @access	public
	 * @param	int
	 * @return	array
	 */
	public function add_executive($slug,$uid)
	{
		$executivedata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'executivename'	=> $this->input->post("project_executives_name"),
			'company'	=> $this->input->post("project_executives_company"),
			'role'	=> $this->input->post("project_executives_role"),
			'email'	=> $this->input->post("project_executives_email"),
			'role_other'	=> $this->input->post("project_executives_role_other")
		);
		$response = array();
		if($this->db->insert("exp_proj_executive",$executivedata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Executiveaddedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["isreset"] 	= "yes";
			/*$response["isreload"] 	= "yes";*/
			$response["listdiv"] 	= "executive_form";
			$response["loadurl"] 	= "/projects/form_load/project_executives/view/".$slug."";

			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileaddingExecutive'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * update executive
	 * (update executives)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function update_executive($slug,$uid)
	{
		$executiveid = $this->input->post("hdn_project_executives_id");
		$executivedata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'executivename'	=> $this->input->post("project_executives_name"),
			'company'	=> $this->input->post("project_executives_company"),
			'role'	=> $this->input->post("project_executives_role"),
			'email'	=> $this->input->post("project_executives_email"),
			'role_other'	=> $this->input->post("project_executives_role_other")
		);

		$this->db->where(array('uid'=> $uid,'slug'=>$slug,'id'=>$executiveid));

		if($this->db->update('exp_proj_executive', $executivedata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Executiveupdatesuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "executive_form";
			$response["loadurl"] 	= "/projects/form_load/project_executives/view/".$slug."";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdateExecutive'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * delete executive
	 * (delete executives)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function delete_executive($delid,$uid)
	{
		$this->db->where(array("id"=>$delid,"uid"=>$uid));
		$response = array();
		$response["remove"]	= FALSE;
		if($qrydelexec = $this->db->delete("exp_proj_executive"))
		{
			$response["remove"]	= TRUE;
		}

		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * Get Organization
	 * (get organization)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	json
	 */
	public function get_organizations($slug,$uid)
	{
		$qryorg = $this->db->get_where("exp_proj_organization",array("slug"=>$slug,"uid"=>$uid));
		$orgarr = $qryorg->result_array();
		$qryorg->free_result();

		return $orgarr;
	}

	/**
	 * add organization
	 * (add organization)
	 *
	 * @access	public
	 * @param	slug,int
	 * @return	json
	 */
	public function add_organization($slug,$uid)
	{
		$organizationdata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'contact'	=> $this->input->post("project_organizations_contact"),
			'company'	=> $this->input->post("project_organizations_company"),
			'role'	=> $this->input->post("project_organizations_role"),
			'email'	=> $this->input->post("project_organizations_email")
		);
		$response = array();
		if($this->db->insert("exp_proj_organization",$organizationdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Organizationaddedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["isreset"] 	= "yes";
			$response["listdiv"] 	= "organization_form";
			$response["loadurl"] 	= "/projects/form_load/project_organization/view/".$slug."";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileaddingOrganization'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * update organization
	 * (update organization)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function update_organization($slug,$uid)
	{
		$organizationid = $this->input->post("hdn_project_organizations_id");
		$organizationdata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'contact'	=> $this->input->post("project_organizations_contact"),
			'company'	=> $this->input->post("project_organizations_company"),
			'role'	=> $this->input->post("project_organizations_role"),
			'email'	=> $this->input->post("project_organizations_email")
		);

		$this->db->where(array('uid'=> $uid,'slug'=>$slug,'id'=>$organizationid));

		if($this->db->update('exp_proj_organization', $organizationdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Organizationupdatesuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "organization_form";
			$response["loadurl"] 	= "/projects/form_load/project_organization/view/".$slug."";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdateOrganization'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * delete organization
	 * (delete organization)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function delete_organization($delid,$uid)
	{
		$this->db->where(array("id"=>$delid,"uid"=>$uid));
		$response = array();
		$response["remove"]	= FALSE;
		if($qrydelexec = $this->db->delete("exp_proj_organization"))
		{
			$response["remove"]	= TRUE;
		}

		//header('Content-type: application/json');
		echo json_encode($response);

	}

    /**
     * Get date on which project profile was last updated
     * @param  string $slug Project slug
     * @return Carbon\Carbon       Date of last update
     */
    public function get_last_updated($slug)
    {
        $sql = "SELECT COALESCE(update_date.last_date, created.created) AS last_updated 
                FROM
                    (SELECT to_timestamp(COALESCE(NULLIF(proj.entry_date,0),1367193600)) AS created
                    FROM exp_projects proj
                      JOIN exp_members m
                        ON (proj.uid = m.uid)
                    WHERE slug = ?
                     ) created
                 FULL OUTER JOIN 
                    (SELECT t1.last_date
                    FROM log_projects AS t1
                    LEFT OUTER JOIN log_projects AS t2
                      ON t1.pid = t2.pid 
                        AND (t1.last_date < t2.last_date 
                         OR (t1.last_date = t2.last_date AND t1.log_id < t2.log_id))
                    JOIN exp_projects proj 
                      ON (proj.pid = t1.pid) 
                    WHERE t2.pid IS NULL
                      AND proj.slug = ?
                    ) update_date
                ON TRUE";

        $bindings = [$slug, $slug];

        $last_updated = $this
                        ->db
                        ->query($sql, $bindings)
                        ->row()
                        ->last_updated;

        Carbon::setLocale(App::$languageToLocaleLookup[$this->session->userdata('lang')]);
        return new Carbon($last_updated);
    }

	/**
	 * Get engineering
	 * (get engineering)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	json
	 */

	public function get_engineering($slug,$uid)
	{
		$qryexec = $this->db->get_where("exp_proj_engg_fundamental",array("slug"=>$slug,"uid"=>$uid));
		$execarr = $qryexec->result_array();
		$qryexec->free_result();

		return $execarr;
	}

	/**
	 * add engineering
	 * (add engineering)
	 *
	 * @access	public
	 * @param	int,int,optional
	 * @return	json
	 */
	public function add_engineering($slug,$uid,$upload='')
	{

		$engineeringdata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'contactname'	=> $this->input->post("project_engineering_cname"),
			'company'	=> $this->input->post("project_engineering_company"),
			'role'		=> $this->input->post("project_engineering_role"),
			'challenges'	=> $this->input->post("project_engineering_challenges"),
			'innovations'	=> $this->input->post("project_engineering_innovations")
		);

		if($upload['error']=='')
		{
			$engineeringdata['schedule'] = $upload['file_name'];
		}

		$response = array();
		if($this->db->insert("exp_proj_engg_fundamental",$engineeringdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['EngineeringFundamentaladdedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["isreset"] 	= "yes";
			$response["listdiv"] 	= "engineering_form";
			$response["loadurl"] 	= "/projects/form_load/project_engineering/view/".$slug."";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileaddingEngineeringFundamental'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * update engineering
	 * (update engineering)
	 *
	 * @access	public
	 * @param	int,int,optional
	 * @return	json
	 */
	public function update_engineering($slug,$uid,$upload='')
	{
		$engineeringid = $this->input->post("hdn_project_engineering_id");
		$engineeringdata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'contactname'	=> $this->input->post("project_engineering_cname"),
			'company'	=> $this->input->post("project_engineering_company"),
			'role'		=> $this->input->post("project_engineering_role"),
			'challenges'	=> $this->input->post("project_engineering_challenges"),
			'innovations'	=> $this->input->post("project_engineering_innovations")
		);

		if($upload['error']=='')
		{
			$engineeringdata['schedule'] = $upload['file_name'];
		}

		$this->db->where(array('uid'=> $uid,'slug'=>$slug,'id'=>$engineeringid));

		if($this->db->update('exp_proj_engg_fundamental', $engineeringdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['EngineeringFundamentalupdatesuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "engineering_form";
			$response["loadurl"] 	= "/projects/form_load/project_engineering/view/".$slug."";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdateEngineeringFundamental'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * Delete engineering
	 * (delete engineering)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function delete_engineering($delid,$uid)
	{
		$unlinkstatus = $this->unlink_files($delid,$uid,'schedule','exp_proj_engg_fundamental',PROJECT_IMAGE_PATH);

			$this->db->where(array("id"=>$delid,"uid"=>$uid));
			$response = array();
			$response["remove"]	= FALSE;
			if($qrydelexec = $this->db->delete("exp_proj_engg_fundamental"))
			{
				$response["remove"]	= TRUE;
			}

			//header('Content-type: application/json');
			echo json_encode($response);

	}



	/**
	 * get map points
	 * (get map points)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	array
	 */
	public function get_map_point($slug,$uid)
	{
		$qryexec = $this->db->get_where("exp_proj_map_points",array("slug"=>$slug,"uid"=>$uid));
		$execarr = $qryexec->result_array();
		$qryexec->free_result();

		return $execarr;
	}

	/**
	 * Add map points
	 * (add map points)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function add_map_point($slug,$uid)
	{
		$mappointdata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name'	=> $this->input->post("project_map_points_mapname"),
			'latitude'	=> $this->input->post("project_map_points_latitude"),
			'longitude'		=> $this->input->post("project_map_points_longitude")
		);
		$response = array();
		if($this->db->insert("exp_proj_map_points",$mappointdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Mappointaddedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "map_points_form";
			$response["loadurl"] 	= "/projects/form_load/project_map_point/view/".$slug."";
			$response["isreset"] 	= "yes";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileaddingMappoint'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * update map points
	 * (update map points)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function update_map_point($slug,$uid)
	{
		$mappointid = $this->input->post("hdn_project_map_points_id");
		$mappointdata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name'	=> $this->input->post("project_map_points_mapname"),
			'latitude'	=> $this->input->post("project_map_points_latitude"),
			'longitude'		=> $this->input->post("project_map_points_longitude")
		);

		$this->db->where(array('uid'=> $uid,'slug'=>$slug,'id'=>$mappointid));

		if($this->db->update('exp_proj_map_points', $mappointdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['MapPointupdatesuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "map_points_form";
			$response["loadurl"] 	= "/projects/form_load/project_map_point/view/".$slug."";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdateingMapPoint'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * Delete map points
	 * (delete map points)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function delete_map_point($delid,$uid)
	{
		$this->db->where(array("id"=>$delid,"uid"=>$uid));
		$response = array();
		$response["remove"]	= FALSE;
		if($qrydelexec = $this->db->delete("exp_proj_map_points"))
		{
			$response["remove"]	= TRUE;
		}

		//header('Content-type: application/json');
		echo json_encode($response);

	}


	/**
	 * Get design issue
	 * (get design issue)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	array
	 */
	public function get_design_issue($slug,$uid)
	{
		$qryexec = $this->db->get_where("exp_proj_design_issues",array("slug"=>$slug,"uid"=>$uid));
		$execarr = $qryexec->result_array();
		$qryexec->free_result();

		return $execarr;
	}

	/**
	 * Add design issue
	 * (add design issue)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function add_design_issue($slug,$uid,$upload='')
	{
		$designissuedata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'title'	=> $this->input->post("project_design_issues_title"),
			'description'	=> $this->input->post("project_design_issues_desc"),
			'attachment'	=> $this->input->post("project_design_issues_attachment"),
			'permission'	=> $this->input->post("project_design_issues_permissions")
		);
		if($upload['error']=='')
		{
			$designissuedata['attachment'] = $upload['file_name'];
		}
		$response = array();
		if($this->db->insert("exp_proj_design_issues",$designissuedata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Designissueaddedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["isreset"] 	= "yes";
			$response["listdiv"] 	= "design_issue_form";
			$response["loadurl"] 	= "/projects/form_load/project_design_issue/view/".$slug."";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileaddingDesignissue'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * update design issue
	 * (update design issue)
	 *
	 * @access	public
	 * @param	int,int,optional
	 * @return	json
	 */
	public function update_design_issue($slug,$uid,$upload='')
	{
		$designissueid = $this->input->post("hdn_project_design_issues_id");
		$designissuedata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'title'				=> $this->input->post("project_design_issues_title"),
			'description'		=> $this->input->post("project_design_issues_desc"),
			'attachment'		=> $this->input->post("project_design_issues_attachment"),
			'permission'	=> $this->input->post("project_design_issues_permissions")

		);
		if($upload['error']=='')
		{
			$designissuedata['attachment'] = $upload['file_name'];
		}

		$this->db->where(array('uid'=> $uid,'slug'=>$slug,'id'=>$designissueid));
		$response = array();
		if($this->db->update("exp_proj_design_issues",$designissuedata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Designissueupdatedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "design_issue_form";
			$response["loadurl"] 	= "/projects/form_load/project_design_issue/view/".$slug."";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdatingDesignissue'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * Delete design issue
	 * (delete design issue)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function delete_design_issue($delid,$uid)
	{
		$unlinkstatus = $this->unlink_files($delid,$uid,'attachment','exp_proj_design_issues',PROJECT_IMAGE_PATH);
		if($unlinkstatus)
		{
			$this->db->where(array("id"=>$delid,"uid"=>$uid));
			$response = array();
			$response["remove"]	= FALSE;
			if($qrydelexec = $this->db->delete("exp_proj_design_issues"))
			{
				$response["remove"]	= TRUE;
			}

			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	 * Get design issue
	 * (get design issue)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	array
	 */
	public function get_environment($slug,$uid)
	{
		$qryexec = $this->db->get_where("exp_proj_environment",array("slug"=>$slug,"uid"=>$uid));
		$execarr = $qryexec->result_array();
		$qryexec->free_result();

		return $execarr;
	}

	/**
	 * Add environment
	 * (add environment)
	 *
	 * @access	public
	 * @param	int,int,optional
	 * @return	json
	 */
	public function add_environment($slug,$uid,$upload='')
	{
		$environmentdata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'title'	=> $this->input->post("project_environment_title"),
			'description'	=> $this->input->post("project_environment_desc"),
			'attachment'	=> $this->input->post("project_environment_attachment"),
			'permission'	=> $this->input->post("project_environment_permissions")
		);
		if($upload['error']=='')
		{
			$environmentdata['attachment'] = $upload['file_name'];
		}

		$response = array();
		if($this->db->insert("exp_proj_environment",$environmentdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Environmentfileaddedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["isreset"] 	= "yes";
			$response["listdiv"] 	= "environment_form";
			$response["loadurl"] 	= "/projects/form_load/project_environment/view/".$slug."";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileaddingEnvironmentfile'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * Update environment
	 * (update environment)
	 *
	 * @access	public
	 * @param	int,int,optional
	 * @return	json
	 */
	 public function update_environment($slug,$uid,$upload='')
	{
		$environmentid = $this->input->post("hdn_project_environment_id");
		$environmentdata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'title'				=> $this->input->post("project_environment_title"),
			'description'		=> $this->input->post("project_environment_desc"),
			'attachment'		=> $this->input->post("project_environment_attachment"),
			'permission'	=> $this->input->post("project_environment_permissions")

		);
		if($upload['error']=='')
		{
			$environmentdata['attachment'] = $upload['file_name'];
		}

		$this->db->where(array('uid'=> $uid,'slug'=>$slug,'id'=>$environmentid));
		$response = array();
		if($this->db->update("exp_proj_environment",$environmentdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Environmentfileupdatedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "environment_form";
			$response["loadurl"] 	= "/projects/form_load/project_environment/view/".$slug."";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdatingEnvironmentfile'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * delete environment
	 * (delete environment)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function delete_environment($delid,$uid)
	{
		$unlinkstatus = $this->unlink_files($delid,$uid,'attachment','exp_proj_environment',PROJECT_IMAGE_PATH);
		if($unlinkstatus)
		{
			$this->db->where(array("id"=>$delid,"uid"=>$uid));
			$response = array();
			$response["remove"]	= FALSE;
			if($qrydelexec = $this->db->delete("exp_proj_environment"))
			{
				$response["remove"]	= TRUE;
			}

			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}



	/**
	 * Get studies
	 * (get studies)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	array
	 */
	public function get_studies($slug,$uid)
	{
		$qryexec = $this->db->get_where('exp_proj_studies', array('slug' => $slug, 'uid' => $uid));
		$execarr = $qryexec->result_array();
		$qryexec->free_result();

		return $execarr;
	}

	/**
	 * Add studies
	 * (add studies)
	 *
	 * @access	public
	 * @param	int,int,optional
	 * @return	array
	 */
	public function add_studies($slug,$uid,$upload='')
	{
		$studiesdata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'title'	=> $this->input->post("project_studies_title"),
			'description'	=> $this->input->post("project_studies_desc"),
			'attachment'	=> $this->input->post("project_studies_attachment"),
			'permission'	=> $this->input->post("project_studies_permissions")
		);
		if($upload['error']=='')
		{
			$studiesdata['attachment'] = $upload['file_name'];
		}

		$response = array();
		if($this->db->insert("exp_proj_studies",$studiesdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Studyfileaddedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "project_studies_form";
			$response["loadurl"] 	= "/projects/form_load/project_studies/view/".$slug."";
			$response["isreset"] 	= "yes";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileaddingStudyfile'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * Update studies
	 * (update studies)
	 *
	 * @access	public
	 * @param	int,int,optional
	 * @return	json
	 */
	public function update_studies($slug,$uid,$upload='')
	{
		$studiesid = $this->input->post("hdn_project_studies_id");
		$studiesdata= array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'title'				=> $this->input->post("project_studies_title"),
			'description'		=> $this->input->post("project_studies_desc"),
			'attachment'		=> $this->input->post("project_studies_attachment"),
			'permission'	=> $this->input->post("project_studies_permissions")

		);
		if($upload['error']=='')
		{
			$studiesdata['attachment'] = $upload['file_name'];
		}

		$this->db->where(array('uid'=> $uid,'slug'=>$slug,'id'=>$studiesid));
		$response = array();
		if($this->db->update("exp_proj_studies",$studiesdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Studyfileupdatedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "project_studies_form";
			$response["loadurl"] 	= "/projects/form_load/project_studies/view/".$slug."";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdatingStudyfiles'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * Delete studies
	 * (delete studies)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function delete_studies($delid,$uid)
	{
		$unlinkstatus = $this->unlink_files($delid,$uid,'attachment','exp_proj_studies',PROJECT_IMAGE_PATH);
		if($unlinkstatus)
		{
			$this->db->where(array("id"=>$delid,"uid"=>$uid));
			$response = array();
			$response["remove"]	= FALSE;
			if($qrydelexec = $this->db->delete("exp_proj_studies"))
			{
				$response["remove"]	= TRUE;
			}

			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	 * Get finance
	 * (get finance)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	array
	 */

	public function get_financial($slug,$uid)
	{
		$this->db->where("(name != ''");
		$this->db->or_where("contactname !=","");
		$this->db->or_where("role !=","");
		$this->db->or_where("contactinfo != '')");
		$qryexec = $this->db->get_where("exp_proj_financial",array("slug"=>$slug,"uid"=>$uid));
		$execarr = $qryexec->row_array();
		$qryexec->free_result();

		return $execarr;
	}

	/**
	 * Add financial
	 * (add financial)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	array
	 */
	public function add_financial($slug,$uid)
	{


		$num_financialrec = count($this->get_financial($slug,$uid));

		$financialdata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name'					=> $this->input->post("project_fs_name"),
			'name_privacy'	 		=> $this->input->post("project_fs_name_permissions"),
			'contactname'			=> $this->input->post("project_fs_name"),
			'contactname_privacy' 	=> $this->input->post("project_fs_contact_permissions"),
			'role' 					=> $this->input->post("project_fs_role"),
			'role_others' 			=> $this->input->post("project_fs_other"),
			'role_privacy' 			=> $this->input->post("project_fs_role_permissions"),
			'contactinfo' 			=> $this->input->post("project_fs_info"),
			'contactinfo_privacy' 	=> $this->input->post("project_fs_info_permissions")
		);
		$response = array();

		if(	$num_financialrec > 0)
		{
			$this->db->where(array('uid'=> $uid,'slug'=>$slug));
			$financialStatus = $this->db->update("exp_proj_financial",$financialdata);
		}
		else
		{
			$financialStatus = $this->db->insert("exp_proj_financial",$financialdata);
		}
		if($financialStatus)
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['FinancialDetailsupdatedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["loadurl"] 	= "/projects/form_load/project_financial/view/".$slug."";
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdatingFinancialDetails'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}


	/**
	 * get fund resources
	 * (get fund resources)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	array
	 */

	public function get_fund_sources($slug,$uid)
	{
		$qryexec = $this->db->get_where("exp_proj_fund_sources",array("slug"=>$slug,"uid"=>$uid));
		$execarr = $qryexec->result_array();
		$qryexec->free_result();

		return $execarr;
	}
	/**
	 * Add fund resources
	 * (add fund resources)
	 *
	 * @access	public
	 * @param	sting,int
	 * @return	json
	 */
	public function add_fund_sources($slug,$uid)
	{

		$fundsourcedata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name'					=> $this->input->post("project_fund_sources_name"),
			'role' 					=> $this->input->post("project_fund_sources_role"),
			'amount' 				=> $this->input->post("project_fund_sources_amount"),
			'description' 			=> $this->input->post("project_fund_sources_desc"),
		);
		$response = array();

		if($this->db->insert("exp_proj_fund_sources",$fundsourcedata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Fundsourcesaddedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["isreset"] 	= "yes";
			$response["listdiv"] 	= "fund_sources_form";
			$response["loadurl"] 	= "/projects/form_load/project_fund_sources/view/".$slug."";
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileaddingFundsources'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * update fund resources
	 * (update fund resources)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	json
	 */

	public function update_fund_sources($slug,$uid)
	{
		$fundsourceid = $this->input->post("hdn_project_fund_sources_id");

		$fundsourcedata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name'					=> $this->input->post("project_fund_sources_name"),
			'role' 					=> $this->input->post("project_fund_sources_role"),
			'amount' 				=> $this->input->post("project_fund_sources_amount"),
			'description' 			=> $this->input->post("project_fund_sources_desc"),
		);
		$response = array();
		$this->db->where(array('uid'=> $uid,'slug'=>$slug,'id'=>$fundsourceid));

		if($this->db->update("exp_proj_fund_sources",$fundsourcedata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Fundsourcesupdatedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "fund_sources_form";
			$response["loadurl"] 	= "/projects/form_load/project_fund_sources/view/".$slug."";
			$response['datafatch']	= $fundsourceid;
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdatingFundsources'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}
	/**
	 * delete fund resources
	 * (delete fund resources)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */

	public function delete_fund_sources($delid,$uid)
	{
		$this->db->where(array("id"=>$delid,"uid"=>$uid));
		$response = array();
		$response["remove"]	= FALSE;
		if($qrydelexec = $this->db->delete("exp_proj_fund_sources"))
		{
			$response["remove"]	= TRUE;
		}

		//header('Content-type: application/json');
		echo json_encode($response);

	}


	/**
	 * Get roi
	 * (get roi)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	array
	 */
	public function get_roi($slug,$uid)
	{
		$qryexec = $this->db->get_where("exp_proj_investment_return",array("slug"=>$slug,"uid"=>$uid));
		$execarr = $qryexec->result_array();
		$qryexec->free_result();

		return $execarr;
	}

	/**
	 * Add roi
	 * (add roi)
	 *
	 * @access	public
	 * @param	string,int,optional
	 * @return	json
	 */
	public function add_roi($slug,$uid,$upload='')
	{
		$roidata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name' 		=> $this->input->post("project_roi_name"),
			'percent' 	=> $this->input->post("project_roi_percent"),
			'type' 		=> $this->input->post("project_roi_type"),
			'approach' 	=> $this->input->post("project_roi_approach"),
			'keystudy' 	=> $this->input->post("project_roi_keystudy"),
			'permission' => $this->input->post("project_roi_permission")
		);

		if($upload['error']=='')
		{
			$roidata['keystudy'] = $upload['file_name'];
		}


		$response = array();

		if($this->db->insert("exp_proj_investment_return",$roidata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['ReturnOnInvestmentaddedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["isreset"] 	= "yes";
			$response["listdiv"] 	= "roi_form";
			$response["loadurl"] 	= "/projects/form_load/project_roi/view/".$slug."";
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileaddingReturnonInvestmentsources'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * update roi
	 * (update roi)
	 *
	 * @access	public
	 * @param	add,int
	 * @return	json
	 */
	public function update_roi($slug,$uid,$upload='')
	{
		$roiid = $this->input->post("hdn_project_roi_id");

		$roidata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name' 		=> $this->input->post("project_roi_name"),
			'percent' 	=> $this->input->post("project_roi_percent"),
			'type' 		=> $this->input->post("project_roi_type"),
			'approach' 	=> $this->input->post("project_roi_approach"),
			'keystudy' 	=> $this->input->post("project_roi_keystudy"),
			'permission' => $this->input->post("project_roi_permission")
		);

		if($upload['error']=='')
		{
			$roidata['keystudy'] = $upload['file_name'];
		}

		$response = array();
		$this->db->where(array('uid'=> $uid,'slug'=>$slug,'id'=>$roiid));

		if($this->db->update("exp_proj_investment_return",$roidata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['ReturnOnInvestmentupdatedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "roi_form";
			$response["loadurl"] 	= "/projects/form_load/project_roi/view/".$slug."";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdatingReturnOnInvestment'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * Delete roi
	 * (delete roi)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function delete_roi($delid,$uid)
	{
		$unlinkstatus = $this->unlink_files($delid,$uid,'keystudy','exp_proj_investment_return',PROJECT_IMAGE_PATH);
		if($unlinkstatus)
		{
			$this->db->where(array("id"=>$delid,"uid"=>$uid));
			$response = array();
			$response["remove"]	= FALSE;
			if($qrydelexec = $this->db->delete("exp_proj_investment_return"))
			{
				$response["remove"]	= TRUE;
			}

			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}


	/**
	 * Get Cretical participant
	 * (Get Cretical participant)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	array
	 */
	public function get_critical_participants($slug,$uid)
	{
		$qryexec = $this->db->get_where("exp_proj_participant_critical",array("slug"=>$slug,"uid"=>$uid));
		$execarr = $qryexec->result_array();
		$qryexec->free_result();

		return $execarr;
	}

	/**
	 * Add Cretical participant
	 * (add Cretical participant)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	json
	 */
	public function add_critical_participants($slug,$uid)
	{

		$criticalparticipantdata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name' 			=> $this->input->post("project_critical_participants_name"),
			'role' 			=> $this->input->post("project_critical_participants_role"),
			'description'	=> $this->input->post("project_critical_participants_desc")
		);
		$response = array();

		if($this->db->insert("exp_proj_participant_critical",$criticalparticipantdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['CriticalParticipantaddedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["isreset"] 	= "yes";
			$response["listdiv"] 	= "critical_participants_form";
			$response["loadurl"] 	= "/projects/form_load/project_critical_participants/view/".$slug."";
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileaddingCriticalParticipant'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * update Cretical participant
	 * (update Cretical participant)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	json
	 */
	public function update_critical_participants($slug,$uid)
	{
		$criticalparticipantid = $this->input->post("hdn_project_critical_participants_id");

		$criticalparticipantdata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name' 			=> $this->input->post("project_critical_participants_name"),
			'role' 			=> $this->input->post("project_critical_participants_role"),
			'description'	=> $this->input->post("project_critical_participants_desc")
		);

		$response = array();
		$this->db->where(array('uid'=> $uid,'slug'=>$slug,'id'=>$criticalparticipantid));

		if($this->db->update("exp_proj_participant_critical",$criticalparticipantdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['CriticalParticipantupdatedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "critical_participants_form";
			$response["loadurl"] 	= "/projects/form_load/project_critical_participants/view/".$slug."";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdatingCriticalParticipant'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * Delete Cretical participant
	 * (delete Cretical participant)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function delete_critical_participants($delid,$uid)
	{
		$this->db->where(array("id"=>$delid,"uid"=>$uid));
		$response = array();
		$response["remove"]	= FALSE;
		if($qrydelexec = $this->db->delete("exp_proj_participant_critical"))
		{
			$response["remove"]	= TRUE;
		}

		//header('Content-type: application/json');
		echo json_encode($response);

	}


	/**
	 * Get project regulatory
	 * (Get project regulatory)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	array
	 */

	public function get_project_regulatory($slug,$uid)
	{
		$qryexec = $this->db->get_where("exp_proj_regulatory",array("slug"=>$slug,"uid"=>$uid));
		$execarr = $qryexec->result_array();
		$qryexec->free_result();

		return $execarr;
	}

	/**
	 * Add project regulatory
	 * (add project regulatory)
	 *
	 * @access	public
	 * @param	string,int,optional
	 * @return	json
	 */
	public function add_regulatory($slug,$uid,$upload='')
	{
		$regulatorydata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'description'	=> $this->input->post("project_regulatory_desc"),
			'permission'	=> $this->input->post("project_regulatory_permissions")
		);
		if($upload['error']=='')
		{
			$regulatorydata['file'] = $upload['file_name'];
		}

		$response = array();

		if($this->db->insert("exp_proj_regulatory",$regulatorydata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Regulatoryaddedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["isreset"] 	= "yes";
			$response["listdiv"] 	= "regulatory_form";
			$response["loadurl"] 	= "/projects/form_load/project_regulatory/view/".$slug."";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileaddingRegulatory'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);


	}

	/**
	 * Update project regulatory
	 * (Update project regulatory)
	 *
	 * @access	public
	 * @param	string,int,optional
	 * @return	json
	 */
	public function update_regulatory($slug,$uid,$upload='')
	{
		$ragulatoryid = $this->input->post("hdn_project_regulatory_id");
		$regulatorydata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'file' 			=> $this->input->post("project_regulatory_filename"),
			'description'	=> $this->input->post("project_regulatory_desc"),
			'permission'	=> $this->input->post("project_regulatory_permission")
		);

		if($upload['error']=='')
		{
			$regulatorydata['file'] = $upload['file_name'];
		}

		$response = array();
		$this->db->where(array('uid'=> $uid,'slug'=>$slug,'id'=>$ragulatoryid));

		if($this->db->update("exp_proj_regulatory",$regulatorydata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Regulatoryupdatedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "regulatory_form";
			$response["loadurl"] 	= "/projects/form_load/project_regulatory/view/".$slug."";
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdatingRegulatory'];
			$response["remove"] 	= true;
		}
		//header('Content-type: application/json');
		echo json_encode($response);

	}

	/**
	 * Delete project regulatory
	 * (delete project regulatory)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */

	public function delete_regulatory($delid,$uid)
	{
		$unlinkstatus = $this->unlink_files($delid,$uid,'file','exp_proj_regulatory',PROJECT_IMAGE_PATH);
		if($unlinkstatus)
		{
			$this->db->where(array("id"=>$delid,"uid"=>$uid));
			$response = array();
			$response["remove"]	= FALSE;
			if($qrydelexec = $this->db->delete("exp_proj_regulatory"))
			{
				$response["remove"]	= TRUE;
			}

			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	 * Get public participants
	 * (Get public participants)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	array
	 */
	public function get_participants_public($slug,$uid)
	{
		$qryexec = $this->db->get_where("exp_proj_participant_public",array("slug"=>$slug,"uid"=>$uid));
		$execarr = $qryexec->result_array();
		$qryexec->free_result();

		return $execarr;
	}


	/**
	 * Add public participants
	 * (add public participants)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	json
	 */
	public function add_participants_public($slug,$uid)
	{
		$participantspublicdata= array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name' 			=> $this->input->post("project_participants_public_name"),
			'type'	=> $this->input->post("project_participants_public_type"),
			'description'	=> $this->input->post("project_participants_public_desc"),
			'permission'	=> $this->input->post("project_participants_public_permissions")

		);
		$response = array();

		if($this->db->insert("exp_proj_participant_public",$participantspublicdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['PublicParticipantaddedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["isreset"] 	= "yes";
			$response["listdiv"] 	= "participants_public_form";
			$response["loadurl"] 	= "/projects/form_load/participants_public/view/".$slug."";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileaddingPublicParticipant'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);

	}

	/**
	 * update public participants
	 * (update public participants)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	json
	 */
	public function update_participants_public($slug,$uid)
	{
		$participantspublicid = $this->input->post("hdn_participants_public_id");
		$participantspublicdata= array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name' 			=> $this->input->post("project_participants_public_name"),
			'type'			=> $this->input->post("project_participants_public_type"),
			'description'	=> $this->input->post("project_participants_public_desc"),
			'permission'	=> $this->input->post("project_participants_political_permission")

		);
		$response = array();
		$this->db->where(array('uid'=> $uid,'slug'=>$slug,'id'=>$participantspublicid));

		if($this->db->update("exp_proj_participant_public",$participantspublicdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Public Participant updated successfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "participants_public_form";
			$response["loadurl"] 	= "/projects/form_load/participants_public/view/".$slug."";
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdatingPublicParticipant'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * Delete public participants
	 * (delete public participants)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function delete_participants_public($delid,$uid)
	{
		$this->db->where(array("id"=>$delid,"uid"=>$uid));
		$response = array();
		$response["remove"]	= FALSE;
		if($qrydelexec = $this->db->delete("exp_proj_participant_public"))
		{
			$response["remove"]	= TRUE;
		}

		//header('Content-type: application/json');
		echo json_encode($response);

	}

	/**
	 * get political participants
	 * (get political participants)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	array
	 */
	public function get_participants_political($slug,$uid)
	{
		$qryexec = $this->db->get_where("exp_proj_participant_political",array("slug"=>$slug,"uid"=>$uid));
		$execarr = $qryexec->result_array();
		$qryexec->free_result();

		return $execarr;
	}

	/**
	 * Add political participants
	 * (add political participants)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	json
	 */
	public function add_participants_political($slug,$uid)
	{
		$participantspoliticaldata= array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name' 			=> $this->input->post("project_participants_political_name"),
			'type'	=> $this->input->post("project_participants_political_type"),
			'description'	=> $this->input->post("project_participants_political_desc"),
			'permission'	=> $this->input->post("project_participants_political_permissions")


		);
		$response = array();

		if($this->db->insert("exp_proj_participant_political",$participantspoliticaldata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['PoliticalParticipantaddedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["isreset"] 	= "yes";
			$response["listdiv"] 	= "participants_political_form";
			$response["loadurl"] 	= "/projects/form_load/participants_political/view/".$slug."";
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileaddingPoliticalParticipant'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);


	}
	/**
	 * update political participants
	 * (update political participants)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	json
	 */
	public function update_participants_political($slug,$uid)
	{
		$participantspoliticalid = $this->input->post("hdn_participants_political_id");
		$participantspoliticaldata= array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name' 			=> $this->input->post("project_participants_political_name"),
			'type'	=> $this->input->post("project_participants_political_type"),
			'description'	=> $this->input->post("project_participants_political_desc"),
			'permission'	=> $this->input->post("project_participants_political_permission")


		);
		$response = array();

		$this->db->where(array('uid'=> $uid,'slug'=>$slug,'id'=>$participantspoliticalid));

		if($this->db->update("exp_proj_participant_political",$participantspoliticaldata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['PoliticalParticipantupdatedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "participants_political_form";
			$response["loadurl"] 	= "/projects/form_load/participants_political/view/".$slug."";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdatingPoliticalParticipant'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);

	}

	/**
	 * delete political participants
	 * (delete political participants)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function delete_participants_political($delid,$uid)
	{
		$this->db->where(array("id"=>$delid,"uid"=>$uid));
		$response = array();
		$response["remove"]	= FALSE;
		if($qrydelexec = $this->db->delete("exp_proj_participant_political"))
		{
			$response["remove"]	= TRUE;
		}

		//header('Content-type: application/json');
		echo json_encode($response);

	}

	/**
	 * get companies
	 * (get companies)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	array
	 */
	public function get_participants_companies($slug,$uid)
	{
		$qryexec = $this->db->get_where("exp_proj_participant_company",array("slug"=>$slug,"uid"=>$uid));
		$execarr = $qryexec->result_array();
		$qryexec->free_result();

		return $execarr;
	}

	/**
	 * Add companies
	 * (add companies)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	json
	 */
	public function add_participants_companies($slug,$uid)
	{
		$participantscompanydata= array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name' 			=> $this->input->post("project_participants_companies_name"),
			'role'	=> $this->input->post("project_participants_companies_role"),
			'description'	=> $this->input->post("project_participants_companies_desc"),
			'permission'	=> $this->input->post("project_participants_companies_permissions")


		);
		$response = array();

		if($this->db->insert("exp_proj_participant_company",$participantscompanydata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['CompanyParticipantaddedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["isreset"] 	= "yes";
			$response["listdiv"] 	= "participants_company_form";
			$response["loadurl"] 	= "/projects/form_load/participants_companies/view/".$slug."";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileaddingCompanyParticipant'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);


	}

	/**
	 * update company
	 * (update company)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	json
	 */
	public function update_participants_companies($slug,$uid)
	{
		$participantscompanyid = $this->input->post("hdn_participants_companies_id");
		$participantscompanydata= array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name' 			=> $this->input->post("project_participants_companies_name"),
			'role'	=> $this->input->post("project_participants_companies_role"),
			'description'	=> $this->input->post("project_participants_companies_desc"),
			'permission'	=> $this->input->post("project_participants_companies_permission")


		);
		$response = array();
		$this->db->where(array('uid'=> $uid,'slug'=>$slug,'id'=>$participantscompanyid));
		if($this->db->update("exp_proj_participant_company",$participantscompanydata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['CompanyParticipantupdatedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "participants_company_form";
			$response["loadurl"] 	= "/projects/form_load/participants_companies/view/".$slug."";
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdatingCompanyParticipant'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);

	}


	/**
	 * Delete company
	 * (delete company)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */

	public function delete_participants_companies($delid,$uid)
	{
		$this->db->where(array("id"=>$delid,"uid"=>$uid));
		$response = array();
		$response["remove"]	= FALSE;
		if($qrydelexec = $this->db->delete("exp_proj_participant_company"))
		{
			$response["remove"]	= TRUE;
		}

		//header('Content-type: application/json');
		echo json_encode($response);

	}


	/**
	 * Get owner
	 * (Get owner)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	array
	 */
	public function get_participants_owners($slug,$uid)
	{
		$qryexec = $this->db->get_where("exp_proj_participant_owner",array("slug"=>$slug,"uid"=>$uid));
		$execarr = $qryexec->result_array();
		$qryexec->free_result();

		return $execarr;
	}


	/**
	 * add owner
	 * (add owner)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	json
	 */
	public function add_participants_owners($slug,$uid)
	{
		$participantsownersdata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name' 			=> $this->input->post("project_participants_owners_name"),
			'type'	=> $this->input->post("project_participants_owners_type"),
			'description'	=> $this->input->post("project_participants_owners_desc"),
			'permission'	=> $this->input->post("project_participants_owners_permissions")


		);
		$response = array();

		if($this->db->insert("exp_proj_participant_owner",$participantsownersdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['OwnerParticipantaddedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["isreset"] 	= "yes";
			$response["listdiv"] 	= "participants_owners_form";
			$response["loadurl"] 	= "/projects/form_load/participants_owners/view/".$slug."";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileaddingOwnerParticipant'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);


	}

	/**
	 * update company
	 * (update company)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	json
	 */
	public function update_participants_owners($slug,$uid)
	{
		$participantsownersid = $this->input->post("hdn_participants_owners_id");
		$participantsownersdata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name' 			=> $this->input->post("project_participants_owners_name"),
			'type'	=> $this->input->post("project_participants_owners_type"),
			'description'	=> $this->input->post("project_participants_owners_desc"),
			'permission'	=> $this->input->post("project_participants_owners_permission")
		);
		$response = array();
		$this->db->where(array('uid'=> $uid,'slug'=>$slug,'id'=>$participantsownersid));
		if($this->db->update("exp_proj_participant_owner",$participantsownersdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['OwnerParticipantupdatedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "participants_owners_form";
			$response["loadurl"] 	= "/projects/form_load/participants_owners/view/".$slug."";
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdatingCompanyParticipant'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
		$response = array();

	}

	/**
	 * Delete owners
	 * (update owners)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function delete_participants_owners($delid,$uid)
	{
		$this->db->where(array("id"=>$delid,"uid"=>$uid));
		$response = array();
		$response["remove"]	= FALSE;
		if($qrydelexec = $this->db->delete("exp_proj_participant_owner"))
		{
			$response["remove"]	= TRUE;
		}

		//header('Content-type: application/json');
		echo json_encode($response);

	}

	/**
	 * Get machinery
	 * (get machinery)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	array
	 */
	public function get_machinery($slug,$uid)
	{
		$qryexec = $this->db->get_where("exp_proj_machinery",array("slug"=>$slug,"uid"=>$uid));
		$execarr = $qryexec->result_array();
		$qryexec->free_result();

		return $execarr;
	}

	/**
	 * Add machinery
	 * (add machinery)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	json
	 */
	public function add_machinery($slug,$uid)
	{
		$machinerydata= array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name' 					=> $this->input->post("project_machinery_name"),
			'procurementprocess'	=> $this->input->post("project_machinery_process"),
			'financialinfo'			=> $this->input->post("project_machinery_financial_info"),
			'permission'			=> $this->input->post("project_machinery_permission")


		);
		$response = array();

		if($this->db->insert("exp_proj_machinery",$machinerydata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Machineryaddedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["isreset"] 	= "yes";
			$response["listdiv"] 	= "machinery_form";
			$response["loadurl"] 	= "/projects/form_load/project_machinery/view/".$slug."";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileaddingMachinery'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * Update machinery
	 * (update machinery)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	json
	 */
	public function update_machinery($slug,$uid)
	{
		$machineryid = $this->input->post("hdn_project_machinery_id");
		$machinerydata= array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name' 					=> $this->input->post("project_machinery_name"),
			'procurementprocess'	=> $this->input->post("project_machinery_process"),
			'financialinfo'			=> $this->input->post("project_machinery_financial_info"),
			'permission'			=> $this->input->post("project_machinery_permission")


		);

		$response = array();
		$this->db->where(array('uid'=> $uid,'slug'=>$slug,'id'=>$machineryid));
		if($this->db->update("exp_proj_machinery",$machinerydata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Machineryupdatedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "machinery_form";
			$response["loadurl"] 	= "/projects/form_load/project_machinery/view/".$slug."";
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdatingMachinery'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);

	}

	/**
	 * Delete machinery
	 * (delete machinery)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function delete_machinery($delid,$uid)
	{
		$this->db->where(array("id"=>$delid,"uid"=>$uid));
		$response = array();
		$response["remove"]	= FALSE;
		if($qrydelexec = $this->db->delete("exp_proj_machinery"))
		{
			$response["remove"]	= TRUE;
		}

		//header('Content-type: application/json');
		echo json_encode($response);

	}

	/**
	 * Get Procurement technology
	 * (Get Procurement technology)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	array
	 */
	public function get_procurement_technology($slug,$uid)
	{
		$qryexec = $this->db->get_where("exp_proj_procurement_technology",array("slug"=>$slug,"uid"=>$uid));
		$execarr = $qryexec->result_array();
		$qryexec->free_result();

		return $execarr;
	}

	/**
	 * Add Procurement technology
	 * (add Procurement technology)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	json
	 */
	public function add_procurement_technology($slug,$uid)
	{
		$protechdata= array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name' 					=> $this->input->post("project_procurement_technology_name"),
			'procurementprocess'	=> $this->input->post("project_procurement_technology_process"),
			'financialinfo'			=> $this->input->post("project_procurement_technology_financial_info"),
			'permission'			=> $this->input->post("project_procurement_technology_permission")


		);
		$response = array();

		if($this->db->insert("exp_proj_procurement_technology",$protechdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['ProcurementTechnologyaddedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["isreset"] 	= "yes";
			$response["listdiv"] 	= "procurement_technology_form";
			$response["loadurl"] 	= "/projects/form_load/procurement_technology/view/".$slug."";
			//$response["loaddata"] 	= $this->load->view("loader",array("val"=>$executivedata,"formname"=>"project_executives","slug"=>$slug));
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileaddingProcurementTechnology'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);

	}

	/**
	 * Update Procurement technology
	 * (update Procurement technology)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	json
	 */

	public function update_procurement_technology($slug,$uid)
	{
		$protechid = $this->input->post("hdn_procurement_technology_id");

		$protechdata= array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name' 					=> $this->input->post("project_procurement_technology_name"),
			'procurementprocess'	=> $this->input->post("project_procurement_technology_process"),
			'financialinfo'			=> $this->input->post("project_procurement_technology_financial_info"),
			'permission'			=> $this->input->post("project_procurement_technology_permission")


		);
		$response = array();

		$this->db->where(array('uid'=> $uid,'slug'=>$slug,'id'=>$protechid));

		if($this->db->update("exp_proj_procurement_technology",$protechdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	=  $this->dataLang['lang']['ProcurementTechnologyupdatedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "procurement_technology_form";
			$response["loadurl"] 	= "/projects/form_load/procurement_technology/view/".$slug."";
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	=  $this->dataLang['lang']['ErrorwhileupdatingProcurementTechnology'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);

	}

	/**
	 * Delete Procurement technology
	 * (delete Procurement technology)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function delete_procurement_technology($delid,$uid)
	{
		$this->db->where(array("id"=>$delid,"uid"=>$uid));
		$response = array();
		$response["remove"]	= FALSE;
		if($qrydelexec = $this->db->delete("exp_proj_procurement_technology"))
		{
			$response["remove"]	= TRUE;
		}

		//header('Content-type: application/json');
		echo json_encode($response);

	}

	/**
	 * Get Procurement services
	 * (get Procurement services)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	array
	 */
	public function get_procurement_services($slug,$uid)
	{
		$qryexec = $this->db->get_where("exp_proj_procurement_services",array("slug"=>$slug,"uid"=>$uid));
		$execarr = $qryexec->result_array();
		$qryexec->free_result();

		return $execarr;
	}

	/**
	 * Add Procurement services
	 * (add Procurement services)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	json
	 */
	public function add_procurement_services($slug,$uid)
	{
		$proservicesdata= array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name' 					=> $this->input->post("project_procurement_services_name"),
			'type' 					=> $this->input->post("project_procurement_services_type"),
			'procurementprocess'	=> $this->input->post("project_procurement_services_process"),
			'financialinfo'			=> $this->input->post("project_procurement_services_financial_info"),
			'permission'			=> $this->input->post("project_procurement_services_permission")


		);
		$response = array();

		if($this->db->insert("exp_proj_procurement_services",$proservicesdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['ProcurementServiceaddedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["isreset"] 	= "yes";
			$response["listdiv"] 	= "procurement_services_form";
			$response["loadurl"] 	= "/projects/form_load/procurement_services/view/".$slug."";
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileaddingProcurementServices'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);

	}

	/**
	 * Update Procurement services
	 * (update Procurement services)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	json
	 */
	public function update_procurement_services($slug,$uid)
	{
		$proservicesid = $this->input->post("hdn_procurement_services_id");
		$proservicesdata= array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'name' 					=> $this->input->post("project_procurement_services_name"),
			'type' 					=> $this->input->post("project_procurement_services_type"),
			'procurementprocess'	=> $this->input->post("project_procurement_services_process"),
			'financialinfo'			=> $this->input->post("project_procurement_services_financial_info"),
			'permission'			=> $this->input->post("project_procurement_services_permission")


		);
		$response = array();

		$this->db->where(array('uid'=> $uid,'slug'=>$slug,'id'=>$proservicesid));

		if($this->db->update("exp_proj_procurement_services",$proservicesdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['ProcurementServiceupdatedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "procurement_services_form";
			$response["loadurl"] 	= "/projects/form_load/procurement_services/view/".$slug."";
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdatingProcurementServices'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);
	}

	/**
	 * Delete Procurement services
	 * (delete Procurement services)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function delete_procurement_services($delid,$uid)
	{
		$this->db->where(array("id"=>$delid,"uid"=>$uid));
		$response = array();
		$response["remove"]	= FALSE;
		if($qrydelexec = $this->db->delete("exp_proj_procurement_services"))
		{
			$response["remove"]	= TRUE;
		}

		//header('Content-type: application/json');
		echo json_encode($response);

	}

	/**
	 * Get Project files
	 * (get projct files)
	 *
	 * @access	public
	 * @param	string,int
	 * @return	array
	 */
	public function get_project_files($slug,$uid)
	{
		$qryexec = $this->db->get_where("exp_proj_files",array("slug"=>$slug,"uid"=>$uid));
		$execarr = $qryexec->result_array();
		$qryexec->free_result();

		return $execarr;
	}

	/**
	 * Add Project files
	 * (Add projct files)
	 *
	 * @access	public
	 * @param	string,int,optional
	 * @return	json
	 */
	public function add_project_files($slug,$uid,$upload='')
	{
		$filedata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'description'	=> $this->input->post("project_files_desc"),
			'permission'	=> $this->input->post("files_permission"),
			'dateofuploading' => date('Y-m-d')
		);
		if($upload['error']=='')
		{
			$filedata['file'] 		= $upload['file_name'];
			$filedata['filesize']	= $upload['file_size'];
		}

		$response = array();

		if($this->db->insert("exp_proj_files",$filedata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Fileaddedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["isreset"] 	= "yes";
			$response["listdiv"] 	= "files_form";
			$response["loadurl"] 	= "/projects/form_load/project_files/view/".$slug."";
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileaddingthisFiles'];
			$response["remove"] 	= true;

		}
		echo json_encode($response);

	}

	/**
	 * Update Project files
	 * (update projct files)
	 *
	 * @access	public
	 * @param	string,int,optional
	 * @return	josn
	 */
	public function update_project_files($slug,$uid,$upload='')
	{
		$fileid = $this->input->post("hdn_project_files_id");
		$filedata = array(
			'pid'	=> $this->check_user_project($slug,$uid),
			'slug'	=> $slug,
			'uid'	=> $uid,
			'description'	=> $this->input->post("project_files_desc"),
			'permission'	=> $this->input->post("project_files_permission")
		);

		if($upload['error']=='')
		{
			$filedata['file'] = $upload['file_name'];
			if(isset($upload["file_size"]) && $upload["file_size"] != "")
			{
				$filedata['filesize']	= $upload['file_size'];
			}
		}


		$response = array();
		$this->db->where(array('uid'=> $uid,'slug'=>$slug,'id'=>$fileid));

		if($this->db->update("exp_proj_files",$filedata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Fileupdatedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["listdiv"] 	= "files_form";
			$response["loadurl"] 	= "/projects/form_load/project_files/view/".$slug."";
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdatingthisFiles'];
			$response["remove"] 	= true;

		}
		echo json_encode($response);

	}

	/**
	 * Delete Project files
	 * (Delete projct files)
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	public function delete_project_files($delid,$uid)
	{
		$unlinkstatus = $this->unlink_files($delid,$uid,'file','exp_proj_files',PROJECT_IMAGE_PATH);
		if($unlinkstatus)
		{
			$this->db->where(array("id"=>$delid,"uid"=>$uid));
			$response = array();
			$response["remove"]	= FALSE;
			if($qrydelexec = $this->db->delete("exp_proj_files"))
			{
				$response["remove"]	= TRUE;
			}

			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/*********************************************************************************************************
	 * Load functions collect each table data from database and prepare array for GET FUNCTION for ajax call
	 * (Load functions for listing above each add forms dynamically)
	 *********************************************************************************************************/

	public function load_executive($formname,$type,$slug,$uid)
	{
		$array_load['executive_data'] 	= $this->get_executives($slug,$uid);
		$array_load['formname']		  	= $formname;
		$array_load['type']		  		= $type;
		$array_load['slug']		  		= $slug;

		return $array_load;
	}

	public function load_organization($formname,$type,$slug,$uid)
	{
		$array_load['organization_data'] 	= $this->get_organizations($slug,$uid);
		$array_load['formname']		  	= $formname;
		$array_load['type']		  		= $type;
		$array_load['slug']		  		= $slug;

		return $array_load;
	}

	public function load_engineering($formname,$type,$slug,$uid)
	{
		$array_load['engineering_data'] 	= $this->get_engineering($slug,$uid);
		$array_load['formname']		  	= $formname;
		$array_load['type']		  		= $type;
		$array_load['slug']		  		= $slug;

		return $array_load;
	}

	public function load_map_point($formname,$type,$slug,$uid)
	{
		$array_load['map_point_data'] 	= $this->get_map_point($slug,$uid);
		$array_load['formname']		  	= $formname;
		$array_load['type']		  		= $type;
		$array_load['slug']		  		= $slug;

		return $array_load;
	}

	public function load_design_issue($formname,$type,$slug,$uid)
	{
		$array_load['design_issue_data'] 	= $this->get_design_issue($slug,$uid);
		$array_load['formname']		  	= $formname;
		$array_load['type']		  		= $type;
		$array_load['slug']		  		= $slug;

		return $array_load;
	}

	public function load_environment($formname,$type,$slug,$uid)
	{
		$array_load['environment_data'] 	= $this->get_environment($slug,$uid);
		$array_load['formname']		  	= $formname;
		$array_load['type']		  		= $type;
		$array_load['slug']		  		= $slug;

		return $array_load;
	}

	public function load_studies($formname,$type,$slug,$uid)
	{
		$array_load['studies_data'] 	= $this->get_studies($slug, $uid);
		$array_load['formname']		  	= $formname;
		$array_load['type']		  		= $type;
		$array_load['slug']		  		= $slug;

		return $array_load;
	}

	public function load_fund_sources($formname,$type,$slug,$uid)
	{
		$array_load['fund_sources_data'] 	= $this->get_fund_sources($slug,$uid);
		$array_load['formname']		  		= $formname;
		$array_load['type']		  			= $type;
		$array_load['slug']		  			= $slug;

		return $array_load;
	}

	public function load_roi($formname,$type,$slug,$uid)
	{
		$array_load['roi_data'] 		= $this->get_roi($slug,$uid);
		$array_load['formname']		  	= $formname;
		$array_load['type']		  		= $type;
		$array_load['slug']		  		= $slug;

		return $array_load;
	}

	public function load_critical_participants($formname,$type,$slug,$uid)
	{
		$array_load['critical_participants_data'] 	= $this->get_critical_participants($slug,$uid);
		$array_load['formname']		  				= $formname;
		$array_load['type']		  					= $type;
		$array_load['slug']		  					= $slug;

		return $array_load;
	}

	public function load_project_regulatory($formname,$type,$slug,$uid)
	{
		$array_load['regulatory_data'] 				= $this->get_project_regulatory($slug,$uid);
		$array_load['formname']		  				= $formname;
		$array_load['type']		  					= $type;
		$array_load['slug']		  					= $slug;

		return $array_load;
	}

	public function load_participants_public($formname,$type,$slug,$uid)
	{
		$array_load['participants_public_data'] 	= $this->get_participants_public($slug,$uid);
		$array_load['formname']		  				= $formname;
		$array_load['type']		  					= $type;
		$array_load['slug']		  					= $slug;
		return $array_load;
	}

	public function load_participants_political($formname,$type,$slug,$uid)
	{
		$array_load['participants_political_data'] 	= $this->get_participants_political($slug,$uid);
		$array_load['formname']		  				= $formname;
		$array_load['type']		  					= $type;
		$array_load['slug']		  					= $slug;

		return $array_load;
	}

	public function load_participants_companies($formname,$type,$slug,$uid)
	{
		$array_load['participants_companies_data'] 	= $this->get_participants_companies($slug,$uid);
		$array_load['formname']		  				= $formname;
		$array_load['type']		  					= $type;
		$array_load['slug']		  					= $slug;

		return $array_load;
	}

	public function load_participants_owners($formname,$type,$slug,$uid)
	{
		$array_load['participants_owners_data'] 	= $this->get_participants_owners($slug,$uid);
		$array_load['formname']		  				= $formname;
		$array_load['type']		  					= $type;
		$array_load['slug']		  					= $slug;

		return $array_load;
	}

	public function load_project_machinery($formname,$type,$slug,$uid)
	{
		$array_load['machinery_data'] 	= $this->get_machinery($slug,$uid);
		$array_load['formname']		  				= $formname;
		$array_load['type']		  					= $type;
		$array_load['slug']		  					= $slug;

		return $array_load;
	}

	public function load_procurement_technology($formname,$type,$slug,$uid)
	{
		$array_load['procurement_technology_data'] 	= $this->get_procurement_technology($slug,$uid);
		$array_load['formname']		  				= $formname;
		$array_load['type']		  					= $type;
		$array_load['slug']		  					= $slug;

		return $array_load;
	}

	public function load_procurement_services($formname,$type,$slug,$uid)
	{
		$array_load['procurement_services_data'] 	= $this->get_procurement_services($slug,$uid);
		$array_load['formname']		  				= $formname;
		$array_load['type']		  					= $type;
		$array_load['slug']		  					= $slug;

		return $array_load;
	}

	public function load_project_files($formname,$type,$slug,$uid)
	{
		$array_load['project_files_data'] 	= $this->get_project_files($slug,$uid);
		$array_load['formname']		  				= $formname;
		$array_load['type']		  					= $type;
		$array_load['slug']		  					= $slug;

		return $array_load;
	}
	public function load_project_comment($formname,$type,$slug,$uid)
	{
		$array_load['project_comment_data'] 	= $this->get_project_comment($slug,$uid);
		$array_load['formname']		  				= $formname;
		$array_load['type']		  					= $type;
		$array_load['slug']		  					= $slug;

		return $array_load;
	}

	/*****************************************************************************************************
	 * GET Functions collect each array set define above and sent to the loder.php in view directory
	 * (Load functions for listing above each add forms dynamically)
	 *****************************************************************************************************/

	public function get_fundamental_data($slug,$uid)
	{
		$fundamental_data = array();
		$this->db->select(array('pid','stage','uid','projectname','slug','fundamental_legal'));
		$qryproj = $this->db->get_where("exp_projects",array("slug"=>$slug,"uid"=>$uid));
		$fundamental_data = $qryproj->row_array();
		$qryproj->free_result();
		$fundamental_data['engineering'] = $this->get_engineering($slug,$uid);
		$fundamental_data['map_point'] = $this->get_map_point($slug,$uid);
		$fundamental_data['design_issue'] = $this->get_design_issue($slug,$uid);
		$fundamental_data['environment'] = $this->get_environment($slug,$uid);
		$fundamental_data['studies'] = $this->get_studies($slug,$uid);
		$fundamental_data['totalfundamental'] = (
            count($fundamental_data['engineering']) +
            count($fundamental_data['map_point']) +
            count($fundamental_data['design_issue']) +
            count($fundamental_data['environment']) +
            count($fundamental_data['studies'])
        );

		return $fundamental_data;
	}

	public function get_financial_data($slug,$uid)
	{
		$financial_data = array();
		$this->db->select(array('pid','stage','uid','projectname','slug'));
		$qryproj = $this->db->get_where("exp_projects",array("slug"=>$slug,"uid"=>$uid));
		$financial_data = $qryproj->row_array();
		$qryproj->free_result();
		$financial_data['financial'] = $this->get_financial($slug,$uid);
		$financial_data['fund_sources'] = $this->get_fund_sources($slug,$uid);
		$financial_data['roi'] = $this->get_roi($slug,$uid);
		$financial_data['critical_participants'] = $this->get_critical_participants($slug,$uid);
		$financial_data['totalfinancial'] = array_sum([
            count_if_set($financial_data['financial']),
            count_if_set($financial_data['fund_sources']),
            count_if_set($financial_data['roi']),
            count_if_set($financial_data['critical_participants'])
        ]);

		return $financial_data;

	}

	public function get_regulatory_data($slug,$uid)
	{
		$regulatory_data = array();

		$this->db->select(array('pid','stage','uid','projectname','slug'));
		$qryproj = $this->db->get_where("exp_projects",array("slug"=>$slug,"uid"=>$uid));
		$regulatory_data = $qryproj->row_array();
		$qryproj->free_result();
		$regulatory_data['regulatory'] = $this->get_project_regulatory($slug,$uid);
		$regulatory_data['totalregulatory'] = (count($regulatory_data['regulatory']));


		return $regulatory_data;

	}
	public function get_participants_data($slug,$uid)
	{
		$participants_data = array();

		$this->db->select(array('pid','stage','uid','projectname','slug'));
		$qryproj = $this->db->get_where("exp_projects",array("slug"=>$slug,"uid"=>$uid));
		$participants_data = $qryproj->row_array();
		$qryproj->free_result();
		$participants_data['public'] = $this->get_participants_public($slug,$uid);
		$participants_data['political'] = $this->get_participants_political($slug,$uid);
		$participants_data['companies'] = $this->get_participants_companies($slug,$uid);
		$participants_data['owners'] = $this->get_participants_owners($slug,$uid);
		$participants_data['totalparticipants'] = (count($participants_data['public'])+count($participants_data['political'])+count($participants_data['companies'])+count($participants_data['owners']));

		return $participants_data;
	}
	
    public function get_procurement_data($slug,$uid)
	{
		$procurement_data = array();

		$this->db->select(array('pid','stage','uid','projectname','slug', 'procurement_criteria', 'procurement_date'));
		$qryproj = $this->db->get_where("exp_projects", array("slug"=>$slug,"uid"=>$uid));
		$procurement_data = $qryproj->row_array();
		$qryproj->free_result();

		$procurement_data['machinery'] = $this->get_machinery($slug,$uid);
		$procurement_data['procurement_technology'] = $this->get_procurement_technology($slug,$uid);
		$procurement_data['procurement_services'] = $this->get_procurement_services($slug,$uid);
		$procurement_data['totalprocurement'] = (count($procurement_data['machinery'])+count($procurement_data['procurement_technology'])+count($procurement_data['procurement_services']));

		return $procurement_data;

	}

	public function get_files_data($slug,$uid)
	{
		$files_data = array();

		$this->db->select(array('pid','stage','uid','projectname','slug'));
		$qryproj = $this->db->get_where("exp_projects",array("slug"=>$slug,"uid"=>$uid));
		$files_data = $qryproj->row_array();
		$qryproj->free_result();
		$files_data['files'] = $this->get_project_files($slug,$uid);
		$files_data['totalfiles'] = count($files_data['files']);


		return $files_data;
	}


	/**
	 * function for unlink
	 *
	 *
	 * @access	public
	 * @param	int,int
	 * @return	json
	 */
	 public function unlink_files($dbid,$uid,$dbfield,$dbtable,$path)
	 {
	 	$this->db->select($dbfield);
		$qryfile = $this->db->get_where($dbtable,array("id"=>$dbid,"uid"=>$uid));
		$files = $qryfile->row_array();
		$qryfile->free_result();

	 	if(isset($files[$dbfield])&& $files[$dbfield] !='')
		{
		 	$unlink_path = "./".$path.$files[$dbfield];
		 	if(file_exists($unlink_path))
		 	{
		 		$unlink_file = unlink($unlink_path);
				return $unlink_file;
		 	}
		 	else
		 	{
		 		return true;
		 	}
		 }
		 else
		 {
		 	return true;
		 }
	 }

	 /**
	 * Get Ads
	 * (get list of ads added in db)
	 *
	 * @access	public
	 * @return	array
	 */
	 public function get_ad_data()
	 {
	 	$adarr = array();
	 	$this->db->select(array("adimage","adurl"));
	 	$qryad = $this->db->get_where("exp_advertisement",array("status"=>"1"));
	 	$totalad = $qryad->num_rows();
	 	$adarr["data"] = $qryad->result_array();

	 	$adarr["totalad"] = $totalad;

	 	return $adarr;
	 }

	 /**
	 * Add comment
	 * (Add comment to project )
	 *
	 * @access	public
	 * @param strin
	 * @return	json
	 */
	 public function add_comment($slug)
	 {
		$response = array();
		$insertdata = array(
			"uid"=>sess_var("uid"),
			"comment"=>$this->input->post("comment"),
			"slug"=>$slug,
			"commentdate" => date("Y-m-d H:i:s"),
			"pid"=>$this->get_uid_from_slug($slug)
		);


		if($this->db->insert("exp_proj_comment",$insertdata))
		{
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']['Commentaddedsuccessfully'];
			$response["remove"] 	= true;
			$response["isload"] 	= "yes";
			$response["isreset"] 	= "yes";
			$response["loadurl"] 	= "/projects/form_load/project_comment/view/".$slug."";
			$response["listdiv"]	= "comment_form";
		}
		else
		{
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']['ErrorwhileaddingComment'];
			$response["remove"] 	= true;

		}
		//header('Content-type: application/json');
		echo json_encode($response);

	 }

		 /**
		 * Get Project comment
		 * (get projct comments)
		 *
		 * @access	public
		 * @param	string,int
		 * @return	array
		 */
		public function get_project_comment($slug,$uid)
		{
			$this->db->order_by("commentdate", "desc");
			$qryexec = $this->db->get_where("exp_proj_comment",array("slug"=>$slug));
			$execarr = $qryexec->result_array();
			$qryexec->free_result();

			return $execarr;
		}

		public function delete_comment($id)
		{
			$response = array();
			$this->db->delete("exp_proj_comment",array("id"=>$id));

			$response["status"] 	= "success";
			$response["remove"] 	= true;

			//header('Content-type: application/json');
			echo json_encode($response);
		}

		 /**
		 * Get top experts
		 *
		 * @access	public
		 * @param	string,int
		 * @return	array
		 */
		public function get_top_experts($slug,$uid)
		{
			$prd_sector = $this->get_sector_from_project($uid,$slug);

			/*$this->db->join('exp_members as m','m.uid = es.uid', 'inner');
			$where1 = "((es.sector='".$prd_sector['sector']."' AND es.subsector='".$prd_sector['subsector']."')";
			$this->db->where($where1);
			$where2 = "(es.sector='".$prd_sector['sector']."')";
			$this->db->or_where($where2);
			$where3 = "(es.subsector='".$prd_sector['subsector']."'))";
			$this->db->or_where($where3);
			$this->db->where("(m.annualrevenue >= 15 OR m.totalemployee != '1-50')");
			$this->db->where("m.membertype","5");
			if($uid != "") {
				$this->db->where("m.uid !=",$uid);
			}
			$this->db->group_by("es.uid");
			$this->db->order_by("es.sector", "desc");
			$this->db->order_by("es.subsector", "desc");
		*/

			if($uid != "") {
				$uid_append = " AND m.uid != '".$uid."'";
			}
			else
			{
				$uid_append ="";
			}

			$query_userlist = $this->db->query("select  * from (
			SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
			WHERE
			(es.sector='".$prd_sector['sector']."' AND es.subsector='".$prd_sector['subsector']."')
			AND (m.annualrevenue >= 15 OR m.totalemployee != '1-50') AND m.membertype = '5'".$uid_append."
			union
			SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
			WHERE
			es.sector='".$prd_sector['sector']."' AND es.subsector !='".$prd_sector['subsector']."'
			AND (m.annualrevenue >= 15 OR m.totalemployee != '1-50') AND m.membertype = '5'".$uid_append."
			union
			SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
			WHERE
			es.sector !='".$prd_sector['sector']."' AND es.subsector ='".$prd_sector['subsector']."'
			AND (m.annualrevenue >= 15 OR m.totalemployee != '1-50') AND m.membertype = '5'".$uid_append."
			)
			as s
			");
			//$query_userlist = $this->db->query('select * from exp_members where uid in (select uid from exp_members Group By uid)');
			//$query_userlist = $this->db->get('exp_expertise_sector as es');
			$execarr = $query_userlist->result_array();

			$query_userlist->free_result();
			return $execarr;
		}

		 /**
		 * Get SME experts
		 *
		 * @access	public
		 * @param	string,int
		 * @return	array
		 */
		public function get_sme_experts($slug,$uid)
		{
			$prd_sector = $this->get_sector_from_project($uid,$slug);


			if($uid != "") {
				$uid_append = " AND m.uid != '".$uid."'";
			}
			else
			{
				$uid_append ="";
			}


			/*$this->db->join('exp_members as m','m.uid = es.uid', 'inner');
			$where1 = "((es.sector='".$prd_sector['sector']."' AND es.subsector='".$prd_sector['subsector']."')";
			$this->db->where($where1);
			$where2 = "(es.sector='".$prd_sector['sector']."')";
			$this->db->or_where($where2);
			$where3 = "(es.subsector='".$prd_sector['subsector']."'))";
			$this->db->or_where($where3);
			$this->db->where("m.public_status !=","open");
			$this->db->where("m.annualrevenue <","15");
		 	$this->db->where("m.totalemployee","1-50");
			$this->db->where("m.membertype","5");
			if($uid != "") {
				$this->db->where("m.uid !=",$uid);
			}
			$this->db->group_by("es.uid");
			$this->db->order_by("es.sector", "desc");
			$this->db->order_by("es.subsector", "desc");
			*/

			$query_sme = $this->db->query("select  * from (
	SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
	WHERE
	(es.sector='".$prd_sector['sector']."' AND es.subsector='".$prd_sector['subsector']."')
	AND (m.annualrevenue < 15 AND m.totalemployee = '1-50') AND m.public_status != 'open' AND m.membertype = '5'".$uid_append."
	union
	SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
	WHERE
	es.sector='".$prd_sector['sector']."' AND es.subsector !='".$prd_sector['subsector']."'
	AND (m.annualrevenue < 15 AND m.totalemployee = '1-50') AND m.public_status != 'open' AND m.membertype = '5'".$uid_append."
	union
	SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
	WHERE
	es.sector !='".$prd_sector['sector']."' AND es.subsector ='".$prd_sector['subsector']."'
	AND (m.annualrevenue < 15 AND m.totalemployee = '1-50') AND m.public_status != 'open' AND m.membertype = '5'".$uid_append."
	)
	as s
	");


			//$query_sme = $this->db->get('exp_expertise_sector as es');
			$smearr = $query_sme->result_array();
			$query_sme->free_result();
			return $smearr;
		}



	/**
	 * Get sector/subsector of given project
	 *
	 *
	 * @access	public
	 * @param	int
	 * @return	array
	 */
	public function get_sector_from_project($uid,$slug)
	{
		$this->db->where('uid', $uid);
		$this->db->where('slug', $slug);
		$this->db->where('isdeleted', '0');
	 	$this->db->select("sector,subsector");
		$query_sector_qryproj = $this->db->get('exp_projects');
		$result_sector_qryproj= $query_sector_qryproj->row_array();
		return $result_sector_qryproj;
	}

	public function get_country_list()
	{
		$this->db->distinct();
		$this->db->select("country");
		$this->db->where(array("isdeleted"=>"0","country !="=>""));
		$this->db->order_by("country", "asc");
		$qrycon = $this->db->get("exp_projects");

		$country[""]	= lang('SelectCountry');
		foreach($qrycon->result_array() as $row)
		{
			$country[$row["country"]] = $row["country"];
		}

		return $country;
	}

	/**
	 * Get User list
	 *
	 * @access	public
	 * @param	none
	 * @return	array
	 */
	public function get_smeexperts_list($perpage,$limit=0,$slug='',$uid){

		$filterby = array();

		$prd_sector = $this->get_sector_from_project($uid,$slug);

		if($uid != "") {
			$uid_append = " AND m.uid != '".$uid."'";
		}
		else
		{
			$uid_append ="";
		}
		if(empty($limit))
		{
			$limit = 0;
		}
		/*$this->db->join('exp_members as m','m.uid = es.uid', 'inner');
		$where1 = "((es.sector='".$prd_sector['sector']."' AND es.subsector='".$prd_sector['subsector']."')";
		$this->db->where($where1);
		$where2 = "(es.sector='".$prd_sector['sector']."')";
		$this->db->or_where($where2);
		$where3 = "(es.subsector='".$prd_sector['subsector']."'))";
		$this->db->or_where($where3);
		$this->db->where("m.public_status !=","open");
		 $this->db->where("(m.annualrevenue >= 15 AND m.totalemployee != '1-50')");
		$this->db->where("m.membertype","5");
		$this->db->group_by("es.uid");
		$this->db->order_by("es.sector", "desc");
		$this->db->order_by("es.subsector", "desc");*/

		$query_filter_usertotal = $this->db->query("select  * from (
		SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
		WHERE
		(es.sector='".$prd_sector['sector']."' AND es.subsector='".$prd_sector['subsector']."')
		AND (m.annualrevenue < 15 AND m.totalemployee = '1-50') AND m.public_status !='open' AND m.membertype = '5'".$uid_append."
		union
		SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
		WHERE
		es.sector='".$prd_sector['sector']."' AND es.subsector !='".$prd_sector['subsector']."'
		AND (m.annualrevenue < 15 AND m.totalemployee = '1-50') AND m.public_status !='open' AND m.membertype = '5'".$uid_append."
		union
		SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
		WHERE
		es.sector !='".$prd_sector['sector']."' AND es.subsector ='".$prd_sector['subsector']."'
		AND (m.annualrevenue < 15 AND m.totalemployee = '1-50') AND m.public_status !='open' AND m.membertype = '5'".$uid_append."
		)
		as s
		");

		//$query_filter_usertotal = $this->db->get('exp_expertise_sector as es');
		//$execarr = $query_filter_usertotal->result_array(); // for total
		$filter_numrows = $query_filter_usertotal->num_rows();
		$query_filter_usertotal->free_result();


		$filterby['forum'] = 'forum';

		/*$this->db->join('exp_members as m','m.uid = es.uid', 'inner');
		$where1 = "((es.sector='".$prd_sector['sector']."' AND es.subsector='".$prd_sector['subsector']."')";
		$this->db->where($where1);
		$where2 = "(es.sector='".$prd_sector['sector']."')";
		$this->db->or_where($where2);
		$where3 = "(es.subsector='".$prd_sector['subsector']."'))";
		$this->db->or_where($where3);
		$this->db->where("m.public_status !=","open");
		 $this->db->where("(m.annualrevenue >= 15 AND m.totalemployee != '1-50')");

		$this->db->where("m.membertype","5");
		$this->db->group_by("es.uid");
		$this->db->order_by("es.sector", "desc");
		$this->db->order_by("es.subsector", "desc");
		*/

		if($uid != "") {
			$uid_append = " AND m.uid != '".$uid."'";
		}
		else
		{
			$uid_append ="";
		}
		$query_userlist = $this->db->query("select  * from (
		SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
		WHERE
		(es.sector='".$prd_sector['sector']."' AND es.subsector='".$prd_sector['subsector']."')
		AND (m.annualrevenue < 15 AND m.totalemployee = '1-50') AND m.public_status !='open' AND m.membertype = '5'".$uid_append."
		union
		SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
		WHERE
		es.sector='".$prd_sector['sector']."' AND es.subsector !='".$prd_sector['subsector']."'
		AND (m.annualrevenue < 15 AND m.totalemployee = '1-50') AND m.public_status !='open' AND m.membertype = '5'".$uid_append."
		union
		SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
		WHERE
		es.sector !='".$prd_sector['sector']."' AND es.subsector ='".$prd_sector['subsector']."'
		AND (m.annualrevenue < 15 AND m.totalemployee = '1-50') AND m.public_status !='open' AND m.membertype = '5'".$uid_append."
		)
		as s limit ".$perpage." offset ".$limit."
		");

		//$query_userlist = $this->db->get('exp_expertise_sector as es',$perpage,$limit);

		if ($query_userlist->num_rows() > 0)
		{
			$mysector = array();
			foreach($query_userlist->result_array() as $row)
			{
				$imgurl   = $row["userphoto"]!=""?$row["userphoto"]:"profile_image_placeholder.png";
				$imgpath  = $row["userphoto"]!=""?USER_IMAGE_PATH:USER_NO_IMAGE_PATH;
				$mysector = $this->get_expertise_mysector($row["uid"]);

				$row["userphoto"]	  			= 	$imgurl;
				$row["userphotoPath"] 			=	$imgpath;
				$row["expert_sector"]		  	=	$mysector;
				$result_userlist["filter"][]	=	$row;
			}
			$result_userlist["filter_total"] 	= $filter_numrows;
			$result_userlist["filter_by"]		= $filterby;

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
	 * Get User list
	 *
	 * @access	public
	 * @param	none
	 * @return	array
	 */
	public function get_topexperts_list($perpage,$limit=0,$slug='',$uid){

		$filterby = array();

		$prd_sector = $this->get_sector_from_project($uid,$slug);

		if($uid != "") {
			$uid_append = " AND m.uid != '".$uid."'";
		}
		else
		{
			$uid_append ="";
		}
		if(empty($limit))
		{
			$limit = 0;
		}

		/*$this->db->join('exp_members as m','m.uid = es.uid', 'inner');
		$where1 = "((es.sector='".$prd_sector['sector']."' AND es.subsector='".$prd_sector['subsector']."')";
		$this->db->where($where1);
		$where2 = "(es.sector='".$prd_sector['sector']."')";
		$this->db->or_where($where2);
		$where3 = "(es.subsector='".$prd_sector['subsector']."'))";
		$this->db->or_where($where3);
		$this->db->where("(m.annualrevenue >= 15 OR m.totalemployee != '1-50')");

		$this->db->where("m.membertype","5");
		$this->db->group_by("es.uid");
		$this->db->order_by("es.sector", "desc");
		$this->db->order_by("es.subsector", "desc");*/


		$query_filter_usertotal = $this->db->query("select  * from (
		SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
		WHERE
		(es.sector='".$prd_sector['sector']."' AND es.subsector='".$prd_sector['subsector']."')
		AND (m.annualrevenue >= 15 OR m.totalemployee != '1-50') AND m.membertype = '5'".$uid_append."
		union
		SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
		WHERE
		es.sector='".$prd_sector['sector']."' AND es.subsector !='".$prd_sector['subsector']."'
		AND (m.annualrevenue >= 15 OR m.totalemployee != '1-50') AND m.membertype = '5'".$uid_append."
		union
		SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
		WHERE
		es.sector !='".$prd_sector['sector']."' AND es.subsector ='".$prd_sector['subsector']."'
		AND (m.annualrevenue >= 15 OR m.totalemployee != '1-50') AND m.membertype = '5'".$uid_append."
		)
		as s
		");
		//$query_filter_usertotal = $this->db->get('exp_expertise_sector as es');
		//$execarr = $query_filter_usertotal->result_array(); // for total
		$filter_numrows = $query_filter_usertotal->num_rows();
		$query_filter_usertotal->free_result();




		$filterby['forum'] = 'forum';

		$query_userlist = $this->db->query("select  * from (
		SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
		WHERE
		(es.sector='".$prd_sector['sector']."' AND es.subsector='".$prd_sector['subsector']."')
		AND (m.annualrevenue >= 15 OR m.totalemployee != '1-50') AND m.membertype = '5'".$uid_append."
		union
		SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
		WHERE
		es.sector='".$prd_sector['sector']."' AND es.subsector !='".$prd_sector['subsector']."'
		AND (m.annualrevenue >= 15 OR m.totalemployee != '1-50') AND m.membertype = '5'".$uid_append."
		union
		SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
		WHERE
		es.sector !='".$prd_sector['sector']."' AND es.subsector ='".$prd_sector['subsector']."'
		AND (m.annualrevenue >= 15 OR m.totalemployee != '1-50') AND m.membertype = '5'".$uid_append."
		)
		as s limit ".$perpage." offset ".$limit."
		");

		/*$this->db->join('exp_members as m','m.uid = es.uid', 'inner');
		$where1 = "((es.sector='".$prd_sector['sector']."' AND es.subsector='".$prd_sector['subsector']."')";
		$this->db->where($where1);
		$where2 = "(es.sector='".$prd_sector['sector']."')";
		$this->db->or_where($where2);
		$where3 = "(es.subsector='".$prd_sector['subsector']."'))";
		$this->db->or_where($where3);
	 	$this->db->where("(m.annualrevenue >= 15 OR m.totalemployee != '1-50')");

		$this->db->where("m.membertype","5");
		$this->db->group_by("es.uid");
		$this->db->order_by("es.sector", "desc");
		$this->db->order_by("es.subsector", "desc");*/

		//$query_userlist = $this->db->get('exp_expertise_sector as es',$perpage,$limit);

		if ($query_userlist->num_rows() > 0)
		{
			$mysector = array();
			foreach($query_userlist->result_array() as $row)
			{
				$imgurl   = $row["userphoto"]!=""?$row["userphoto"]:"profile_image_placeholder.png";
				$imgpath  = $row["userphoto"]!=""?USER_IMAGE_PATH:USER_NO_IMAGE_PATH;
				$mysector = $this->get_expertise_mysector($row["uid"]);

				$row["userphoto"]	  			= 	$imgurl;
				$row["userphotoPath"] 			=	$imgpath;
				$row["expert_sector"]		  	=	$mysector;
				$result_userlist["filter"][]	=	$row;
			}

			$result_userlist["filter_total"] 	= $filter_numrows;
			$result_userlist["filter_by"]		= $filterby;

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
	 * Get expertise sector
	 * @return	array
	 */
	public function get_expertise_mysector($userid)
	{
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

		$imp_result_export = implode(",",array_unique($expert_sectors));
		//$query_expertise->free_result();
		return $imp_result_export;
	}


	public function get_project_organization($projectid)
	{
		$expAdv_proj_data = array();
		$expAdv_proj_data['orgid'] = '';
		$expAdv_proj_data['projid'] = '';
		$expAdv_proj_data['status'] = '';

		$this->db->where('isdeleted', '0');
		$this->db->where('projid', $projectid);
		$query_projexp = $this->db->get('exp_proj_expertadvert');

		foreach($query_projexp->result_array() as $row)
		{
			$expAdv_proj_data['orgid'] = $row['orgid'];
			$expAdv_proj_data['projid'] = $row['projid'];
			$expAdv_proj_data['status'] = $row['status'];
		}

		return $expAdv_proj_data;
	}


	public function update_orgExpert($projectid)
	{
		if(isset($projectid)&& $projectid != '')
		{
			$org_expAdvert = $this->input->post("project_expAdv");
			$org_action = $this->input->post("hdn_project_organizations_action");

			if(isset($org_action)&&$org_action=='Add' && $org_expAdvert!='')
			{
				$expAdv_proj_data = array('ownerid'=>sess_var('uid'),'projid'=>$projectid,'orgid'=>$org_expAdvert,'status'=>'0','isdeleted'=>'0');
				if($this->db->insert('exp_proj_expertadvert', $expAdv_proj_data))
				{
					$response["status"] 	= "success";
					$response["message"] 	= $this->dataLang['lang']['OrganizationAddedsuccessfully'];
					$response["remove"] 	= true;
					$response["isredirect"]	= "yes";
				}
				else
				{
					$response["status"] 	= "error";
					$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdateOrganization'];
					$response["remove"] 	= true;
				}
			}
			else
			{
				$this->db->where('isdeleted', '0');
				$this->db->where('projid', $projectid);
				if($this->db->update("exp_proj_expertadvert",array('orgid'=>$org_expAdvert,'status'=>'0')))
				{
					$response["status"] 	= "success";
					$response["message"] 	= $this->dataLang['lang']['Organizationupdatesuccessfully'];
					$response["remove"] 	= true;
					$response["isredirect"]	= "yes";
				}
				else
				{
					$response["status"] 	= "error";
					$response["message"] 	= $this->dataLang['lang']['ErrorwhileupdateOrganization'];
					$response["remove"] 	= true;

				}
			}
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

 	/**
	 * Get project Organization suggetion
	 *
	 * @access	public
	 * @param	string,int
	 * @return	array
	 */
	public function get_proj_matches($slug,$uid)
	{
		$prd_sector = $this->get_sector_from_project($uid,$slug);

		$uid_append = '';
		if($uid != "") {
			$uid_append = " AND m.uid != '".$uid."'";
		}

		/*$this->db->join('exp_members as m','m.uid = es.uid', 'inner');
		$where1 = "((es.sector='".$prd_sector['sector']."' AND es.subsector='".$prd_sector['subsector']."')";
		$this->db->where($where1);
		$where2 = "(es.sector='".$prd_sector['sector']."'))";
		$this->db->or_where($where2);
		//$where3 = "(es.subsector='".$prd_sector['subsector']."'))";
		//$this->db->or_where($where3);
		$this->db->where("m.public_status !=","open");
		//$this->db->where("(m.annualrevenue >= 15 OR m.totalemployee != '1-50')");
		$this->db->where("m.membertype","8");
		$this->db->group_by("es.uid");
		$this->db->order_by("es.sector", "desc");
		$this->db->order_by("es.subsector", "desc");*/


		$query_sme = $this->db->query("select  * from (
		SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
		WHERE
		(es.sector='".$prd_sector['sector']."' AND es.subsector='".$prd_sector['subsector']."')
		AND (m.annualrevenue >= 15 OR m.totalemployee != '1-50') AND m.public_status !='open' AND m.membertype = '8'".$uid_append."
		union
		SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
		WHERE
		es.sector='".$prd_sector['sector']."' AND es.subsector !='".$prd_sector['subsector']."'
		AND (m.annualrevenue >= 15 OR m.totalemployee != '1-50') AND m.public_status !='open'  AND m.membertype = '8'".$uid_append."
		union
		SELECT distinct es.uid,m.* FROM exp_expertise_sector as es INNER JOIN exp_members as m ON m.uid = es.uid
		WHERE
		es.sector !='".$prd_sector['sector']."' AND es.subsector ='".$prd_sector['subsector']."'
		AND (m.annualrevenue >= 15 OR m.totalemployee != '1-50') AND m.public_status !='open'  AND m.membertype = '8'".$uid_append."
		)
		as s
		");

		//$query_sme = $this->db->get('exp_expertise_sector as es');
		//echo $this->db->last_query();
		$smearr = $query_sme->result_array();
		$query_sme->free_result();

		return $smearr;
	}

    public function get_jobs_created($projectid)
    {
        $sql = "
        SELECT 
            ((totalbudget / 1E3) *  
            COALESCE(
                (CASE WHEN c.devlevel = 'EM' THEN devlookup_mostspecific.jobs_em ELSE devlookup_mostspecific.jobs_row END), 
                (CASE WHEN c.devlevel = 'EM' THEN devlookup_midspecific.jobs_em ELSE devlookup_midspecific.jobs_row END), 
                (CASE WHEN c.devlevel = 'EM' THEN devlookup_leastspecific.jobs_em ELSE devlookup_leastspecific.jobs_row END)
            ))::integer AS jobs_created
        FROM exp_projects p 
        JOIN exp_countries c ON (p.country = c.countryname)
        LEFT OUTER JOIN exp_sector_jobs devlookup_mostspecific
        ON
            p.sector = devlookup_mostspecific.sector AND
            p.subsector = devlookup_mostspecific.subsector
        LEFT OUTER JOIN exp_sector_jobs devlookup_midspecific
        ON
            p.sector = devlookup_midspecific.sector AND
            devlookup_midspecific.subsector IS NULL
        LEFT OUTER JOIN exp_sector_jobs devlookup_leastspecific
        ON
            devlookup_leastspecific.sector IS NULL AND
            devlookup_leastspecific.subsector IS NULL
        WHERE p.pid = ?
        ";

        $jobs_created_resultobject = $this->db->query($sql, $projectid)->row();
        if (!isset($jobs_created_resultobject)) return null; // For instance, non-existent PID specified
        $jobs_created = $jobs_created_resultobject->jobs_created;
        if ($jobs_created !== null) return (int) $jobs_created; // Result from DB will be null if no totalbudget yet specified
        return null;

    }
	
	
	public function get_proj_map_data()
	{
		$query_sme = $this->db->query("SELECT pid, projectname, slug, lat, lng, sector, projectphoto, description, country, subsector, stage
										FROM public.exp_projects
										WHERE isdeleted = '0' AND lat IS NOT NULL

										");

		$smearr = $query_sme->result_array();
		$query_sme->free_result();

		return $smearr;
	}


}
?>
