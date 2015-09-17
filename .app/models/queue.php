<?php

/*
 * This model handles putting and pulling data from the queue table. Ideally would like to use names for the queues and
 * this could be used as a general queue class, but codeigniter doesn't allow passing a variable to the model constructor
 * directly. May need to just use a setter function for the queue name.
 *
 * For the time being this class is hardcoded to work with the model "match_score" as it queues project and expert ids,
 * but could be used to store any variable or object.
 */

class Queue extends CI_Model
{
    private $queue_tbl_name = QUEUE_TABLE; //....hmmmm..this should be moved into a constant and tied to the migrations file

    private $queue;

    public function __construct()
    {
        parent::__construct();
    }

    public function initialize($queue){
      if(!is_numeric($queue)) throw new InvalidArgumentException("Parameter value must be an integer");
      $this->queue = $queue;
    }

    /**
     * Pushes an item onto the queue. The contents of the function parameter is serialized.
     * @access public
     * @param $data mixed
     * @param $queue int
     * @return void
     */
    public function push($data)
    {
        if(!is_numeric($this->queue)) throw new InvalidArgumentException("The object was not initialized. Please use the initialize function to assign queue value.");

        $serialized = serialize($data);

        $insert_data = array(
            'queue'    => $this->queue,
            'data'     => $serialized,
        );

        $this->db->insert($this->queue_tbl_name,$insert_data);
    }

    /**
     * Pulls the oldest item in the queue from the MEMBER_PROJECT_MATCH_SCORE_QUEUE queue. This could be made more
     * flexible down the road to be a general queue.
     * @access public
     * @return void
     */
    public function pull()
    {
        if(!is_numeric($this->queue)) throw new InvalidArgumentException("The object was not initialized. Please use the initialize function to assign queue value.");

        $qry = $this->db->select("id,data")
                    ->from($this->queue_tbl_name)
                    ->where("queue",$this->queue)
                    ->order_by("created_at","ASC")
                    ->limit(1)
                    ->get();

        if($qry->num_rows() === 1)
        {
            $serialized = array_shift($qry->result());
            $unserialized = unserialize($serialized->data);
            $this->remove_queue_item($serialized->id);//remove the item from the queue.
            return $unserialized;
        }

        return NULL;
    }

    /**
     * Returns the number of items in the queue.
     * @access public
     * @return int
     */
    public function num_of_items()
    {

        if(!is_numeric($this->queue)) throw new InvalidArgumentException("The object was not initialized. Please use the initialize function to assign queue value.");

        $this->db->where('queue',$this->queue);
        $this->db->from($this->queue_tbl_name);
        return $this->db->count_all_results();
    }

    /**
     * Removes a queue item from the queue table with the given id.
     * @access private
     * @param int
     * @return boolean
     */
    private function remove_queue_item($id)
    {
        if(!is_numeric($this->queue)) throw new InvalidArgumentException("The object was not initialized. Please use the initialize function to assign queue value.");

        if(empty($id)) return FALSE;

        $this->db->delete($this->queue_tbl_name,array(
            'id' => $id,
        ));

        return TRUE;
    }
}