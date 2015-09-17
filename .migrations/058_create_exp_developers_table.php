<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_exp_developers_table extends CI_Migration {

    protected $table = 'exp_developers';

    public function up()
    {
        if (! $this->db->table_exists($this->table)) {
            $fields = array(
                'member_id'  => array('type' => 'int', 'null' => FALSE),
                'status'     => array('type' => 'char', 'constraint' => 1, 'null' => FALSE, 'default' => '2'),
                'created_at' => array('type' => 'timestamp', 'null' => FALSE),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('member_id', TRUE);
            $this->dbforge->create_table($this->table);

            $sql = array(
                "ALTER TABLE {$this->table} ALTER created_at SET DEFAULT CURRENT_TIMESTAMP",
                "ALTER TABLE {$this->table}
                    ADD CONSTRAINT {$this->table}_member_id_fkey
                    FOREIGN KEY (member_id) REFERENCES exp_members (uid)",
                "CREATE INDEX {$this->table}_member_id_idx ON {$this->table} (member_id)",
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