<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_exp_project_updates_table extends CI_Migration {

    protected $table = 'exp_project_updates';

    public function up()
    {
        if (! $this->db->table_exists($this->table)) {
            $fields = array(
                'update_id'  => array('type' => 'int', 'null' => FALSE),
                'project_id' => array('type' => 'int', 'null' => FALSE),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('update_id', TRUE);
            $this->dbforge->add_key('project_id', TRUE);
            $this->dbforge->create_table($this->table);

            $this->db->query("ALTER TABLE {$this->table} ADD CONSTRAINT {$this->table}_update_id_fkey FOREIGN KEY (update_id) REFERENCES exp_updates (id)");
            $this->db->query("ALTER TABLE {$this->table} ADD CONSTRAINT {$this->table}_project_id_fkey FOREIGN KEY (project_id) REFERENCES exp_projects (pid)");
            $this->db->query("CREATE INDEX {$this->table}_update_id_idx ON {$this->table} (update_id)");
            $this->db->query("CREATE INDEX {$this->table}_project_id_idx ON {$this->table} (project_id)");
        }
    }

    public function down()
    {
        if ($this->db->table_exists($this->table)) {
            $this->dbforge->drop_table($this->table);
        }
    }
}