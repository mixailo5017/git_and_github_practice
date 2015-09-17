<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_exp_member_followers_table extends CI_Migration {

    protected $table = 'exp_member_followers';

    public function up()
    {
        if ($this->db->table_exists($this->table)) {
            return;
        }

        $fields = array(
            'member_id' => array('type' => 'int', 'null' => FALSE),
            'follower' => array('type' => 'int', 'null' => FALSE),
            'created_at' => array('type' => 'timestamp', 'null' => FALSE)
        );
        $this->dbforge->add_field($fields);

        $this->dbforge->add_key('member_id', TRUE);
        $this->dbforge->add_key('follower', TRUE);

        $this->dbforge->create_table($this->table);

        $this->db->query("ALTER TABLE {$this->table} ADD CONSTRAINT {$this->table}_member_id_fkey FOREIGN KEY (member_id) REFERENCES exp_members (uid)");
        $this->db->query("ALTER TABLE {$this->table} ADD CONSTRAINT {$this->table}_follower_fkey FOREIGN KEY (follower) REFERENCES exp_members (uid)");
        $this->db->query("CREATE INDEX {$this->table}_member_id_idx ON {$this->table} (member_id)");
        $this->db->query("CREATE INDEX {$this->table}_follower_idx ON {$this->table} (follower)");
        $this->db->query("CREATE INDEX {$this->table}_created_at_idx ON {$this->table} (created_at)");
    }

    public function down()
    {
        if ($this->db->table_exists($this->table)) {
            $this->dbforge->drop_table($this->table);
        }
    }
}