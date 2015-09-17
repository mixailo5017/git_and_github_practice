<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_exp_member_project_scores_table2 extends CI_Migration {

    protected $table = 'exp_member_project_scores';

    public function up()
    {
        $sqls = array(
            "ALTER TABLE {$this->table}
                DROP CONSTRAINT IF EXISTS {$this->table}_member_id_fkey",
            "ALTER TABLE {$this->table}
                ADD CONSTRAINT {$this->table}_member_id_fkey
                FOREIGN KEY (member_id)
                REFERENCES exp_members (uid)
                ON DELETE CASCADE",

            "ALTER TABLE {$this->table}
                DROP CONSTRAINT IF EXISTS {$this->table}_project_id_fkey",
            "ALTER TABLE {$this->table}
                ADD CONSTRAINT {$this->table}_project_id_fkey
                FOREIGN KEY (project_id)
                REFERENCES exp_projects (pid)
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
                DROP CONSTRAINT IF EXISTS {$this->table}_member_id_fkey",
            "ALTER TABLE {$this->table}
                ADD CONSTRAINT {$this->table}_member_id_fkey
                FOREIGN KEY (member_id)
                REFERENCES exp_members (uid)",

            "ALTER TABLE {$this->table}
                DROP CONSTRAINT IF EXISTS {$this->table}_project_id_fkey",
            "ALTER TABLE {$this->table}
                ADD CONSTRAINT {$this->table}_project_id_fkey
                FOREIGN KEY (project_id)
                REFERENCES exp_projects (pid)",
        );

        foreach ($sqls as $sql) {
            $this->db->query($sql);
        }
    }
} 