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
}

?>