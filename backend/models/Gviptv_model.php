<?php
class Gviptv_model extends CI_Model {

    /**
     * @var string
     */
    protected $select = 'id, link, thumbnail, title, description, category,
        created_at, status';


    /**
     * @var array
     */
    protected $order_by = array(
        'created_at' => 'desc'
    );


    /**
     * Retrieving a record by primary key
     *
     * @param int $id
     * @param null $select
     * @return array
     */
    public function find($id, $select = null)
    {
        $this->base_query($select, array('id' => (int) $id));

        $row = $this->db
            ->get()
            ->result_array();

        if (count($row) > 0) {
            $row = $row[0];
        }

        return $row;
    }

    /**
     * Return an array of forums
     *
     * @param array $where
     * @param string $select
     * @param string|array $order_by
     * @param int $limit
     * @param int $offset
     * @param bool $row_count
     * @return array
     */
    public function all($where = null, $select = null, $order_by = null, $limit = null, $offset = null, $row_count = false)
    {
        $this->base_query($select, $where, $order_by, $row_count);

        if (! is_null($limit)) {
            $this->db->limit($limit, (! is_null($offset)) ? $offset : 0);
        }

        $rows = $this->db
            ->get()
            ->result_array();

        return $rows;
    }

    /**
     * Delete forum(s) by id(s)
     *
     * @param int|array $id
     * @return bool
     */
    public function delete($id)
    {
        if (! is_array($id)) {
            $id = array($id);
        }

        // BEGIN TRANSACCTION
        $this->db->trans_start();

        // And only now we can delete forum records themselves
        $this->db
            ->where_in('id', $id)
            ->delete('exp_gviptv');

        // COMMIT
        $this->db->trans_complete();
        $this->db->trans_off(); // TODO: Revisit this

        if ($this->db->trans_status() === false) {
            return false;
        }

        return true;
    }

    /**
     * @param $id
     * @param $data
     */
    public function update($id, $data)
    {
        $this->db
            ->where('id', $id)
            ->set($data)
            ->update('exp_gviptv');
    }

    /**
     * @param $data
     */
    public function create($data)
    {
        $this->db
            ->set($data)
            ->insert('exp_gviptv');
        return $this->db->insert_id();
    }


    /**
     * Get Account Details of loged in user
     *
     * @access	public
     * @param	int
     * @return	array
     */
    public function add_forum(){

        $this->db->select(array("uid","firstname","lastname","email","registerdate","membertype","organization","typename","m.status"));
        $this->db->from("exp_members m");
        $this->db->join("exp_member_type mt","m.membertype=mt.typeid");
        if($groupid != "") {
            $this->db->where(array("m.membertype"=>$groupid));
        }
        $this->db->order_by("m.firstname", "asc");

        $query_user = $this->db->get();
        $memberarray = array();
        $totalmembers = $query_user->num_rows();
        if ($totalmembers > 0)
        {
            $memberarray["data"] = $query_user->result_array();
        }

        $memberarray["totalmembers"] = $totalmembers;
        $memberarray["member_group"] = "";
        if($groupid != "" && $totalmembers > 0) {
            $memberarray["member_group"] = $memberarray["data"][0]["typename"];
        }
        return $memberarray;
    }



    public function delete_forum()
    {
        $delids = $this->input->get("delids");
        if(count($delids) > 0)
        {
            $response = array();
            $this->db->where_in("uid",$delids);
            if($this->db->delete("exp_members"))
            {
                $this->db->where_in("uid",$delids);
                $this->db->update("exp_projects",array("isdeleted"=>"1"));
                $response["status"] = "success";
                $response["msgtype"] = "success";
                $response["msg"] = "Member(s) Deleted Successfully";
            }

            header('Content-type: application/json');
            echo json_encode($response);
        }
    }


    /**
     * Update User
     * Insert Post data of registration from
     *
     * @access	public
     * @return	array
     */
    public function update_forum()
    {

        $ret_data = array();

        $this->load->library('encryption');

        //$encrypted_password = encrypt_password($this->input->post('register_password'));

        //create registration post array

        ($this->input->post('chkMembers')) ? $expID = implode(",",$this->input->post('chkMembers')) : $expID = "";
        ($this->input->post('chkProjects')) ? $projID = implode(",",$this->input->post('chkProjects')) : $projID = "";

        $data = array(
            'content' 		=> decode_iframe($this->input->post('forum_description', TRUE)),
            'experts'		=> $expID,
            'projects'		=> $projID,
            'expertcount'	=> $this->input->post('expertcount'),
            'projectcount'	=> $this->input->post('projectcount')
        );

        //insert into db and set session
        if($this->db->update('exp_forum', $data))
        {
            redirect('/forum/edit_forum','refresh');
        }
    }


