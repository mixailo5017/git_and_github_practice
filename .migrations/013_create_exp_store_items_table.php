<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_exp_store_items_table extends CI_Migration {

    protected $table = 'exp_store_items';

    public function up()
    {
        if (! $this->db->table_exists($this->table)) {
            $fields = array(
                'id'    => array('type' => 'SERIAL', 'auto_increment' => TRUE, 'null' => FALSE),
                'title' => array('type' => 'varchar', 'constraint' => 255, 'null' => FALSE),
                'url'   => array('type' => 'varchar', 'constraint' => 255, 'null' => FALSE),
                'photo' => array('type' => 'varchar', 'constraint' => 255, 'null' => TRUE),
                'created_at' => array('type' => 'timestamp', 'null' => TRUE),
                'updated_at' => array('type' => 'timestamp', 'null' => TRUE)
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