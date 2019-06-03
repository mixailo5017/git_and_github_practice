<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_exp_projects_table_add_procurement_fields extends CI_Migration {

    protected $table = 'exp_projects';

    public function up()
    {
        $sql = array(
            "ALTER TABLE {$this->table} ADD procurement_criteria VARCHAR(255) NULL",
            "ALTER TABLE {$this->table} ADD procurement_date date NULL"
        );

        $this->execute($sql);
    }

    public function down()
    {
        $sql = array(
            "ALTER TABLE {$this->table} DROP IF EXISTS procurement_criteria",
            "ALTER TABLE {$this->table} DROP IF EXISTS procurement_date"
        );

        $this->execute($sql);
    }

    private function execute($sql) {
        foreach ($sql as $stmt) {
            $this->db->query($stmt);
        }
    }
    
}