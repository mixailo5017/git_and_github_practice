<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_replace_log_projects_other_function extends CI_Migration {

    public function up()
    {
        $sql = "
        CREATE OR REPLACE FUNCTION log_projects_other_update()
        RETURNS trigger AS $$
        DECLARE
          fields TEXT;
          id BIGINT;
          h_changes hstore;
        BEGIN
          id = CASE WHEN TG_OP::TEXT = 'DELETE' THEN OLD.pid ELSE NEW.pid END;

          IF (TG_OP::TEXT = 'UPDATE') THEN
            h_changes = hstore(NEW.*) - hstore(OLD.*) - ARRAY[]::TEXT[];
            IF h_changes != hstore('') THEN
              -- Create a delimited string containing a list of changed columns
              fields = array_to_string(akeys(h_changes), ' ');
            END IF;
          END IF;

          IF (TG_OP::TEXT IN('INSERT', 'DELETE')) OR (TG_OP::TEXT = 'UPDATE' AND fields IS NOT NULL) THEN
            INSERT INTO log_projects (pid, table_name, operation, fields, last_date, last_user)
            VALUES (id, TG_RELNAME::TEXT, TG_OP::TEXT, fields, CURRENT_TIMESTAMP, CURRENT_USER);
          END IF;

          RETURN NULL;
        END;
        $$ LANGUAGE plpgsql VOLATILE";
        $this->db->query($sql);
    }

    public function down()
    {
        $sql = "
        CREATE OR REPLACE FUNCTION log_projects_other_update()
          RETURNS trigger
        AS $$
        DECLARE
          fields VARCHAR(300):= '';
          table_name VARCHAR(50);
          pid BIGINT;
        BEGIN
          pid = CASE WHEN TG_OP::TEXT = 'DELETE' THEN OLD.pid ELSE NEW.pid END;
          table_name = TG_RELNAME;
          fields = TG_OP;

          INSERT INTO log_projects (pid, table_name, fields, last_date, last_user)
          VALUES (pid, table_name, fields, CURRENT_TIMESTAMP, CURRENT_USER);

          PERFORM register_project_profile_update(pid, table_name, fields);

          RETURN NULL;
        END;
        $$ LANGUAGE plpgsql VOLATILE";
        $this->db->query($sql);
    }
}