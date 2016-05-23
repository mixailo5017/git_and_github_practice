<?php

class Members_model extends CI_Model {

    /**
     * Create a new member (user) record

     * @param $data
     * @return bool|int
     */
    public function create($data, $is_developer = false)
    {
        // BEGIN TRANSACCTION
        $this->db->trans_start();

        $result = $this->db->insert('exp_members', $data);
        if (! $result) return false;

        $id = (int) $this->db->insert_id();

        // If a member is a project developer
        if ($is_developer) {
            $result = $this->db
                ->set(array('member_id' => $id, 'status' => STATUS_PENDING))
                ->insert('exp_developers');
            if (! $result) return false;
        }

        // Calculate PCI for a new member
        $this->db->query("SELECT calc_member_pci(?)", array($id));

        // COMMIT
        $this->db->trans_complete();
        $this->db->trans_off(); // TODO: Revisit this

        if ($this->db->trans_status() === FALSE) return false;

        // Return the id of a newly created member
        return $id;
    }

    /**
     * Updates user's record
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        $result = $this->db
            ->where('uid', (int) $id)
            ->set($data)
            ->update('exp_members');

        if (! $result) return false;

        return true;
    }

    /**
     * Retrieve a record by email
     *
     * @param $email
     * @param null $select
     * @return mixed
     */
    public function find_by_email($email, $select = null)
    {
        if (! is_null($select)) {
            $this->db->select($select);
        }

        $row = $this->db
            ->where('email', $email)
            ->where('status', STATUS_ACTIVE)
            ->get('exp_members')
            ->row_array();

        return $row;
    }

    /**
     * Retrieve a record by id
     *
     * @param $id
     * @param null $select
     * @return mixed
     */
    public function find($id, $select = null)
    {
        if (! is_null($select)) {
            $this->db->select($select);
        }

        $row = $this->db
            ->where('uid', (int) $id)
            ->where('status', STATUS_ACTIVE)
            ->get('exp_members')
            ->row_array();

        return $row;
    }

    public function follow($member_id, $follower) {
        if (is_null($member_id) || is_null($follower) || $member_id == $follower) {
            return false;
        }

        // BEGIN TRANSACCTION
        $this->db->trans_start();

        $this->unfollow($member_id, $follower);
        $result = $this->db
            ->set(array(
                'member_id' => $member_id,
                'follower' => $follower,
                'created_at' => date('Y-m-d H:i:s')
            ))
            ->insert('exp_member_followers');

        // COMMIT
        $this->db->trans_complete();
        $this->db->trans_off(); // TODO: Revisit this

        if ($this->db->trans_status() === FALSE) {
            return false;
        }

        return true;
    }

    public function unfollow($member_id, $follower) {
        if (is_null($member_id) || is_null($follower || $member_id == $follower)) {
            return false;
        }

        $result = $this->db
            ->where('member_id', $member_id)
            ->where('follower', $follower)
            ->delete('exp_member_followers');

        return $result;
    }

    /**
     * Returns the number of followers and following (experts + projects)
     * @param $member_id
     * @return array
     */
    public function follows($member_id) {
        $followers = $this->db
            ->from('exp_member_followers')
            ->where('member_id', $member_id)
            ->count_all_results();

        $following_members = $this->db
            ->from('exp_member_followers')
            ->where('follower', $member_id)
            ->count_all_results();
        $following_projects = $this->db
            ->from('exp_project_followers')
            ->where('follower', $member_id)
            ->count_all_results();
        $following = $following_members + $following_projects;

        $result = compact('followers', 'following');

        return $result;
    }

    /**
     *
     * @param $member_id
     * @param $follower
     * @return bool|int
     */
    public function isfollowing($member_id, $follower) {
        if (is_null($member_id) || is_null($follower) || $member_id == $follower) {
            return false;
        }

        $result = $this->db
            ->where('member_id', $member_id)
            ->where('follower', $follower)
            ->count_all_results('exp_member_followers');

        return $result;
    }

