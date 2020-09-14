<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_exp_proj_likes_table extends CI_Migration {

    protected $table = 'exp_proj_likes';

    public function up()
    {
        if (! $this->db->table_exists($this->table)) {
            $fields = array(
                'id' => array('type' => 'SERIAL', 'auto_increment' => TRUE, 'null' => 'FALSE'),
                'proj_id'  => array('type' => 'int', 'null' => FALSE),
                'rated_by'   => array('type' => 'int', 'null' => FALSE),
                'created_at' => array('type' => 'timestamp', 'null' => FALSE),
                'isliked'     => array('type' => 'smallint', 'null' => FALSE),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->create_table($this->table);
            $this->dbforge->add_key('id', TRUE);

            $sql = array(
                // FK for a project that is being rated
                "ALTER TABLE {$this->table}
                    ADD CONSTRAINT {$this->table}_proj_id_fkey
                    FOREIGN KEY (proj_id) REFERENCES exp_projects (pid)",
                "CREATE INDEX {$this->table}_proj_id_idx ON {$this->table} (proj_id)",
                // FK for a member who is giving the rating
                "ALTER TABLE {$this->table}
                    ADD CONSTRAINT {$this->table}_rated_by_fkey
                    FOREIGN KEY (rated_by) REFERENCES exp_members (uid)",
                "CREATE INDEX {$this->table}_rated_by_idx ON {$this->table} (rated_by)",
                // Default value of NOW() for created_at
                "ALTER TABLE {$this->table} ALTER created_at SET DEFAULT CURRENT_TIMESTAMP",
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