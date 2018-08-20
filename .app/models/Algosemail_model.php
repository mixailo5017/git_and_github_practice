<?php

class Algosemail_model extends CI_Model {

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
	 * For a member attending a forum, returns the top
	 * $numberOfRecommendations recommended experts ranked by least distance.
	 * No one from CG/LA will be included in the results.
	 * A maximum of one person from any one organization will be included. 
	 * @param  int    $forMemberId [description]
	 * @param  int    $forumId     [description]
	 * @return [type]              [description]
	 */
	public function get_forum_recommendations(int $forMemberId, int $forumId, int $numberOfRecommendations = 3)
	{
		$sql = "
	        WITH allmatches AS (
				SELECT member_id_2 AS uid, distance
				FROM exp_member_member_scores
				WHERE member_id_1 = ?
				UNION
				SELECT member_id_1 AS uid, distance
				FROM exp_member_member_scores 
				WHERE member_id_2 = ? 
			), distinctcompanies AS (
				SELECT DISTINCT ON (m.organization)
					am.uid, distance, ('https://www.gvip.io/expertise/' || am.uid) AS url, firstname, lastname, organization
				FROM allmatches am
				JOIN exp_forum_member fm ON (fm.member_id = am.uid AND fm.forum_id = ?)
				JOIN exp_members m ON (m.uid = am.uid)
				WHERE m.organization NOT LIKE 'CG/LA%'
				ORDER BY m.organization, distance ASC
			)
			SELECT uid, distance, url, firstname, lastname, organization
			FROM distinctcompanies
			ORDER BY distance ASC
			LIMIT ?;
        ";

        $bindings = [$forMemberId, $forMemberId, $forumId, $numberOfRecommendations];

        $rows = $this->db->query($sql, $bindings)->result_array();

        return $rows;
	}

	/**
	 * Retrieves recommendations for $forMemberId, drawn from all active 
	 * GViP members. See get_forum_recommendations for further documentation.
	 * @param  int         $forMemberId             [description]
	 * @param  int|integer $numberOfRecommendations [description]
	 * @return [type]                               [description]
	 */
	public function get_recommendations(int $forMemberId, int $numberOfRecommendations = 3)
	{
		$sql = "
	        WITH allmatches AS (
				SELECT member_id_2 AS uid, distance
				FROM exp_member_member_scores
				WHERE member_id_1 = ?
				UNION
				SELECT member_id_1 AS uid, distance
				FROM exp_member_member_scores 
				WHERE member_id_2 = ? 
			), distinctcompanies AS (
				SELECT DISTINCT ON (m.organization)
					am.uid, distance, ('https://www.gvip.io/expertise/' || am.uid) AS url, firstname, lastname, organization, userphoto, title
				FROM allmatches am
				JOIN exp_members m ON (m.uid = am.uid)
				WHERE m.organization NOT LIKE 'CG/LA%'
				ORDER BY m.organization, distance ASC
			)
			SELECT uid, distance, url, firstname, lastname, organization, userphoto, title
			FROM distinctcompanies
			ORDER BY distance ASC
			LIMIT ?;
        ";

        $bindings = [$forMemberId, $forMemberId, $numberOfRecommendations];

        $rows = $this->db->query($sql, $bindings)->result_array();

        foreach ($rows as &$expert) {
        	$expert['imageUrl'] = (ENVIRONMENT === 'production' ? BASE_URL : 'https://www.gvip.io') . expert_image($expert['userphoto'], 120);
        }

        return $rows;
	}

}

?>