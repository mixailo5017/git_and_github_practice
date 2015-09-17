<?php

class Profile_model extends CI_Model {

	/**
	 * run_geocode
	 * 
	 * @access	public
	 * @return	boolean/string
	 */	
	public function run_geocode($id=false)
	{

		if( $id == false ) return false;

		$qry = $this->db->where('uid',$id)->get('exp_members',50);

		if( $qry->num_rows != 1 ) return false;

		$this->load->library('mapquest');

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

	/**
	 * Get Account Details of loged in user
     *
     * TODO: Need to consolidate all member related method calls in a common model, including this one
	 *
	 * @access	public
	 * @param	int
	 * @return	array
	 */
	public function get_user($sess_uid){
		
		$this->db->where("uid ='".$sess_uid."' AND status ='1'");
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
	 * Get Account Details of loged in user 
	 *
	 * @access	public
	 * @param	int
	 * @return	array
	 */
	public function get_user_photo(){
		
		$this->db->where('uid', sess_var('uid'));
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
	 * Get List of Sectors 
	 * @return	array
	 */
	public function get_sectors(){
		$this->db->where("parentid ='0'");
		$query_sector = $this->db->get('exp_sectors');
		if ($query_sector->num_rows() > 0)
		{
			foreach($query_sector->result_array() as $row)
			{
				$result_sector[$row['sectorid']]	=	$row['sectorvalue'];
			}

			return $result_sector;
		}

	}

	/**
	 * Get List of Subsectors 
	 * @return	array
	 */
	public function get_subsectors()
	{
		$this->db->where("parentid !='0'");
		$query = $this->db->get('exp_sectors');
		if ($query->num_rows() > 0)
		{
			foreach($query->result_array() as $row)
			{
				$result[$row['parentid']]	=	$row['sectorvalue'];
			}

			return $result;
		}
	}

	/**
	 * Get Education 
	 * @return	array
	 */
	public function get_education($eduid = '')
	{
		$this->db->where('uid', sess_var('uid'));
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
	public function get_expertise()
	{
		$this->db->where('uid', sess_var('uid'));
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
	 * update Account Details
	 * @return	boolean
	 */
	public function update_user()
	{	
		$uid = (int) sess_var('uid');
		//global $lang;
		//create profile information post array
		$input = array(
            'firstname'       => $this->input->post('member_first_name', TRUE),
            'lastname'        => $this->input->post('member_last_name', TRUE),
            'organization'    => $this->input->post('member_organization', TRUE),
            'title'	          => $this->input->post('member_title', TRUE),
            'totalemployee'	  => $this->input->post('member_org_employees', TRUE),
            'annualrevenue'   => $this->input->post('member_org_annual_revenue', TRUE),
            'discipline'      => $this->input->post('member_discipline', TRUE),
            'sector'          => $this->input->post('member_sector', TRUE),
            'subsector'       => $this->input->post('member_sub_sector', TRUE),
            'subsector_other' => $this->input->post('member_sub_sector_other', TRUE),
            'country'         => $this->input->post('member_country', TRUE),
            'address'         => $this->input->post('member_address', TRUE),
            'city'            => $this->input->post('member_city', TRUE),
            'state'           => $this->input->post('member_state', TRUE),
            'postal_code'     => $this->input->post('member_postal_code', TRUE),
            'public_status'	  => $this->input->post('member_public', TRUE),
		);
        if (sess_var('usertype') == MEMBER_TYPE_EXPERT_ADVERT) {
            $input = array_merge($input, array(
                'vcontact' => $this->input->post('member_phone', TRUE),
                'mission'  => decode_iframe($this->input->post('member_mission', TRUE))
            ));
        }

        // Convert empty strings to NULLs
        $input = array_map(function($value) {
            return $value === '' ? null : $value;
        }, $input);

		$this->db
            ->where('uid', $uid)
		    ->where('status', STATUS_ACTIVE); // Not sure this is necessary in this particular case

		if ($str = $this->db->update('exp_members', $input)) {
			$this->run_geocode($uid);

			if (sess_var('usertype') == MEMBER_TYPE_EXPERT_ADVERT) {
                $ret_data['name'] = $input['organization'];
            } else {
                $ret_data['name'] = $input['firstname'] . " " . $input['lastname'];
            }
			$this->session->set_userdata($ret_data);

            // Analytics
            // Fetch PCI value
            $this->load->model('expertise_model');
            $pci = $this->expertise_model->get_pci($uid);

            $analytics = array(
                'id' => $uid,
                'user_properties' => array(
                    'firstName' => $input['firstname'],
                    'lastName' => $input['lastname'],
                    'Title' => $input['title'],
                    'Organization' => $input['organization'],
                    'Organization Structure' => $input['public_status'],
                    'Total Employees' => $input['totalemployee'],
                    'Annual Revenue' => $input['annualrevenue'],
                    'Discipline' => $input['discipline'],
                    'User Country' => $input['country'],
                    'User City' => $input['city'],
                    'State' => $input['state'],
                    'Profile Completion Index' => (int) $pci['pci'],
                ),
                'event' => array(
                    'name' => 'Profile Updated',
                    'properties' => array('id' => $uid)
                )
            );
			
			$response = array(
                'status' => 'success',
                'message' => lang('ProfileUpdatesuccessfully'),
                'isredirect' => 'yes',
                'analytics' => $analytics
            );
		} else {
            $response = array(
                'status' => 'error',
                'message' => lang('ErrorwhileupdatingProfile'),
                'isload' => 'no',
            );
		}

        sendResponse($response);
        exit;
	}

	/**
	 * send_invite_seat
	 * @return	boolean
	 */
	public function send_invite_seat($invt, $orgid)
	{
		//create profile information post array
		$newpassword = randomPassword();
		$password = encrypt_password($newpassword);
		
		//insert into db
		
		$attendee = '0';
			
		//create registration post array
		$data = array(
					'firstname' 	=> $this->input->post('first_name_'.$invt),
					'lastname' 		=> $this->input->post('last_name_'.$invt),
					'email' 		=> strtolower($this->input->post('email_'.$invt)), // apply strtolower() for a good measure
					'organization' 	=> sess_var('name'),
					'password' 		=> $password["password"],
					'status' 		=> '2',
					'membertype'	=> '5',
					'registerdate'	=> date("Y-m-d H:i:s"),
					'registerip'	=> $this->input->ip_address(),
					'forum_attendee'=> $attendee,
					'salt'			=> $password["salt"],
					'organizationid'=> $orgid
		);
		
		//insert into db and set session
		if($this->db->insert('exp_members', $data))
		{
			$insertid = $this->db->insert_id();
			if($this->db->insert('exp_invite_experts', array('uid'=>$insertid,'orgid'=>$orgid,'existance'=>'0','status'=>'2')))
			{
				$this->seat_invite_mail($insertid,$newpassword);
			}
		}

	}

	public function seat_invite_mail($insertid,$newpassword)
	{
		 
		$returnarr = array();
		$qrysel = $this->db->get_where("exp_members", array("uid"=>$insertid, "status"=>"2"));
		if ($qrysel->num_rows() > 0) {
			$objuser = $qrysel->row_array();
			$orgid	 = sess_var('uid');
			
			$qryemail = $this->db->get_where('exp_email_template', array('id' => 17));
			$objemail = $qryemail->row_array();
			
			$to = $objuser['email'];
            $to_name = $objuser['firstname']. ' ' . $objuser['lastname'];

			$subject = $objemail['emailsubject'];
			$content = $objemail['emailcontent'];
			$content = str_replace("{name}", $to_name ,$content);
			$content = str_replace("{organization}",sess_var('name'),$content);
			$content = str_replace("{username}",$to,$content);
			$content = str_replace("{password}",$newpassword,$content);
			
			$content = str_replace("{site_name}",SITE_NAME,$content);
			//$content = str_replace("{site_url}","<a href='".base_url()."'>".base_url()."</a>",$content);
			$content = str_replace("{site_url}",base_url(),$content);
				
			$url_code = new_encrypt_string($to."|".$orgid);

			$reset_url 	= base_url()."home/seat_accept_account/".$url_code;
			$cancel_url = base_url()."home/seat_cancel_account/".$url_code;

			$content = str_replace("{reset_url}",$reset_url,$content);
			$content = str_replace("{cancel_url}",$cancel_url,$content);

			
//			$htmlcontent = $this->load->view("templates/email",array("content"=>$content,"title"=>"Invitation Request"),TRUE);
            $htmlcontent = simple_mail_content($content);

            if (SendHTMLMail(null, array($to, $to_name), $subject, $htmlcontent, null, 'html')) {
				$response = array();
				$response["status"] 	= "success";
				$response["message"] 	= $this->dataLang['lang']["Yourrequestsentsuccessfully"];
				$response["isload"] 	= "no";
				
				//header('Content-type: application/json');
				echo json_encode($response);
			} 
			else
			{
				$response = array();
				$response["status"] 	= "error";
				$response["message"] 	= $this->dataLang['lang']["Errorwhilesendingrequest1"];
				$response["isload"] 	= "no";
				
				//header('Content-type: application/json');
				echo json_encode($response);
			}
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']["Errorwhilesendingrequest"];
			$response["isload"] 	= "no";
			$response["resp"]		= $insertid;
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	 * resend_invite_seat
	 * @return	boolean
	 */
	public function resend_invite_seat($expertiseid, $orgid)
	{
		$organizationname = sess_var('name');

	 
		$returnarr = array();
		$user_existance = '';
				
				
		$qrysel = $this->db->get_where('exp_members', array('uid' => $expertiseid));
		if($qrysel->num_rows() > 0)
		{
			$objuser = $qrysel->row_array();
			$qryemail = $this->db->get_where('exp_email_template', array('id' => '18'));
			$objemail = $qryemail->row_array();

            $to = $objuser['email'];
            $to_name = $objuser['firstname'] . ' ' . $objuser['lastname'];
			
			//start checking user is new or activated into the system.
			$this->db
                ->where('orgid', $orgid)
			    ->where('uid', $expertiseid);

			$query_invite = $this->db->get('exp_invite_experts');
			if ($query_invite->num_rows() > 0)
			{
				$row = $query_invite->row();
				$user_existance = $row->existance;			
			}

			$url_code = new_encrypt_string($to."|".$orgid);
			
			if (isset($user_existance) && $user_existance != '' && $user_existance == '1')
			{
				$reset_url = base_url()."home/seat_accept_account/".$url_code;
				//$cancel_url = base_url()."home/seat_cancel_account/".encryptstring($to."|",$orgid);
			}
			else
			{
				$reset_url = base_url()."home/seat_member_account/".$url_code;
				//$cancel_url = base_url()."home/seat_member_cancel/".encryptstring($to."|",$orgid);
			}
			
			//start email template
			$subject = $objemail['emailsubject'];
			$content = $objemail['emailcontent'];
			$content = str_replace("{name}", $to_name, $content);
			$content = str_replace("{organization}",sess_var('name'),$content);
			//$content = str_replace("{reset_url}","<a href='".$reset_url."'>".$reset_url."</a>",$content);
			$content = str_replace("{reset_url}",$reset_url,$content);
			
			$content = str_replace("{site_name}",SITE_NAME,$content);
			//$content = str_replace("{site_url}","<a href='".base_url()."'>".base_url()."</a>",$content);
			$content = str_replace("{site_url}",base_url(),$content);	
			
//			$htmlcontent = $this->load->view("templates/email",array("content"=>$content,"title"=>"Invitation remider"),TRUE);
            $htmlcontent = simple_mail_content($content);

            if (SendHTMLMail(null, array($to, $to_name), $subject, $htmlcontent, null, 'html'))
			{
				$response = array();
				$response["status"] 	= "success";
				$response["message"] 	= $this->dataLang['lang']["Yourrequestsentsuccessfully"];
				$response["isload"] 	= "no";
				
				//header('Content-type: application/json');
				echo json_encode($response);
			} 
			else
			{
				$response = array();
				$response["status"] 	= "error";
				$response["message"] 	= $this->dataLang['lang']["Errorwhilesendingrequest1"];
				$response["isload"] 	= "no";
				
				//header('Content-type: application/json');
				echo json_encode($response);
			}
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']["Errorwhilesendingrequest"];
			$response["isload"] 	= "no";
			$response["resp"]		= $expertiseid;
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	 * send_invite_seat
	 * @return	boolean
	 */
	public function send_member_invite_seat($invt, $orgid)
	{
		//create profile information post array
		$email = $this->input->post('email_'.$invt);
		
		$memberid = $this->get_accountid($email);
		if(!$this->isinvited($memberid,$orgid))
		{
			//insert into db
			if($this->db->insert('exp_invite_experts', array('uid'=>$memberid,'orgid'=>$orgid,'existance'=>'1','status'=>'2')))
			{
				$this->send_member_invite_mail($memberid, $orgid);
			}
		}
		else
		{
			$response = array();
			$response["status"] 	= "custom_error";
			$response["message"] 	= $this->dataLang['lang']["Requestalreadysenttotheexpert"];
			$response["isload"] 	= "no";
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}


	/**
	 * resend_invite_seat
	 * @return	boolean
	 */
	public function send_member_invite_mail($expertiseid, $orgid)
	{
		$organizationname = sess_var('name');

	 
		$returnarr = array();
		$qrysel = $this->db->get_where('exp_members', array('uid' => $expertiseid, 'status != ' => '0'));
		if ($qrysel->num_rows() > 0)
		{
			$objuser = $qrysel->row_array();
			$qryemail = $this->db->get_where('exp_email_template', array('id' => 20));
			$objemail = $qryemail->row_array();
			
			$to = $objuser['email'];
            $to_name = $objuser['firstname'] . ' ' . $objuser['lastname'];

			$subject = $objemail['emailsubject'];
			$content = $objemail['emailcontent'];
			$content = str_replace("{name}", $to_name, $content);
			$content = str_replace("{organization}",sess_var('name'),$content);
			
			$content = str_replace("{site_name}",SITE_NAME,$content);
			//$content = str_replace("{site_url}","<a href='".base_url()."'>".base_url()."</a>",$content);
			$content = str_replace("{site_url}",base_url(),$content);
			
			$reset_url = base_url()."home/seat_member_account/".encryptstring($to."|".$orgid);
			$cancel_url = base_url()."home/seat_member_cancel/".encryptstring($to."|".$orgid);
			
			
			$content = str_replace("{reset_url}",$reset_url,$content);
			$content = str_replace("{cancel_url}",$cancel_url,$content);

//			$htmlcontent = $this->load->view("templates/email",array("content"=>$content,"title"=>"Request to join an organization"),TRUE);
            $htmlcontent = simple_mail_content($content);

            if (SendHTMLMail(null, array($to, $to_name), $subject, $htmlcontent, null, 'html'))
			{
				$response = array();
				$response["status"] 	= "success";
				$response["message"] 	= $this->dataLang['lang']["Yourrequestsentsuccessfully"];
				$response["isload"] 	= "no";
				
				//header('Content-type: application/json');
				echo json_encode($response);
			} 
			else
			{
				$response = array();
				$response["status"] 	= "error";
				$response["message"] 	= $this->dataLang['lang']["Errorwhilesendingrequest1"];
				$response["isload"] 	= "no";
				
				//header('Content-type: application/json');
				echo json_encode($response);
			}
		}
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']["Errorwhilesendingrequest"];
			$response["isload"] 	= "no";
			$response["resp"]		= $expertiseid;
			//header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	/**
	 * remove_seat
	 * @return	boolean
	 */
	public function remove_seat($invt,$orgid)
	{
			
		//create registration post array

		if($str = $this->db->delete('exp_invite_experts', array("uid"=>$invt,'orgid'=>$orgid)))
		{
			$response = array();
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']["Expertisremovedsuccessfully"];
			$response["isload"] 	= "no";
			//header('Content-type: application/json');
			echo json_encode($response);
		} 
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']["Errorwhileremovingexpert"];
			$response["isload"] 	= "no";
			
			//header('Content-type: application/json');
			echo json_encode($response);
		}

	}

    public function update_photo($id, $filename)
    {
        $uid = (int) $id;
        $data = array('userphoto' => $filename);

        $this->db->where('uid', $uid);

        if (! $this->db->update('exp_members', $data)) {
            return false;
        }

        return true;
    }

    /**
     * Update profile picture in Account Details
     *
     * @param $filename
     * @return bool
     */
	public function upload_photo($filename)
    {
        $this->db->where('uid', (int)sess_var('uid'));
        if (! $this->db->update('exp_members', array('userphoto' => $filename))) {
            return false;
        }

        return true;
    }

	/**
	* update video url in Account Details
	* @return	boolean
	*/
	public function upload_video()
	{
		//create profile information post array
		$update_data = array(
					'uservideo' 		=> $this->input->post('member_video')
		);
			
		$this->db->where('uid', sess_var('uid'));
		
		if($str = $this->db->update('exp_members', $update_data))
		{
			$response = array();
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']["VideoUrlupdatedsuccessfully"];
			$response["isload"] 	= "no";
			
			header('Content-type: application/json');
			echo json_encode($response);	
		} 
		else
		{
			$response = array();
			$response["status"] 	= "error";
			$response["message"] 	= $this->dataLang['lang']["ErrorwhileupdatingVideoUrl"];
			$response["isload"] 	= "no";
			
			header('Content-type: application/json');
			echo json_encode($response);
		}
	}


	/**
	 * update expertise data in Account Details
	 * @return	json
	 */
	public function update_expertise()
	{
		
		if($this->get_expertise())
		{
		
			//create profile information post array
			$update_expertise_data = array(
						'areafocus' 		=> $this->input->post('member_areas_keywords'),
						'summary' 			=> $this->input->post('member_expertise'),
						'progoals'		 	=> $this->input->post('member_pro_goals'),
						'success'			=> $this->input->post('member_success')
			);
			
			
			$this->db->where('uid', sess_var('uid'));
			
			if($str = $this->db->update('exp_expertise', $update_expertise_data))
			{
				$response = array();
				$response["status"] 	= "success";
				$response["message"] 	= $this->dataLang['lang']["ExpertiseUpdatedsuccessfully"];
				$response["isload"] 	= "no";
				
				header('Content-type: application/json');
				echo json_encode($response);
			} 
			else
			{
				$response = array();
				$response["status"] 	= "error";
				$response["message"] 	= $this->dataLang['lang']["ErrorwhileupdatingExpertise"];
				$response["isload"] 	= "no";
				
				header('Content-type: application/json');
				echo json_encode($response);
			}

		}
		else
		{
			$add_expertise_data = array(
						'uid'			    => sess_var('uid'),
						'areafocus' 		=> $this->input->post('member_areas_keywords'),
						'summary' 			=> $this->input->post('member_expertise'),
						'progoals'		 	=> $this->input->post('member_pro_goals'),
						'success'			=> $this->input->post('member_success')
						);
		
			//insert into db
			if($this->db->insert('exp_expertise', $add_expertise_data))
			{
				$response = array();
				$response["status"] 	= "success";
				$response["message"] 	= $this->dataLang['lang']["ExpertiseUpdatedsuccessfully"];
				$response["isload"] 	= "no";
				
				header('Content-type: application/json');
				echo json_encode($response);
			}
			else
			{
				$response = array();
				$response["status"] 	= "error";
				$response["message"] 	= $this->dataLang['lang']["ErrorwhileupdatingExpertise"];
				$response["isload"] 	= "no";
				
				header('Content-type: application/json');
				echo json_encode($response);
			}
		
		}
	}

    /**
     * Check if sector/subsector already exist for this user (member)
     * @param $uid
     * @param $sector
     * @param $subsector
     * @return bool
     */
    private function check_expert_sector($uid, $sector, $subsector) {

        $exists = $this->db
            ->from('exp_expertise_sector')
            ->where('uid', $uid)
            ->where('sector', $sector)
            ->where('subsector', $subsector)
            ->count_all_results();
        if ($exists > 0) {
            $response = array(
                'status' => 'error',
                'message' => array('hdn_expert_sector_number' => 'Sector/subsector already exist.'),
                'isload' => 'no'
            );

            sendResponse($response);
            return false;
        }

        return true;
    }

    /**
     * Add Expert sector
     * @param int $numberof
     * @return    json
     */
	public function add_expert_sector($numberof = 0)
	{
		if($numberof > 0 && $numberof >=6)
		{
				$response = array();
				$response["status"] 	= "error";
				$response["message"] 	= array('hdn_expert_sector_number'=>$this->dataLang['lang']["Onlysixsectorsallowtoadd"]);
				$response["isload"] 	= "no";
				
				header('Content-type: application/json');
				echo json_encode($response);
				return FALSE;
		}

		$data = array(
            'uid'			    => sess_var('uid'),
            'sector' 			=> $this->input->post('member_sector'),
            'subsector'			=> $this->input->post('member_sub_sector'),
            'permission'		=> 'All',
            'status'			=> '1'
        );

        // Check if sector/subsector already exist for this user (member)
        if (! $this->check_expert_sector($data['uid'], $data['sector'], $data['subsector']) > 0) {
            return;
        }

		// insert into db
		if($this->db->insert('exp_expertise_sector', $data))
		{
				$response = array();
				$response["status"] 	= "success";
				$response["message"] 	= $this->dataLang['lang']["ExpertisesectorAddedsuccessfully"];
				$response["isload"] 	= "yes";
				$response["isreset"] 	= "yes";
				$response["listdiv"] 	= "expertise_sector_form";
				$response["loadurl"]	= "/profile/form_load/expertise_sector_form/view";
				
				header('Content-type: application/json');
				echo json_encode($response);	
		}
		else
		{
				$response = array();
				$response["status"] 	= "error";
				$response["message"] 	= $this->dataLang['lang']["ErrorwhileaddingSectordetails"];
				$response["isload"] 	= "no";
				
				header('Content-type: application/json');
				echo json_encode($response);
		}
	}

    /**
     * Update Expert sector
     * @param $secid
     * @param $uid
     * @return    json
     */
	public function update_expert_sector($secid, $uid)
	{
        $data = array(
            'sector' => $this->input->post('member_sector'),
            'subsector'	=> $this->input->post('member_sub_sector'),
        );

        // Check if sector/subsector already exist for this user (member)
        if (! $this->check_expert_sector($uid, $data['sector'], $data['subsector']) > 0) {
            return;
        }

		$this->db->where('uid', sess_var('uid'));
		$this->db->where('id', $secid);

		if ($this->db->update('exp_expertise_sector', $data))
		{
				$response = array();
				$response["status"] 	= "success";
				$response["message"] 	= $this->dataLang['lang']["ExpertisesectorUpdtedsuccessfully"];
				$response["isload"] 	= "yes";
				$response["listdiv"] 	= "expertise_sector_form";
				$response["loadurl"]	= "/profile/form_load/expertise_sector_form/view";
				
				header('Content-type: application/json');
				echo json_encode($response);	
		}
		else
		{
				$response = array();
				$response["status"] 	= "error";
				$response["message"] 	= $this->dataLang['lang']["ErrorwhileupdatingSectordetails"];
				$response["isload"] 	= "no";
				
				header('Content-type: application/json');
				echo json_encode($response);
		}
	}

	/**
	 * delete exprert sector
	 * @return	json
	 */
	public function delete_expert_sector($secid)
	{
		$userid = sess_var('uid');
		if($str = $this->db->delete('exp_expertise_sector', array('id' => $secid,'uid'=>$userid)))
		{
				$response = array();
				$response["status"] 	= "success";
				$response["message"] 	= $this->dataLang['lang']["ExpertSectordeletedsuccessfully"];
				$response["remove"] 	= true;
				$response["formname"] 	= 'expertise_sector_form';
				
				
				header('Content-type: application/json');
				echo json_encode($response);	
		} 
		else
		{
				$response = array();
				$response["status"] 	= "error";
				$response["message"] 	= $this->dataLang['lang']["ErrorwhiledeletingSector"];
				$response["isload"] 	= "no";
				
				header('Content-type: application/json');
				echo json_encode($response);
		}
	}


	/**
	 * Add Education
	 * @return	json
	 */

	public function add_education()
	{
		$add_education_data = array(
					'uid'			    => sess_var('uid'),
					'university' 		=> $this->input->post('education_university'),
					'degree' 			=> $this->input->post('education_degree'),
					'major'			 	=> $this->input->post('education_major'),
					'startyear'			=> $this->input->post('education_start_year'),
					'gradyear'			=> $this->input->post('education_grad_year'),
					'visibility'		=> $this->input->post('education_visibility'),
					'status'			=> '1',
					'degree_other'		=> $this->input->post('education_degree_other')
					);
		//insert into db
		if($this->db->insert('exp_education', $add_education_data))
		{
				$response = array();
				$response["status"] 	= "success";
				$response["message"] 	= $this->dataLang['lang']["EducationdetailsAddedsuccessfully"];
				$response["isload"] 	= "yes";
				$response["isreset"] 	= "yes";
				$response["listdiv"] 	= "expertise_education_form";
				$response["loadurl"]	= "/profile/form_load/expertise_education_form/view";
				
				header('Content-type: application/json');
				echo json_encode($response);	
		}
		else
		{
			$response = array();
				$response["status"] 	= "error";
				$response["message"] 	= $this->dataLang['lang']["ErrorwhileaddingEducationdetails"];
				$response["isload"] 	= "no";
				
				header('Content-type: application/json');
				echo json_encode($response);
		}
	}


	/**
	 * Update Education
	 * @param int
	 * @return	json
	 */

	public function update_education($educationid)
	{

		$update_education_data = array(
					'uid'			    => sess_var('uid'),
					'university' 		=> $this->input->post('education_university'),
					'degree' 			=> $this->input->post('education_degree'),
					'major'			 	=> $this->input->post('education_major'),
					'startyear'			=> $this->input->post('education_start_year'),
					'gradyear'			=> $this->input->post('education_grad_year'),
					'visibility'		=> $this->input->post('education_visibility'),
					'status'			=> '1',
					'degree_other'		=> $this->input->post('education_degree_other')
					);
			
			
			$this->db->where('uid', sess_var('uid'));
			$this->db->where('educationid', $educationid);
			
			if($str = $this->db->update('exp_education', $update_education_data))
			{
				$response = array();
				$response["status"] 	= "success";
				$response["message"] 	= $this->dataLang['lang']["Educationformupdatedsuccessfully"];
				$response["isload"] 	= "yes";
				$response["listdiv"] 	= "expertise_education_form";
				$response["loadurl"]	= "/profile/form_load/expertise_education_form/view";
				header('Content-type: application/json');
				echo json_encode($response);	
			} 
			else
			{
				$response = array();
				$response["status"] 	= "error";
				$response["message"] 	= $this->dataLang['lang']["ErrorwhileupdatingEducationform"];
				$response["isload"] 	= "no";
				
				header('Content-type: application/json');
				echo json_encode($response);		
			}
	}

	/**
	 * delete Education
	 * @return	json
	 */
	public function delete_education($educationid)
	{
		$userid = sess_var('uid');
		if($str = $this->db->delete('exp_education', array('educationid' => $educationid,'uid'=>$userid)))
		{
				$response = array();
				$response["status"] 	= "success";
				$response["message"] 	= $this->dataLang['lang']["Educationrecorddeletedsuccessfully"];
				$response["remove"] 	= true;
				
				header('Content-type: application/json');
				echo json_encode($response);	
		} 
		else
		{
				$response = array();
				$response["status"] 	= "error";
				$response["message"] 	= $this->dataLang['lang']["ErrorwhiledeletingEducation"];
				$response["isload"] 	= "no";
				
				header('Content-type: application/json');
				echo json_encode($response);
		}
	}

	/**
	 * Load Education
	 * @return	array
	 */

	public function load_education($formname,$type,$eduid)
	{
		$array_load['education_data'] = $this->get_education($eduid);
		$array_load['formname']		  = $formname;
		$array_load['type']		  = $type;
		
		return $array_load;
	}

	/**
	 * Load expert sector
	 * @return	array
	 */

	public function load_expertsector($formname,$type,$secid)
	{
		$array_load['sector_data'] = $this->get_expert_sectors($secid);
		$array_load['formname']		  = $formname;
		$array_load['type']		  = $type;
		
		return $array_load;
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
		$this->db->select("pid,uid,projectname,slug,projectphoto,country,sector,stage,location");
		$this->db->where('isdeleted','0');
		$this->db->where('uid', $userid);
		$query_project = $this->db->get('exp_projects');

		$totalproj = $query_project->num_rows;
		$projectdata["totalproj"] = $totalproj;
		
		foreach($query_project->result_array() as $row)
		{
			$projectdata["proj"][] = $row;
		}
		return $projectdata;
	}


	/**
	 * Get projects 
	 * (get user projects)
	 *
	 * @access	public
	 * @param	int
	 * @return	array
	 */
	public function get_org_projects($userid)
	{
		
		$this->db->select("orgid,projid,status");
		$this->db->where('isdeleted','0');
		$this->db->where('orgid', $userid);
		$query_expertadvert = $this->db->get('exp_proj_expertadvert');

		$totalproj = $query_expertadvert->num_rows;
		$projects = array();
		
		if($totalproj > 0)
		{
			foreach($query_expertadvert->result_array() as $row2)
			{
				$projects['projid'][] = $row2['projid'];
				$projects['exp_status'][$row2['projid']] = $row2['status'];
			}
			$this->db->select("pid,uid,projectname,slug,projectphoto,country,sector,stage,location");
			$this->db->where('isdeleted','0');
			$this->db->where_in('pid',$projects['projid']);
			$query_project = $this->db->get('exp_projects');
		
			$totalproj = $query_project->num_rows;
			$projectdata["totalproj"] = $totalproj;
			
			$row['exp_status'] = array();
			foreach($query_project->result_array() as $row)
			{
				$row['exp_status'] = $projects['exp_status'][$row['pid']];
				$projectdata["proj"][] = $row;
			}
			return $projectdata;
		}
		
		//retrive user's project information from db
		
	}

    /**
     * update email in Account Details
     * @param int $uid
     * @param string $password Current user's password
     * @param $new_email New email for the user
     * @return int (1 success, -1 passwords don't match, -2 error while updating the record)
     */
	public function update_email($uid, $password, $new_email)
	{
        // Check if provided password is valid
        if (! $this->checkPassword($uid, $password)) {
            return -1;
        }

        // Try to update the email for the current user
        $result = $this->db
            ->where('uid', $uid)
            ->update('exp_members', array('email' => $new_email));
		if ($result) {
            return 1;
        } else {
            return -2;
        }
	}


    /**
     * update password in Account Details
     * @param int $uid
     * @param string $old_password
     * @param string $new_password
     * @return int (1 success, -1 passwords don't match, -2 error while updating the record)
     */
	public function update_password($uid, $old_password, $new_password)
	{
        // Check if provided password is valid
        if (! $this->checkPassword($uid, $old_password)) {
            return -1;
        }

        // Hash a new password
        $hashed = encrypt_password($new_password);

        // Try to update the password to the new one
        $result = $this->db
            ->where('uid', $uid)
            ->update('exp_members', $hashed);
        if ($result) {
            return 1;
        } else {
            return -2;
        }
	}

    /**
     * Get List of expert sectors of user
     * @param string $secid
     * @return array
     */
	public function get_expert_sectors($secid = '')
	{
		$this->db->where('uid', sess_var('uid'));	
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
     * Get List of Case studies for the user
     *
     * @param $userid
     * @param string $cstudyid
     * @param string $status
     * @return array
     */
	public function get_case_studies($userid, $cstudyid = null, $status = null)
	{
		$this->db->where('uid', $userid);

		if(! is_null($cstudyid) && $cstudyid !== '') {
			$this->db->where('casestudyid', $cstudyid);
		}
        if (! is_null($status)) {
            $this->db->where('status', $status);
        }

		$result = $this->db
            ->get('exp_case_studies')
            ->result_array();
        return $result;
	}

	public function get_accountid($email)
	{
        $this->db->where('email', $email);
        $query = $this->db->get('exp_members');
        if( $query->num_rows() > 0 )
        {
            foreach($query->result_array() as $row)
            {
                $memberid = $row['uid'];
            }
            return $memberid;
	    }
	}


    /**
     * Update case studies
     *
     * @param $uploaded
     * @param string $userid
     * @param $cno
     * @return    array
     */
	public function update_case_study($uploaded, $userid = '', $cno)
	{
		
		if(sess_var('usertype')=='8')
		{
			$data = array(
                'uid'			=> sess_var('uid'),
                'name' 			=> $this->input->post('case_name_'.$cno),
                'description'	=> $this->input->post('case_description_'.$cno),
                'filename'		=> $uploaded['file_name'],
                'access'		=> '0',
                'status'		=> $this->input->post('case_status_'.$cno)
			);

			if($this->input->post('hdn_casestudyid'))
			{
				$casestudyid = $this->input->post('hdn_casestudyid');
			
				$this->db->where('uid', sess_var('uid'));
				$this->db->where('casestudyid', $casestudyid);
			
				if($str = $this->db->update('exp_case_studies', $data))
				{
					$response = array();
					$response["status"] 	= "success";
					$response["message"] 	= $this->dataLang['lang']["CasesStudyupdatedsuccessfully"];
					$response["isload"] 	= "no";
					$response["isreload"] 	= "yes";
					header('Content-type: application/json');
					echo json_encode($response);	
				} 
				else
				{
					$response = array();
					$response["status"] 	= "error";
					$response["message"] 	= $this->dataLang['lang']["ErrorwhileupdatingCaseStudy"];
					$response["isload"] 	= "no";
					
					header('Content-type: application/json');
					echo json_encode($response);		
				}
			}
			else{
					if($this->db->insert('exp_case_studies', $data))
					{
							$response = array();
							$response["status"] 	= "success";
							$response["message"] 	= $this->dataLang['lang']["CaseStudyisAddedsuccessfully"];
							$response["isload"] 	= "no";
							$response["isreload"] 	= "yes";
                            $response['casestudyid'] = $this->db->insert_id();

							header('Content-type: application/json');
							echo json_encode($response);	
					}
					else
					{
							$response = array();
							$response["status"] 	= "error";
							$response["message"] 	= $this->dataLang['lang']["ErrorwhileaddingCasestudy"];
							$response["isload"] 	= "no";
							
							header('Content-type: application/json');
							echo json_encode($response);
					}
			}
		}
	}


    /**
     * Delete Case studies
     * @param $casestudyid
     * @return json
     */
	public function delete_case_studies($casestudyid)
	{
		$userid = sess_var('uid');
		if(sess_var('usertype')=='8')
		{
			if($str = $this->db->delete('exp_case_studies', array('casestudyid' => $casestudyid,'uid'=>$userid)))
			{
					$response = array();
					$response["status"] 	= "success";
					$response["message"] 	= $this->dataLang['lang']["Casestudydeletedsuccessfully"];
					$response["remove"] 	= true;
					
					header('Content-type: application/json');
					echo json_encode($response);	
			} 
			else
			{
					$response = array();
					$response["status"] 	= "error";
					$response["message"] 	= $this->dataLang['lang']["ErrorwhiledeletingCasestudy"];
					$response["isload"] 	= "no";
					
					header('Content-type: application/json');
					echo json_encode($response);
			}
		}
		else
		{
				$response = array();
				$response["status"] 	= "error";
				$response["message"] 	= $this->dataLang['lang']["ErrorwhiledeleteingCasestudy"];
				$response["isload"] 	= "no";
				
				header('Content-type: application/json');
				echo json_encode($response);
		}
	}


	private function isinvited($uid,$orgid)
	{
	  $this->db->where('uid', $uid);
	  $this->db->where('orgid', $orgid);	  	  
	  $query = $this->db->get('exp_invite_experts');
	  if( $query->num_rows() > 0 ){ 
	  	return TRUE; 
	  } else { return FALSE; }
	}

	/*public function send_model_mail()
	{
		$msg_data = array(
			'msgfrom'		=> sess_var('uid'),
			'msgto' 		=> $this->input->post('hdn_to'),
			'msgsubject'	=> $this->input->post('model_esubject'),
			'msgmessage'	=> $this->input->post('model_emessage'),
			'msgdatetime'	=> date('Y-m-d H:i:s')
			);
			
			$toemail  	 	= $this->input->post('hdn_to');
			$fromemail 		= $this->input->post('hdn_from');
			$subjectemail 	= $this->input->post('model_esubject');
			$msgemail 		= $this->input->post('model_emessage');
			
			if(isset($toemail)&& $toemail != "")
			{
				if($str = $this->db->insert('exp_model_email', $msg_data))
				{
				
					if($str)
					{
							// get current user email and name
							$get_user = $this->get_user($fromemail);
							//$from_email = $get_user['firstname'] . " " . $get_user['lastname'] . "<" . $get_user['email'] . ">";

							$returnarr = array();
							$qrysel = $this->db->get_where("exp_members",array("uid"=>$toemail,"status"=>"1"));
							if($qrysel->num_rows() > 0)
							{
								$objuser = $qrysel->row_array();
								$qryemail = $this->db->get_where("exp_email_template",array("id"=>"22"));
								$objemail = $qryemail->row_array();
								
								$to = $objuser["email"];
								$subject = $subjectemail;
								$content = $msgemail;
								$content = str_replace("{site_name}",SITE_NAME,$content);
								$content = str_replace("{site_url}","<a href='".base_url()."'>".base_url()."</a>",$content);
								
								$htmlcontent = $this->load->view("templates/email_send_message",array(
									"content"		=> $content,
									"title"			=> "New Message Received",
									"from_name"		=> $get_user['firstname'] . " " . $get_user['lastname'],
									"from_email"	=> $get_user['email']),TRUE);
								
								if(SendHTMLMail($get_user['email'],$to,$subject,$htmlcontent))
								{
									$response = array();
									$response["status"] 	= "success";
									$response["message"] 	= "Message Send successfully.";
									$response["isload"] 	= "no";
									$response["isreload"] 	= "yes";
									header('Content-type: application/json');
									echo json_encode($response);
								}
								else
								{
									$response = array();
									$response["status"] 	= "error";
									$response["message"] 	= "Error while sending Message.";
									$response["isload"] 	= "no";
									
									header('Content-type: application/json');
									echo json_encode($response);		
								}
					
					
					}
						
				} 
				else
				{
					$response = array();
					$response["status"] 	= "error";
					$response["message"] 	= "Error while sending Message.";
					$response["isload"] 	= "no";
					
					header('Content-type: application/json');
					echo json_encode($response);		
				}
			}

		}
	 }
	 */
	 
	public function accept_projExpadv($projid,$orgid)
	{
		$update_data = array('status' 		=> '1');
		$this->db->where('projid', $projid);
		$this->db->where('orgid', $orgid);
		
		if($str = $this->db->update('exp_proj_expertadvert', $update_data))
		{
			$response = array();
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']["Projectisassociatedwithorganization"];
			$response["isload"] 	= "no";
			$response["isredirect"] 	= "yes";
			
			header('Content-type: application/json');
			echo json_encode($response);
		}
	}

	 
	 public function reject_projExpadv($projid,$orgid)
	{
		//create registration post array
		/*if($str = $this->db->delete('exp_proj_expertadvert', array("projid"=>$projid,'orgid'=>$orgid,'status'=>'1')))
		{
			$response = array();
			$response["status"] 	= "success";
			$response["message"] 	= "Request cancel successfully.";
			$response["isload"] 	= "no";
			$response["isredirect"] 	= "yes";
			
			header('Content-type: application/json');
			echo json_encode($response);
		} */
		$update_data = array('status' 		=> '0');
		$this->db->where('projid', $projid);
		$this->db->where('orgid', $orgid);
		
		if($str = $this->db->update('exp_proj_expertadvert', $update_data))
		{
			$response = array();
			$response["status"] 	= "success";
			$response["message"] 	= $this->dataLang['lang']["Organizationisnomoreassociatedwiththisproject"];
			$response["isload"] 	= "no";
			$response["isredirect"] 	= "yes";
			
			header('Content-type: application/json');
			echo json_encode($response);
		}

	}
	 
	public function get_project_links($userid)
	{
		$getprojectlinks = array();

		$this->db->where('uid',$userid);
		$this->db->where('status', '1');
		$query = $this->db->get('exp_proj_links');
		if ($query->num_rows() > 0)
		{
			foreach($query->result_array() as $row)
			{
				$getprojectlinks[]	=	$row;
			}

			return $getprojectlinks;
		}

	}


	public function add_project_link()
	{

	$userid = sess_var('uid');
		
	$linkdata = array(
				'projectname' => $this->input->post('project_name'),
				'projectlink' => $this->input->post('project_link'),
				'status'	  => '1',
				'uid'		  => $userid,
				);
		
		//insert into db and set session
		if($this->db->insert('exp_proj_links', $linkdata))
		{
			$response = array();
			$response["status"] 		= "success";
			$response["message"] 		= $this->dataLang['lang']["ProjectLinkAddedSuccessfully"];
			$response["isload"] 		= "no";
			$response["isredirect"] 	= 'yes';
			
			header('Content-type: application/json');
			echo json_encode($response);
		}

	}

	public function update_project_link($linkid,$userid)
	{
		$update_linkdata = array(
							'projectname' => $this->input->post('project_name'),
							'projectlink' => $this->input->post('project_link')
						);
		$this->db->where('linkid', $linkid);
		$this->db->where('uid', $userid);
		
		if($str = $this->db->update('exp_proj_links', $update_linkdata))
		{
			$response = array();
			$response["status"] 		= "success";
			$response["message"] 		= $this->dataLang['lang']["ProjectLinkUpdatedSuccessfully"];
			$response["isload"] 		= "no";
			$response["isredirect"] 	= 'yes';
			
			header('Content-type: application/json');
			echo json_encode($response);
		}

	}

	public function delete_projlink($linkid,$userid)
	{
		
		if($str = $this->db->delete('exp_proj_links', array("uid"=>$userid,'linkid'=>$linkid)))
		{
			$response["remove"]	= TRUE;
			//header('Content-type: application/json');
			echo json_encode($response);
		} 
	}

    private function checkPassword($uid, $password){
        // Retrieve current user's password from the database
        $user = $this->db
            ->select('password, salt')
            ->get_where('exp_members', array('uid' => $uid))
            ->row_array();

        // Make a hash from the provided password
        $hash = hash('sha512', $user['salt'] . $password);
        // If provided and original passwords don't match reutrn with an error
        if ($hash !== $user['password']) {
            return false;
        }

        return true;
    }
}
