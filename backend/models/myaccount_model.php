<?php

class Myaccount_model extends CI_Model {

	/**
	 * update_seats
	 * 
	 * @access	public
	 * @return	boolean/string
	 */	
	public function update_seats($org_id, $new_seats)
	{
		// $qry = $this->db->from('exp_members')
		// 		//->join('exp_invite_experts', 'exp_members.uid = exp_invite_experts.uid')
		// 		->where('membertype','5')
		// 		//->where('( organizationid = 0 OR organizationid is null OR  organizationid = '.$org_id.' )')
		// 		->where('organizationid',$org_id)
		// 		//->where('status','2')
		// 		->order_by('lastname','asc')
		// 		->order_by('firstname','asc')
		// 		->get('');
		// if( ! $qry->num_rows() > 0 ) return false;
		$hold = $new_seats; 

		$current = $this->get_seats($org_id);
		$current = isset($current['approved']) ? $current['approved'] : '';
		
		$current_seats = array();

		if( $current )
		{
			foreach( $current as $key => $row )  
			{
				$current_seats[] = $row['uid'];
			}	
		}
		
		//$current_seats = $qry->result();

		$seats = array(
			'remove'=> array(),
			'keep'	=> array(),
			'add'	=> array()
		);
	

		foreach( $current_seats as $i => $row )  
		{

			if( in_array($row, $new_seats) )
			{
				// add to keep array
				$seats['keep'][] = $row;
			}
			else
			{
				// add to remove array 
				$seats['remove'][] = $row;
			}

			// remove from add array
			$key = array_search($row, $new_seats);
			if( $key !== false)
			{
				unset( $new_seats[$key] );	
			}
			
			
		}

		foreach( $new_seats as $key => $row )  
		{
			if( $row != '' && $row > 0 )
			{
				$seats['add'][] = $row;
			}
		}

		//echo "<pre>"; var_dump( $hold, $seats ); exit;

		$this->load->model('experts_model');

		// remove missing items
		foreach( $seats['remove'] as $key => $seat )  
		{
			$this->experts_model->remove_member_to_seat($seat,$org_id);
		}
		// drop any ones in both arrays
		
		// nothing to do


		// add any new items
		
		foreach( $seats['add'] as $key => $seat )  
		{
			//$this->experts_model->uid = $seat;
			//$this->experts_model->orgid = $org_id;
			$this->experts_model->add_member_to_seat($seat,$org_id);
		}

		return $seats;
	}

	/**
	 * get_non_expert_members
	 * 
	 * @access	public
	 * @return	boolean/string
	 */	
	public function get_non_expert_members($org_id, $array=false)
	{
		
		$qry = $this->db->select('exp_members.uid as id, exp_members.*')
				->from('exp_members')
				->join('exp_invite_experts','exp_members.uid = exp_invite_experts.uid','left')
				->where('membertype','5')
				->where("( orgid = $org_id OR orgid is null )")
				->order_by('firstname','asc')
				->order_by('lastname','asc')
				->get();

		//echo "<pre>"; var_dump( $qry->result() ); exit;
		
		if( ! $qry->num_rows() > 0 ) return false;

		if( ! $array ) return $qry->result();

		$options = array(''=>'- Empty Seat -');
		foreach( $qry->result() as $key => $row )  
		{		
			$name = $row->firstname . ' ' . $row->lastname;
			//if( $row->organizationid == $org_id ) $name .= ' *';
			//$name .= ' ' . $row->uid;
			$options[$row->id] = $name;
		}

		return $options;
		
	}

