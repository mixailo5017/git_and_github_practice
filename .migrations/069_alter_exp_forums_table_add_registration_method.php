<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_exp_forums_table_add_registration_method extends CI_Migration {

    protected $table = 'exp_forums';

    public function up()
    {
        $sql = array(
            "ALTER TABLE {$this->table} ADD registration_type integer NOT NULL DEFAULT 0"
        );

        $this->execute($sql);
    }

    public function down()
    {
        $sql = array(
            "ALTER TABLE {$this->table} DROP IF EXISTS registration_type"
        );

        $this->execute($sql);
    }

    private function execute($sql) {
        foreach ($sql as $stmt) {
            $this->db->query($stmt);
        }
    }
    
}