<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_exp_linkedin_log_table extends CI_Migration {

    protected $table = 'exp_linkedin_log';

    public function up()
    {
        if (! $this->db->table_exists($this->table)) {
            $fields = array(
                'id'          => array('type' => 'serial', 'null' => FALSE),
                'email'       => array('type' => 'varchar', 'constraint' => 255, 'null' => TRUE),
                'picture_url' => array('type' => 'varchar', 'constraint' => 1024, 'null' => TRUE),
                'payload'     => array('type' => 'text', 'null' => TRUE),
                'created_at'  => array('type' => 'timestamp', 'null' => FALSE),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table($this->table);

            $sql = array(
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