	/**
	 * Get get_seats
	 *
	 * @access	public
	 * @param	int
	 * @return	array
	 */
	public function get_seats($sess_uid){
		
		//$this->db->select("uid,firstname,lastname,email,organization,status,membertype,discipline,country,city,state,userphoto,vContact,organizationid");
		$this->db->where("orgid ='".$sess_uid."'");
		$query_invite = $this->db->get('exp_invite_experts');
		
		if ($query_invite->num_rows() > 0)
		{
			$result_seat = array();
			$result_seat['pending'] = array();
			$result_seat['approved'] = array();
			
			foreach($query_invite->result_array() as $row)
			{
				$this->db->where("uid ='".$row['uid']."'");
				$query_user = $this->db->get('exp_members');
				if ($query_user->num_rows() > 0)
				{
					foreach($query_user->result_array() as $row2)
					{
						$result_user	=	$row2;
					}
				
					$getuser = $result_user;
			
					if($row['status']=='2')
					{
						$imgurl 	= $getuser["userphoto"]!=""?$getuser["userphoto"]:"seat_pending_new.png";
						$imgpath 	= $getuser["userphoto"]!=""?USER_IMAGE_PATH:USER_NO_IMAGE_PATH; 
					
						$getuser["userphoto"]	  = $imgurl;
						$getuser["userphotoPath"] = $imgpath;
		
						$result_seat['pending'][]	=	$getuser;
					}
					if($row['status']=='1')
					{
						$imgurl 	= $getuser["userphoto"]!=""?$getuser["userphoto"]:"seat_empty_new.png";
						$imgpath 	= $getuser["userphoto"]!=""?USER_IMAGE_PATH:USER_NO_IMAGE_PATH; 
					
						$getuser["userphoto"]	  = $imgurl;
						$getuser["userphotoPath"] = $imgpath;
		
						$result_seat['approved'][]	=	$getuser;
					}
				}
			}
			return $result_seat;
		}
	}

