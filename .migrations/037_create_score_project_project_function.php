<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_score_project_project_function extends CI_Migration {

    public function up()
    {
        $sql = "
        CREATE OR REPLACE FUNCTION score_project_project()
          RETURNS INT AS $$
        DECLARE
          proc_id SMALLINT = 3::SMALLINT;
          start_time TIMESTAMP;
          affected INT;
        BEGIN
          start_time = CLOCK_TIMESTAMP();

          -- Exit if the project queue is empty
          IF NOT EXISTS(SELECT * FROM exp_queue WHERE queue = 13) THEN
            INSERT INTO log_score(proc_id, row_count, start_time, end_time)
              VALUES (proc_id, 0, start_time, CLOCK_TIMESTAMP());
            RETURN 0;
          END IF;

          -- Empty the project scores' table
          DELETE FROM exp_project_project_scores;
          -- Calculate and insert scores for all projects
          WITH raw AS
          (
              SELECT p1.pid pid1, p2.pid pid2,
                     st_distance_sphere(st_setsrid(st_makepoint(p1.lng, p1.lat), 4326),
                                        st_setsrid(st_makepoint(p2.lng, p2.lat), 4326)) * 0.000621371 distance,
                     CASE WHEN p1.sector = p2.sector THEN 1 ELSE 0 END sector_match,
                     CASE WHEN p1.subsector = p2.subsector THEN 1 ELSE 0 END subsector_match,
                     ABS(p1.totalbudget - p2.totalbudget) budget_diff,
                     CASE WHEN p1.stage = p2.stage THEN 1 ELSE 0 END stage_match
               FROM exp_projects p1 JOIN exp_projects p2
                 ON p1.pid < p2.pid
              WHERE p1.isdeleted = '0'
                AND p2.isdeleted = '0'
          ), detailed AS
          (
              SELECT pid1, pid2,
                CASE WHEN distance IS NULL THEN 0
                     WHEN distance <= 1000 THEN 10
                     WHEN distance BETWEEN 1001 AND 2000 THEN 5
                ELSE 3 END location_score,
                sector_match * 20 sector_score,
                subsector_match * 20 subsector_score,
                CASE WHEN budget_diff <= 500 THEN 25
                     WHEN budget_diff BETWEEN 501 AND 4000 THEN 15
                     WHEN budget_diff BETWEEN 4001 AND 10000 THEN 10
                     ELSE  5 END budget_score,
                stage_match * 25 stage_score
              FROM raw
          )
          INSERT INTO exp_project_project_scores (project_id_1, project_id_2, budget_score,
                                                  location_score, sector_score, subsector_score,
                                                  stage_score, score_sum, created_at)
          SELECT pid1, pid2, budget_score, location_score, sector_score, subsector_score, stage_score,
                 budget_score + location_score + sector_score + subsector_score + stage_score, CURRENT_TIMESTAMP
            FROM detailed
           WHERE budget_score + location_score + sector_score + subsector_score + stage_score > 0;

          GET DIAGNOSTICS affected = ROW_COUNT;

          -- Delete entries from the queue
          DELETE FROM exp_queue WHERE queue = 13;

          INSERT INTO log_score(proc_id, row_count, start_time, end_time)
          VALUES (proc_id, affected, start_time, CLOCK_TIMESTAMP());

          RETURN 1;
        END;
        $$ LANGUAGE plpgsql VOLATILE";
        $this->db->query($sql);
    }

    public function down()
    {
        $this->db->query("DROP FUNCTION IF EXISTS score_project_project()");
    }
}