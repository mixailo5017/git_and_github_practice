<?php
//if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Matches_lib
 *
 * @author Goce Evtimovski goce@viminteractive.com
 *
 * Description: This class is used as a utility function for the match score calculations between projects and experts.
 * The controller and model files for the algorithm functionality is in the backend folder of the applications. This
 * is a helper class so we could use the backend algorithm functions in the application/frontend of the VIP application.
 *
 * This library helper is used during project or expert update on the front end side.
 */
class Matches_lib
{

    /* Private variables */
    private $ci = null; //code igniter global object
    private $type;
    private $queue;

    /**
     * @param $entity_type
     */
    public function __construct($entity_type)
    {
        $this->ci =& get_instance();

        //load the match_score model and the queue model
        $this->ci->load->model("match_score");

        //This class will eat up a lot of memory and will cause memory issues. We don't want CI to store queries in this case
        //$this->ci->db->save_queries = false;
    }


    public function get_top_experts_for_project($pid)
    {
        return $this->ci->match_score->get_top_experts_for_project($pid);
    }

    public function get_top_project_for_each($ids)
    {
        $results = array();

        if (empty($ids) || !is_array($ids)) {
            return $results;
        }

        foreach ($ids as $pid) {
            $top_project = $this->ci->match_score->get_ids_for_top_projects($pid);
            $top_id = array_shift($top_project);
            if (!empty($top_id)) {
                $results[$pid] = $top_id;
            }

        }

        return $results;
    }
}