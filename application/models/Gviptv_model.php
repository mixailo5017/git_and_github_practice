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