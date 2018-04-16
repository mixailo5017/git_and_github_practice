<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_exp_projects_table_add_unique_constraint_slug extends CI_Migration {

    protected $table = 'exp_projects';

    public function up()
    {
        $sql = array(
            "ALTER TABLE {$this->table} ADD CONSTRAINT exp_projects_slug_key UNIQUE (slug)",
        );

        $this->execute($sql);
    }

    public function down()
    {
        $sql = array(
            "ALTER TABLE {$this->table} DROP CONSTRAINT IF EXISTS exp_projects_slug_key",
        );

        $this->execute($sql);
    }

    private function execute($sql) {
        foreach ($sql as $stmt) {
            $this->db->query($stmt);
        }
    }
    
}