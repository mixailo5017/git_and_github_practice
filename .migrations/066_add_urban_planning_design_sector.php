<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_urban_planning_design_sector extends CI_Migration {

    private $sector = 'Urban Planning & Design';

    public function up()
    {
        $sql = array(
            "INSERT INTO exp_sectors (sectorname, sectorvalue, parentid, sequenceby, status)
             VALUES('$this->sector', '$this->sector', 0, 0, '1')"
        );
        $this->execute($sql);
    }

    public function down()
    {
        $sql = array(
            "DELETE FROM exp_sectors WHERE sectorvalue = '$this->sector'"
        );
        $this->execute($sql);
    }

    private function execute($sql) {
        foreach ($sql as $stmt) {
            $this->db->query($stmt);
        }
    }
}