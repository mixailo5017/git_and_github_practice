<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_unique_index_to_exp_expertise_sector_table extends CI_Migration {

    public function up()
    {
        // !!! In order to be able to deduplicate records in exp_expertise_sector
        // table we have to fix log_members_other trigger function
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

        // Deduplicate the table first
        // leaving only records with smallest id
        $sql = "
        WITH keep AS
        (
            SELECT uid, sector, subsector, MIN(id) id
              FROM exp_expertise_sector
             GROUP BY uid, sector, subsector
             HAVING COUNT(*) > 1
        )
        DELETE FROM exp_expertise_sector s
        WHERE EXISTS
        (
            SELECT *
              FROM keep
             WHERE s.uid = uid AND s.sector = sector AND s.subsector = subsector
               AND s.id <> id
        );";
        $this->db->query($sql);

        // Introduce the unique contraint on uid, sector, subsector columns
        $sql = "
        CREATE UNIQUE INDEX exp_expertise_sector_uid_sector_subsector_uqe
	        ON exp_expertise_sector (uid, sector, subsector);";
        $this->db->query($sql);
    }

    public function down()
    {
        $sql = "DROP INDEX IF EXISTS exp_expertise_sector_uid_sector_subsector_uqe";
        $this->db->query($sql);
    }
}