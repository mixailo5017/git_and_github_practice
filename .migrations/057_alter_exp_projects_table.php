<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_exp_projects_table extends CI_Migration {

    protected $table = 'exp_projects';

    public function up()
    {
        $sql = array(
            "ALTER TABLE {$this->table} ADD website VARCHAR(255) NULL",
        );

        $this->execute($sql);
    }

    public function down()
    {
        $sql = array(
            "ALTER TABLE {$this->table} DROP IF EXISTS website",
        );

        $this->execute($sql);
    }

    private function execute($sql) {
        foreach ($sql as $stmt) {
            $this->db->query($stmt);
        }
    }
}