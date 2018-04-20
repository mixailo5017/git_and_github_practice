<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_exp_sector_jobs_table extends CI_Migration {

    protected $table = 'exp_sector_jobs';

    public function up()
    {
        $this->create_table();
        $this->insert_data();
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

    private function create_table()
    {
        if (! $this->db->table_exists($this->table)) {
            $fields = [
                'id'        => [
                    'type' => 'serial',
                    'null' => FALSE
                ],
                'sector'    => [
                    'type'       => 'varchar',
                    'constraint' => 255,
                    'null'       => TRUE
                ],
                'subsector' => [
                    'type'       => 'varchar',
                    'constraint' => 255,
                    'null'       => TRUE
                ],
                'devlevel'  => [
                    'type' => 'text',
                    'null' => FALSE
                ],
                'jobs'      => [
                    'type' => 'integer',
                    'null' => FALSE
                ],
            ];
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table($this->table);

            $sql = array(
                "COMMENT ON COLUMN {$this->table}.devlevel IS 'Denotes whether country is an emerging market or a developed economy'",
                "COMMENT ON COLUMN {$this->table}.jobs IS 'Jobs created per billion USD invested'"
            );

            $this->execute($sql);
        }
    }

    private function insert_data()
    {
        
    }
}