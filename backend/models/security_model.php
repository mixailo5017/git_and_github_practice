<?php
class Security_model extends CI_Model {

	public function get_banning_data()
	{
		$qrysec = $this->db->get_where("exp_member_ban",array("id"=>"1"));
		return $qrysec->row_array();
	}
	
	public function update_banning_data()
	{
		$updatedata = array(
			"bannedips" => $this->input->post("banned_ips"),
			"bannedemails" => $this->input->post("banned_emails"),
			"modifieddate" => date("Y-m-d H:i:s")
		);
		
		$this->db->where("id","1");
		if($this->db->update("exp_member_ban",$updatedata)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function get_throttling_data()
	{
		$qrythr = $this->db->get_where("exp_throttle",array("id"=>"1"));
		return $qrythr->row_array();
	}
	
	public function update_throttling_data()
	{
		$updatedata = array(
			"isenabled"	=> $this->input->post("isenabled"),
			"noipdenyenabled" => $this->input->post("noipdeny"),
			"maxpageloads" => $this->input->post("max_page_loads"),
			"timeinterval" => $this->input->post("time_interval"),
			"lockouttime" => $this->input->post("lockout_time"),
			"action" => $this->input->post("action"),
			"urlredirect" => $this->input->post("urlredirect"),
			"custommsg" => $this->input->post("custommsg"),
			"modifieddate" => date("Y-m-d H:i:s")
		);
		
		$this->db->where("id","1");
		if($this->db->update("exp_throttle",$updatedata)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

}

?>