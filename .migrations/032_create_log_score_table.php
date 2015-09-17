<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_log_score_table extends CI_Migration {

    protected $table = 'log_score';

    public function up()
    {
        if (! $this->db->table_exists($this->table)) {
            $fields = array(
                'id'         => array('type' => 'SERIAL', 'auto_increment' => TRUE, 'null' => FALSE),
                'proc_id'    => array('type' => 'SMALLINT', 'null' => FALSE),
                'row_count'  => array('type' => 'INT', 'null' => FALSE),
                'start_time' => array('type' => 'TIMESTAMP', 'null' => FALSE),
                'end_time'   => array('type' => 'TIMESTAMP', 'null' => FALSE),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table($this->table);
        }
    }

    public function down()
    {
        if ($this->db->table_exists($this->table)) {
            $this->dbforge->drop_table($this->table);
        }
    }
}