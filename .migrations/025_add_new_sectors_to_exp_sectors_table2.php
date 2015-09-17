<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_new_sectors_to_exp_sectors_table2 extends CI_Migration {

    // TODO: Request the list of subsectors from the client
    private $with = "
        WITH new_sectors AS
        (
            SELECT 'Real Estate' sectorvalue UNION ALL
            SELECT 'Industrial' UNION ALL
            SELECT 'Tourism & Related' UNION ALL
            SELECT 'Logistics'
        )";

    public function up()
    {
        $sql = $this->with;
        $sql .= "
        INSERT INTO exp_sectors (sectorname, sectorvalue, parentid, sequenceby, status)
        SELECT t.sectorvalue, t.sectorvalue, 0, 0, '1'
          FROM new_sectors t
         WHERE NOT EXISTS
        (
            SELECT *
              FROM exp_sectors s JOIN new_sectors n
                ON s.sectorvalue = t.sectorvalue
               AND s.parentid = 0
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
              FROM new_sectors
        )
           AND parentid = 0";

        $this->db->query($sql);
    }
}