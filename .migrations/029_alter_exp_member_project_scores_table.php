<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_exp_member_project_scores_table extends CI_Migration {

    protected $table = 'exp_member_project_scores';

    public function up()
    {
        $sqls = array();
        // Truncate the table first
        $sqls[] = "TRUNCATE TABLE {$this->table}";
        //
        $sqls[] = "DROP TRIGGER IF EXISTS update_timestamp ON {$this->table}";
        //
        $sqls[] = "DROP INDEX IF EXISTS {$this->table}_created_at_idx";
        $sqls[] = "DROP INDEX IF EXISTS {$this->table}_updated_at_idx";
        $sqls[] = "DROP INDEX IF EXISTS {$this->table}_score_sum_idx";
        //
        $sqls[] = "ALTER TABLE {$this->table} DROP IF EXISTS id";
        $sqls[] = "ALTER TABLE {$this->table} DROP IF EXISTS keywords_aof_score";
        $sqls[] = "ALTER TABLE {$this->table} DROP IF EXISTS keywords_aoe_score";
        $sqls[] = "ALTER TABLE {$this->table} DROP IF EXISTS updated_at";
        $sqls[] = "ALTER TABLE {$this->table} ALTER created_at SET DEFAULT CURRENT_TIMESTAMP";

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
        $sqls[] = "ALTER TABLE {$this->table} ADD id SERIAL";
        $sqls[] = "ALTER TABLE {$this->table} ADD keywords_aof_score SMALLINT DEFAULT 0::SMALLINT";
        $sqls[] = "ALTER TABLE {$this->table} ADD keywords_aoe_score SMALLINT DEFAULT 0::SMALLINT";
        $sqls[] = "ALTER TABLE {$this->table} ADD updated_at TIMESTAMP";
        //
        foreach ($sqls as $sql) {
            $this->db->query($sql);
        }
    }
} 