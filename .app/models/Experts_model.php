<?php

/*
--- Handles Experts Seat Actions ---

--- USAGE ---

	seat_member_cancel()

	seat_member_cancel()

	seat_cancel_account()

	seat_accept_account()

*/

class Experts_model extends CI_Model {

	private $user;
	private $email;
	private $name;
	private $uid;


	private $orgid;
	private $code;

	// ----------------------------------------------------------------
	// ----------------------------------------------------------------
	// ----------------------------------------------------------------
	

	/**
	 * __set
	 */	
	public function __set($var, $val)
	{
		// if variable is there set it
		if( property_exists(__class__,$var) )
		{

			$this->$var = $val;

			// if setting user id get all other info
			if( $var === 'uid' )
			{
				$this->_load_user($val);
			}
		}
		return $this;
	}
	// ----------------------------------------------------------------


	/**
	 * __get
	 */	
	public function __get($var)
	{
		// if variable is there set it
		if( in_array($var, array('email','message','date','name')) )
		{
			return $this->$var;
		}

		// normal model function
		return parent::__get($var);
	}
	// ----------------------------------------------------------------


	/**
	 * decrypt_string
	 * 		set email and orgid from code
	 */	
	public function decrypt_string($code=false)
	{	
		if( !$code ) return false;

		$orginal		= explode("|",new_decrypt_string($code));

		$this->email	= $orginal[0];
		$this->orgid	= $orginal[1];

		return $this;
	}
	// ----------------------------------------------------------------


	/**
	 * encrypt_string
	 * 		return code from email and orgid
	 */	
	public function encrypt_string($email=false, $orgid)
	{	

		$email = $email ? $email : $this->email;

		$url_code = new_encrypt_string($email."|".$orgid);
		
		$this->code = $url_code;

		return $url_code;
	}
	// ----------------------------------------------------------------


	/**
	 * add_member_to_seat
	 * 		
	 */	
	public function add_member_to_seat($uid,$orgid)
	{	

		$orgname = get_organization($orgid);

		$data = array("status"=>'1','organizationid'=>$orgid,'organization'=>$orgname);

		$this->db->where('uid',$uid)->update("exp_members",$data);
		unset($data);

		$where = array('uid'=>$uid,'orgid'=>$orgid);
		$qry = $this->db->where($where)->get('exp_invite_experts');

		if( $qry->num_rows() == 0 )
		{
			$data = array('uid'=>$uid,'orgid'=>$orgid,'existance'=>'1','status'=>'1');
			$db = $this->db->insert('exp_invite_experts', $data);	
		}
		else
		{
			$data = array('existance'=>'1','status'=>'1');
			$db = $this->db->where($where)->update('exp_invite_experts', $data);	
		}

		return $db;
	}
	// ----------------------------------------------------------------


	/**
	 * remove_member_to_seat
	 * 		
	 */	
	public function remove_member_to_seat($uid,$orgid)
	{	

		//$data = array('organizationid'=>'','organization'=>'');
		//$this->db->where('uid'=>$uid)->update("exp_members",$data);

		$this->db->delete('exp_invite_experts', array("uid"=>$uid,'orgid'=>$orgid));

	}
	// ----------------------------------------------------------------


