<?php

class Updates_model extends CI_Model {
    /*
     * Update target types defined in constants.master.php
     * MEMBER_UPDATE  = 1
     * COMPANY_UPDATE = 2
     * PROJECT_UPDATE = 3
     */

    /*
     * Update types defined in constants.master.php
     * UPDATE_TYPE_STATUS     = 1
     * UPDATE_TYPE_COMMENT    = 2
     * UPDATE_TYPE_PROFILE    = 3
     * UPDATE_TYPE_NEWPROJECT = 4
     */

    public function authors_list()
    {
        $sql = "
        SELECT DISTINCT author author_id, m.firstname || ' ' || m.lastname author_name
          FROM exp_updates u JOIN exp_members m
            ON u.author = m.uid
         WHERE u.type IN(?, ?)
         ORDER BY author_name
        ";

        $rows = $this->db
            ->query($sql, array(UPDATE_TYPE_STATUS, UPDATE_TYPE_COMMENT))
            ->result_array();
        return flatten_assoc($rows, 'author_id', 'author_name');
    }

    public function projects_list()
    {
        $sql = "
        SELECT DISTINCT p.pid project_id, p.projectname project_name
          FROM exp_updates u JOIN exp_project_updates pu
            ON u.id = pu.update_id JOIN exp_projects p
            ON pu.project_id = p.pid
         WHERE u.type IN(?, ?)
         ORDER BY project_name
        ";

        $rows = $this->db
            ->query($sql, array(UPDATE_TYPE_STATUS, UPDATE_TYPE_COMMENT))
            ->result_array();
        return flatten_assoc($rows, 'project_id', 'project_name');
    }

    public function find($update_id)
    {
        $sql = "
        SELECT u.id, u.author, m.firstname || ' ' || m.lastname author_name, u.type,
               u.content, u.reply_to, u.created_at, u.deleted_at,
               CASE WHEN CHARACTER_LENGTH(r.content) > 100 THEN LEFT(r.content, 100) || '...' ELSE r.content END reply_to_content,
               pu.project_id, p.projectname project_name, p.slug project_slug
          FROM exp_updates u JOIN exp_members m
            ON u.author = m.uid JOIN exp_project_updates pu
            ON u.id = pu.update_id JOIN exp_projects p
            ON pu.project_id = p.pid LEFT JOIN exp_updates r
            ON u.reply_to = r.id
         WHERE u.id = ?
           AND u.type IN(?, ?)";
        $bindings = array($update_id, UPDATE_TYPE_STATUS, UPDATE_TYPE_COMMENT);

        $row = $this->db
            ->query($sql, $bindings)
            ->row_array();

        return $row;
    }

    public function all($filter = array())
    {
        $sql = "
        SELECT u.id, u.author, m.firstname || ' ' || m.lastname author_name, u.type,
               CASE WHEN CHARACTER_LENGTH(u.content) > 100 THEN LEFT(u.content, 100) || '...' ELSE u.content END \"content\",
               u.reply_to, u.created_at, u.deleted_at
          FROM exp_updates u JOIN exp_members m
            ON u.author = m.uid JOIN exp_project_updates pu
            ON u.id = pu.update_id
         WHERE u.type IN(?, ?)";
        $bindings = array(UPDATE_TYPE_STATUS, UPDATE_TYPE_COMMENT);

        if (! empty($filter['id'])) {
            $sql .= " AND u.id = ?";
            $bindings[] = (int) $filter['id'];
        }

        if (! empty($filter['author_id'])) {
            $sql .= " AND u.author = ?";
            $bindings[] = (int) $filter['author_id'];
        }

        if (! empty($filter['created_at'])) {
            $sql .= " AND u.created_at >= DATE(?) AND u.created_at < DATE(?) + 1";
            $bindings[] = $filter['created_at'];
            $bindings[] = $filter['created_at'];
        }

        if (! empty($filter['project_id'])) {
            $sql .= " AND pu.project_id = ?";
            $bindings[] = (int) $filter['project_id'];
        }

        // By default show ony records that haven't been deleted
        if (empty($filter['deleted']) || $filter['deleted'] == false) {
            $sql .= " AND u.deleted_at IS NULL"; // only records that haven't been deleted
        }

        // Apply default sort order
        $sql .= " ORDER BY created_at";

        $rows = $this->db
            ->query($sql, $bindings)
            ->result_array();
        return $rows;
    }

    public function flag($update_id)
    {
        // TODO: TBD
        return true;
    }

    /**
     * Restore
     *
     * @param $update_id
     * @return bool
     */
    public function restore($update_id)
    {
        return $this->set_deleted_at($update_id, true);
    }

