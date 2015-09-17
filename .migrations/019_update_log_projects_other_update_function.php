<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_update_log_projects_other_update_function extends CI_Migration {

    public function up()
    {
        $sql = "
        CREATE OR REPLACE FUNCTION log_projects_other_update()
        RETURNS trigger AS $$
          DECLARE
            fields VARCHAR(300):= '';
            table_name VARCHAR(50);
            project_id integer;

          BEGIN
            IF TG_OP = 'INSERT' THEN
              project_id = NEW.pid;
            ELSE
              project_id = OLD.pid;
            END IF;

            IF project_id IS NULL THEN
              RAISE EXCEPTION 'project_id cannot be null';
            END IF;

            table_name = TG_RELNAME;
            fields = TG_OP;

            INSERT INTO log_projects (
                pid,
                table_name,
                fields,
                last_date,
                last_user
              ) VALUES (
                project_id,
                table_name,
                fields,
                current_timestamp,
                current_user
              );

            PERFORM register_project_profile_update(NEW.pid, table_name, fields);

            RETURN NULL;

          END;
        $$ LANGUAGE plpgsql VOLATILE;
        ";
        $this->db->query($sql);
    }

    public function down()
    {
        $sql = "
        CREATE OR REPLACE FUNCTION log_projects_other_update()
        RETURNS trigger AS $$
          DECLARE
            fields VARCHAR(300):= '';
            table_name VARCHAR(50);
            project_id integer;

          BEGIN
            IF TG_OP = 'INSERT' THEN
              project_id = NEW.pid;
            ELSE
              project_id = OLD.pid;
            END IF;

            IF project_id IS NULL THEN
              RAISE EXCEPTION 'project_id cannot be null';
            END IF;

            table_name = TG_RELNAME;
            fields = TG_OP;

            INSERT INTO log_projects (
                pid,
                table_name,
                fields,
                last_date,
                last_user
              ) VALUES (
                project_id,
                table_name,
                fields,
                current_timestamp,
                current_user
              );

            RETURN NULL;

          END;
        $$ LANGUAGE plpgsql VOLATILE;
        ";
        $this->db->query($sql);
    }
}