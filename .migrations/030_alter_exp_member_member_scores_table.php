<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_exp_member_member_scores_table extends CI_Migration {

    protected $table = 'exp_member_member_scores';

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
//        $sqls[] = "ALTER TABLE {$this->table} RENAME location TO location_score";
        $sqls[] = "ALTER TABLE {$this->table} DROP IF EXISTS location";
        $sqls[] = "ALTER TABLE {$this->table} DROP IF EXISTS location_score";
        $sqls[] = "ALTER TABLE {$this->table} ADD location_score SMALLINT DEFAULT 0::SMALLINT";
//        $sqls[] = "ALTER TABLE {$this->table} RENAME country TO country_score";
        $sqls[] = "ALTER TABLE {$this->table} DROP IF EXISTS country";
        $sqls[] = "ALTER TABLE {$this->table} DROP IF EXISTS country_score";
        $sqls[] = "ALTER TABLE {$this->table} ADD country_score SMALLINT DEFAULT 0::SMALLINT";
//        $sqls[] = "ALTER TABLE {$this->table} RENAME discipline TO discipline_score";
        $sqls[] = "ALTER TABLE {$this->table} DROP IF EXISTS discipline";
        $sqls[] = "ALTER TABLE {$this->table} DROP IF EXISTS discipline_score";
        $sqls[] = "ALTER TABLE {$this->table} ADD discipline_score SMALLINT DEFAULT 0::SMALLINT";

        $sqls[] = "ALTER TABLE {$this->table} DROP IF EXISTS area_of_focus";
        $sqls[] = "ALTER TABLE {$this->table} DROP IF EXISTS updated_at";
        $sqls[] = "ALTER TABLE {$this->table} ALTER created_at SET DEFAULT CURRENT_TIMESTAMP";
        //
        $sqls[] = "ALTER TABLE {$this->table} ADD PRIMARY KEY (member_id_1, member_id_2)";

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
        $sqls[] = "ALTER TABLE {$this->table} RENAME location_score TO location";
        $sqls[] = "ALTER TABLE {$this->table} RENAME country_score TO country";
        $sqls[] = "ALTER TABLE {$this->table} RENAME discipline_score TO discipline";
        $sqls[] = "ALTER TABLE {$this->table} ADD area_of_focus SMALLINT DEFAULT 0::SMALLINT";
        $sqls[] = "ALTER TABLE {$this->table} ADD updated_at TIMESTAMP";
        //
        foreach ($sqls as $sql) {
            $this->db->query($sql);
        }
    }
} 