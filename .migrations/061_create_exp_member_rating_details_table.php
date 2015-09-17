<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_exp_member_rating_details_table extends CI_Migration {

    protected $table = 'exp_member_rating_details';

    public function up()
    {
        if (! $this->db->table_exists($this->table)) {
            $fields = array(
                'rating_id' => array('type' => 'int', 'null' => 'FALSE'),
                'category'   => array('type' => 'smallint', 'null' => FALSE),
                'rating'     => array('type' => 'smallint', 'null' => FALSE),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('rating_id', TRUE);
            $this->dbforge->add_key('category', TRUE);
            $this->dbforge->create_table($this->table);

            $sql = array(
                // FK for a member who is being rated
                "ALTER TABLE {$this->table}
                    ADD CONSTRAINT {$this->table}_member_id_fkey
                    FOREIGN KEY (rating_id) REFERENCES exp_member_ratings (id)",
//                "CREATE INDEX {$this->table}_rating_id_idx ON {$this->table} (rating_id)",
                // CHECK constraint that ensures category has a valid value (1-4)
                "ALTER TABLE {$this->table}
                    ADD CONSTRAINT {$this->table}_category_value CHECK (category BETWEEN 1 AND 3)",
                // CHECK constraint that ensures rating has a valid value (1-5)
                "ALTER TABLE {$this->table}
                    ADD CONSTRAINT {$this->table}_rating_value CHECK (rating BETWEEN 1 AND 5)",
            );

            $this->execute($sql);
        }
    }

    public function down()
    {
        if ($this->db->table_exists($this->table)) {
            $this->dbforge->drop_table($this->table);
        }
    }

    private function execute($sql) {
        foreach ($sql as $stmt) {
            $this->db->query($stmt);
        }
    }
}