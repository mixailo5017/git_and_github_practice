<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_new_sectors_to_exp_sectors_table extends CI_Migration {

    private $with = "
        WITH parent_sector AS
        (
            SELECT sectorid
              FROM exp_sectors
             WHERE sectorname = 'Water'
               AND parentid = 0
             LIMIT 1 -- Just for a good measure to ensure the only record
        ), new_subsectors AS
        (
            SELECT 'Water Resources' sectorvalue
            UNION ALL
            SELECT 'Water Supply'
        )";

    public function up()
    {
        $sql = $this->with;
        $sql .= "
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
        $this->db->query($sql);
    }

    public function down()
    {
        $sql = $this->with;
        $sql .= "
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
        $this->db->query($sql);
    }
}