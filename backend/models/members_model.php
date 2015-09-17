<?php

class Members_model extends CI_Model {

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
     * Updates user's record
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        $result = $this->db
            ->where('uid', (int) $id)
            ->set($data)
            ->update('exp_members');

        if (! $result) return false;

        return true;
    }

    public function export_csv($fields, $filter = array(), $delimiter, $new_line)
    {
        $columns = array(
            'uid' => 'uid AS "User ID"',
            'firstname' => 'firstname AS "First Name"',
            'lastname' => 'lastname AS "Last Name"',
            'email' => 'email AS "Email"',
            'title' => 'title AS "Title"',
            'organization' => 'organization AS "Organization"',
            'country' => 'country AS "Country"',
            'userphoto' => 'userphoto AS "Photo"',
            'pci' => 'pci AS "Profile Completeness Index"',
            'registerdate' => 'to_char(registerdate, \'MM/DD/YYYY\') AS "Join Date"',
            'discipline' => 'discipline AS "Discipline"',
            'annualrevenue' => 'annualrevenue AS "Annual Revenue"',
            'totalemployee' => 'totalemployee AS "Total Employees"',
            'public_status' => 'public_status AS "Org Structure"',
            'address' => 'address AS "Address"',
            'state' => 'state AS "State"',
            'postal_code' => 'postal_code AS "Postal Code"',
            'rating_overall' => 'ROUND(AVG(d.rating), 1) AS "Overall Rating"',
            'rating_count' => 'NULLIF(COUNT(DISTINCT r.rated_by), 0) AS "Number of Ratings"'
        );

        $aggregates = array('rating_overall', 'rating_count');

        if (in_array('_all', $fields)) $fields = array_keys($columns);

        $desired = array_intersect_key($columns, array_flip($fields));
        $select = implode(',', $desired);

        $group_by = implode(',', array_diff(array_keys($desired), $aggregates));

        $this->db
            ->select($select, FALSE)
            ->from('exp_members AS m')
            ->join('exp_member_pci AS i', 'm.uid = i.member_id', 'left')
            ->join('exp_member_ratings AS r', 'r.member_id = m.uid', 'left')
            ->join('exp_member_rating_details AS d', 'd.rating_id = r.id', 'left')
            ->group_by($group_by);

        $this->db->where('membertype', MEMBER_TYPE_MEMBER);

        if (empty($filter['deleted'])) {
            $this->db->where('status', STATUS_ACTIVE);
        }

        $query = $this->db
            ->order_by('firstname')
            ->order_by('lastname')
            ->get();

        $this->load->dbutil();

        return $this->dbutil->csv_from_result($query, $delimiter, $new_line);
    }

    public function experts_list()
    {
        $sql = "
        SELECT uid expert_id, firstname || ' ' || lastname expert_name
          FROM exp_members
         WHERE membertype = ?
           AND status = ?
         ORDER BY expert_name";

        $bindings = array(MEMBER_TYPE_MEMBER, STATUS_ACTIVE);

        $rows = $this->db
            ->query($sql, $bindings)
            ->result_array();

        return flatten_assoc($rows, 'expert_id', 'expert_name');
    }

    /**
     * @return array|bool
     */
    public function get_all_members()
	{
		$qry = $this->db->select("uid, firstname, lastname, organization, membertype")
						->from("exp_members")
						->where_in('membertype',array(5,8))
						->where('status','1')
						->get();
		if( ! $qry->num_rows() > 0 ) return false;

		$rows = $qry->result();

		$select = array();
		foreach( $rows as $key => $row )
		{
			$rows[$key]->name		= $row->membertype == 8 ? $row->organization : $row->firstname . ' ' . $row->lastname;
			$select[$row->uid]		= $rows[$key]->name;
		}

		return $select;
	}

    /**
     * Get Account Details of loged in user
     *
     * @access    public
     * @param string $membertype
     * @internal param $int
     * @return    array
     */
    public function get_members($membertype = null){

        $this->db->select(array("uid","firstname","lastname","email","registerdate","membertype","organization","typename","m.status","lat","lng","geocode"));
        $this->db->from("exp_members m");
        $this->db->join("exp_member_type mt","m.membertype=mt.typeid");

        if (! is_null($membertype)) {
            $this->db->where('m.membertype', (int) $membertype);
        }
        $this->db->order_by("m.firstname", "asc");

        $query_user = $this->db->get();
        $memberarray = array();
        $totalmembers = $query_user->num_rows();

        if ($totalmembers > 0) {
            $memberarray["data"] = $query_user->result_array();
        }

        $memberarray["totalmembers"] = $totalmembers;
        $memberarray["member_group"] = "";

        if (! is_null($membertype) && $totalmembers > 0) {
            $memberarray["member_group"] = $memberarray["data"][0]["typename"];
        }

        return $memberarray;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function enable($id)
    {
        return $this->set_member_status($id, STATUS_ACTIVE);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function disable($id)
    {
        return $this->set_member_status($id, STATUS_INACTIVE);
    }

    private function set_member_status($id, $status) {
        if (empty($id)) {
            return false;
        }
        $id = (int) $id;

        $this->db->where('uid', $id);
        if (! $this->db->update('exp_members', compact('status'))) {
            return false;
        }

        return true;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function delete($id)
    {
        if (empty($id)) {
            return false;
        }

        if (! is_array($id)) {
            $id = array($id);
        }

        // TODO: Wrap into a transaction
        if (! $this->db->where_in('uid', $id)->delete("exp_members")) {
            return false;
        }

        if (! $this->db->where_in('uid', $id)->update('exp_projects', array('isdeleted' => '1'))) {
            return false;
        }

        return true;
    }

    // TODO: Rewrite it with a single query!!!
    /**
     * @param string $groupid
     * @return array
     */
    public function get_member_group($groupid = '')
    {
        $query_member = $this->db->get('exp_member_type');
        /*$this->db->select(array("count(uid) as members","typeid","typename","mt.status"));

        $this->db->from("exp_member_type mt");
        $this->db->join("exp_members m","m.membertype=mt.typeid","left");
        $this->db->group_by("mt.typeid");

        $query_member = $this->db->get();
        */
        $membergrouparray = array();
        $totalgroups = $query_member->num_rows();
        if ($totalgroups > 0)
        {
            $membergrouparray = $query_member->result_array();
            foreach($membergrouparray as $row)
            {
                $getusertype = $this->db->query('select count(uid) as members from exp_members where membertype = '.$row['typeid'].'');
                $membergroup = $getusertype->row_array();
                $row['members'] = $membergroup['members'];
                $membergrouparray2[] = $row;
            }
        }
        $membergrouparray2["totalgroups"] = $totalgroups;
        return $membergrouparray2;
    }

    /**
     * @param string $groupid
     * @return array
     */
    public function get_member_group_detail($groupid = '')
    {
        $this->db->from("exp_member_type mt");
        $this->db->where("typeid",$groupid);

        $query_member = $this->db->get();
        $membergrouparray = array();
        $totalgroups = $query_member->num_rows();
        if ($totalgroups > 0)
        {
            $membergrouparray = $query_member->result_array();
        }
        $membergrouparray["totalgroups"] = $totalgroups;
        return $membergrouparray;
    }

    /**
     *
     */
    public function delete_group()
    {
        $delids = $this->input->get("delids");
        if(count($delids) > 0)
        {
            $response = array();
            $this->db->where_in("typeid",$delids);
            if($this->db->delete("exp_member_type"))
            {
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Member Group(s) Deleted Successfully";
            }

            header('Content-type: application/json');
            echo json_encode($response);
        }
    }


    /**
     * Add User
     * Insert Post data of registration from
     *
     * @access	public
     * @return	array
     */
    public function add_user()
    {
        $ret_data = array();

        $this->load->library('encrypt');

        $encrypted_password = encrypt_password($this->input->post('register_password'));


        $attendee = '0';
        if($this->input->post("member_conference") AND $this->input->post("member_conference") == "yes" ) {
            $attendee = '1';
        }

        //create registration post array
        $data = array(
                    'firstname' 	=> $this->input->post('member_first_name'),
                    'lastname' 		=> $this->input->post('member_last_name'),
                    'email' 		=> strtolower($this->input->post('email')),
                    'organization' 	=> $this->input->post('member_organization'),
                    'password' 		=> $encrypted_password["password"],
                    'status' 		=> '1',
                    'membertype'	=> $this->input->post('member_group'),
                    'registerdate'	=> date("Y-m-d H:i:s"),
                    'registerip'	=> $this->input->ip_address(),
                    'forum_attendee'=> '0',
                    'salt' 			=> $encrypted_password["salt"],
        );

        //insert into db and set session
        if($this->db->insert('exp_members', $data))
        {
            $ret_data['message']	=	'Member Added Successfully';
            return $ret_data;
        }
    }

    /**
     * Update User
     * Insert Post data of registration from
     *
     * @access	public
     * @return	array
     */
    public function update_user()
    {

        $ret_data = array();

        $this->load->library('encrypt');

        //$encrypted_password = encrypt_password($this->input->post('register_password'));

        //create registration post array

        $data = array(
                    'email' 		=> strtolower($this->input->post('expadvert_license_cemail')),
                    'organization' 	=> $this->input->post('expadvert_organizationname'),
                    'numberofseat'	=> $this->input->post('expadvert_number_of_seat'),
                    'licenseno'		=> $this->input->post('expadvert_license_no'),
                    'licensecost'	=> $this->input->post('expadvert_license_cost'),
                    'accountname'	=> $this->input->post('expadvert_license_cname'),
                    'licensestart'	=> DateFormat($this->input->post('expadvert_licensestart'),DATEFORMATDB,FALSE),
                    'licenseend'	=> DateFormat($this->input->post('expadvert_licenseend'),DATEFORMATDB,FALSE),
        );

        //insert into db and set session
        if($this->db->update('exp_members', $data))
        {
            return $ret_data;
        }
    }

    /**
     * @param $groupid
     */
    public function update_member_group($groupid)
    {
        $data = array('typename' => $this->input->post('group_title'));
        $this->db->where('typeid',$groupid);
        //insert into db and set session
        if($this->db->update('exp_member_type', $data))
        {
            redirect('/members/manage_group','refresh');
        }
    }


    /**
     * Get Account Details of loged in user
     *
     * @access	public
     * @param	int
     * @return	array
     */
    public function get_dashboard_members(){

        $this->db->select(array("uid","firstname","lastname","email","registerdate","title","userphoto","membertype","organization"));
        $this->db->order_by("registerdate","desc");

        $query_user = $this->db->get("exp_members",10,0);
        $memberarray = array();
        $totalmembers = $query_user->num_rows();
        if ($totalmembers > 0)
        {
            $memberarray["data"] = $query_user->result_array();
        }

        $memberarray["totalmembers"] = $totalmembers;
        return $memberarray;
    }

//    /**
//     * @param string $uid
//     * @return array|string
//     */
//    public function approve_request($uid = '')
//    {
//        if($uid !='')
//        {
//            $response = array();
//
//            $this->db->where(array("status"=>'0',"uid"=>$uid));
//
//            if($this->db->update("exp_members",array('status'=>'1')))
//            {
//                $response = "success";
//            }
//            else
//            {
//                $response = "";
//            }
//            return $response;
//        }
//    }

//    /**
//     * @param string $delids
//     * @return array|string
//     */
//    public function deny_request($delids='')
//    {
//        $response = '';
//
//        if(count($delids) > 0)
//        {
//            $response = array();
//            $this->db->where_in("uid",$delids);
//            if($this->db->delete("exp_members"))
//            {
//                $this->db->where_in("uid",$delids);
//                $this->db->update("exp_projects",array("isdeleted"=>"1"));
//                $response = "success";
//            }
//
//        }
//        return $response;
//    }


    /**
     * @return array
     */
    public function add_expadvert()
    {
        $ret_data = array();

        $this->load->library('encrypt');

        //$encrypted_password = encrypt_password($this->input->post('register_password'));

        //create registration post array

        $newpassword = randomPassword();
        $encryptedpassword = encrypt_password($newpassword);
        $data = array(
                    'email' 		=> strtolower($this->input->post('expadvert_license_cemail')), // change case to lower
                    'organization' 	=> $this->input->post('expadvert_organizationname'),
                    'status' 		=> '1',
                    'membertype'	=> '8',
                    'registerdate'	=> date("Y-m-d H:i:s"),
                    'registerip'	=> $this->input->ip_address(),
                    'numberofseat'	=> $this->input->post('expadvert_number_of_seat'),
                    'licenseno'		=> $this->input->post('expadvert_license_no'),
                    'licensecost'	=> $this->input->post('expadvert_license_cost'),
                    'accountname'	=> $this->input->post('expadvert_license_cname'),
                    'licensestart'	=> DateFormat($this->input->post('expadvert_licensestart'),DATEFORMATDB,FALSE),
                    'licenseend'	=> DateFormat($this->input->post('expadvert_licenseend'),DATEFORMATDB,FALSE),
                    'password'		=> $encryptedpassword["password"],
                    'salt'			=> $encryptedpassword["salt"]
                );

        //insert into db and set session
        if($this->db->insert('exp_members', $data))
        {
            $qryemail = $this->db->get_where("exp_email_template",array("id"=>"21"));
            $objemail = $qryemail->row_array();
            $email = strtolower($this->input->post('expadvert_license_cemail'));
            $to = $email;
            $subject = $objemail["emailsubject"];
            $content = $objemail["emailcontent"];
            $content = str_replace("{name}",$this->input->post('expadvert_organizationname'),$content);
            $content = str_replace("{site_name}",SITE_NAME,$content);
            //$content = str_replace("{site_url}","<a href='".base_url()."'>".base_url()."</a>",$content);
            $content = str_replace("{site_url}",base_url(),$content);
            $content = str_replace("{username}",$email,$content);
            $content = str_replace("{password}",$newpassword,$content);

            SendHTMLMail('',$to,$subject,$content);
            $ret_data['message']	=	'Expert Advert Added Successfully';
            return $ret_data;
        }
    }
}

?>