    public function my_experts($member_id, $limit = 3) {
        if (is_null($member_id)) {
            return false;
        }

        $sql = "
        SELECT m.uid, firstname || ' ' || lastname fullname, userphoto, title, organization
          FROM exp_member_followers f JOIN exp_members m
            ON f.member_id = m.uid
         WHERE f.follower = ?
           AND m.membertype = ?
           AND m.status = ?
         ORDER BY RANDOM()
         LIMIT ?";

        $rows = $this->db
            ->query($sql, array($member_id, MEMBER_TYPE_MEMBER, STATUS_ACTIVE, $limit))
            ->result_array();

        return $rows;
    }

    /**
     * Returns an array of followers for an expert
     * @param $member_id
     * @param $limit
     * @param int $offset
     * @return array
     */
    public function my_followers($member_id, $limit, $offset = 0) {
        $sql = "
        SELECT m.uid, firstname, lastname, firstname || ' ' || lastname fullname,
               userphoto, title, organization, discipline, country,
               STRING_AGG(DISTINCT s.sector, ',' ORDER BY s.sector) expert_sector,
               COUNT(*) OVER () row_count
          FROM exp_member_followers f JOIN exp_members m
            ON f.follower = m.uid LEFT JOIN exp_expertise_sector s
            ON m.uid = s.uid
         WHERE f.member_id = ?
           AND m.membertype = ?
           AND m.status = ?
         GROUP BY m.uid, firstname, lastname, userphoto
         ORDER BY firstname, lastname
         LIMIT ? OFFSET ?
         ";

        $bindings = array(
            $member_id,
            MEMBER_TYPE_MEMBER, // Only experts
            STATUS_ACTIVE, // Follower should be active (not deleted)
            $limit,
            $offset
        );
        $result = $this->db
            ->query($sql, $bindings)
            ->result_array();

        return $result;
    }



    /**
     * Add sector, subsector pairs

     * @param $data
     * @return bool|int
     */
    public function add_sector($data, $uid)
    {
        foreach ($data as $key => $value) {
            $formatted_data = array(
                'uid'               => $uid,
                'sector'            => $value['sector'],
                'subsector'         => $value['subsector'],
                'permission'        => 'All',
                'status'            => '1'
            );
            $result = $this->db->insert('exp_expertise_sector', $formatted_data);
            if (! $result) return false;
        }

    }



//    public function following($member_id, $follower) {
//        if (is_null($member_id) || is_null($follower)) {
//            return false;
//        }
//
//        $sql = "
//        SELECT id, \"type\", \"name\", photo, sector, created_at, isfollowing,
//               COUNT(*) OVER () row_count
//          FROM
//        (
//            SELECT id, \"type\", \"name\", photo, sector, q.created_at,
//                   CASE WHEN i.member_id IS NOT NULL THEN 1 ELSE 0 END isfollowing
//              FROM
//            (
//                SELECT m.uid id,
//                       CASE WHEN membertype = 8 THEN 2 ELSE 1 END \"type\",
//                       CASE WHEN membertype = 8 THEN organization
//                            ELSE firstname || ' ' || lastname END \"name\", userphoto photo,
//                       STRING_AGG(DISTINCT s.sector, ',' ORDER BY s.sector) sector,
//                       created_at
//                  FROM exp_member_followers f JOIN exp_members m
//                    ON f.member_id = m.uid LEFT JOIN exp_expertise_sector s
//                    ON m.uid = s.uid
//                 WHERE f.follower = ?
//                 GROUP BY m.uid, membertype, firstname, lastname, organization, userphoto, created_at
//            ) q LEFT JOIN exp_member_followers i
//                ON q.id = i.member_id
//               AND i.follower = ?
//             UNION ALL
//            SELECT id, \"type\", \"name\", photo, sector, q.created_at,
//                   CASE WHEN i.project_id IS NOT NULL THEN 1 ELSE 0 END isfollowing
//              FROM
//            (
//                SELECT p.pid id, 3 \"type\", projectname \"name\", projectphoto photo, sector, created_at
//                  FROM exp_project_followers f JOIN exp_projects p
//                    ON f.project_id = p.pid
//                 WHERE f.follower = ?
//            ) q LEFT JOIN exp_project_followers i
//                ON q.id = i.project_id
//               AND i.follower = ?
//        ) p ORDER BY created_at DESC
//    ";
//
//        $result = $this->db
//            ->query($sql, array($member_id, $follower, $member_id, $follower))
//            ->result_array();
//
//        return $result;
//    }
}