	/**
	 * batch_geocode
	 * 
	 * @access	public
	 * @return	boolean/string
	 */	
	public function batch_geocode($id=false)
	{


		//$qry = $this->db->update('exp_projects',array('geocode' => NULL, 'lat' => NULL, 'lng' => NULL) );
		//echo "<pre>"; var_dump( $qry ); exit;
		
		$this->load->library('mapquest');

		if( $id )
		{
			$qry = $this->db->where('uid',$id)->get('exp_members',50);
		}
		else
		{
			$qry = $this->db->where("geocode IS NULL")->get('exp_members',50);	
		}
		


		foreach($qry->result() as $i => $row )
		{

			$location = trim($row->address . ' ' . $row->city . ' ' . $row->state . ' ' . $row->postal_code . ' ' . $row->country);

			if( $location == '' )
			{
				$this->db->where('uid',$row->uid)->update('exp_members',array('geocode'=>'[]'));
				continue;
			}

			$location = urlencode($location);

			$data = $this->mapquest->geocode($location)->json_raw;

			//echo "<pre>"; var_dump( $location, $data ); exit;

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

	public function get_accountinfo($uid)
	{
		$this->db->where(array("uid"=>$uid));
		$qryacc = $this->db->get("exp_members");
	
		return $qryacc->result_array();
	}
	
    /**
     * Get Account Details of loged in user
     *
     * @access	public
     * @param	int
     * @return	array
     */
    public function get_user($uid){

        $this->db->where("uid ='".$uid."'");
        $query_user = $this->db->get('exp_members');
        if ($query_user->num_rows() > 0)
        {
            foreach($query_user->result_array() as $row)
            {
                $result_user	=	$row;
            }

            return $result_user;
        }
    }

    /**
     * Get expertise
     * @return	array
     */
    public function get_expertise($uid)
    {
        $this->db->where('uid', $uid);
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
     * Get Education
     * @return	array
     */
    public function get_education($uid,$eduid = '')
    {
        $this->db->where('uid',$uid);
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
     * Get List of expert sectors of user
     * @return	array
     */
    public function get_expert_sectors($userid,$secid='')
    {
        $this->db->where('uid', $userid);
        if(isset($secid)&& $secid != 0)
        {
            $this->db->where('id', $secid);
        }

        $query = $this->db->get('exp_expertise_sector');
        if ($query->num_rows() > 0)
        {
            foreach($query->result_array() as $row)
            {
                $getsectors[]	=	$row;
            }

            return $getsectors;
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
    public function get_projects($uid)
    {
        //retrive user's project information from db
        $this->db->select("pid,uid,projectname,slug,projectphoto,country,sector,stage");
        $this->db->where('isdeleted','0');
        $this->db->where('uid', $uid);
        $query_project = $this->db->get('exp_projects');

        $totalproj = $query_project->num_rows;
        $projectdata["totalproj"] = $totalproj;

        foreach($query_project->result_array() as $row)
        {
            $projectdata["proj"][] = $row;
        }
        return $projectdata;
    }

    public function load_education($userid,$loadtype,$eduid)
    {
        $array_load['education_data'] = $this->get_education($userid,$eduid);
        $array_load['loadtype']		  = $loadtype;
        return $array_load;
    }


    /**
     * Load expert sector
     * @return	array
     */

    public function load_expertsector($userid,$loadtype,$secid)
    {
        $array_load['sector_data'] = $this->get_expert_sectors($userid,$secid);
        $array_load['loadtype']		  = $loadtype;
        return $array_load;
    }


    /**
     * update Account Details
     * @return	boolean
     */
    public function update_user($userid)
    {

        //create profile information post array
        $update_data = array(
                    'firstname' 		=> $this->input->post('member_first_name'),
                    'lastname' 			=> $this->input->post('member_last_name'),
                    'organization'	 	=> $this->input->post('member_organization'),
                    'title'				=> $this->input->post('member_title'),
                    'totalemployee'	 	=> $this->input->post('member_org_employees'),
                    'annualrevenue' 	=> $this->input->post('member_org_annual_revenue') != '' ? $this->input->post('member_org_annual_revenue') : NULL,
                    'discipline'	 	=> $this->input->post('member_discipline'),
                    'sector' 			=> $this->input->post('member_sector'),
                    'subsector' 		=> $this->input->post('member_sub_sector'),
                    'subsector_other' 	=> $this->input->post('member_sub_sector_other'),
                    'country' 			=> $this->input->post('member_country'),
                    'address' 			=> $this->input->post('member_address'),
                    'city' 				=> $this->input->post('member_city'),
                    'state' 			=> $this->input->post('member_state'),
                    'postal_code' 		=> $this->input->post('member_postal_code'),
                    'vcontact' 			=> $this->input->post('member_phone'),
                    'public_status'		=> $this->input->post('member_public'),
                    'mission'			=> $this->input->post('member_mission'),
                    'government_level'  => $this->input->post('member_government_level') ?: NULL
        );


        $this->db->where('uid', $userid);

        //ExpertAdverts Start
        $usertype = $this->input->post('hdn_member_usertype');
            ($usertype == '8') ? $ret_data['name'] 	= 	$this->input->post('member_organization') : $ret_data['name'] = $this->input->post('member_first_name')." ".$this->input->post('member_last_name');
        //ExpertAdverts End

        if($str = $this->db->update('exp_members', $update_data))
        {
            $this->batch_geocode($userid);

            $ret_data['message']	=	'User Detail Updated Successfully';
            return $ret_data;

        }
    }


    /**
     * update Account Details
     * @return	boolean
     */
    public function update_expadvert($userid)
    {
        //create profile information post array

        $update_data = array(
            'email' 		=> $this->input->post('expadvert_license_cemail'),
            'organization' 	=> $this->input->post('expadvert_organizationname'),
            'numberofseat'	=> $this->input->post('expadvert_number_of_seat'),
            'licenseno'		=> $this->input->post('expadvert_license_no'),
            'licensecost'	=> $this->input->post('expadvert_license_cost'),
            'accountname'	=> $this->input->post('expadvert_license_cname'),
            'licensestart'	=> DateFormat($this->input->post('expadvert_licensestart'),DATEFORMATDB,FALSE),
            'licenseend'	=> DateFormat($this->input->post('expadvert_licenseend'),DATEFORMATDB,FALSE),
        );

        $this->db->where('uid', (int) $userid);

        if ($str = $this->db->update('exp_members', $update_data))
        {
            $response = array();
            $response["isreload"]	= "yes";
            $response["status"] = "success";
            $response["msgtype"] = "success";
            $response["msg"] = "User details updated successfully";

            //header('Content-type: application/json');
            echo json_encode($response);
        }
    }


    /**
     * update profile picture in Account Details
     * @return	boolean
     */
    public function upload_photo($userid,$file)
    {
        $update_data = array(
                    'userphoto' 		=> $file['file_name']
        );

        $this->db->where('uid', $userid);

        if($str = $this->db->update('exp_members', $update_data))
        {
            $ret_data['message']	=	'User Detail Updated Successfully';
            return $ret_data;

        }
    }

    /**
     * update expertise data in Account Details
     * @return	json
     */
    public function update_expertise($userid)
    {

        if($this->get_expertise($userid))
        {

            //create profile information post array
            $update_expertise_data = array(
                        'areafocus' 		=> $this->input->post('member_areas_keywords'),
                        'summary' 			=> $this->input->post('member_expertise'),
                        'progoals'		 	=> $this->input->post('member_pro_goals'),
                        'success'			=> $this->input->post('member_success')
            );


            $this->db->where('uid', $userid);

            if($str = $this->db->update('exp_expertise', $update_expertise_data))
            {
                $ret_data['message']	=	'Expertise Updated Successfully';
                return $ret_data;
            }

        }
        else
        {
            $add_expertise_data = array(
                        'uid'			    => $userid,
                        'areafocus' 		=> $this->input->post('member_areas_keywords'),
                        'summary' 			=> $this->input->post('member_expertise'),
                        'progoals'		 	=> $this->input->post('member_pro_goals'),
                        'success'			=> $this->input->post('member_success')
                        );

            //insert into db
            if($this->db->insert('exp_expertise', $add_expertise_data))
            {
                $ret_data['message']	=	'Expertise Added Successfully';
                return $ret_data;
            }


        }
    }


    /**
     * delete Education
     * @return	json
     */
    public function delete_education()
    {

        $delids = $this->input->get("delids");
        if(count($delids) > 0)
        {
            $response = array();
            $this->db->where_in("educationid",$delids);
            if($this->db->delete("exp_education"))
            {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "education(s) Deleted Successfully";
            }

            header('Content-type: application/json');
            echo json_encode($response);
        }

    }


    /**
     * Add Education
     * @return	json
     */

    public function add_education($userid)
    {
        $add_education_data = array(
                    'uid'			    => $userid,
                    'university' 		=> $this->input->post('education_university'),
                    'degree' 			=> $this->input->post('education_degree'),
                    'major'			 	=> $this->input->post('education_major'),
                    'startyear'			=> $this->input->post('education_start_year'),
                    'gradyear'			=> $this->input->post('education_grad_year'),
                    'visibility'		=> $this->input->post('education_visibility'),
                    'status'			=> '1'
                    );
        //insert into db
        if($this->db->insert('exp_education', $add_education_data))
        {
            $ret_data['message']	=	'Education Added Successfully';
            return $ret_data;
        }
    }


    /**
     * Update Education
     * @param int
     * @return	json
     */

    public function update_education($userid,$educationid)
    {

        $update_education_data = array(
                    'uid'			    => $userid,
                    'university' 		=> $this->input->post('education_university'),
                    'degree' 			=> $this->input->post('education_degree'),
                    'major'			 	=> $this->input->post('education_major'),
                    'startyear'			=> $this->input->post('education_start_year'),
                    'gradyear'			=> $this->input->post('education_grad_year'),
                    'visibility'		=> $this->input->post('education_visibility'),
                    'status'			=> '1'
                    );


            $this->db->where('uid', $userid);
            $this->db->where('educationid', $educationid);

            if($str = $this->db->update('exp_education', $update_education_data))
            {
                $ret_data['message']	=	'Education Updated Successfully';
                return $ret_data;
            }
    }


    /**
     * Add Expert sector
     * @return	json
     */

    public function add_expert_sector($userid,$numberof=0)
    {

        $add_expert_sector_data = array(
                    'uid'			    => $userid,
                    'sector' 			=> _htmlentities($this->input->post('member_sector')),
                    'subsector'			=> _htmlentities($this->input->post('member_sub_sector')),
                    'permission'		=> 'All',
                    'status'			=> '1'
                    );
        //insert into db
        if($this->db->insert('exp_expertise_sector', $add_expert_sector_data))
        {
                $ret_data['message']	=	'Sector Inserted Successfully';
                return $ret_data;
        }
    }



    /**
     * Update Expert sector
     * @return	json
     */

    public function update_expert_sector($userid,$sectorid)
    {
        $update_expert_sector_data = array(
                    'sector' 			=> _htmlentities($this->input->post('member_sector')),
                    'subsector'			=> _htmlentities($this->input->post('member_sub_sector')),
        );

        $this->db->where('uid', $userid);
        $this->db->where('id', $sectorid);

        if($this->db->update('exp_expertise_sector', $update_expert_sector_data))
        {
                $ret_data['message']	=	'Sector Updated Successfully';
                return $ret_data;
        }
    }
    /**
     * delete exprert sector
     * @return	json
     */
    public function delete_expert_sector($userid,$secid)
    {

        $delids = $this->input->get("delids");
        if(count($delids) > 0)
        {
            $response = array();
            $this->db->where("uid",$userid);
            $this->db->where_in("id",$delids);
            if($this->db->delete("exp_expertise_sector"))
            {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Education(s) Deleted Successfully";
            }

            header('Content-type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * update email in Account Details
     * @return	json
     */
    public function update_email()
    {
        $password = $this->input->post("es_password");
        $update_data = array(
            'email'		=> $this->input->post('es_username')
        );

        $this->db->select("password,salt");
        $qryuser = $this->db->get_where("exp_admin",array("uid"=>sess_var("admin_uid")));
        $userarr = $qryuser->row_array();

        $hashpass = hash('sha512',$userarr["salt"].$password);

        if($hashpass == $userarr["password"])
        {
            $this->db->where('uid', sess_var('uid'));

            if($str = $this->db->update('exp_admin', $update_data))
            {
                    $response = array();
                    $response["status"] 	= "success";
                    $response["message"] 	= "Email updated successfully.";
                    $response["remove"] 	= true;
                    $response["redirect"] 	= '/admin.php/myaccount/settings';

                    header('Content-type: application/json');
                    echo json_encode($response);
            }
            else
            {
                    $response = array();
                    $response["status"] 	= "error";
                    $response["message"] 	= "Error while updating Email.";
                    $response["isload"] 	= "no";

                    header('Content-type: application/json');
                    echo json_encode($response);
            }
        } else {
            $response = array();
            $response["status"] 	= "error";
            $response["message"] 	= array("es_password"=>"Password does not match with Original Password.");
            $response["isload"] 	= "no";

            header('Content-type: application/json');
            echo json_encode($response);

        }
    }


    /**
     * update password in Account Details
     * @return	json
     */
    public function update_password()
    {
        $ret_data = array();

        $this->db->select("password,salt");
        $qryuser = $this->db->get_where("exp_admin",array("uid"=>sess_var("admin_uid")));
        $userarr = $qryuser->row_array();

        $hashpass = hash('sha512',$userarr["salt"].$this->input->post('ps_currentpass'));

        if($hashpass == $userarr["password"])
        {
            $encrypted_password = encrypt_password($this->input->post('ps_newpassword'));

            $update_passdata = array(
                        'password' 	=> $encrypted_password["password"],
                        'salt' 	=> $encrypted_password["salt"]
            );

            $this->db->where('uid', sess_var('uid'));
            if($str = $this->db->update('exp_admin', $update_passdata))
            {
                    $response = array();
                    $response["status"] 	= "success";
                    $response["message"] 	= "Password updated successfully.";
                    $response["remove"] 	= true;
                    $response["isreset"] 	= "yes";
                    $response["redirect"] 	= '/admin.php/myaccount/settings';

                    header('Content-type: application/json');
                    echo json_encode($response);
            }
            else
            {
                    $response = array();
                    $response["status"] 	= "error";
                    $response["message"] 	= "Error while updating Password.";
                    $response["isload"] 	= "no";

                    header('Content-type: application/json');
                    echo json_encode($response);
            }
        }
        else {
            $response = array();
            $response["status"] 	= "error";
            $response["message"] 	= array("ps_currentpass"=>"Password does not match with Current Password.");
            $response["isload"] 	= "no";

            header('Content-type: application/json');
            echo json_encode($response);

        }
    }

    /**
     * Returns list of valid government levels from DB
     * @return array Array of government levels (keys identical to values)
     */
    public function lookup_government_levels()
    {
        $query = $this->db
                      ->order_by('government_level_english ASC')
                      ->get('exp_member_government_level_lookup');

        $results = $query->result_array();
        $results = array_column($results, 'government_level_english', 'government_level_english');

       return $results;
    }

}
