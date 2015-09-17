<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_register_member_profile_update_function extends CI_Migration {

    public function up()
    {
        $sql = "
        CREATE OR REPLACE FUNCTION register_member_profile_update
        (
          IN _member_id BIGINT,
          IN _table VARCHAR(50),
          IN _fields VARCHAR(300) DEFAULT NULL
        )
        RETURNS INT AS $$
          BEGIN
            WITH an_update AS
            (
              INSERT INTO exp_updates (author, \"type\", content, created_at)
                SELECT m.uid, 3, '', CURRENT_TIMESTAMP
                FROM
            (
              SELECT _member_id member_id
            ) q JOIN exp_members m
              ON q.member_id = m.uid
                WHERE NOT EXISTS
                (
                  SELECT *
                    FROM exp_updates u JOIN exp_member_updates mu
                      ON u.id = mu.update_id
                   WHERE mu.member_id = q.member_id
                     AND EXTRACT(EPOCH FROM (CURRENT_TIMESTAMP - u.created_at)) < 24 * 60 * 60
                )
              RETURNING id
            )
            INSERT INTO exp_member_updates (update_id, member_id)
            SELECT id, _member_id
              FROM an_update;

            RETURN 1;
        END;
        $$ LANGUAGE plpgsql";
        $this->db->query($sql);
    }

    public function down()
    {
        $sql = "DROP FUNCTION IF EXISTS register_member_profile_update (BIGINT, VARCHAR(50), VARCHAR(300))";
        $this->db->query($sql);
    }
}