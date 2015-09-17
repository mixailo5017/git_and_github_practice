<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_exp_project_project_scores_table extends CI_Migration {

    protected $table = 'exp_project_project_scores';

    public function up()
    {
        $sqls = array();
        // Truncate the table first
        $sqls[] = "TRUNCATE TABLE {$this->table}";
        //
        $sqls[] = "DROP TRIGGER IF EXISTS update_timestamp ON {$this->table}";
        // This is the last object that was using update_changed_timestamp() function
        $sqls[] = "DROP FUNCTION IF EXISTS update_changed_timestamp() CASCADE";
        //
        $sqls[] = "DROP INDEX IF EXISTS {$this->table}_created_at_idx";
        $sqls[] = "DROP INDEX IF EXISTS {$this->table}_updated_at_idx";
        $sqls[] = "DROP INDEX IF EXISTS {$this->table}_score_sum_idx";
        //
        $sqls[] = "ALTER TABLE {$this->table} DROP IF EXISTS id";
        $sqls[] = "ALTER TABLE {$this->table} DROP IF EXISTS updated_at";
        $sqls[] = "ALTER TABLE {$this->table} ALTER created_at SET DEFAULT CURRENT_TIMESTAMP";
        //
        $sqls[] = "ALTER TABLE {$this->table} ADD PRIMARY KEY (project_id_1, project_id_2)";

        foreach ($sqls as $sql) {
            $this->db->query($sql);
        }
    }

    public function down()
    {
        $sqls = array();
        // Truncate the table first
        $sqls[] = "TRUNCATE TABLE {$this->table}";
        //
        $sqls[] = "ALTER TABLE {$this->table} DROP CONSTRAINT IF EXISTS {$this->table}_pkey";
        //
        $sqls[] = "ALTER TABLE {$this->table} ADD id SERIAL PRIMARY KEY";
        $sqls[] = "ALTER TABLE {$this->table} ADD updated_at TIMESTAMP";
        //
        foreach ($sqls as $sql) {
            $this->db->query($sql);
        }
    }
} 