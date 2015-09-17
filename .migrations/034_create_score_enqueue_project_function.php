<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_score_enqueue_project_function extends CI_Migration {

    public function up()
    {
        $sql = "
        CREATE OR REPLACE FUNCTION score_enqueue_project(IN project_id BIGINT)
        RETURNS INT AS $$
        BEGIN
          INSERT INTO exp_queue (queue, data)
          SELECT 13, project_id
          WHERE NOT EXISTS
          (
            SELECT *
              FROM exp_queue
             WHERE queue = 13
          );
          RETURN NULL;
        END;
        $$ LANGUAGE plpgsql VOLATILE";
        $this->db->query($sql);
    }

    public function down()
    {
        $this->db->query("DROP FUNCTION IF EXISTS score_enqueue_project(BIGINT)");
    }
}