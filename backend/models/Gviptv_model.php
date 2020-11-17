<?php
class Gviptv_model extends CI_Model {

    /**
     * @var string
     */
    protected $select = 'id, link, thumbnail, title, description, category,
        created_at';


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
     * Return an array of videos
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
     * @param int
     * @return bool
     */
    public function delete($id)
    {
        // BEGIN TRANSACCTION
        $this->db->trans_start();

        // And only now we can delete forum records themselves
        $this->db
            ->where('id', $id)
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
            ->update('exp_gviptv', $data);
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
