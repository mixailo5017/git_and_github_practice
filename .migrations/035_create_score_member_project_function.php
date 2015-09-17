<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_score_member_project_function extends CI_Migration {

    public function up()
    {
        $sql = "
        CREATE OR REPLACE FUNCTION score_member_project()
          RETURNS INT AS $$
        DECLARE
          proc_id SMALLINT = 1::SMALLINT;
          start_time TIMESTAMP;
          affected INT;
        BEGIN
          start_time = CLOCK_TIMESTAMP();

          -- Exit if the project queue is empty
          IF NOT EXISTS(SELECT * FROM exp_queue WHERE queue IN(13, 14)) THEN
            INSERT INTO log_score(proc_id, row_count, start_time, end_time)
              VALUES (proc_id, 0, start_time, CLOCK_TIMESTAMP());
            RETURN 0;
          END IF;

          -- Empty the project scores' table
          DELETE FROM exp_member_project_scores;
          -- Calculate and insert scores for all projects and members
          WITH raw AS
          (
              SELECT p.pid, m.uid uid,
                     st_distance_sphere(st_setsrid(st_makepoint(p.lng, p.lat), 4326),
                                        st_setsrid(st_makepoint(m.lng, m.lat), 4326)) * 0.000621371 distance,
                     CASE WHEN p.country = m.country THEN 1 ELSE 0 END country_match
               FROM exp_projects p CROSS JOIN exp_members m
              WHERE p.isdeleted = '0'
                AND m.membertype = 5
                AND m.status = '1'
          ), withsectors AS
          (
              SELECT r.pid, r.uid, r.distance, r.country_match,
                   COALESCE(s.sector_match, 0) sector_match,
                   COALESCE(s.subsector_match, 0) subsector_match
              FROM raw r LEFT JOIN
            (
              SELECT p.pid, s.uid,
                     COUNT(DISTINCT s.sector) sector_match,
                     SUM(CASE WHEN p.subsector = s.subsector THEN 1 ELSE 0 END) subsector_match
                FROM exp_projects p JOIN exp_expertise_sector s
                  ON p.sector = s.sector
               GROUP BY p.pid, s.uid
            ) s ON r.pid = s.pid AND r.uid = s.uid
          ), detailed AS
          (
              SELECT pid, uid,
                     CASE WHEN distance IS NULL THEN 0
                          WHEN distance BETWEEN  0 AND  50 THEN 9
                          WHEN distance BETWEEN 51 AND 500 THEN 4
                          ELSE 0 END location_score,
                country_match * 6 country_score,
                CASE WHEN sector_match > 0 THEN 20 ELSE 0 END sector_score,
                CASE WHEN subsector_match > 0 THEN 45 ELSE 0 END subsector_score
              FROM withsectors
          )
          INSERT INTO exp_member_project_scores (project_id, member_id, location_score, country_score,
                                                 sector_score, subsector_score, score_sum, created_at)
          SELECT pid, uid, location_score, country_score, sector_score, subsector_score,
                 location_score + country_score + sector_score + subsector_score, CURRENT_TIMESTAMP
            FROM detailed
           WHERE location_score + country_score + sector_score + subsector_score > 0;

          -- !!! Do not delete entries from the queue table
          -- !!! This procedure supposed to be executed before score_projects() and score_members()

          GET DIAGNOSTICS affected = ROW_COUNT;
          INSERT INTO log_score(proc_id, row_count, start_time, end_time)
          VALUES (proc_id, affected, start_time, CLOCK_TIMESTAMP());

          RETURN 1;
        END;
        $$ LANGUAGE plpgsql VOLATILE";
        $this->db->query($sql);
    }

    public function down()
    {
        $this->db->query("DROP FUNCTION IF EXISTS score_member_project()");
    }
}