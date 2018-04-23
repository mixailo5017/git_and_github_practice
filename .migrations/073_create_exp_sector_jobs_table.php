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
                'jobs_em'  => [
                    'type' => 'integer',
                    'null' => FALSE
                ],
                'jobs_row' => [
                    'type' => 'integer',
                    'null' => FALSE
                ],
            ];
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table($this->table);

            $sql = array(
                "COMMENT ON COLUMN {$this->table}.jobs_em IS 'Jobs created per billion USD invested, for emerging market economies'",
                "COMMENT ON COLUMN {$this->table}.jobs_row IS 'Jobs created per billion USD invested, for developed economies'"
            );

            $this->execute($sql);
        }
    }

    private function insert_data()
    {
       $data = [
            new EfficiencyRecord('Transport', 'Ports & Logistics', 8500, 5100),
            new EfficiencyRecord('Social', 'Hospitals', 7500, 4500),
            new EfficiencyRecord('Oil & Gas', NULL, 7000, 4200),
            new EfficiencyRecord('Transport', 'Airports & Logistics', 6500, 3900),
            new EfficiencyRecord('Information & Communication Technologies', 'Broadband', 4167, 2500),
            new EfficiencyRecord('Transport', 'Highways', 3667, 2200),
            new EfficiencyRecord('Transport', 'Urban Highways', 3667, 2200),
            new EfficiencyRecord('Transport', 'Bridges', 3000, 1800),
            new EfficiencyRecord('Energy', 'Distribution', 2500, 1500),
            new EfficiencyRecord('Energy', 'Transmission', 2500, 1500),
            new EfficiencyRecord('Water', NULL, 2333, 1400),
            new EfficiencyRecord('Transport', 'Freight Rail', 2000, 1200),
            new EfficiencyRecord('Transport', 'Transit', 1500, 900),
            new EfficiencyRecord('Logistics', 'Other', 1333, 800),
            new EfficiencyRecord('Transport', 'Multimodal', 1333, 800),
            new EfficiencyRecord('Transport', NULL, 4194, 2517),
            new EfficiencyRecord('Energy', 'Generation — Hydro', 4194, 2517),
            new EfficiencyRecord('Energy', NULL, 2936, 1762),
            new EfficiencyRecord(NULL, NULL, 2097, 2097)
       ];

       foreach($data as $record) {
           $this->db->insert($this->table, $record);
       }
    }
}

class EfficiencyRecord
{
    public $sector, $subsector, $jobs_em, $jobs_row;

    public function __construct($sector, $subsector, $jobs_em, $jobs_row)
    {
        $this->sector    = $sector;
        $this->subsector = $subsector;
        $this->jobs_em   = $jobs_em;
        $this->jobs_row  = $jobs_row;
    }

}