<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_replace_log_members_update_function2 extends CI_Migration {

    public function up()
    {
        $sql = "
        CREATE OR REPLACE FUNCTION log_members_update()
        RETURNS trigger AS $$
        DECLARE
          fields TEXT;
          id BIGINT;
          h_changes hstore;

        BEGIN
          id = CASE WHEN TG_OP::TEXT = 'DELETE' THEN OLD.uid ELSE NEW.uid END;

          IF (TG_OP::TEXT = 'UPDATE') THEN
            h_changes = hstore(NEW.*) - hstore(OLD.*) - ARRAY['lastlogin','lastlogout','geocode'];
            IF h_changes != hstore('') THEN
              -- Create a delimited string containing a list of changed columns
              fields = array_to_string(akeys(h_changes), ' ');
              IF (NEW.membertype = 5) THEN
                -- If columns are essential for match scoring register uid of the member
                IF (h_changes ?| ARRAY['lng','lat','country','discipline']) THEN
                  PERFORM score_enqueue_member(id);
                END IF;
                -- If columns are essential for PCI recalculate the member's PCI
                IF (h_changes ?| ARRAY['userphoto','discipline','country','totalemployee','annualrevenue',
                                 'title','address','city','postal_code','public_status']) THEN
                  PERFORM calc_member_pci(id::INT);
                END IF;
                -- register the fact that member profile has been changed in the updates feed
                PERFORM register_member_profile_update(id, TG_RELNAME::TEXT, fields);
              END IF;
            END IF;
          END IF;

          IF (TG_OP::TEXT = 'INSERT') AND (NEW.membertype = 5) THEN
            PERFORM calc_member_pci(id::INT);
          END IF;

          IF (TG_OP::TEXT = 'DELETE') OR (TG_OP::TEXT = 'UPDATE' AND fields IS NOT NULL) THEN
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
        CREATE OR REPLACE FUNCTION log_members_update()
        RETURNS trigger AS $$
        DECLARE
          fields TEXT;
          id BIGINT;
          h_changes hstore;

        BEGIN
          id = CASE WHEN TG_OP::TEXT = 'DELETE' THEN OLD.uid ELSE NEW.uid END;

          IF (TG_OP::TEXT = 'UPDATE') THEN
            h_changes = hstore(NEW.*) - hstore(OLD.*) - ARRAY['lastlogin','lastlogout','geocode'];
            IF h_changes != hstore('') THEN
              -- Create a delimited string containing a list of changed columns
              fields = array_to_string(akeys(h_changes), ' ');
              -- If columns essential for scoring have been changed register pid of the project
              IF (NEW.membertype = 5 AND h_changes ?| ARRAY['lng','lat','country','discipline']) THEN
                PERFORM score_enqueue_member(id);
              END IF;
            END IF;
          END IF;

          IF (TG_OP::TEXT = 'DELETE') OR (TG_OP::TEXT = 'UPDATE' AND fields IS NOT NULL) THEN
            INSERT INTO log_members (uid, table_name, operation, fields, last_date, last_user)
            VALUES (id, TG_RELNAME::TEXT, TG_OP::TEXT, fields, CURRENT_TIMESTAMP, CURRENT_USER);
          END IF;

          RETURN NULL;
        END;
        $$ LANGUAGE plpgsql VOLATILE";
        $this->db->query($sql);
    }
}