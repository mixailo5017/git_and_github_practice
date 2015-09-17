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

class Activity_log_model extends CI_Model {

	protected $member_table		= 'log_members';
	protected $project_table	= 'log_projects';

	// List of Project Tables without prefix and their Labels
	public $table_names = array(
		'comment' => 'Comment',
		'assessment' => 'Assessment',
		'design_issues' => 'Design Issue',
		'engg_fundamental' => 'Engineering Fundamental',
		'environment' => 'Environment',
		'executive' => 'Executive',
		'expertadvert' => 'Expert Advert',
		'files' => 'File',
		'financial' => 'Financial',
		'fund_sources' => 'Fund Source',
		'investment_return' => 'Investment Return',
		'machinery' => 'Machinery',
		'map_points' => 'Map Point',
		'organization' => 'Oganization',
		'participant_company' => 'Company',
		'participant_critical' => 'Critical',
		'participant_owner' => 'Owner',
		'participant_political' => 'Political',
		'participant_public' => 'Public',
		'procurement_services' => 'Procurement Service',
		'procurement_technology' => 'Procurement Technology',
		'regulatory' => 'Regulatory',
		'studies' => 'Study'
		);

	// List of Member Field names and their Labels
	public $member_fields = array(
		'firstname' 		=> 'Firstname',
		'lastname' 			=> 'Lastname',
		'email' 			=> 'Email',
		'organization' 		=> 'Organization',
		'status' 			=> 'Status',
		'title' 			=> 'Title',
		'totalemploye' 		=> 'Total Employees',
		'annualrevenu' 		=> 'Annual Revenue',
		'discipline' 		=> 'Discipline',
		'sector' 			=> 'Sector',
		'subsector' 		=> 'Sub-Sector',
		'subsector_ot' 		=> 'Sub-Sector Other',
		'country' 			=> 'Country',
		'city' 				=> 'City',
		'state' 			=> 'State',
		'userphoto' 		=> 'Photo',
		'uservideo' 		=> 'Video',
		'forum_attend' 		=> 'Forum',
		);

	// List of Projects Field names and their Labels
	public $project_fields = array(
		'stage' 					=> 'Stage',
		'projectname' 				=> 'Name',
		'slug' 						=> 'Slug',
		'projectphoto' 				=> 'Photo',
		'description' 				=> 'Description',
		'keywords' 					=> 'Keywords',
		'country' 					=> 'Country',
		'location' 					=> 'Location',
		'sector' 					=> 'Sector',
		'subsector' 				=> 'Subsector',
		'subsector_other' 			=> 'Subsector Other',
		'totalbudget' 				=> 'Total Budget',
		'financialstructure' 		=> 'Financial Structure',
		'financialstructure_other' 	=> 'Financial Structure Other',
		'fundamental_legal' 		=> 'Fundamental Legal',
		'isforum' 					=> 'Forum',
		'status' 					=> 'Status',
		'entry_date' 				=> 'Date',
		'eststart' 					=> 'Est Start',
		'estcompletion' 			=> 'Est Completion',
		'developer' 				=> 'Developer',
		'sponsor' 					=> 'Sponsor',
		'geocode' 					=> 'Geocode',
		);

	/**
	* Constructor
	* Called when the object is created
	*
	* @access public
	*/
	public function __construct()
	{
		parent::__construct();
	}


	/**
	* get_new_projects
	* 	returns collection of newly created projects
	*
	* @access public
	*/
	public function get_new_projects($limit=15)
	{

		$qry = $this->db->from('exp_projects')
						->order_by('pid','DESC')
						->limit($limit)
						->get();

		if( ! $qry->num_rows() > 0 ) return false;

		$rows = $qry->result();
		foreach( $rows as $key => $row )
		{

		//	$rows[$key]->updated	= strtotime($row->last_date);
		//	$rows[$key]->timeago	= DateDiffernece( date("Y-m-d H:i:s"), $row->last_date );
		}

		return $rows;

	}

	/**
	* get_project_updates
	* 	returns collection of updated projects
	*
	* @access public
	*/
	public function get_project_updates($limit=15)
	{

		if( ! $this->db->table_exists('log_projects') ) return false;

		$fields = "CASE
					WHEN l.fields = 'INSERT' THEN 'Added'
					WHEN l.fields = 'DELETE' THEN 'Removed'
					WHEN l.fields = 'UPDATE' THEN 'Added'
					ELSE l.fields
				END";

		$qry = $this->db->select("p.*, substring(l.table_name,10) AS t, $fields, l.last_date",false)
						->from('log_projects AS l')
						->join('exp_projects AS p','l.pid = p.pid')
						->where('l.fields <>','')
						->order_by('l.last_date','DESC')
						->limit($limit)
						->get();

		if( ! $qry->num_rows() > 0 ) return false;

		$rows = $qry->result();
		foreach( $rows as $key => $row )
		{

			$rows[$key]->fields = $this->project_fields_label($row->fields);
			if( $row->t != 'cts' )
			{
				$rows[$key]->fields = $this->project_table_label($row->t) . ' ' . $row->fields;
			}

			$rows[$key]->updated	= strtotime($row->last_date);
			$rows[$key]->timeago	= DateDiffernece( date("Y-m-d H:i:s"), $row->last_date );
		}

		return $rows;

	}

	/**
	* get_member_updates
	* 	returns collection of updated members
	*
	* @access public
	*/
	public function get_member_updates($limit=15)
	{

		if( ! $this->db->table_exists('log_members') ) return false;

		$qry = $this->db->select("m.*, l.fields, l.last_date",false)
				->from('log_members AS l')
				->join('exp_members AS m','l.uid = m.uid')
				->where('l.fields <>','')
				->where('l.fields <>',' geocode')
				->order_by('l.last_date','DESC')
				->limit($limit)
				->get();

		if( ! $qry->num_rows() > 0 ) return false;

		$rows = $qry->result();

		foreach( $rows as $key => $row )
		{

			$rows[$key]->fields = $this->project_fields_label($row->fields);
			$rows[$key]->title 		= $row->membertype == 8 ? 'Expert Advert' : $row->title;
			$rows[$key]->name		= $row->membertype == 8 ? $row->organization : $row->firstname . ' ' . $row->lastname;
			$rows[$key]->updated	= strtotime($row->last_date);
			$rows[$key]->timeago	= DateDiffernece( date("Y-m-d H:i:s"), $row->last_date );
		}

		return $rows;
	}

	/**
	* project_table_label
	* 	returns table lable from above array
	*
	* @access public
	*/
	public function project_table_label($name)
	{
		if( isset($this->table_names[$name]) ) return $this->table_names[$name];
		return $name;
	}

	/**
	* project_fields_label
	* 	returns field lable from above array
	*
	* @access public
	*/
	public function project_fields_label($fields)
	{
		$fields = explode(' ', trim($fields) );

		foreach( $fields as $key => $value )  
		{
			if( isset($this->project_fields[$value]) )
			{
				$fields[$key] = $this->project_fields[$value];
			}
		}

		return implode(', ', $fields);

	}

	/**
	* member_fields_label
	* 	returns field lable from above array
	*
	* @access public
	*/
	public function member_fields_label($fields)
	{
		$fields = explode(' ', trim($fields) );

		foreach( $fields as $key => $value )  
		{
			if( isset($this->member_fields[$value]) )
			{
				$fields[$key] = $this->member_fields[$value];
			}
		}

		return implode(', ', $fields);

	}

}

?>