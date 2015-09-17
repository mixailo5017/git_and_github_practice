<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_exp_forums_table extends CI_Migration {

    protected $table = 'exp_forums';

    public function up()
    {
        // Create exp_forums table
        if (! $this->db->table_exists($this->table)) {
            $fields = array(
                'id' => array('type' => 'SERIAL', 'auto_increment' => TRUE, 'null' => 'FALSE'),
                'title' => array('type' => 'varchar', 'constraint' => 1024, 'null' => FALSE),
                'start_date' => array('type' => 'datetime', 'null' => TRUE),
                'end_date' => array('type' => 'datetime', 'null' => TRUE),
                'category_id' => array('type' => 'int', 'null' => FALSE),
                'register_url' => array('type' => 'varchar', 'constraint' => 255, 'null' => TRUE),
                'venue' => array('type' => 'varchar', 'constraint' => 255, 'null' => TRUE),
                'venue_url' => array('type' => 'varchar', 'constraint' => 255, 'null' => TRUE),
                'venue_address' => array('type' => 'varchar', 'constraint' => 255, 'null' => TRUE),
                'venue_lat' => array('type' => 'decimal', 'constraint' => '10, 6', 'null' => TRUE),
                'venue_lng' => array('type' => 'decimal', 'constraint' => '10, 6', 'null' => TRUE),
                'photo' => array('type' => 'varchar', 'constraint' => 255, 'null' => TRUE),
                'banner' => array('type' => 'varchar', 'constraint' => 255, 'null' => TRUE),
                'meeting_url' => array('type' => 'varchar', 'constraint' => 255, 'null' => TRUE),
                'content' => array('type' => 'text', 'null' => TRUE),
                'status' => array('type' => 'varchar', 'constraint' => 1, 'null' => FALSE, 'default' => '0'),
                'is_featured' => array('type' => 'varchar', 'constraint' => 1, 'null' => FALSE, 'default' => '0'),
                'created_at' => array('type' => 'timestamp', 'null' => TRUE),
                'updated_at' => array('type' => 'timestamp', 'null' => TRUE)
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table($this->table);

            $this->db->query("ALTER TABLE {$this->table} ADD CONSTRAINT {$this->table}_category_id_fkey FOREIGN KEY (category_id) REFERENCES exp_forum_categories (id)");
            $this->db->query("CREATE INDEX {$this->table}_category_id_idx ON {$this->table} (category_id)");
            $this->db->query("CREATE INDEX {$this->table}_status_idx ON {$this->table} (status)");
        }
    }

    public function down()
    {
        if ($this->db->table_exists($this->table)) {
            $this->dbforge->drop_table($this->table);
        }
    }
} 