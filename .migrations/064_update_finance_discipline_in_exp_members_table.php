<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_update_finance_discipline_in_exp_members_table extends CI_Migration {

    protected $table = 'exp_members';

    public function up()
    {
        $sql = array(
            "START TRANSACTION",
            "ALTER TABLE {$this->table} DISABLE TRIGGER USER",
            "UPDATE {$this->table} SET discipline = NULL WHERE discipline = ''",
            "UPDATE {$this->table} SET discipline = 'Finance Other' WHERE discipline = 'Finance'",
            "ALTER TABLE {$this->table} ENABLE TRIGGER USER",
            "COMMIT",
            "SELECT score_member_project(); SELECT score_member_member(); SELECT score_project_project();"
        );

        $this->execute($sql);
    }

    public function down()
    {
        $sql = array(
            "START TRANSACTION",
            "ALTER TABLE {$this->table} DISABLE TRIGGER USER",
            "UPDATE {$this->table} SET discipline = 'Finance' WHERE discipline = 'Finance Other'",
            "ALTER TABLE {$this->table} DISABLE TRIGGER USER",
            "COMMIT",
            "SELECT score_member_project(); SELECT score_member_member(); SELECT score_project_project();"
        );

        $this->execute($sql);
    }

    private function execute($sql) {
        foreach ($sql as $stmt) {
            $this->db->query($stmt);
        }
    }
}