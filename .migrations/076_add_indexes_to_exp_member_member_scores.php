<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_indexes_to_exp_member_member_scores extends CI_Migration {

    private $table = 'exp_member_member_scores';

    public function up()
    {
        $sqls = [];

        $sqls[] = "CREATE INDEX exp_member_member_scores_member_id_1_idx ON $this->table (member_id_1)";
        $sqls[] = "CREATE INDEX exp_member_member_scores_member_id_2_idx ON $this->table (member_id_2)";
        
        $this->execute($sqls);
    }

    public function down()
    {
        $sqls = [];
        
        $sqls[] = "DROP INDEX IF EXISTS exp_member_member_scores_member_id_1_idx";
        $sqls[] = "DROP INDEX IF EXISTS exp_member_member_scores_member_id_2_idx";

        $this->execute($sqls);
    }

    private function execute(array $sqlStatements)
    {
        foreach ($sqlStatements as $sqlStatement) {
            $this->db->query($sqlStatement);
        }
    }


}