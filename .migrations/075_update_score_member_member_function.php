<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_update_score_member_member_function extends CI_Migration {

    $this->results_table = 'exp_member_member_scores';

    public function up()
    {
        $sqls = [];

        // Alter table structure for storing results
        $sqls[] = "
        ALTER TABLE {$this->results_table}
            DROP COLUMN score_sum,
            DROP COLUMN sector_score,
            DROP COLUMN subsector_score,
            DROP COLUMN location_score,
            DROP COLUMN country_score,
            DROP COLUMN discipline_score,
            ADD COLUMN distance DOUBLE PRECISION
        ";

        $sqls[] = "
        CREATE OR REPLACE FUNCTION score_member_member()
          RETURNS INT AS $$
        DECLARE
          proc_id SMALLINT = 2::SMALLINT;
          start_time TIMESTAMP;
          affected INT;
        BEGIN
          start_time = CLOCK_TIMESTAMP();

          -- Exit if the member queue is empty
          IF NOT EXISTS(SELECT * FROM exp_queue WHERE queue = 14) THEN
            INSERT INTO log_score(proc_id, row_count, start_time, end_time)
              VALUES (proc_id, 0, start_time, CLOCK_TIMESTAMP());
            RETURN 0;
          END IF;

          -- Empty the project scores' table
          DELETE FROM exp_member_member_scores;
          -- Calculate and insert scores for all projects
          WITH raw AS
          (
            SELECT m1.uid uid1, m2.uid uid2,
                   ST_DistanceSphere(st_setsrid(st_makepoint(m1.lng, m1.lat), 4326),
                                      st_setsrid(st_makepoint(m2.lng, m2.lat), 4326)) * 0.000621371 distance --Distance in miles
              FROM exp_members m1 JOIN exp_members m2
                ON m1.uid < m2.uid
             WHERE m1.membertype = 5 --Only for people, not companies etc.
               AND m1.status = '1' --Only for active users
               AND m2.membertype = 5
               AND m2.status = '1'
               AND (m1.discipline != m2.discipline OR (coalesce(m1.discipline, '') = '' AND coalesce(m2.discipline, '') = '')) --Don't recommend members of the same discipline
               AND m1.organization != m2.organization
          ), sectormatches AS (
              SELECT s1.uid uid1, s2.uid uid2, s1.sector sector1, s2.sector sector2,
                  max(CASE WHEN s1.sector = s2.sector THEN 1 ELSE 0 END) sector_match
              FROM exp_expertise_sector s1 CROSS JOIN exp_expertise_sector s2
              WHERE s1.uid < s2.uid
              AND s1.uid = 28
              GROUP BY uid1, uid2, sector1, sector2
          ), sectorcounts AS (
                SELECT uid1, uid2, sum(sector_match) AS sector_matches
                FROM sectormatches
                GROUP BY uid1, uid2
          ), results AS
          (
              SELECT raw.uid1, raw.uid2, raw.distance
              FROM raw
              LEFT OUTER JOIN sectorcounts ON (raw.uid1 = sectorcounts.uid1 AND raw.uid2 = sectorcounts.uid2)
              WHERE @ COALESCE(sector_matches, -1) > 0 --Each diad must either have at least one matching sector, or else at least one member must have no sector data at all
          )
          INSERT INTO {$this->results_table} (member_id_1, member_id_2, distance, created_at)
          SELECT uid1, uid2, distance, CURRENT_TIMESTAMP
            FROM results;

          GET DIAGNOSTICS affected = ROW_COUNT;

          -- Delete entries from the queue
          DELETE FROM exp_queue WHERE queue = 14;

          INSERT INTO log_score(proc_id, row_count, start_time, end_time)
          VALUES (proc_id, affected, start_time, CLOCK_TIMESTAMP());

          RETURN 1;
        END;
        $$ LANGUAGE plpgsql VOLATILE";
        
        $this->execute($sqls);
    }

    public function down()
    {
        $sqls = [];
        $sqls[] = "
            DROP TABLE {$this->results_table}
        ";

        $sqls[] = "
        CREATE TABLE public.exp_member_member_scores
        (
            member_id_1 bigint NOT NULL,
            member_id_2 bigint NOT NULL,
            score_sum integer NOT NULL DEFAULT 0,
            sector_score smallint NOT NULL DEFAULT (0)::smallint,
            subsector_score smallint NOT NULL DEFAULT (0)::smallint,
            created_at timestamp without time zone NOT NULL DEFAULT now(),
            location_score smallint DEFAULT (0)::smallint,
            country_score smallint DEFAULT (0)::smallint,
            discipline_score smallint DEFAULT (0)::smallint,
            CONSTRAINT exp_member_member_scores_pkey PRIMARY KEY (member_id_1, member_id_2),
            CONSTRAINT exp_member_member_scores_member_id_1_fkey FOREIGN KEY (member_id_1)
                REFERENCES public.exp_members (uid) MATCH SIMPLE
                ON UPDATE NO ACTION
                ON DELETE CASCADE,
            CONSTRAINT exp_member_member_scores_member_id_2_fkey FOREIGN KEY (member_id_2)
                REFERENCES public.exp_members (uid) MATCH SIMPLE
                ON UPDATE NO ACTION
                ON DELETE CASCADE
        )
        WITH (
            OIDS = FALSE
        )
        TABLESPACE pg_default;
        ";

        $sqls[] = "
        CREATE OR REPLACE FUNCTION score_member_member()
          RETURNS INT AS $$
        DECLARE
          proc_id SMALLINT = 2::SMALLINT;
          start_time TIMESTAMP;
          affected INT;
        BEGIN
          start_time = CLOCK_TIMESTAMP();

          -- Exit if the member queue is empty
          IF NOT EXISTS(SELECT * FROM exp_queue WHERE queue = 14) THEN
            INSERT INTO log_score(proc_id, row_count, start_time, end_time)
              VALUES (proc_id, 0, start_time, CLOCK_TIMESTAMP());
            RETURN 0;
          END IF;

          -- Empty the project scores' table
          DELETE FROM exp_member_member_scores;
          -- Calculate and insert scores for all projects
          WITH raw AS
          (
            SELECT m1.uid uid1, m2.uid uid2,
                   st_distance_sphere(st_setsrid(st_makepoint(m1.lng, m1.lat), 4326),
                                      st_setsrid(st_makepoint(m2.lng, m2.lat), 4326)) * 0.000621371 distance,
                   CASE WHEN m1.country = m2.country THEN 1 ELSE 0 END country_match,
                   CASE WHEN m1.discipline = m2.discipline THEN 1 ELSE 0 END discipline_match
              FROM exp_members m1 JOIN exp_members m2
                ON m1.uid < m2.uid
             WHERE m1.membertype = 5
               AND m1.status = '1'
               AND m2.membertype = 5
               AND m2.status = '1'
          ), withsectors AS
          (
            SELECT r.uid1, r.uid2, r.distance, r.country_match, r.discipline_match,
                   COALESCE(s.sector_match, 0) sector_match,
                   COALESCE(s.subsector_match, 0) subsector_match
              FROM raw r LEFT JOIN
            (
              SELECT s1.uid uid1, s2.uid uid2,
                     COUNT(DISTINCT s1.sector) sector_match,
                     SUM(CASE WHEN s1.subsector = s2.subsector THEN 1 ELSE 0 END) subsector_match
                FROM exp_expertise_sector s1 CROSS JOIN exp_expertise_sector s2
               WHERE s1.uid < s2.uid
                 AND s1.sector = s2.sector
               GROUP BY s1.uid, s2.uid
            ) s ON r.uid1 = s.uid1 AND r.uid2 = s.uid2
          ), detailed AS
          (
            SELECT uid1, uid2,
                   CASE WHEN distance IS NULL THEN 0
                        WHEN distance BETWEEN  0 AND  50 THEN 9
                        WHEN distance BETWEEN 51 AND 500 THEN 4
                        ELSE 0 END location_score,
                   CASE WHEN sector_match > 0 THEN 20 ELSE 0 END sector_score,
                   CASE WHEN subsector_match >0 THEN 45 ELSE 0 END subsector_score,
                   country_match * 6 country_score,
                   discipline_match * 10 discipline_score
            FROM withsectors
          )
          INSERT INTO exp_member_member_scores (member_id_1, member_id_2, location_score, sector_score,
                                                 subsector_score, country_score, discipline_score,
                                                 score_sum, created_at)
          SELECT uid1, uid2, location_score, sector_score, subsector_score, country_score, discipline_score,
                 location_score + sector_score + subsector_score + country_score + discipline_score, CURRENT_TIMESTAMP
            FROM detailed
           WHERE location_score + sector_score + subsector_score + country_score + discipline_score > 0;

          GET DIAGNOSTICS affected = ROW_COUNT;

          -- Delete entries from the queue
          DELETE FROM exp_queue WHERE queue = 14;

          INSERT INTO log_score(proc_id, row_count, start_time, end_time)
          VALUES (proc_id, affected, start_time, CLOCK_TIMESTAMP());

          RETURN 1;
        END;
        $$ LANGUAGE plpgsql VOLATILE";
        $this->db->query($sql);
    }

    private function execute(array $sqlStatements)
    {
        foreach ($sqlStatements as $sqlStatement) {
            $this->db->query($sqlStatement);
        }
    }


}