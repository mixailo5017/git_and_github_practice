<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_log_projects_table extends CI_Migration {

    protected $table = 'log_projects';

    public function up()
    {
        $sqls = array();
        $sqls[] = "ALTER TABLE {$this->table} ALTER table_name TYPE VARCHAR(64)";
        $sqls[] = "ALTER TABLE {$this->table} ALTER last_user TYPE VARCHAR(64)";
        $sqls[] = "ALTER TABLE {$this->table} ADD operation CHAR(6) NULL";

        foreach ($sqls as $sql) {
            $this->db->query($sql);
        }
    }

    public function down()
    {
        $sql = "ALTER TABLE {$this->table} DROP COLUMN IF EXISTS operation";
        $this->db->query($sql);
        // We won't return data type TEXT to table_name and last_user columns
        // because max length for identifiers in Postres is 63 bites
    }
}