<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_exp_project_followers_table extends CI_Migration {

    protected $table = 'exp_project_followers';

    public function up()
    {
        if (! $this->db->table_exists($this->table)) {
            $fields = array(
                'project_id' => array('type' => 'int', 'null' => FALSE),
                'follower'   => array('type' => 'int', 'null' => FALSE),
                'created_at' => array('type' => 'timestamp', 'null' => FALSE)
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('project_id', TRUE);
            $this->dbforge->add_key('follower', TRUE);
            $this->dbforge->create_table($this->table);

            $this->db->query("ALTER TABLE {$this->table} ADD CONSTRAINT {$this->table}_project_id_fkey FOREIGN KEY (project_id) REFERENCES exp_projects (pid)");
            $this->db->query("ALTER TABLE {$this->table} ADD CONSTRAINT {$this->table}_follower_fkey FOREIGN KEY (follower) REFERENCES exp_members (uid)");
            $this->db->query("CREATE INDEX {$this->table}_project_id_idx ON {$this->table} (project_id)");
            $this->db->query("CREATE INDEX {$this->table}_follower_idx ON {$this->table} (follower)");
            $this->db->query("CREATE INDEX {$this->table}_created_at_idx ON {$this->table} (created_at)");
        }
    }

    public function down()
    {
        // Drop exp_forum_project table
        if ($this->db->table_exists($this->table)) {
            $this->dbforge->drop_table($this->table);
        }
    }
}