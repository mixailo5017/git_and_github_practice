<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_register_project_profile_update_function extends CI_Migration {

    public function up()
    {
        $sql = "
        CREATE OR REPLACE FUNCTION register_project_profile_update
        (
          IN _project_id BIGINT,
          IN _table VARCHAR(50),
          IN _fields VARCHAR(300) DEFAULT NULL
        )
        RETURNS INT AS $$
          BEGIN
            WITH an_update AS
            (
              INSERT INTO exp_updates (author, \"type\", content, created_at)
                SELECT p.uid, 3, '', CURRENT_TIMESTAMP
                FROM
            (
              SELECT _project_id project_id
            ) q JOIN exp_projects p
              ON q.project_id = p.pid
                WHERE NOT EXISTS
                (
                    SELECT *
                      FROM exp_updates u JOIN exp_project_updates pu
                        ON u.id = pu.update_id
                     WHERE pu.project_id = q.project_id
                       AND EXTRACT(EPOCH FROM (CURRENT_TIMESTAMP - u.created_at)) < 24 * 60 * 60
                )
              RETURNING id
            )
            INSERT INTO exp_project_updates (update_id, project_id)
            SELECT id, _project_id
              FROM an_update;

            RETURN 1;
        END;
        $$ LANGUAGE plpgsql
        ";
        $this->db->query($sql);
    }

    public function down()
    {
        $sql = "
        DROP FUNCTION IF EXISTS register_project_profile_update
        (
          IN _project_id BIGINT,
          IN _table VARCHAR(50),
          IN _fields VARCHAR(300)
        )
        ";
        $this->db->query($sql);
    }
}