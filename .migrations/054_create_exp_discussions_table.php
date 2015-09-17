<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_exp_discussions_table extends CI_Migration {

    protected $table = 'exp_discussions';

    public function up()
    {
        if (! $this->db->table_exists($this->table)) {
            $fields = array(
                'id'		  => array('type' => 'SERIAL', 'auto_increment' => TRUE, 'null' => FALSE, ),
                'title'       => array('type' => 'varchar', 'constraint' => 255, 'null' => FALSE),
                'description' => array('type' => 'varchar', 'constraint' => 1024, 'null' => TRUE),
                'project_id'  => array('type' => 'int', 'null' => FALSE),
                'created_at'  => array('type' => 'timestamp', 'null' => FALSE),
                'deleted_at'  => array('type' => 'timestamp', 'null' => TRUE),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table($this->table);

            $sql = array(
                "ALTER TABLE {$this->table} ALTER created_at SET DEFAULT CURRENT_TIMESTAMP",
                "ALTER TABLE {$this->table}
                    ADD CONSTRAINT {$this->table}_project_id_fkey
                    FOREIGN KEY (project_id) REFERENCES exp_projects (pid)",
                "CREATE INDEX {$this->table}_project_id_idx ON {$this->table} (project_id)",
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

    private function execute($sql) {
        foreach ($sql as $stmt) {
            $this->db->query($stmt);
        }
    }
}