<?php

class Discussions_model extends CI_Model
{
    /**
     * Returns replies for a specific comment in the discussion
     *
     * @param $id
     */
    public function replies($id)
    {
        $sql = "
        SELECT p.id, p.author, CASE WHEN m.membertype = 8 THEN m.organization ELSE m.firstname || ' ' || m.lastname END author_name,
               m.userphoto author_photo, content, p.created_at
          FROM exp_discussion_posts p JOIN exp_members m
            ON p.author = m.uid
         WHERE p.reply_to = ?
           AND p.deleted_at IS NULL
         ORDER BY p.id";
        $bindings = array($id);

        $rows = $this->db
            ->query($sql, $bindings)
            ->result_array();
        return $rows;
    }


    /**
     * Returns a portion of comments limitted by $limit for the discussion
     *
     * @param $id
     * @param $last_id
     * @param $limit
     * @return
     */
    public function feed($id, $last_id, $limit)
    {
        $sql = "
        SELECT q.id, p.author, CASE WHEN m.membertype = 8 THEN m.organization ELSE m.firstname || ' ' || m.lastname END author_name,
               m.userphoto author_photo, content, p.created_at, q.replies, q.row_count
          FROM
        (
            SELECT p.id, COUNT(r.id) replies, COUNT(*) OVER () row_count
              FROM exp_discussion_posts p LEFT JOIN exp_discussion_posts r
                ON p.id = r.reply_to AND r.deleted_at IS NULL
             WHERE p.discussion_id = ?
               AND p.deleted_at IS NULL
               AND p.reply_to IS NULL";

        $bindings = array($id);

        if (! is_null($last_id) && $last_id > 0) {
            $sql .= " AND p.id < ?";
            $bindings[] = $last_id;
        }

        $sql .= "
            GROUP BY p.id
            ORDER BY p.id DESC
            LIMIT ?";
        $bindings[] = $limit;

        $sql .= "
        ) q JOIN exp_discussion_posts p
            ON q.id = p.id JOIN exp_members m
            ON p.author = m.uid
         ORDER BY q.id DESC";

        $rows = $this->db
            ->query($sql, $bindings)
            ->result_array();
        return $rows;
    }

    /**
     * Create a new record (post in a discussion)
     *
     * @param array $data
     * @return bool
     */
    public function create_post($data)
    {
        $this->db->set(array(
            'discussion_id' => $data['discussion_id'],
            'author' => $data['author'],
            'content' => $data['content'],
            'reply_to' => $data['reply_to'],
        ));

        if (! empty($data['created_at'])) {
            $this->db->set(array(
                'created_at' => $data['created_at'],
            ));
        }

        if (! $this->db->insert('exp_discussion_posts')) return false;

        return true;
    }

    /**
     * Returns true if the user has access to the specific
     * discussion and the discussion hasn't been deleted
     *
     * @param $member_id
     * @param $discussion_id
     * @return bool
     */
    public function has_access_to($member_id, $discussion_id)
    {
        $sql = "
        SELECT 1 allowed
          FROM exp_discussions d JOIN exp_projects p
            ON d.project_id = p.pid LEFT JOIN exp_discussion_members m
            ON d.id = m.discussion_id AND m.member_id = ?
         WHERE d.id = ?
           AND d.deleted_at IS NULL
           AND (p.uid = ?
            OR m.member_id IS NOT NULL)";

        $bindings = array($member_id, $discussion_id, $member_id);

        $row = $this->db
            ->query($sql, $bindings)
            ->row_array();

        return ! empty($row['allowed']) && $row['allowed'] == 1;
    }

    /**
     * Returns true if the user has access to the specific
     * discussion and the discussion hasn't been deleted
     *
     * @param $member_id
     * @param $project_id
     * @return bool
     */
    public function has_access($member_id, $project_id)
    {
        $sql = "
        SELECT 1 allowed
          FROM exp_discussions d JOIN exp_projects p
            ON d.project_id = p.pid LEFT JOIN exp_discussion_members m
            ON d.id = m.discussion_id AND m.member_id = ?
         WHERE d.project_id = ?
           AND d.deleted_at IS NULL
           AND (p.uid = ?
            OR m.member_id IS NOT NULL)
          LIMIT 1";
        $bindings = array($member_id, $project_id, $member_id);

        $row = $this->db
            ->query($sql, $bindings)
            ->row_array();

        return ! empty($row['allowed']) && $row['allowed'] == 1;
    }

