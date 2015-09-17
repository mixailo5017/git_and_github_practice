<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_replace_log_members_update_function extends CI_Migration {

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

    public function down()
    {
        $sql = "
        CREATE OR REPLACE FUNCTION log_members_update ()
          RETURNS trigger AS $$
        DECLARE
          fields VARCHAR(300):= '';
          table_name VARCHAR(50);
          uid BIGINT;
        BEGIN
          uid = CASE WHEN TG_OP::TEXT = 'DELETE' THEN OLD.uid ELSE NEW.uid END;
          table_name = TG_RELNAME;
          fields = '';

          -- compare all new and old values to get diff fields
          IF NEW.firstname != OLD.firstname THEN
            fields = fields || ' firstname';
          END IF;
          IF NEW.lastname != OLD.lastname THEN
            fields = fields || ' lastname';
          END IF;
          IF NEW.email != OLD.email THEN
            fields = fields || ' email';
          END IF;
          IF NEW.organization != OLD.organization THEN
            fields = fields || ' organization';
          END IF;
          IF NEW.title != OLD.title THEN
            fields = fields || ' title';
          END IF;
          IF NEW.totalemployee != OLD.totalemployee THEN
            fields = fields || ' totalemployee';
          END IF;
          IF NEW.annualrevenue != OLD.annualrevenue THEN
            fields = fields || ' annualrevenue';
          END IF;
          IF NEW.discipline != OLD.discipline THEN
            fields = fields || ' discipline';
          END IF;
          IF NEW.country != OLD.country THEN
            fields = fields || ' country';
          END IF;
          IF NEW.city != OLD.city THEN
            fields = fields || ' city';
          END IF;
          IF NEW.state != OLD.state THEN
            fields = fields || ' state';
          END IF;
          IF NEW.userphoto != OLD.userphoto THEN
            fields = fields || ' userphoto';
          END IF;
          IF NEW.uservideo != OLD.uservideo THEN
            fields = fields || ' uservideo';
          END IF;
          IF NEW.forum_attendee != OLD.forum_attendee THEN
            fields = fields || ' forum_attendee';
          END IF;
          IF NEW.geocode != OLD.geocode THEN
            fields = fields || ' geocode';
          END IF;
          IF NEW.address != OLD.address THEN
            fields = fields || ' address';
          END IF;
          IF NEW.postal_code != OLD.postal_code THEN
            fields = fields || ' postal_code';
          END IF;

          IF fields = '' THEN
            RETURN NULL;
          END IF;

          INSERT INTO log_members (uid, table_name, fields, last_date, last_user)
          VALUES (uid, table_name, fields, CURRENT_TIMESTAMP, CURRENT_USER);

          RETURN NULL;

        END;
        $$ LANGUAGE plpgsql VOLATILE";
        $this->db->query($sql);
    }
}