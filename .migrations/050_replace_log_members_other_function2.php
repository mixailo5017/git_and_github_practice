<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_replace_log_members_other_function2 extends CI_Migration {

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
          pci_flag BOOL;
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
              pci_flag = TRUE;
            END IF;

            -- If a row in exp_expertise has been updated recalculate pci
            IF (TG_RELNAME::TEXT = 'exp_expertise') AND (h_changes ?| ARRAY['areafocus','summary','progoals','success']) THEN
              pci_flag = TRUE;
            END IF;
          END IF;

          -- If a row has been added or deleted to/from exp_expertise_sector register uid of the member
          IF (TG_RELNAME::TEXT IN('exp_expertise_sector', 'exp_expertise')) AND
             (TG_OP::TEXT IN('INSERT', 'DELETE')) THEN
            score_flag = TRUE;
            pci_flag = TRUE;
          END IF;

          -- If education has been added or deleted recalculate PCI
          IF (TG_RELNAME::TEXT = 'exp_education') AND
             (TG_OP::TEXT IN('INSERT', 'DELETE')) THEN
            pci_flag = TRUE;
          END IF;

          IF (score_flag = 't'::BOOLEAN) THEN
            PERFORM score_enqueue_member(id);
          END IF;

          IF (pci_flag = 't'::BOOLEAN) THEN
            PERFORM calc_member_pci(id::INT);
            -- register the fact that member profile has been changed in the updates feed
            PERFORM register_member_profile_update(id, TG_RELNAME::TEXT, fields);
          END IF;

          IF (TG_OP::TEXT IN('INSERT', 'DELETE')) OR (TG_OP::TEXT = 'UPDATE' AND fields IS NOT NULL) THEN
            INSERT INTO log_members (uid, table_name, operation, fields, last_date, last_user)
            VALUES (id, TG_RELNAME::TEXT, TG_OP::TEXT, fields, CURRENT_TIMESTAMP, CURRENT_USER);
          END IF;

          RETURN NULL;
        END;
        $$ LANGUAGE plpgsql";
        $this->db->query($sql);
    }

    public function down()
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
}