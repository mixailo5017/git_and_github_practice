<?php
class Googleapi_model extends CI_Model {

	public function get_ga_data()
	{
		$qrysec = $this->db->get_where("exp_ga_setting",array("id"=>"1"));
		return $qrysec->row_array();
	}
	
	public function update_ga_data()
	{
		$updatedata = array(
			"profileid" => $this->input->post("profileid"),
			"api_key " => $this->input->post("api_key"),
			"clientid" => $this->input->post("clientid")
		);
		
		$this->db->where("id","1");
		if($this->db->update("exp_ga_setting",$updatedata)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function averageRecency()
	{
		$sql = "
			SELECT age(date_trunc('day', current_timestamp - avg(current_date - COALESCE(update_dates.last_date, to_timestamp(created,'MM/DD/YYYY'))))) AS days_since_last_updated 
			FROM
				(SELECT proj.pid, COALESCE(NULLIF(to_char(to_timestamp(proj.entry_date),'MM/DD/YYYY'),'01/01/1970'),'04/29/2013') AS created
				FROM exp_projects proj
				  JOIN exp_members m
				    ON (proj.uid = m.uid)
				WHERE m.status = '1'
				  AND proj.isdeleted = '0'
				  AND m.membertype IN (5,8)
				 ORDER BY proj.pid ASC) live_pids
			 LEFT OUTER JOIN 
				(SELECT t1.pid, t1.last_date
				FROM log_projects AS t1
				LEFT OUTER JOIN log_projects AS t2
				  ON t1.pid = t2.pid 
					AND (t1.last_date < t2.last_date 
					 OR (t1.last_date = t2.last_date AND t1.log_id < t2.log_id))
				JOIN exp_projects proj 
				  ON (proj.pid = t1.pid) 
				WHERE t2.pid IS NULL
				  AND proj.isdeleted = '0'
				ORDER BY t1.last_date DESC) update_dates
			ON live_pids.pid = update_dates.pid
		";
		return $this->db->query($sql)->row()->days_since_last_updated;
	}

	public function recencyGroupings()
	{
		$sql = "
			WITH intervals AS (
				SELECT 
					(current_date - COALESCE(update_dates.last_date, to_timestamp(created,'MM/DD/YYYY'))) AS days_since_last_updated,
					live_pids.pid
				FROM
					(SELECT proj.pid, COALESCE(NULLIF(to_char(to_timestamp(proj.entry_date),'MM/DD/YYYY'),'01/01/1970'),'04/29/2013') AS created
					FROM exp_projects proj
					  JOIN exp_members m
					    ON (proj.uid = m.uid)
					WHERE m.status = '1'
					  AND proj.isdeleted = '0'
					  AND m.membertype IN (5,8)
					 ORDER BY proj.pid ASC) live_pids
				 LEFT OUTER JOIN 
					(SELECT t1.pid, t1.last_date
					FROM log_projects AS t1
					LEFT OUTER JOIN log_projects AS t2
					  ON t1.pid = t2.pid 
						AND (t1.last_date < t2.last_date 
						 OR (t1.last_date = t2.last_date AND t1.log_id < t2.log_id))
					JOIN exp_projects proj 
					  ON (proj.pid = t1.pid) 
					WHERE t2.pid IS NULL
					  AND proj.isdeleted = '0'
					ORDER BY t1.last_date DESC) update_dates
				ON live_pids.pid = update_dates.pid
			)
			SELECT 
				CASE
				WHEN days_since_last_updated >= '0 days'::interval AND days_since_last_updated < '90 days'::interval THEN '1) 0–3 months'
				WHEN days_since_last_updated >= '90 days'::interval AND days_since_last_updated < '180 days'::interval THEN '2) 3–6 months'
				WHEN days_since_last_updated >= '180 days'::interval AND days_since_last_updated < '365 days'::interval THEN '3) 6–12 months'
				WHEN days_since_last_updated >= '365 days'::interval AND days_since_last_updated < '730 days'::interval THEN '4) 1–2 years'
				ELSE '5) More than 2 years'
				END agegrouping,
				count(*),
				(ROUND((count(*)::numeric / (SELECT count(*) FROM intervals)) * 100, 1) || '%') AS percentage
			FROM intervals
			GROUP BY agegrouping
			ORDER BY agegrouping;
		";

		return $this->db->query($sql)->result_object();
	}
}

?>