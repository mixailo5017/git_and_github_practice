<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_score_enqueue_member_function extends CI_Migration {

    public function up()
    {
        $sql = "
        CREATE OR REPLACE FUNCTION score_enqueue_member(IN member_id BIGINT)
        RETURNS INT AS $$
        BEGIN
          INSERT INTO exp_queue (queue, data)
          SELECT 14, member_id
          WHERE NOT EXISTS
          (
            SELECT *
              FROM exp_queue
             WHERE queue = 14
          );
          RETURN NULL;
        END;
        $$ LANGUAGE plpgsql VOLATILE";
        $this->db->query($sql);
    }

    public function down()
    {
        $this->db->query("DROP FUNCTION IF EXISTS score_enqueue_member(BIGINT)");
    }
}