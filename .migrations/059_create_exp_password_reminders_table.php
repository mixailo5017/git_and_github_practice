<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_exp_password_reminders_table extends CI_Migration {

    protected $table = 'exp_password_reminders';

    public function up()
    {
        if (! $this->db->table_exists($this->table)) {
            $fields = array(
                'email'      => array('type' => 'varchar', 'constraint' => 255, 'null' => FALSE),
                'token'      => array('type' => 'varchar', 'constraint' => 255, 'null' => FALSE),
                'created_at' => array('type' => 'timestamp', 'null' => FALSE),
//                'ip'         => array('type' => 'varchar', 'constraint' => 45, 'null' => TRUE),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('token', TRUE); // PK
            $this->dbforge->create_table($this->table);

            $sql = array(
                "ALTER TABLE {$this->table} ALTER created_at SET DEFAULT CURRENT_TIMESTAMP",
                "CREATE INDEX {$this->table}_email_idx ON {$this->table} (email)",
                "CREATE INDEX {$this->table}_created_at_idx ON {$this->table} (created_at)",
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