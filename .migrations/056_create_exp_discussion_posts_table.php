<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_exp_discussion_posts_table extends CI_Migration {

    protected $table = 'exp_discussion_posts';

    public function up()
    {
        if (! $this->db->table_exists($this->table)) {
            $fields = array(
                'id'            => array('type' => 'SERIAL', 'auto_increment' => TRUE, 'null' => FALSE),
                'discussion_id' => array('type' => 'int', 'null' => FALSE),
                'author'        => array('type' => 'int', 'null' => FALSE),
                'content'       => array('type' => 'varchar', 'constraint' => 1024, 'null' => FALSE),
                'reply_to'      => array('type' => 'int', 'null' => TRUE),
                'created_at'    => array('type' => 'timestamp', 'null' => FALSE),
                'deleted_at'    => array('type' => 'timestamp', 'null' => TRUE),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table($this->table);

            $sql = array(
                "ALTER TABLE {$this->table} ALTER created_at SET DEFAULT CURRENT_TIMESTAMP",
                "ALTER TABLE {$this->table}
                    ADD CONSTRAINT {$this->table}_discussion_id_fkey
                    FOREIGN KEY (discussion_id) REFERENCES exp_discussions (id)",
                "CREATE INDEX {$this->table}_discussion_id_idx ON {$this->table} (discussion_id)",
                "ALTER TABLE {$this->table}
                    ADD CONSTRAINT {$this->table}_author_fkey
                    FOREIGN KEY (author) REFERENCES exp_members (uid)",
                "CREATE INDEX {$this->table}_author_idx ON {$this->table} (author)",
                "CREATE INDEX {$this->table}_discussion_id_deleted_at_idx ON {$this->table} (discussion_id, deleted_at)",
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