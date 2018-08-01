<?php

class Ratings_model extends CI_Model
{

    /**
     * Returns true if there is a rating record made by $rated_by for $member_id
     *
     * @param $member_id
     * @param $rated_by
     * @return bool
     */
    public function exists($member_id, $rated_by)
    {
        $sql = "
        SELECT 1
          FROM exp_member_ratings
         WHERE member_id = ?
           AND rated_by = ?
         LIMIT 1";

        $bindings = array((int) $member_id, (int) $rated_by);

        $row = $this->db
            ->query($sql, $bindings)
            ->row_array();

        return (empty($row) == false);
    }

//    /**
//     * Returns array average rating for each category
//     *
//     * @param $member_id
//     * @return array
//     */
//    public function ratings($member_id)
//    {
//        $member_id = (int) $member_id;
//
//        $sql = "
//        SELECT category, rating, total_count, unique_count,
//               ROUND(AVG(rating) OVER (), 1) overall
//          FROM
//        (
//            SELECT category,
//                   ROUND(AVG(rating), 1) rating,
//                   COUNT(DISTINCT r.id) total_count,
//                   COUNT(DISTINCT r.rated_by) unique_count
//              FROM exp_member_rating_details d JOIN exp_member_ratings r
//                ON d.rating_id = r.id
//             WHERE member_id = ?
//             GROUP BY category
//        ) q
//         ORDER BY category";
//
//        $bindings = array($member_id);
//
//        $rows = $this->db
//            ->query($sql, $bindings)
//            ->result_array();
//
//        $result = array(
//            'total_count' => 0,
//            'unique_count' => 0,
//            'ratings' => array()
//        );
//
//        if (empty($rows)) return $result;
//
//        $result['total_count'] = (int) $rows[0]['total_count'];
//        $result['unique_count'] = (int) $rows[0]['unique_count'];
//        $result['ratings'][] = array('category' => 0, 'rating' => (float) $rows[0]['overall']);
//        foreach ($rows as $row) {
//            $result['ratings'][] = array(
//                'category' => (int) $row['category'],
//                'rating' => (float) $row['rating']
//            );
//        }
//
//        return $result;
//    }

    /**
     * Returns array average rating for each category
     *
     * @param $member_id
     * @return array
     */
    public function ratings($member_id)
    {
        $member_id = (int) $member_id;

        $sql = "
        SELECT total_count, unique_count, overall,
               MAX(CASE WHEN category = 1 THEN rating END) helpful,
               MAX(CASE WHEN category = 2 THEN rating END) responsive,
               MAX(CASE WHEN category = 3 THEN rating END) knowledgeable
          FROM
        (
            SELECT member_id, total_count, unique_count, category, rating,
                   ROUND(AVG(rating) OVER (), 1) overall
              FROM
            (
                SELECT member_id, category,
                       ROUND(AVG(rating), 1) rating,
                       COUNT(DISTINCT r.id) total_count,
                       COUNT(DISTINCT r.rated_by) unique_count
                  FROM exp_member_rating_details d JOIN exp_member_ratings r
                    ON d.rating_id = r.id
                 WHERE member_id = ?
                 GROUP BY member_id, category
            ) q
        ) q
         GROUP BY member_id, total_count, unique_count, overall";

        $bindings = array($member_id);

        $row = $this->db
            ->query($sql, $bindings)
            ->row_array();

        $empty = array(
            'total_count' => 0,
            'unique_count' => 0,
            'overall' => 0.0,
            'helpful' => 0.0,
            'responsive' => 0.0,
            'knowledgeable' => 0.0
        );

        if (empty($row)) return $empty;

        // Fix data types
        array_walk($row, function(&$item, $key) {
            if (in_array($key, array('total_count', 'unique_count'))) {
                $item = (int) $item;
            } else {
                $item = (float) $item;
            }
        });

        return array_merge($empty, $row);
    }

    /**
     * Create a new rating record(s)
     *
     * @param array $data
     * @return bool
     */
    public function create($data)
    {
        // Members can't rate themselves
        if ($data['member_id'] == $data['rated_by']) return false;

        // For now we allow only one ratings
        // Hopefully this will change in the future
        if ($this->exists($data['member_id'], $data['rated_by'])) return false;

        // To save a rating we need to insert rows in two tables
        // exp_member_ratings and exp_member_rating_details
        // Therefore we need to use transactions

        // BEGIN TRANSACTION
        $this->db->trans_start();

        $this->db->set(array(
            'member_id' => $data['member_id'],
            'rated_by' => $data['rated_by'],
        ));

        if (! empty($data['created_at'])) {
            $this->db->set(array('created_at' => $data['created_at']));
        }

        if (! $this->db->insert('exp_member_ratings')) return false; // TODO: revisit this

        $rating_id = $this->db->insert_id();

        $insert = array();
        foreach ($data['ratings'] as $category => $rating) {
            $insert[] = compact('rating_id', 'category', 'rating');
        }

        if (! $this->db->insert_batch('exp_member_rating_details', $insert)) return false; // TODO: revisit this

        // COMMIT
        $this->db->trans_complete();
        $this->db->trans_off(); // TODO: Revisit this

        if ($this->db->trans_status() === FALSE) return false;

        return true;
    }
}