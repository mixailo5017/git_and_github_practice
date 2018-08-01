<?php

/*

--- USAGE ---

	CREATE NEW MESSAGE
		$this->concierge_model->uid = 479;
		$this->concierge_model->message = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.';
		$saved = $this->concierge_model->save();

		echo "<pre>"; var_dump( $saved, $this->concierge_model->errors() ); exit;

	GET ALL
		$data = $this->concierge_model->get();

	GET BY ID
		$data = $this->concierge_model->get(1);

*/

class Concierge_model extends CI_Model {

	protected $table = 'exp_concierge';

	protected $id = null;
	protected $uid;
	protected $name;
	protected $email;
    protected $membertype;
    protected $userphoto;
	protected $message;
	protected $date;
	protected $updated;
	protected $archive	= 0;
	protected $read		= 0;

	public $return_array = false;
	public $errors = array();

	public function __construct()
	{
		parent::__construct();

		if( logged_in() )
			$this->_load_user( sess_var('uid') );
	}

	public function __set($var, $val)
	{
		// if variable is there set it
		if (property_exists(__class__,$var) )
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

	public function __get($var)
	{
		// if variable is there set it
		if (in_array($var, array('email', 'message', 'date', 'name', 'userphoto', 'membertype')))
		{
			return $this->$var;
		}

		// normal model function
		return parent::__get($var);

	}

	public function save()
	{
		// validate the inputs
		if( ! $this->_validate() )
			return false;

		// constuct the update data array
		$data = array(
			'uid'		=> $this->uid,
			'name'		=> $this->name,
			'email'		=> $this->email,
			'message'	=> $this->message,
			'date'		=> time(),
			'updated'	=> time()
			);

		// add to the table table - set this id
		$this->id = $this->db->insert($this->table,$data);

		return $this;
	}

	private function _validate()
	{
		// check user uid
		if( ! $this->_load_user($this->uid, true) )
		{
			// no user found set error and stop
			$this->errors[] = 'Bad User';
			return false;
		}

		// check if there is a message
		if( empty($this->message) )
		{
			// no message found set error and stop
			$this->errors[] = 'No Message';
			return false;
		}

		// check db for duplicate message
		if( $this->_is_duplicate() )
		{
			// this is a duplicate message in db from this user
			$this->errors[] = 'Duplicate Message';
			return false;
		}

		return true;
	}

	public function archive_many($ids=array())
	{
		$this->db->where_in('id',$ids);
		$archive = array('archive'=>'1');
		return $this->db->update($this->table,$archive);
	}

	public function archive($id)
	{
		$d = $this->get($id);
		if( ! $d ) return false;

		// map current data and set archive flag
		$this->_map_data($d);
		$this->archive = 1;

		return $this->update();

	}

	public function unarchive($id)
	{
		$d = $this->get($id);
		if( ! $d ) return false;

		// map current data and set archive flag
		$this->_map_data($d);
		$this->archive = 0;

		return $this->update();
	}

	private function update()
	{
		$data = array(
			// NOT NEEDED NOW
			// 'uid' 		=> $this->uid,
			// 'name' 		=> $this->name,
			// 'email' 		=> $this->email,
			// 'message' 	=> $this->message,
			'archive' 	=> $this->archive,
			'read' 		=> $this->read,
			'updated'	=> time()
		);

		$this->db->where(array('id'=>$this->id));

		return $this->db->update($this->table,$data);

	}

	public function get_errors()
	{
		$errors = implode(' ', $this->errors);
		return $errors;
	}

	public function get($id=false)
	{

		if( $id )
		{
			$r = $this->_get_by_id($id);
			$this->_map_data($r);
		}
		else
			$r = $this->_get_all();

		return $r;
	}

	private function _get_by_id($id)
	{
		$qry = $this->db->get_where($this->table, array('id' => $id));

		// return query array if set as true
		if( $this->return_array )
			return $qry->row_array(0);

		// return normal obj
		return $qry->row(0);
	}

	private function _get_all()
	{
		//$this->db->where( array('archive'=>0) );
		$qry = $this->db->get($this->table);

		// return query array if set as true
		if( $this->return_array )
			return $qry->result_array();

		// return normal obj
		return $qry->result();
	}

	private function _is_duplicate()
	{

		$where = array('uid'=>$this->uid, 'message'=>$this->message);

		$qry = $this->db->get_where($this->table, $where);

		// message found
		if( $qry->num_rows() > 0 )
			return true;

		// no rows - this message is unique
		return false;
	}

	private function _load_user($uid, $val=false)
	{
		$qry = $this->db->select('uid, firstname, lastname, email, membertype, organization, userphoto')
						->get_where('exp_members', array('uid' => $uid));

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

	private function _map_data($data)
	{
		//echo "<pre>"; var_dump( $data ); exit;

		foreach ($data as $key => $val) 
		{
			if (property_exists(__class__, $key))
			{
				$this->$key = $val;
			}
		}

		// Set name for normal members
		if (isset($data->membertype) && $data->membertype == MEMBER_TYPE_MEMBER )
		{
			$this->name = trim($data->firstname . ' ' . $data->lastname);
		}

		// Set name for Expert Adverts
		if( isset($data->membertype) && $data->membertype == MEMBER_TYPE_EXPERT_ADVERT )
		{
			$this->name = $data->organization;
		}
	}

}

?>