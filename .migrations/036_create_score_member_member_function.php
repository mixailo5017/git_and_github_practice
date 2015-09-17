<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_score_member_member_function extends CI_Migration {

    public function up()
    {
        $sql = "
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

    public function down()
    {
        $this->db->query("DROP FUNCTION IF EXISTS score_member_member()");
    }
}