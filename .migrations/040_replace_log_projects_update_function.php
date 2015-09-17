<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_replace_log_projects_update_function extends CI_Migration {

    public function up()
    {
        $sql = "
        CREATE OR REPLACE FUNCTION log_projects_update()
        RETURNS trigger AS $$
        DECLARE
          fields TEXT;
          id BIGINT;
          h_changes hstore;

        BEGIN
          id = CASE WHEN TG_OP::TEXT = 'DELETE' THEN OLD.pid ELSE NEW.pid END;

          IF (TG_OP::TEXT = 'UPDATE') THEN
            h_changes = hstore(NEW.*) - hstore(OLD.*) - ARRAY['geocode'];
            IF h_changes != hstore('') THEN
              -- Create a delimited string containing a list of changed columns
              fields = array_to_string(akeys(h_changes), ' ');
              -- If columns essential for scoring have been changed register pid of the project
              IF (h_changes ?| ARRAY['lng','lat','sector','subsector','totalbudget','stage']) THEN
                PERFORM score_enqueue_project(id);
              END IF;
              -- register the fact that project has been changed in the project feed
              PERFORM register_project_profile_update(id, TG_RELNAME::TEXT, fields);
            END IF;
          END IF;

          IF (TG_OP::TEXT = 'DELETE') OR (TG_OP::TEXT = 'UPDATE' AND fields IS NOT NULL) THEN
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
        CREATE OR REPLACE FUNCTION log_projects_update ()
          RETURNS trigger AS $$
        DECLARE
          fields     VARCHAR(300) := '';
          table_name VARCHAR(50);
          pid BIGINT;
        BEGIN
          pid = CASE WHEN TG_OP::TEXT = 'DELETE' THEN OLD.pid ELSE NEW.pid END;
          table_name = TG_RELNAME;

          fields = '';
          -- compare all new and old values to get diff fields
          IF NEW.stage != OLD.stage THEN
            fields = fields || ' stage';
          END IF;
          IF NEW.projectname != OLD.projectname THEN
            fields = fields || ' projectname';
          END IF;
          IF NEW.slug != OLD.slug THEN
            fields = fields || ' slug';
          END IF;
          IF NEW.projectphoto != OLD.projectphoto THEN
            fields = fields || ' projectphoto';
          END IF;
          IF NEW.description != OLD.description THEN
            fields = fields || ' description';
          END IF;
          IF NEW.keywords != OLD.keywords THEN
            fields = fields || ' keywords';
          END IF;
          IF NEW.country != OLD.country THEN
            fields = fields || ' country';
          END IF;
          IF NEW.location != OLD.location THEN
            fields = fields || ' location';
          END IF;
          IF NEW.sector != OLD.sector THEN
            fields = fields || ' sector';
          END IF;
          IF NEW.subsector != OLD.subsector THEN
            fields = fields || ' subsector';
          END IF;
          IF NEW.subsector_other != OLD.subsector_other THEN
            fields = fields || ' subsector_other';
          END IF;
          IF NEW.totalbudget != OLD.totalbudget THEN
            fields = fields || ' totalbudget';
          END IF;
          IF NEW.financialstructure != OLD.financialstructure THEN
            fields = fields || ' financialstructure';
          END IF;
          IF NEW.financialstructure_other != OLD.financialstructure_other THEN
            fields = fields || ' financialstructure_other';
          END IF;
          IF NEW.fundamental_legal != OLD.fundamental_legal THEN
            fields = fields || ' fundamental_legal';
          END IF;
          IF NEW.isforum != OLD.isforum THEN
            fields = fields || ' isforum';
          END IF;
          IF NEW.status != OLD.status THEN
            fields = fields || ' status';
          END IF;
          IF NEW.entry_date != OLD.entry_date THEN
            fields = fields || ' entry_date';
          END IF;
          IF NEW.eststart != OLD.eststart THEN
            fields = fields || ' eststart';
          END IF;
          IF NEW.estcompletion != OLD.estcompletion THEN
            fields = fields || ' estcompletion';
          END IF;
          IF NEW.developer != OLD.developer THEN
            fields = fields || ' developer';
          END IF;
          IF NEW.sponsor != OLD.sponsor THEN
            fields = fields || ' sponsor';
          END IF;
          IF NEW.geocode != OLD.geocode THEN
            fields = fields || ' geocode';
          END IF;

          INSERT INTO log_projects (pid, table_name, fields, last_date, last_user)
          VALUES (pid, table_name, fields, CURRENT_TIMESTAMP, CURRENT_USER);

          PERFORM register_project_profile_update(pid, table_name, fields);

          RETURN NULL;
        END;
        $$ LANGUAGE plpgsql VOLATILE";
        $this->db->query($sql);
    }
}