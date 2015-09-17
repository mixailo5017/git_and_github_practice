<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_exp_updates_table extends CI_Migration {

    protected $table = 'exp_updates';

    public function up()
    {
        $sql = array(
            "ALTER TABLE {$this->table} ALTER reply_to TYPE INT",
            "ALTER TABLE {$this->table}
                ADD CONSTRAINT {$this->table}_reply_to_fkey
                FOREIGN KEY (reply_to)
                REFERENCES exp_updates (id)
                ON DELETE CASCADE",
            "ALTER TABLE {$this->table} ADD deleted_at TIMESTAMP",
            "CREATE INDEX {$this->table}_reply_to_idx ON {$this->table} (reply_to)",
            "CREATE INDEX {$this->table}_author_idx ON {$this->table} (author)",
            "CREATE INDEX {$this->table}_deleted_at_idx ON {$this->table} (deleted_at)",
        );

        $this->execute($sql);
    }

    public function down()
    {
        $sql = array(
            "DROP INDEX IF EXISTS {$this->table}_deleted_at_idx",
            "DROP INDEX IF EXISTS {$this->table}_author_idx",
            "DROP INDEX IF EXISTS {$this->table}_reply_to_idx",
            "ALTER TABLE {$this->table} DROP IF EXISTS deleted_at",
            "ALTER TABLE {$this->table}
                DROP CONSTRAINT IF EXISTS {$this->table}_reply_to_fkey",
            "ALTER TABLE {$this->table} ALTER reply_to TYPE BIGINT",
        );

        $this->execute($sql);
    }

    private function execute($sql) {
        foreach ($sql as $stmt) {
            $this->db->query($stmt);
        }
    }
}