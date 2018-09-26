<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_indexes_to_exp_project_project_scores extends CI_Migration {

    private $table = 'exp_project_project_scores';

    public function up()
    {
        $sqls = [];

        $sqls[] = "CREATE INDEX exp_project_project_scores_project_id_1_idx ON $this->table (project_id_1)";
        $sqls[] = "CREATE INDEX exp_project_project_scores_project_id_2_idx ON $this->table (project_id_2)";
        
        $this->execute($sqls);
    }

    public function down()
    {
        $sqls = [];
        
        $sqls[] = "DROP INDEX IF EXISTS exp_project_project_scores_project_id_1_idx";
        $sqls[] = "DROP INDEX IF EXISTS exp_project_project_scores_project_id_2_idx";

        $this->execute($sqls);
    }

    private function execute(array $sqlStatements)
    {
        foreach ($sqlStatements as $sqlStatement) {
            $this->db->query($sqlStatement);
        }
    }


}