    /**
     * @param $member_id
     * @param int $limit
     * @return mixed
     */
    public function my_discussions($member_id, $limit = 3)
    {
        $sql = "
        SELECT d.id, title, project_id, created_at,
               COUNT(*) OVER () row_count
          FROM
        (
          SELECT d.id
            FROM exp_discussions d JOIN exp_projects p
              ON d.project_id = p.pid JOIN exp_members m
              ON p.uid = m.uid
           WHERE (p.uid = ? AND
                  d.deleted_at IS NULL AND
                  m.status = ?)
              OR EXISTS
          (
            SELECT *
              FROM exp_discussion_members
             WHERE member_id = ?
               AND discussion_id = d.id
               AND m.status = ?
          )
          ORDER BY RANDOM()
          LIMIT ?
        ) q JOIN exp_discussions d
            ON q.id = d.id
        ";
        $bindings = array(
            $member_id,
            STATUS_ACTIVE, // project owner is not deleted
            $member_id,
            STATUS_ACTIVE, // project owner is not deleted
            $limit
        );

        $rows = $this->db
            ->query($sql, $bindings)
            ->result_array();
        return $rows;
    }

    /**
     * @param $limit
     * @param int $offset
     * @param array $filter
     * @param null $sort
     * @return mixed
     */
    public function all($limit = 0, $offset = 0, $filter = array(), $sort = null)
    {
        $bindings = array();

        $sql = "
        WITH discussions AS
        (
            SELECT d.id, d.title, d.created_at, d.deleted_at,
                   project_id, p.projectname project_name, p.slug project_slug, p.projectphoto project_photo
              FROM exp_discussions d JOIN exp_projects p
                ON d.project_id = p.pid JOIN exp_members m
                ON p.uid = m.uid
             WHERE";

        if (! empty($filter['deleted']) && $filter['deleted'] == true) {
            $sql .= " 1 = 1"; // all records including deleted
        } else {
            $sql .= " d.deleted_at IS NULL AND m.status = ? "; // only records that haven't been deleted
            $bindings[] = STATUS_ACTIVE; // only if a project owner has not been deleted
        }

        if (! empty($filter['project_id'])) {
            $sql .= " AND d.project_id = ?";
            $bindings[] = $filter['project_id'];
        }

        if (! empty($filter['member_id'])) {
            $sql .= " AND (p.uid = ? OR EXISTS(SELECT * FROM exp_discussion_members WHERE member_id = ? AND discussion_id = d.id))";
            $bindings[] = $filter['member_id'];
            $bindings[] = $filter['member_id'];
        }
        $sql .= ")";

        $sql .= "
        SELECT d.id, d.title, d.created_at, d.deleted_at,
               d.project_id, d.project_name, d.project_slug, d.project_photo,
               COUNT(*) OVER () row_count,
               COALESCE(e.expert_count, 0) expert_count,
               COALESCE(f.post_count, 0) post_count,
               f.last_activity_at
          FROM discussions d LEFT JOIN
        (
            SELECT m.discussion_id, COUNT(*) expert_count
              FROM exp_discussion_members m JOIN discussions d
                ON m.discussion_id = d.id
             GROUP BY m.discussion_id
        ) e ON d.id = e.discussion_id LEFT JOIN
        (
            SELECT p.discussion_id, COUNT(*) post_count, MAX(p.created_at) last_activity_at
              FROM exp_discussion_posts p JOIN discussions d
                ON p.discussion_id = d.id
             GROUP BY p.discussion_id
        ) f ON d.id = f.discussion_id";

        // Default order by
        $order_by = 'project_name, last_activity_at DESC, created_at DESC';

        if (! is_null($sort)) {
            if (is_integer($sort)) {
                // TBD
            } else {
                $order_by = $sort;
            }

        }
        $sql .= " ORDER BY $order_by";

        if ($limit > 0) {
            $sql .= " LIMIT ? OFFSET ?";
            $bindings[] = $limit;
            $bindings[] = $offset;
        }

        $rows = $this->db
            ->query($sql, $bindings)
            ->result_array();
        return $rows;
    }

    /**
     * Fetch a discussion by id
     *
     * @param $id
     * @param bool $deleted
     * @return mixed
     */
    public function find($id, $deleted = false)
    {
        $this->db
            ->select('d.id, d.title, d.description, d.project_id, d.created_at, d.deleted_at')
            ->from('exp_discussions AS d')
            ->join('exp_projects AS p', 'd.project_id = p.pid')
            ->join('exp_members AS m', 'p.uid = m.uid')
            ->where('id', (int) $id);

        if (! $deleted) {
            $this->db
                ->where('d.deleted_at', null)
                ->where('p.isdeleted', '0')
                ->where('m.status', STATUS_ACTIVE);
        }

        $row = $this->db->get()->row_array();

        return $row;
    }

    public function update($id, $data)
    {
        $updatable = array('title' => '', 'description' => '');

        // Strip everything except for updatable fields
        $update = array_intersect_key($data, $updatable);

        if (! $this->db
            ->where('id', (int) $id)
            ->set($update)
            ->update('exp_discussions')) {
            return false;
        }

        return true;
    }

    /**
     * Create a new record (discussion)
     *
     * @param array $data
     * @return bool
     */
    public function create($data)
    {
        $this->db->set(array(
            'title' => $data['title'],
            'description' => $data['description'],
            'project_id' => $data['project_id'],
        ));

        if (! empty($data['created_at'])) {
            $this->db->set(array(
                'created_at' => $data['created_at'],
            ));
        }

        if (! empty($data['deleted_at'])) {
            $this->db->set(array(
                'deleted_at' => $data['deleted_at'],
            ));
        }

        if (! $this->db->insert('exp_discussions')) return false;

        return $this->db->insert_id();
    }

    public function projects_list($all = true)
    {
        $sql = "SELECT p.pid project_id, p.projectname project_name FROM";

        if ($all) {
            $sql .= " exp_projects p";
        } else {
            $sql .= " (SELECT DISTINCT project_id FROM exp_discussions) d JOIN exp_projects p ON d.project_id = p.pid";
        }

        $sql .= " WHERE p.isdeleted = '0' ORDER BY project_name";

        $rows = $this->db
            ->query($sql)
            ->result_array();

        return flatten_assoc($rows, 'project_id', 'project_name');
    }

    public function experts($id, $all = false, $with_project_owner = false)
    {
        $sql = "
        WITH project_owner AS
        (
            SELECT uid member_id
            FROM exp_discussions d JOIN exp_projects p
              ON d.project_id = p.pid
            WHERE d.id = ?
        )
        SELECT m.uid id, m.firstname || ' ' || m.lastname expert_name,
               m.organization, m.title, m.userphoto, m.email,
               CASE WHEN dm.member_id IS NULL THEN 0 ELSE 1 END status
          FROM exp_members m ";

        if ($all) $sql .= " LEFT";

        $sql .= " JOIN exp_discussion_members dm
            ON member_id = m.uid AND dm.discussion_id = ?
         WHERE NOT EXISTS
         (
            SELECT * FROM project_owner WHERE member_id = m.uid
         )
           AND m.membertype = ?
           AND m.status = ?";

        if ($with_project_owner) {
            $sql .= " UNION ALL
            SELECT m.uid, m.firstname || ' ' || m.lastname,
               m.organization, m.title, m.userphoto, m.email, 1
              FROM exp_members m JOIN project_owner o
                ON m.uid = o.member_id";
        }

        $sql .= " ORDER BY expert_name";

        $bindings = array($id, $id, MEMBER_TYPE_MEMBER, STATUS_ACTIVE);

        $rows = $this->db
            ->query($sql, $bindings)
            ->result_array();

        return $rows;
    }

    public function allow($id, $member_id)
    {
        $sql = "
        WITH values AS
        (
            SELECT ? discussion_id, ? member_id
        )
        INSERT INTO exp_discussion_members (discussion_id, member_id)
        SELECT discussion_id, member_id
          FROM values v
         WHERE NOT EXISTS
        (
            SELECT *
              FROM exp_discussion_members
             WHERE discussion_id = v.discussion_id
               AND member_id = v.member_id
        )";

        $bindings = array((int) $id, (int) $member_id);

        if (! $this->db->query($sql, $bindings)) {
            return false;
        }

        return true;
    }

    public function deny($id, $member_id)
    {
        $sql = "
        DELETE
          FROM exp_discussion_members
         WHERE discussion_id = ?
           AND member_id = ?";

        $bindings = array((int) $id, (int) $member_id);

        if (! $this->db->query($sql, $bindings)) {
            return false;
        }

        return true;
    }
}