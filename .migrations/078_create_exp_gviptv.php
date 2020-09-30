<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_exp_gviptv extends CI_Migration {

    protected $table = 'exp_gviptv';

    public function up()
    {
        // Create exp_forums table
        if (! $this->db->table_exists($this->table)) {
            $fields = array(
                'id' => array('type' => 'SERIAL', 'auto_increment' => TRUE, 'null' => 'FALSE'),
                'link' => array('type' => 'varchar', 'constraint' => 255, 'null' => FALSE),
                'thumbnail' => array('type' => 'varchar', 'constraint' => 255, 'null' => FALSE),
                'title' => array('type' => 'varchar', 'constraint' => 1024, 'null' => FALSE),
                'description' => array('type' => 'text', 'null' => TRUE),
                'category'   => array('type' => 'varchar', 'constraint' => 255, 'null' => FALSE),
                'created_at' => array('type' => 'timestamp', 'null' => TRUE)

            );
            $this->dbforge->add_field($fields);
            $this->dbforge->create_table($this->table);
            $this->dbforge->add_key('id', TRUE);

            $sql = array(
                // Default value of NOW() for created_at
                "ALTER TABLE {$this->table} ALTER created_at SET DEFAULT CURRENT_TIMESTAMP",
                "CREATE INDEX {$this->table}_status_idx ON {$this->table} (status)",
            );

            $this->execute($sql);
        }
    }

    public function down()
    {
        if ($this->db->table_exists($this->table)) {
            $this->dbforge->drop_table($this->table);
        }
    }

    private function execute($sql)
    {
        foreach ($sql as $stmt) {
            $this->db->query($stmt);
        }
    }
}