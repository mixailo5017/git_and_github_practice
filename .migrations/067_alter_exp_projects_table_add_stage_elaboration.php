<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_exp_projects_table_add_stage_elaboration extends CI_Migration {

    protected $table = 'exp_projects';

    public function up()
    {
        $sql = array(
            "ALTER TABLE {$this->table} ADD stage_elaboration VARCHAR(255) NULL",
        );

        $this->execute($sql);
    }

    public function down()
    {
        $sql = array(
            "ALTER TABLE {$this->table} DROP IF EXISTS stage_elaboration",
        );

        $this->execute($sql);
    }

    private function execute($sql) {
        foreach ($sql as $stmt) {
            $this->db->query($stmt);
        }
    }
    
}