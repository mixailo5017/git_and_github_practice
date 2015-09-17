<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_energy_generation_oil_fired_subsector extends CI_Migration {

    private $with = "
        WITH parent_sector AS
        (
            SELECT sectorid
              FROM exp_sectors
             WHERE sectorname = 'Energy'
               AND parentid = 0
             LIMIT 1 -- Just for a good measure to ensure the only record
        ), new_subsectors AS
        (
            SELECT 'Generation — Oil-Fired'::VARCHAR sectorvalue
        )";

    public function up()
    {
        $insert = $this->with;
        $insert .= "
        INSERT INTO exp_sectors (sectorname, sectorvalue, parentid, sequenceby, status)
        SELECT n.sectorvalue, n.sectorvalue, p.sectorid, 0, '1'
        FROM new_subsectors n CROSS JOIN parent_sector p
        WHERE NOT EXISTS
        (
            SELECT *
              FROM exp_sectors
             WHERE sectorvalue = n.sectorvalue
               AND parentid = p.sectorid
        )";
        $sql = array($insert);
        $this->execute($sql);
    }

    public function down()
    {
        $delete = $this->with;
        $delete .= "
        DELETE FROM exp_sectors
         WHERE sectorname IN
        (
            SELECT sectorvalue
              FROM new_subsectors
        )
           AND parentid =
        (
            SELECT parentid
              FROM parent_sector
        )";
        $sql = array($delete);
        $this->execute($sql);
    }

    private function execute($sql) {
        foreach ($sql as $stmt) {
            $this->db->query($stmt);
        }
    }
}