    /**
     * Delete an update and all related replies
     *
     * @param $update_id
     * @return bool
     */
    public function delete($update_id)
    {
        return $this->set_deleted_at($update_id, false);
    }

    /**
     * Delete an update and all related replies
     *
     * @param $update_id
     * @param bool $clear
     * @return bool
     */
    private function set_deleted_at($update_id, $clear = false)
    {
        $value = $clear ? 'NULL' : 'CURRENT_TIMESTAMP';

        $this->db
            ->where('id', $update_id)
            ->set('deleted_at', $value, FALSE);

        if (! $this->db->update('exp_updates')) {
            return false;
        }

        return true;
    }

    /**
     * Returns an array of replies (comments) for a specific update
     * @param $update_id
     */
    public function replies($update_id)
    {
        $sql = "
        SELECT u.id, u.author, CASE WHEN m.membertype = 8 THEN m.organization ELSE m.firstname || ' ' || m.lastname END author_name,
               m.userphoto author_photo, u.type, content, pu.project_id target, ? target_type, p.projectname target_name,
               p.projectphoto target_photo, u.created_at
          FROM exp_updates u JOIN exp_members a
            ON u.author = a.uid JOIN exp_project_updates pu
            ON u.id = pu.update_id JOIN exp_projects p
            ON pu.project_id = p.pid JOIN exp_members m
            ON u.author = m.uid
         WHERE u.type = ?
           AND u.reply_to = ?
           AND u.deleted_at IS NULL
           AND a.status = ?
           AND p.isdeleted = ?
         ORDER BY u.id";
        $bindings = array(
            PROJECT_UPDATE, // currently you can reply only in the project feed
            UPDATE_TYPE_COMMENT, // replies can be only of type COMMENT
            $update_id,
            STATUS_ACTIVE, // Author should be active
            '0', // Project is not deleted
        );

        $rows = $this->db
            ->query($sql, $bindings)
            ->result_array();
        return $rows;
    }

    /**
     * Returns an array of project specific updates
     *
     * @param int $project_id
     * @param int $last_id
     * @param int $limit
     * @return
     */
    public function project_feed($project_id, $last_id, $limit)
    {
        $sql = "
            SELECT u.id, u.author,
                   CASE WHEN a.membertype = 8
                        THEN a.organization
                        ELSE a.firstname || ' ' || a.lastname END author_name,
                   a.userphoto author_photo, u.type, content, pu.project_id target,
                   ? target_type, p.projectname target_name,
                   p.projectphoto target_photo, u.created_at,
                   COUNT(*) OVER () row_count
              FROM exp_updates u JOIN exp_members a -- author should be active
                ON u.author  = a.uid JOIN exp_project_updates pu
                ON u.id = pu.update_id JOIN exp_projects p
                ON pu.project_id = p.pid
             WHERE (pu.project_id = ?
               AND p.isdeleted = ?
               AND u.deleted_at IS NULL
               AND u.reply_to IS NULL)
               AND (u.type IN(?, ?) OR (u.type = ? AND a.status = ?))
        ";
        $bindings = array(
            PROJECT_UPDATE,
            $project_id,
            '0', // project is not deleted
            UPDATE_TYPE_STATUS,
            UPDATE_TYPE_PROFILE,
            UPDATE_TYPE_COMMENT,
            STATUS_ACTIVE, // author should be active for UPDATE_TYPE_COMMENT
        );

        if (! is_null($last_id) && $last_id > 0) {
            $sql .= " AND u.id < ?";
            $bindings[] = $last_id;
        }

        $sql .= "
            ORDER BY u.id DESC
            LIMIT ?";
        $bindings[] = $limit;


        $sql = "
        WITH base AS
        (
            $sql
        ), reply_count AS
        (
            SELECT b.id, COUNT(*) replies
              FROM exp_updates r JOIN base b
                ON r.reply_to = b.id JOIN exp_members a
                ON r.author = a.uid
             WHERE a.status = ? -- active
             GROUP BY b.id
        )
        SELECT b.id, author, author_name, author_photo, b.type, content,
               target, target_type, target_name, target_photo,
               created_at, row_count, COALESCE(replies, 0) replies
          FROM base b LEFT JOIN reply_count r
            ON b.id = r.id
         ORDER BY b.id DESC
        ";
        $bindings[] = STATUS_ACTIVE; // authors should be active for replies

        $rows = $this->db
            ->query($sql, $bindings)
            ->result_array();
        return $rows;
    }

