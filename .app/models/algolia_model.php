<?php

class Algolia_model extends CI_Model {

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
	*
	* @access public
	*/
	public function get_all_experts()
	{

		$sql = "
	        SELECT m.uid, 
				firstname,
				lastname,
				organization,
				registerdate,
				title AS jobtitle,
				discipline,
				(
					SELECT json_agg(json_build_object('Sector', sector, 'Subsector', subsector))
					FROM exp_expertise_sector es
					WHERE es.uid = m.uid
					AND status = '1'
				) AS sectors,
				country,
				city,
				state,
				CASE WHEN COALESCE(userphoto, '') = '' THEN 0 ELSE 1 END has_photo,
				userphoto,
				lastlogin,
				areafocus,
				summary,
				progoals,
				success,
				('/expertise/' || m.uid) AS uri
			FROM exp_members m
			LEFT OUTER JOIN exp_expertise e ON (m.uid = e.uid)
			WHERE status = '1'
			AND membertype = 5
        ";

        $rows = $this->db->query($sql)->result_array();
        
        foreach ($rows as &$row) {
        	// Provide URL to the mini user photo (for display in dropdown search results)
        	$row['image'] = expert_image($row['userphoto'], 27, array('rounded_corners' => array('all', '2')));
        	unset($row['userphoto']);

        	$row['uid'] = (int) $row['uid'];
        	$row['objectID'] = $row['uid'];

        	$row['has_photo'] = (int) $row['has_photo'];
        	$row['lastlogin'] = (int) $row['lastlogin'];

        	$row['sectors'] = json_decode($row['sectors'], true);
        }

		return $rows;

	}

	public function save_all_experts()
	{
		$config = $this->config->item('algolia');
		$client = new \AlgoliaSearch\Client($config['application_id'], $config['admin_api_key']);
		$index = $client->initIndex($config['index_members']);

		$members = $this->get_all_experts();
		$index->saveObjects($members);
		return true;
	}

}

?>