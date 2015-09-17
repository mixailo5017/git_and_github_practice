<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_exp_updates_table extends CI_Migration {

    protected $table = 'exp_updates';

    public function up()
    {
        if (! $this->db->table_exists($this->table)) {
            $fields = array(
                'id'		 => array('type' => 'SERIAL', 'auto_increment' => TRUE, 'null' => FALSE, ),
                'author'     => array('type' => 'int', 'null' => FALSE),
                'type'       => array('type' => 'int', 'null' => FALSE),
                'content'    => array('type' => 'varchar', 'constraint' => 1024, 'null' => FALSE),
                'reply_to'   => array('type' => 'int', 'unsigned' => TRUE, 'null' => TRUE),
                'created_at' => array('type' => 'timestamp', 'null' => FALSE),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table($this->table);

            $this->db->query("ALTER TABLE {$this->table} ADD CONSTRAINT {$this->table}_author_fkey FOREIGN KEY (author) REFERENCES exp_members (uid)");
            $this->db->query("CREATE INDEX {$this->table}_created_at_idx ON {$this->table} (created_at)");
        }
    }

    public function down()
    {
        if ($this->db->table_exists($this->table)) {
            $this->dbforge->drop_table($this->table);
        }
    }
}