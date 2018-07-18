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


	public function get_recommendations(int $forMemberId)
	{
		$sql = "
	        WITH allmatches AS (
				SELECT member_id_2 AS uid, score_sum
				FROM exp_member_member_scores
				WHERE member_id_1 = ?
				UNION
				SELECT member_id_1 AS uid, score_sum
				FROM exp_member_member_scores 
				WHERE member_id_2 = ? 
				ORDER BY score_sum DESC
			)
			SELECT am.uid, score_sum, ('https://www.gvip.io/expertise/' || am.uid) AS url, firstname, lastname, organization
			FROM allmatches am
			JOIN exp_forum_member fm ON (fm.member_id = am.uid AND fm.forum_id = 31)
			JOIN exp_members m ON (m.uid = am.uid)
			ORDER BY score_sum DESC
			LIMIT 3;
        ";

        $bindings = [$forMemberId, $forMemberId];

        $rows = $this->db->query($sql, $bindings)->result_array();

        return $rows;
	}

}

?>