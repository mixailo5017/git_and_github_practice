<?php

class Home_model extends CI_Model {

	/**
	 * Returns array of counters
	 *   experts
	 *   projects
	 *   countries (across experts and projects)
	 * Is used on the landing (home) page
	 *
	 * @return mixed
     */
	public function get_counters()
	{
		$sql = "
		WITH members AS (
			SELECT uid, country, membertype
			  FROM exp_members
			 WHERE membertype IN(?, ?)
			   AND status = ?
		), projects AS (
			SELECT pid, p.country
			  FROM exp_projects p JOIN members m
				ON p.uid = m.uid
			 WHERE p.isdeleted = ?
		), totalvalue AS (
			SELECT (SUM(totalbudget)) AS totalbudget
			  FROM exp_projects p
			  JOIN exp_members m ON (m.uid = p.uid)
			 WHERE p.isdeleted = ?
			   AND m.status = ?
			   AND ((totalbudget <= 50E3) OR (p.uid = 492))
		)
		SELECT
		( SELECT COUNT(*) FROM members WHERE membertype = ?) experts,
		( SELECT COUNT(*) FROM projects ) projects,
		( SELECT (round(SUM(totalbudget) / 1E6, 1)) FROM totalvalue ) totalvalue," . // Convert total dollars from millions to trillions, one decimal point
		"( SELECT (floor(totalbudget / 1E3 * 30000 / 1E6)) FROM totalvalue ) jobs," . // 30,000 jobs created per $1bn invested
		"( SELECT COUNT(*) FROM (
			SELECT country FROM members WHERE country IS NOT NULL AND country <> ''
			 UNION
			SELECT country FROM projects WHERE country IS NOT NULL AND country <> ''
		)q ) countries
		";

		$bindings = array(
			MEMBER_TYPE_MEMBER,
            MEMBER_TYPE_EXPERT_ADVERT,
			STATUS_ACTIVE,
			'0',
			'0',
			STATUS_ACTIVE,
			MEMBER_TYPE_MEMBER, // Exclude ligtning companies
		);

		$row = $this->db
			->query($sql, $bindings)
			->row_array();

		return $row;
    }

	public function verify_account_mail()
	{
		$returnarr = array();
		$qrysel = $this->db->get_where("exp_members",array("email"=>sess_var('email'),"status"=>"2"));
		if($qrysel->num_rows() > 0)
		{
			$objuser = $qrysel->row_array();
			$qryemail = $this->db->get_where("exp_email_template",array("id"=>"15"));
			$objemail = $qryemail->row_array();

			$to = $objuser["email"];
			$subject = $objemail["emailsubject"];
			$content = $objemail["emailcontent"];
			$content = str_replace("{name}",$objuser["firstname"]." ".$objuser["lastname"],$content);
			$content = str_replace("{site_name}",SITE_NAME,$content);
			//$content = str_replace("{site_url}","<a href='".base_url()."'>".base_url()."</a>",$content);
			$content = str_replace("{site_url}",base_url(),$content);

			$reset_url = base_url()."home/verifyaccount/".encryptstring($to);

			//$content = str_replace("{reset_url}","<a href='".$reset_url."'>".$reset_url."</a>",$content);
			$content = str_replace("{reset_url}",$reset_url,$content);

			$htmlcontent = $this->load->view("templates/email",array("content"=>$content,"title"=>"Account Verification"),TRUE);

			if(SendHTMLMail('',$to,$subject,$htmlcontent))
			{
				$returnarr["status"] = "success";
				$returnarr["msg"] = lang('ThanksForRegistration');
			}
			else
			{
				$returnarr["status"] = "error";
				$returnarr["msg"] = lang('errowhilesendingemail');
			}
		}
		else
		{
			$returnarr["status"] = "error";
			$returnarr["msg"] = lang('emailwasnotfound');
		}

		return $returnarr;
	}

	public function verified_account($encodedemail)
	{

		$email  		= new_decrypt_string($encodedemail);
		$verify_status 	= array();
		$qryuser = $this->db->get_where("exp_members",array("email"=>$email,"status"=>"2"));

		if($qryuser->num_rows() > 0)
		{
			$objuser = $qryuser->row_array();

			$this->db->where(array("email"=>$email));
			if($this->db->update("exp_members",array("status"=>'0')))
			{
				$qryemail = $this->db->get_where("exp_email_template",array("id"=>"16"));
				$objemail = $qryemail->row_array();

				$to = 'admin@vip.com';
				//$to = 'testfunction0@gmail.com';
				$subject = $objemail["emailsubject"];
				$content = $objemail["emailcontent"];
				$content = str_replace("{name}",'Admin',$content);
				$content = str_replace("{site_name}",SITE_NAME,$content);
				//$content = str_replace("{site_url}","<a href='".base_url()."'>".base_url()."</a>",$content);
				$content = str_replace("{site_url}",base_url(),$content);
				$content = str_replace("{username}",$email,$content);

				$htmlcontent = $this->load->view("templates/email",array("content"=>$content,"title"=>"Account Verification"),TRUE);

				if(SendHTMLMail('',$to,$subject,$htmlcontent))
				{
					$verify_status = 'success';
				}
				else
				{
					$verify_status = 'error';
				}

				return $verify_status;
			}
			else
			{
				$verify_status = 'error';
				return $verify_status;
			}
		}
		else
		{
			$verify_status = 'waiting';
			return $verify_status;
		}
	}

	public function seat_member_account($encodedemail)
	{
		$orginal = explode('|', new_decrypt_string($encodedemail));

		$email   = $orginal[0];
		$orgid   = $orginal[1];
		$orgname = get_organization($orgid);

		$verify_status 	= array();
		$qryuser = $this->db->get_where("exp_members",array("email"=>$email));

		if($qryuser->num_rows() > 0)
		{
			//update member status
			$objuser = $qryuser->row_array();

			$this->db->where(array("email"=>$email));
			if($this->db->update("exp_members",array("status"=>'1','organizationid'=>$orgid,'organization'=>$orgname)))
			{
				$firstname	 	=	$objuser['firstname'];
				$lastname 		=	$objuser['lastname'];
				$email 	   		= 	$objuser['email'];


				$this->db->where(array("uid"=>$objuser['uid'],'orgid'=>$orgid));
				if($this->db->update("exp_invite_experts",array("status"=>'1')))
				{
					if($str = $this->db->delete('exp_invite_experts', array("uid"=>$objuser['uid'],'status'=>'2')))
					{
						$verify_status = 'success';
						return $verify_status;
					}
				}
				else
				{
					$verify_status = 'error';
					return $verify_status;
				}
			}
			else
			{
				$verify_status = 'error';
				return $verify_status;
			}
		}
		else
		{
			$verify_status = 'error';
			return $verify_status;
		}
	}

	public function seat_member_cancel($encodedemail)
	{
		$orginal  		= explode("|",new_decrypt_string($encodedemail));

		$email = $orginal[0];
		$orgid = $orginal[1];

		$verify_status 	= array();
		$qryuser = $this->db->get_where("exp_members",array("email"=>$email));

		if($qryuser->num_rows() > 0)
		{
			$objuser = $qryuser->row_array();

			$firstname	 	=	$objuser['firstname'];
			$lastname 		=	$objuser['lastname'];
			$email 	   		= 	$objuser['email'];
			$organization	= 	$objuser['organizationid'];

			if($str = $this->db->delete('exp_invite_experts', array("uid"=>$objuser['uid'],'orgid'=>$orgid)))
			{
				$verify_status = 'success';
				return $verify_status;
			}
			else
			{
				$verify_status = 'error';
				return $verify_status;

			}
		}
		else
		{
			$verify_status = 'error';
			return $verify_status;
		}
	}

	public function seat_cancel_account($encodedemail)
	{
		$orginal  		= explode("|",new_decrypt_string($encodedemail));

		$email = $orginal[0];
		$orgid = $orginal[1];

		$verify_status 	= array();
		$qryuser = $this->db->get_where("exp_members",array("email"=>$email,"status"=>"2"));

		if($qryuser->num_rows() > 0)
		{
			$objuser = $qryuser->row_array();

			$firstname	 	=	$objuser['firstname'];
			$lastname 		=	$objuser['lastname'];
			$email 	   		= 	$objuser['email'];

			$this->db->delete('exp_invite_experts', array("uid"=>$objuser['uid'],'orgid'=>$orgid));

			$this->db->where(array("email"=>$email));
			if($str = $this->db->delete('exp_members', array('email'=>$email,"status"=>'2')))
			{
				$verify_status = 'success';
				return $verify_status;
			}
			else
			{
				$verify_status = 'error';
				return $verify_status;

			}
		}
		else
		{
			$verify_status = 'error';
			return $verify_status;
		}
	}

	public function seat_accept_account($encodedemail=false)
	{
		list($email, $orgid) = explode("|", new_decrypt_string($encodedemail));

		$orgname = get_organization($orgid);

		$verify_status 	= array();
		$qryuser = $this->db->get_where('exp_members', array('email' => $email, 'status' => STATUS_PENDING));

		if ($qryuser->num_rows() > 0) {
			$objuser = $qryuser->row_array();

			$firstname = $objuser['firstname'];
			$lastname =	$objuser['lastname'];
			$email = $objuser['email'];

			$this->db->where(array('email' => $email));
			if ($this->db->update('exp_members', array('status' => STATUS_ACTIVE, 'organizationid' => $orgid, 'organization' => $orgname)))
			{
				$this->db->where(array('uid' => $objuser['uid'], 'orgid' => $orgid));
				if ($this->db->update('exp_invite_experts', array('status' => '1')))
				{
					if ($str = $this->db->delete('exp_invite_experts', array('uid' => $objuser['uid'], 'status' => '2')))
					{
						if ($this->seat_confirm_email($firstname, $lastname, $email, $orgid))
						{
							$verify_status = 'success';
							return $verify_status;
						}
						else
						{
							$verify_status = 'error';
							return $verify_status;
						}
					}
				}
			}
			else
			{
				$verify_status = 'error';
				return $verify_status;

			}
		}
		else
		{
			$verify_status = 'error';
			return $verify_status;
		}
	}

	public function seat_confirm_email($firstname, $lastname, $email, $organizationid)
	{
		$returnarr = array();

		if (isset($email) && $email != '')
		{
			$qryemail = $this->db->get_where('exp_email_template', array('id' => 19));
			$objemail = $qryemail->row_array();

			$qryuser = $this->db->get_where('exp_members', array('uid' => $organizationid, 'status' => '1'));

			if ($qryuser->num_rows() > 0)
			{
				$objuser = $qryuser->row_array();

				$organization = $objuser['organization'];
				$organization_email = $objuser['email'];

				$this->db->where(array('email' => $email));
				if ($this->db->update('exp_members', array('status' => '1', 'organizationid' => $organizationid, 'organization' => $organization)))
				{
					$to = $organization_email;
                    $to_name = $organization;

					$subject = str_replace("{organization}", $organization, $objemail["emailsubject"]);

					$content = $objemail["emailcontent"];
					$content = str_replace("{name}",'Admin', $content);
					$content = str_replace("{email}", $email, $content);
					$content = str_replace("{fullname}", $firstname . " " . $lastname, $content);
					$content = str_replace("{organization}", $organization, $content);
					$content = str_replace("{site_name}", SITE_NAME, $content);
					//$content = str_replace("{site_url}","<a href='".base_url()."'>".base_url()."</a>",$content);
					$content = str_replace("{site_url}", base_url(), $content);

//                    $content = nl2br($content);
//					$htmlcontent = $this->load->view('templates/email', array('content' => $content, 'title' => 'Confirmation Mail'), TRUE);
                    $htmlcontent = simple_mail_content($content);

                    if (SendHTMLMail(null, array($to, $to_name), $subject, $htmlcontent, null, 'html'))
					{
						return true;
					}
					else
					{
						return false;
					}
				}
			}
		}
		else
		{
			return false;
		}

	}
}
