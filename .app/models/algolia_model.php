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
	* get_all_experts
	* 	returns all experts, formatted for Algolia
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

	/**
	 * retrieves all experts from DB and saves them to Algolia
	 * @return [string] returns 'experts' if successful
	 */
	public function save_all_experts()
	{
		$config = $this->config->item('algolia');
		$client = new \AlgoliaSearch\Client($config['application_id'], $config['admin_api_key']);
		$index = $client->initIndex($config['index_members']);

		$response = $index->clearIndex();
		$this->log_response('clear the index');

		$members = $this->get_all_experts();
		$response = $index->saveObjects($members);
		$this->log_response('save the member objects');

		return 'experts';
	}

	/**
	* get_all_projects
	* 	returns all experts, formatted for Algolia
	*
	* @access public
	*/
	public function get_all_projects()
	{

		$sql = "
	        SELECT p.pid,
				p.stage,
				p.projectname,
				('/projects/' || p.slug) AS uri,
				p.projectphoto,
				p.description,
				p.keywords,
				p.country,
				p.location,
				p.sector,
				CASE WHEN p.subsector = 'Other' THEN (
					CASE WHEN COALESCE(p.subsector_other, '') NOT IN ('0', '1', '') THEN p.subsector_other ELSE NULL END
				) ELSE p.subsector END AS subsector,
				p.totalbudget,
				CASE WHEN p.financialstructure = 'Other' THEN (
					CASE WHEN COALESCE(p.financialstructure_other, '') NOT IN ('0', '1', '.', '') THEN p.financialstructure_other ELSE NULL END
				) ELSE p.financialstructure END AS financialstructure,
				p.entry_date,
				p.eststart,
				p.estcompletion,
				p.developer,
				p.sponsor
			FROM exp_projects p
			JOIN exp_members m ON (p.uid = m.uid)
			WHERE p.isdeleted = '0'
			AND m.status = '1'
        ";

        $rows = $this->db->query($sql)->result_array();
        
        foreach ($rows as &$row) {
        	// Provide URL to the mini project photo (for display in dropdown search results)
        	$row['image'] = project_image($row['projectphoto'], 27, array('rounded_corners' => array('all', '2')));
        	unset($row['projectphoto']);

        	$row['pid'] = (int) $row['pid'];
        	$row['objectID'] = $row['pid'];

        	$row['totalbudget'] = (int) $row['totalbudget'];
        }

		return $rows;

	}

	/**
	 * retrieves all projects from DB and saves them to Algolia
	 * @return [string] returns 'projects' if successful
	 */
	public function save_all_projects()
	{
		$config = $this->config->item('algolia');
		$client = new \AlgoliaSearch\Client($config['application_id'], $config['admin_api_key']);
		$index = $client->initIndex($config['index_projects']);

		$response = $index->clearIndex();
		$this->log_response('clear the index');

		$projects = $this->get_all_projects();
		$response = $index->saveObjects($projects);
		$this->log_response('save the project objects');

		return 'projects';
	}

	/**
	 * Log the response received from the Algolia API, for debugging purposes.
	 * Only runs if called from the command line (not the admin interface)
	 * @param  [type] $response           Response output by Algolia API
	 * @param  string $commandDescription Text to be included in log file describing the command that the response refers to
	 * @return void                     
	 */
	private function log_response($response, $commandDescription = '[unspecified command]')
	{
		if (!$this->input->is_cli_request()) {
			return;
		}
		
		echo "Just issued the command to ".$commandDescription.". Got this response:\n";
		var_dump($response);

	}

}

?>