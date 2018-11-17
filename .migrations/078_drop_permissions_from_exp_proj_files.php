<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_drop_permissions_from_exp_proj_files extends CI_Migration {

    private $table = 'exp_proj_files';

    public function up()
    {
        $sqls = [];

        $sqls[] = "ALTER TABLE {$this->table} DROP COLUMN IF EXISTS permission";
        
        $this->execute($sqls);
    }

    public function down()
    {
        $sqls = [];
        
        $sqls[] = "ALTER TABLE {$this->table} ADD COLUMN IF NOT EXISTS permission VARCHAR(5)";

        $this->execute($sqls);
    }

    private function execute(array $sqlStatements)
    {
        foreach ($sqlStatements as $sqlStatement) {
            $this->db->query($sqlStatement);
        }
    }


}