	/**
	 * seat_member_account
	 * 		add member as expert
	 */	
	public function seat_member_account($encodedemail=false)
	{

		// $orginal  		= explode("|",new_decrypt_string($encodedemail));

		// $email   = $orginal[0];
		// $orgid   = $orginal[1];


		$this->decrypt_string($encodedemail);

		$email = $this->email;
		$orgid = $this->orgid;

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
	// ----------------------------------------------------------------


	/**
	 * seat_member_cancel
	 * 		remove member from expert list 
	 */	
	public function seat_member_cancel($encodedemail=false)
	{
		// $orginal  		= explode("|",new_decrypt_string($encodedemail));

		// $email = $orginal[0];
		// $orgid = $orginal[1];
	
		$this->decrypt_string($encodedemail);

		$email = $this->email;
		$orgid = $this->orgid;

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
	// ----------------------------------------------------------------


	/**
	 * seat_cancel_account
	 * 		remove member from expert and delete account if its not active
	 */	
	public function seat_cancel_account($encodedemail=false)
	{
		// $orginal  		= explode("|",new_decrypt_string($encodedemail));

		// $email = $orginal[0];
		// $orgid = $orginal[1];

		$this->decrypt_string($encodedemail);

		$email = $this->email;
		$orgid = $this->orgid;

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
	// ----------------------------------------------------------------
	

	/**
	 * seat_accept_account
	 * 		
	 */		
	public function seat_accept_account($encodedemail=false)
	{

		// $orginal  		= explode("|",new_decrypt_string($encodedemail));

		// $email = $orginal[0];
		// $orgid = $orginal[1];
		
		$this->decrypt_string($encodedemail);

		$email = $this->email;
		$orgid = $this->orgid;

		$orgname = get_organization($orgid);

		$verify_status 	= array();
		$qryuser = $this->db->get_where("exp_members",array("email"=>$email,"status"=>"2"));

		if($qryuser->num_rows() > 0)
		{
			$objuser = $qryuser->row_array();

			$firstname	 	=	$objuser['firstname'];
			$lastname 		=	$objuser['lastname'];
			$email 	   		= 	$objuser['email'];


			$this->db->where(array("email"=>$email));
			if($this->db->update("exp_members",array("status"=>'1','organizationid'=>$orgid,'organization'=>$orgname)))
			{
				$this->db->where(array("uid"=>$objuser['uid'],'orgid'=>$orgid));	
				if($this->db->update("exp_invite_experts",array("status"=>'1')))
				{
					if($str = $this->db->delete('exp_invite_experts', array("uid"=>$objuser['uid'],'status'=>'2')))
					{

						if($this->seat_confirm_email($firstname,$lastname,$email,$orgid))
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
	// ----------------------------------------------------------------


	/**
	 * get_non_expert_members
	 * 
	 * @access	public
	 * @return	boolean/string
	 */	
	public function seat_confirm_email($firstname,$lastname,$email,$organizationid)
	{
		$returnarr = array();
		if(isset($email)&&$email!='')
		{
			$qryemail = $this->db->get_where("exp_email_template",array("id"=>"19"));
			$objemail = $qryemail->row_array();

			$qryuser = $this->db->get_where("exp_members",array("uid"=>$organizationid,"status"=>"1"));

			if($qryuser->num_rows() > 0)
			{
				$objuser = $qryuser->row_array();

				$organization 		= $objuser['organization'];
				$organization_email = $objuser['email'];

				$this->db->where(array("email"=>$email));
				if($this->db->update("exp_members",array("status"=>'1','organizationid'=>$organizationid,'organization'=>$organization)))
				{
					$to = $organization_email;
					$subject = str_replace("{organization}",$organization,$objemail["emailsubject"]);
					$content = $objemail["emailcontent"];
					$content = str_replace("{name}",'Admin',$content);
					$content = str_replace("{email}",$email,$content);
					$content = str_replace("{fullname}",$firstname." ".$lastname,$content);
					$content = str_replace("{organization}",$organization,$content);
					$content = str_replace("{site_name}",SITE_NAME,$content);
					//$content = str_replace("{site_url}","<a href='".base_url()."'>".base_url()."</a>",$content);
					$content = str_replace("{site_url}",base_url(),$content);

					$htmlcontent = $this->load->view("templates/email",array("content"=>$content,"title"=>"Confirmation Mail"),TRUE);

					if(SendHTMLMail('',$to,$subject,$htmlcontent))
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
	// ----------------------------------------------------------------


	/**
	 * get_non_expert_members
	 * 		get user info from database
	 */	
	private function _load_user( $user_where,$val=false)
	{
		if( ! is_array($user_where) ) $user_where = array('uid' => $user_where);

		$qry = $this->db->select('uid, firstname, lastname, email, membertype, organization')
						->get_where('exp_members',$user_where);

		// no user found
		if( $qry->num_rows() !== 1 )
			return false;

		// are we just validation the users info
		if( $val )
			return true;

		// set user info since its not just validating one
		$user = $qry->row(0);
		$this->_map_data($user);

		return true;
	}
	// ----------------------------------------------------------------


	/**
	 * _map_data
	 * 		load data to local vars
	 */	
	private function _map_data($data)
	{
		//echo "<pre>"; var_dump( $data ); exit;

		foreach ($data as $key => $val) 
		{
			if( property_exists(__class__,$key) )
			{
				$this->$key = $val;
			}
		}

		// Set name for normal members
		if( isset($data->membertype) && $data->membertype == 5 )
		{
			$this->name = trim($data->firstname . ' ' . $data->lastname);
		}

		// Set name for Expert Adverts
		if( isset($data->membertype) && $data->membertype == 8 )
		{
			$this->name = $data->organization;
		}

	}
	// ----------------------------------------------------------------



}

// END FILE