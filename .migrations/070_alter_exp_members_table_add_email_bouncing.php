<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_exp_members_table_add_email_bouncing extends CI_Migration {

    protected $table = 'exp_members';

    public function up()
    {
        $sql = array(
            "ALTER TABLE {$this->table} ADD email_bouncing varchar(1) NOT NULL DEFAULT '0'"
        );

        $this->execute($sql);
    }

    public function down()
    {
        $sql = array(
            "ALTER TABLE {$this->table} DROP IF EXISTS email_bouncing"
        );

        $this->execute($sql);
    }

    private function execute($sql) {
        foreach ($sql as $stmt) {
            $this->db->query($stmt);
        }
    }
    
}