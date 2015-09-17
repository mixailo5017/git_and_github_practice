<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_exp_member_ratings_table extends CI_Migration {

    protected $table = 'exp_member_ratings';

    public function up()
    {
        if (! $this->db->table_exists($this->table)) {
            $fields = array(
                'id' => array('type' => 'SERIAL', 'auto_increment' => TRUE, 'null' => 'FALSE'),
                'member_id'  => array('type' => 'int', 'null' => FALSE),
                'rated_by'   => array('type' => 'int', 'null' => FALSE),
                'created_at' => array('type' => 'timestamp', 'null' => FALSE),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table($this->table);

            $sql = array(
                // FK for a member who is being rated
                "ALTER TABLE {$this->table}
                    ADD CONSTRAINT {$this->table}_member_id_fkey
                    FOREIGN KEY (member_id) REFERENCES exp_members (uid)",
                "CREATE INDEX {$this->table}_member_id_idx ON {$this->table} (member_id)",
                // FK for a member who is giving the rating
                "ALTER TABLE {$this->table}
                    ADD CONSTRAINT {$this->table}_rated_by_fkey
                    FOREIGN KEY (rated_by) REFERENCES exp_members (uid)",
                "CREATE INDEX {$this->table}_rated_by_idx ON {$this->table} (rated_by)",
                // CHECK constraint that prohibits self-rating
                "ALTER TABLE {$this->table}
                    ADD CONSTRAINT {$this->table}_self_rating CHECK (member_id <> rated_by)",
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