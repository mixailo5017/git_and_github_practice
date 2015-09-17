<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_replace_log_members_other_function extends CI_Migration {

    public function up()
    {
        $sql = "
        CREATE OR REPLACE FUNCTION log_members_other()
        RETURNS trigger AS $$
        DECLARE
          fields TEXT;
          id BIGINT;
          h_changes hstore;
          score_flag BOOL;
        BEGIN
          id = CASE WHEN TG_OP::TEXT = 'DELETE' THEN OLD.uid ELSE NEW.uid END;

          IF (TG_OP::TEXT = 'UPDATE') THEN
            h_changes = hstore(NEW.*) - hstore(OLD.*) - ARRAY[]::TEXT[];
            IF h_changes != hstore('') THEN
              -- Create a delimited string containing a list of changed columns
              fields = array_to_string(akeys(h_changes), ' ');
            END IF;
            -- If a row in exp_expertise_sector has been updated register uid of the member
            IF (TG_RELNAME::TEXT = 'exp_expertise_sector') AND (h_changes ?| ARRAY['sector','subsector']) THEN
              score_flag = TRUE;
            END IF;
          END IF;

          -- If a row has been added or deleted to/from exp_expertise_sector register uid of the member
          IF (TG_RELNAME::TEXT = 'exp_expertise_sector') AND
             (TG_OP::TEXT IN('INSERT', 'DELETE')) THEN
            score_flag = TRUE;
          END IF;

          IF (score_flag = 't'::BOOLEAN) THEN
            PERFORM score_enqueue_member(id);
          END IF;

          IF (TG_OP::TEXT IN('INSERT', 'DELETE')) OR (TG_OP::TEXT = 'UPDATE' AND fields IS NOT NULL) THEN
            INSERT INTO log_members (uid, table_name, operation, fields, last_date, last_user)
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
        CREATE OR REPLACE FUNCTION log_members_other()
        RETURNS trigger AS $$
        DECLARE
          fields VARCHAR(300) := '';
          table_name VARCHAR(50);
          uid BIGINT;
        BEGIN
          uid = CASE WHEN TG_OP::TEXT = 'DELETE' THEN OLD.uid ELSE NEW.uid END;
          table_name = TG_RELNAME;
          fields = TG_OP;

          INSERT INTO log_members (uid, table_name, fields, last_date, last_user)
          VALUES (uid, table_name, fields, CURRENT_TIMESTAMP, CURRENT_USER);

          RETURN NULL;
        END; $$
        LANGUAGE plpgsql VOLATILE";
        $this->db->query($sql);
    }
}