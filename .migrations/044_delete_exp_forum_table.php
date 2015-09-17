<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_delete_exp_forum_table extends CI_Migration {

    protected $table = 'exp_forum';

    public function up()
    {
        if ($this->db->table_exists($this->table)) {
            $this->dbforge->drop_table($this->table);
        }
    }

    public function down()
    {
        if ($this->db->table_exists($this->table)) {
            return;
        }

        $fields = array(
            'forumid' => array('type' => 'SERIAL', 'auto_increment' => TRUE, 'null' => FALSE),
            'banner' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE),
            'content' => array('type' => 'TEXT', 'null' => TRUE),
            'experts' => array('type' => 'TEXT', 'null' => TRUE),
            'projects' => array('type' => 'TEXT', 'null' => TRUE),
            'status' => array('type' => 'VARCHAR', 'constraint' => 1, 'null' => TRUE),
            'date' => array('type' => 'TIMESTAMP', 'null' => TRUE),
            'expertcount' => array('type' => 'BIGINT', 'null' => TRUE),
            'projectcount' => array('type' => 'BIGINT', 'null' => TRUE),
        );
        $this->dbforge->add_field($fields);

        $this->dbforge->add_key('forumid', TRUE);

        $this->dbforge->create_table($this->table);
    }
}