    /**
     * @param $member_id
     * @param int $last_id
     * @param int $limit
     * @return
     */
    public function myvip_feed($member_id, $last_id, $limit)
    {
        $sql = "
        WITH follows AS
        (
            SELECT member_id
              FROM exp_member_followers f
             WHERE f. follower = ?
        ), base AS
        (
            SELECT u.*, COUNT(*) OVER () row_count
              FROM exp_updates u JOIN exp_members a
                ON u.author = a.uid LEFT JOIN exp_member_updates mu
                ON u.id = mu.update_id LEFT JOIN exp_members m
                ON mu.member_id = m.uid LEFT JOIN exp_project_updates pu
                ON u.id = pu.update_id LEFT JOIN exp_projects p
                ON pu.project_id = p.pid LEFT JOIN exp_members o
                ON p.uid = o.uid AND o.status = ?
             WHERE
             (
               (mu.member_id IS NOT NULL AND
                EXISTS(SELECT * FROM follows f WHERE f.member_id = mu.member_id) AND
                m.status = ?) OR
               (pu.project_id IS NOT NULL AND u.type IN(?, ?) AND p.isdeleted = ? AND o.uid IS NOT NULL) OR
               (pu.project_id IS NOT NULL AND u.type = ? AND a.status = ? AND
                EXISTS(SELECT * FROM follows f WHERE f.member_id = u.author) AND
                p.isdeleted = ?
               )
             )";
        $bindings = array(
            $member_id, // Follower id
            STATUS_ACTIVE, //
            STATUS_ACTIVE, // Project owner is not deleted
            UPDATE_TYPE_STATUS,
            UPDATE_TYPE_PROFILE,
            '0', // Only projects that are not deleted
            UPDATE_TYPE_NEWPROJECT,
            STATUS_ACTIVE, // Author for UPDATE_TYPE_NEWPROJECT updates should be active
            '0', // Only projects that are not deleted
        );

        if (! is_null($last_id) && $last_id > 0) {
            $sql .= " AND u.id < ?";
            $bindings[] = $last_id;
        }

        // Don't include deleted updates
        $sql .= " AND u.deleted_at IS NULL";

        // TODO: Revisit and deal with deleted projecs, members, authors
        // $sql .= " AND pu.project_id IS NOT NULL";

        $sql .= " ORDER BY u.id DESC LIMIT ?";
        $bindings[] = $limit;

        $sql .= "
        ), project_feed AS
        (
            SELECT id, author, a.firstname || ' ' || a.lastname author_name, a.userphoto author_photo, u.type, content, pu.project_id target, ? target_type,
                   p.projectname target_name, p.projectphoto target_photo, u.created_at, row_count
              FROM base u JOIN exp_project_updates pu
                ON u.id = pu.update_id JOIN exp_projects p
                ON pu.project_id = p.pid JOIN exp_members a
                ON author = a.uid
        ), member_feed AS
        (
            SELECT id, author, a.firstname || ' ' || a.lastname author_name, a.userphoto author_photo, u.type, content, mu.member_id target, ? target_type,
                   m.firstname || ' ' || m.lastname target_name, m.userphoto target_photo, u.created_at, row_count
              FROM base u JOIN exp_member_updates mu
                ON u.id = mu.update_id JOIN exp_members m
                ON mu.member_id = m.uid JOIN exp_members a
                ON author = a.uid
        )
        SELECT * FROM project_feed
        UNION ALL
        SELECT * FROM member_feed
        ORDER BY created_at DESC";

        $bindings[] = PROJECT_UPDATE;
        $bindings[] = MEMBER_UPDATE;

        $rows = $this->db
            ->query($sql, $bindings)
            ->result_array();
        return $rows;
    }

    /**
     * Create a new update record
     *
     * @param array $data
     * @return bool
     */
    public function create($data)
    {
        // To save an update we need to insert rows in two tables
        // exp_updates (a supertype table) and a type specific table (e.g. exp_project_updates)
        // Therefore we need to use transactions

        // BEGIN TRANSACTION
        $this->db->trans_start();

        $this->db
            ->set(array(
                'author'     => $data['author'],
                'type'       => $data['type'],
                'content'    => $data['content'],
                'created_at' => $data['created_at'],
                'reply_to'   => $data['reply_to'],
            ))
            ->insert('exp_updates');
        $id = $this->db->insert_id();

        // Currently there are only project updates
        if ($data['target_type'] == PROJECT_UPDATE) {
            $this->db
                ->set(array('update_id' => $id, 'project_id' => $data['target_id']))
                ->insert('exp_project_updates');
        } elseif ($data['target_type'] == MEMBER_UPDATE) {
            $this->db
                ->set(array('update_id' => $id, 'member_id' => $data['target_id']))
                ->insert('exp_member_updates');
        }

        // COMMIT
        $this->db->trans_complete();
        $this->db->trans_off(); // TODO: Revisit this

        if ($this->db->trans_status() === FALSE) {
            return false;
        }

        return true;
    }
}