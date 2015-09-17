<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_delete_exp_forum_comments_table extends CI_Migration {

    protected $table = 'exp_forum_comments';

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
            'id' => array('type' => 'SERIAL', 'auto_increment' => TRUE, 'null' => FALSE),
            'uid' => array('type' => 'BIGINT', 'null' => TRUE),
            'comment' => array('type' => 'TEXT', 'null' => TRUE),
            'commentdate' => array('type' => 'TIMESTAMP', 'null' => TRUE)
        );
        $this->dbforge->add_field($fields);

        $this->dbforge->add_key('id', TRUE);

        $this->dbforge->create_table($this->table);
    }
}