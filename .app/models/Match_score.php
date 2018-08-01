<?php

class Match_score extends CI_Model
{

    //private $projects_tbl_name = "exp_projects"; //project information table
    private $experts_tbl_name = "exp_members"; //experts table
    //private $experts_expertise_tbl_name = "exp_expertise"; //table for the expert area of focus keywords
    //private $expertise_sector_tbl_name = "exp_expertise_sector"; //holds the experts sector and subsector information.
    private $score_tbl_name = MEMBER_PROJECT_TABLE; //name of the table where we keep the member->project score information
    private $project_project_table = PROJECT_PROJECT_TABLE; //name of the project to project table
    //private $member_member_table = MEMBER_MEMBER_TABLE; //name of the member to member score table
    //private $project_log_tbl_name = "log_projects";
    //private $members_log_tbl_name = "log_members";

    /**
     * Returns the top experts for the given project id $pid
     *
     * @access public
     *
     * @param $pid int The project pid
     *
     * @return array
     *  Returned data is in the form of array[expert_uid]=score_sum
     */
    public function get_top_experts_for_project($pid)
    {
        $results = null;
        if (empty($pid)) {
            return $results;
        }

        $qry = $this->db->select(
            "m.eid,m.score_sum,m.subsector_score,m.sector_score,m.country_score,m.location_score,m.keywords_aof_score,m.keywords_aoe_score,exp.firstname,exp.lastname,exp.organization"
        )
            ->from($this->score_tbl_name . " m")
            ->join($this->experts_tbl_name . " exp", "exp.id = m.member_id")
            ->where("m.project_id", $pid)
            ->limit(10)
            ->order_by("score_sum", "desc")
            ->get();

        if ($qry->num_rows() === 0) {
            return $results;
        }

        foreach ($qry->result() as $row) {
            $ref = new ReflectionObject($row);
            foreach ($ref->getProperties() as $property) {
                $propName = $property->getName();
                $results[$row->eid][$propName] = $row->$propName;
            }
        }

        return $results;
    }

    /**
     * Returns the project id for the top matched project for a particular project. Default returns 1 value, but could be more
     *
     * @access public
     *
     * @param int $pid Project id
     * @param int $top Defaults to 1, but we could specify more than 1 project ids.
     *
     * @return array
     *
     */
    public function get_ids_for_top_projects($pid, $top = 1)
    {
        $results = array();

        if (empty($pid) || empty($top) || ! is_numeric($pid) || ! is_numeric($top)) {
            return $results;
        }

        $sql = "
        SELECT CASE WHEN project_id_1 = ? THEN project_id_2 ELSE project_id_1 END id
          FROM exp_project_project_scores s JOIN exp_projects p1
            ON s.project_id_1 = p1.pid JOIN exp_projects p2
            ON s.project_id_2 = p2.pid JOIN exp_members m1
            ON p1.uid = m1.uid JOIN exp_members m2
            ON p2.uid = m2.uid
         WHERE ? IN(project_id_1, project_id_2)
           AND p1.isdeleted = ?
           AND p2.isdeleted = ?
           AND m1.status = ?
           AND m2.status = ?
         ORDER BY score_sum DESC
         LIMIT ?
        ";

        $pid = (int) $pid; // Ensure that $pid is of type int
        $bindings = array(
            $pid, $pid,
            '0', '0', // Projects should not be in a deleted state
            STATUS_ACTIVE, STATUS_ACTIVE, // Project owners should be active (not deleted)
            (int) $top
        );

        $rows = $this->db->query($sql, $bindings)->result_array();
        if (! empty($rows)) {
            // Transform into a plain array of project ids
            $results = flatten_assoc($rows, null, 'id');
        }

        return $results;
    }
}