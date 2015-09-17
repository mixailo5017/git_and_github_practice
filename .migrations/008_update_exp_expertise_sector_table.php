<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_exp_expertise_sector_table extends CI_Migration {

    protected $table = 'exp_expertise_sector';

    public function up()
    {
        if ($this->db->table_exists($this->table)) {
            // Make uid column NOT NULL
            $this->db->query("ALTER TABLE {$this->table} ALTER COLUMN uid SET NOT NULL");
            // Create an index that supports queries that filter by uid and status columns
            $this->db->query("CREATE INDEX {$this->table}_uid_status_idx ON {$this->table} (uid, status)");
        }
    }

    public function down()
    {
        $this->db->query("DROP INDEX IF EXISTS {$this->table}_uid_status_idx");
        $this->db->query("ALTER TABLE {$this->table} ALTER COLUMN uid DROP NOT NULL");
    }
}