    public function get_all_users_checkbox($selected= '')
    {
        $userarr = array();
        $this->db->order_by("lastname", "asc");
        $this->db->select("uid,firstname,lastname");
        $this->db->where('status', '1');
        $this->db->where('membertype', '5');
        if(sess_var("admin_uid"))
        {
            $this->db->where_not_in("uid",sess_var("admin_uid"));
        }
        $this->db->order_by("firstname", "asc");
        $qryuser = $this->db->get("exp_members");

        foreach($qryuser->result_array() as $row2)
        {
            $userarray[$row2['uid']]	=	$row2['firstname'].' '.$row2['lastname'];
        }

        return $userarray;
    }

    public function get_projects_checkbox()
    {
        //retrive user's project information from db
        $this->db->order_by("projectname", "asc");
        $this->db->select("pid,uid,projectname,slug,projectphoto,country,sector,stage,fundamental_legal");
        $qryproj = $this->db->get_where("exp_projects",array("isdeleted"=>"0"));
        $totalproj = $qryproj->num_rows();
        //$projectdata["totalproj"] = $totalproj;

        foreach($qryproj->result_array() as $row)
        {
            //$projectdata["proj"][] = $row;
            $projectdata[$row['pid']] = $row['projectname'];
        }

        return $projectdata;
    }

    public function get_forum_detail()
    {
        $qryforum = $this->db->get_where("exp_forum",array("status"=>"1"));
        $totalforum = $qryforum->num_rows();
        $forumdata["totalforum"] = $totalforum;

        foreach($qryforum->result_array() as $row)
        {
            //$projectdata["proj"][] = $row;
            $forumdata = $row;
            $forumdata['ExpID']  = $row['experts'];
            $forumdata['ProjID'] = $row['projects'];
            //$forumdata['expertcount'] = $row['expertcount'];
        }

        return $forumdata;
    }

    /**
     * update profile picture in Account Details
     * @return	boolean
     */
    public function upload_banner($file)
    {
        $update_data = array(
            'banner' 		=> $file['file_name']
        );

        $this->db->where('status', '1');

        if($str = $this->db->update('exp_forum', $update_data))
        {
            $ret_data['message']	=	'Banner Updated Successfully';
            return $ret_data;
        }
    }
    
    /**
     * Generates a base query for forums
     *
     * @param string $select
     * @param array $where
     * @param string|array $order_by
     * @param bool $row_count
     * @return void
     */
    private function base_query($select = null, $where = null, $order_by = null, $row_count = false)
    {
        $select = (! is_null($select)) ? $select : $this->select;
        $this->db
            ->from('exp_gviptv')
            ->select($select);

        $this->apply_where($where);

        $order_by = (! is_null($order_by)) ? $order_by : $this->order_by;
        $this->apply_order_by($order_by);

        if ($row_count) {
            $this->db->select('COUNT(*) OVER () AS row_count', false);
        }
    }
    
        /**
     * Receives an array of conditions and applies the to ORDER BY clause of the current query
     *
     * @param array $order_by
     * @return void
     */
    private function apply_order_by($order_by)
    {
        if (! is_null($order_by) && is_array($order_by)) {
            foreach ($order_by as $column => $direction) {
                $this->db->order_by($column, $direction);
            }
        }
    }

    /**
     * Receives an array of conditions and applies them to WHERE clause of the current query
     *
     * @param array $where
     * @return void
     */
    private function apply_where($where)
    {
        if (! is_null($where) && is_array($where)) {
            foreach ($where as $column => $value) {
                // If the key is of type int that means that it is a RAW WHERE clause.
                // Therefore we need to apply it as such
                if (is_int($column)) {
                    $this->db->where($value, null, false);
                } else {
                    $this->db->where($column, $value);
                }
            }
        }
    }

}


?>
