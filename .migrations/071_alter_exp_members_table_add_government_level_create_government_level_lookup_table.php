<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_exp_members_table_add_government_level_create_government_level_lookup_table extends CI_Migration {

    protected $members_table = 'exp_members';
    protected $government_level_lookup_table = 'exp_member_government_level_lookup';

    public function up()
    {
        $sql = [
            // Create lookup table
            "CREATE TABLE {$this->government_level_lookup_table} (
                government_level_english VARCHAR(50) PRIMARY KEY
            )",
            // Populate lookup table with initial values (for Brazil)
            "INSERT INTO {$this->government_level_lookup_table} (government_level_english)
            VALUES ('Federal'), ('State'), ('Municipal')",
            // Add column to exp_members referencing lookup table
            "ALTER TABLE {$this->members_table}
                ADD COLUMN government_level VARCHAR(50) DEFAULT NULL,
                ADD CONSTRAINT government_level_fk FOREIGN KEY (government_level) REFERENCES {$this->government_level_lookup_table} (government_level_english)
                    ON UPDATE CASCADE ON DELETE SET NULL",
            // Update record for Brazilian Federal Government
            "UPDATE exp_members 
            SET government_level = 'Federal'
            WHERE uid = 2812"
        ];

        $this->execute($sql);
    }

    public function down()
    {
        $sql = [
            "ALTER TABLE {$this->members_table} 
                DROP COLUMN IF EXISTS government_level",
            "DROP TABLE IF EXISTS {$this->government_level_lookup_table}"
        ];

        $this->execute($sql);
    }

    private function execute($sql) {
        foreach ($sql as $stmt) {
            $this->db->query($stmt);
        }
    }
    
}