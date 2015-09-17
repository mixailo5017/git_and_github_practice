<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_exp_member_member_scores_table2 extends CI_Migration {

    protected $table = 'exp_member_member_scores';

    public function up()
    {
        $sqls = array(
            "ALTER TABLE {$this->table}
                DROP CONSTRAINT IF EXISTS {$this->table}_member_id_1_fkey",
            "ALTER TABLE {$this->table}
                DROP CONSTRAINT IF EXISTS {$this->table}_member_id_2_fkey",
            "ALTER TABLE {$this->table}
                ADD CONSTRAINT {$this->table}_member_id_1_fkey
                FOREIGN KEY (member_id_1)
                REFERENCES exp_members (uid)
                ON DELETE CASCADE",
            "ALTER TABLE {$this->table}
                ADD CONSTRAINT {$this->table}_member_id_2_fkey
                FOREIGN KEY (member_id_2)
                REFERENCES exp_members (uid)
                ON DELETE CASCADE"
        );

        foreach ($sqls as $sql) {
            $this->db->query($sql);
        }
    }

    public function down()
    {
        $sqls = array(
            "ALTER TABLE {$this->table}
                DROP CONSTRAINT IF EXISTS {$this->table}_member_id_1_fkey",
            "ALTER TABLE {$this->table}
                DROP CONSTRAINT IF EXISTS {$this->table}_member_id_2_fkey",
            "ALTER TABLE {$this->table}
                ADD CONSTRAINT {$this->table}_member_id_1_fkey
                FOREIGN KEY (member_id_1)
                REFERENCES exp_members (uid)",
            "ALTER TABLE {$this->table}
                ADD CONSTRAINT {$this->table}_member_id_2_fkey
                FOREIGN KEY (member_id_2)
                REFERENCES exp_members (uid)"
        );

        foreach ($sqls as $sql) {
            $this->db->query($sql);
        }
    }
} 