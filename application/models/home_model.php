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
		), WEBprojects AS (
            SELECT pid, p.country, totalbudget, p.uid, p.sector, p.subsector
              FROM exp_projects p JOIN exp_members m
                ON p.uid = m.uid
             WHERE p.isdeleted = '0'
             AND   m.status = '1'
             AND ((totalbudget <= 50E3) OR (p.uid = 492))
        ), EMcountries AS (
        	VALUES ('United Arab Emirates'), ('Afghanistan'), ('Antigua and Barbuda'), ('Anguilla'), ('Armenia'), ('Netherlands Antilles'), ('Angola'), ('Antarctica'), ('Argentina'), ('American Samoa'), ('Aruba'), ('Azerbaijan'), ('Barbados'), ('Bangladesh'), ('Burkina'), ('Bahrain'), ('Burundi'), ('Benin'), ('Brunei'), ('Bolivia'), ('Brazil'), ('Bahamas'), ('Bhutan'), ('Bouvet Island'), ('Botswana'), ('Belarus'), ('Belize'), ('Cocos (Keeling) Islands'), ('Congo {Democratic Rep}'), ('Central African Republic'), ('Congo'), ('Ivory Coast'), ('Cook Islands'), ('Chile'), ('Cameroon'), ('China'), ('Colombia'), ('Costa Rica'), ('Cuba'), ('Cape Verde'), ('Christmas Island'), ('Djibouti'), ('Dominica'), ('Dominican Republic'), ('Algeria'), ('Ecuador'), ('Egypt'), ('Western Sahara'), ('Eritrea'), ('Ethiopia'), ('Fiji'), ('Falkland Islands (Malvinas)'), ('Micronesia), (Federated States of'), ('Gabon'), ('Grenada'), ('Georgia'), ('French Guiana'), ('Ghana'), ('Gambia'), ('Guinea'), ('Guadeloupe'), ('Equatorial Guinea'), ('South Georgia and the South Sandwich Islands'), ('Guatemala'), ('Guam'), ('Guinea-Bissau'), ('Guyana'), ('Heard Island and McDonald Islands'), ('Honduras'), ('Haiti'), ('Indonesia'), ('India'), ('British Indian Ocean Territory'), ('Iraq'), ('Iran'), ('Jamaica'), ('Jordan'), ('Kenya'), ('Kyrgyzstan'), ('Cambodia'), ('Kiribati'), ('Comoros'), ('Saint Kitts and Nevis'), ('Korea), (Democratic People''s Republic of'), ('Kuwait'), ('Cayman Islands'), ('Kazakhstan'), ('Lao People''s Democratic Republic'), ('Lebanon'), ('St Lucia'), ('Sri Lanka'), ('Liberia'), ('Lesotho'), ('Libya'), ('Morocco'), ('Madagascar'), ('Marshall Islands'), ('Mali'), ('Myanmar), ({Burma}'), ('Mongolia'), ('Northern Mariana Islands'), ('Martinique'), ('Mauritania'), ('Montserrat'), ('Mauritius'), ('Maldives'), ('Malawi'), ('Mexico'), ('Malaysia'), ('Mozambique'), ('Namibia'), ('New Caledonia'), ('Niger'), ('Norfolk Island'), ('Nigeria'), ('Nicaragua'), ('Nepal'), ('Nauru'), ('Niue'), ('Oman'), ('Panama'), ('Peru'), ('French Polynesia'), ('Papua New Guinea'), ('Philippines'), ('Pakistan'), ('Pitcairn Islands'), ('Puerto Rico'), ('Palestinian Territory'), ('Palau'), ('Paraguay'), ('Qatar'), ('Reunion'), ('Rwanda'), ('Saudi Arabia'), ('Solomon Islands'), ('Seychelles'), ('South Sudan'), ('Sudan'), ('Saint Helena'), ('Sierra Leone'), ('Senegal'), ('Somalia'), ('Suriname'), ('Sao Tome & Principe'), ('El Salvador'), ('Syria'), ('Swaziland'), ('Turks and Caicos Islands'), ('Chad'), ('French Southern Territories'), ('Togo'), ('Thailand'), ('Tajikistan'), ('Tokelau'), ('Turkmenistan'), ('Tunisia'), ('Tonga'), ('Timor-Leste'), ('Trinidad & Tobago'), ('Tuvalu'), ('Tanzania'), ('Ukraine'), ('Uganda'), ('United States Minor Outlying Islands'), ('Uruguay'), ('Uzbekistan'), ('Saint Vincent and the Grenadines'), ('Venezuela'), ('Virgin Islands), (British'), ('Virgin Islands), (U.S.'), ('Vietnam'), ('Vanuatu'), ('Wallis and Futuna'), ('Samoa'), ('Yemen'), ('Mayotte'), ('South Africa'), ('Zambia'), ('Zimbabwe'), ('Saint Barthelemy'), ('Saint Martin')
    	)
		SELECT
		( SELECT COUNT(*) FROM members WHERE membertype = ?) experts,
		( SELECT COUNT(*) FROM projects ) projects,
		( SELECT (round(SUM(totalbudget) / 1E6, 1)) FROM WEBprojects ) totalvalue," . // Convert total dollars from millions to trillions, one decimal point
		"( SELECT floor((EM_PrimeSectors + RestOfWorld_PrimeSectors + EM_EnergyNonHydro + RestOfWorld_EnergyNonHydro + Global_Other) / 1E6) jobs FROM 
		    (SELECT
		        ( SELECT (SUM(totalbudget) / 1E3 * 16000) FROM WEBprojects WHERE 
		            country IN (SELECT * FROM EMcountries)
		            AND (sector = 'Transport' OR sector = 'Water' OR (sector = 'Energy' AND subsector = 'Generation — Hydro'))
		        ) EM_PrimeSectors,
		        ( SELECT (SUM(totalbudget) / 1E3 * 9600) FROM WEBprojects WHERE 
		            country NOT IN (SELECT * FROM EMcountries)
		            AND (sector = 'Transport' OR sector = 'Water' OR (sector = 'Energy' AND subsector = 'Generation — Hydro'))
		        ) RestOfWorld_PrimeSectors,
		        ( SELECT (SUM(totalbudget) / 1E3 * 11200) FROM WEBprojects WHERE 
		            country IN (SELECT * FROM EMcountries)
		            AND (sector = 'Energy' AND subsector != 'Generation — Hydro')
		        ) EM_EnergyNonHydro,
		        ( SELECT (SUM(totalbudget) / 1E3 * 6720) FROM WEBprojects WHERE 
		            country NOT IN (SELECT * FROM EMcountries)
		            AND (sector = 'Energy' AND subsector != 'Generation — Hydro')
		        ) RestOfWorld_EnergyNonHydro,
		        ( SELECT (SUM(totalbudget) / 1E3 * 8000) FROM WEBprojects WHERE 
		            sector NOT IN ('Transport', 'Energy', 'Water')
		        ) Global_Other
		    ) AS jobs_table ) jobs," . // Between 6,720 and 16,000 jobs created per $1bn invested. For spec see https://docs.google.com/spreadsheets/d/1paQhZEpi4fc5-n4j04haz2Yu2s9E7HiTh7r9zoGnMgc/edit#gid=0
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
			MEMBER_TYPE_MEMBER, // Exclude Lightning companies
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
