<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_exp_members_table extends CI_Migration {

    protected $table = 'exp_members';

    public function up()
    {
        if ($this->db->table_exists($this->table)) {
            // Make changes in a transaction
            $this->db->trans_start();

            // Make all emails lowercase
            $this->db->query("UPDATE {$this->table} SET email = LOWER(email) WHERE email <> LOWER(email)");

            // Deal with duplicate emails
            // 1. Fetch uids of records with duplicate emails
            $sql = "
                SELECT uid, REPLACE(email, '@', uid || '@') AS new_email
                  FROM
                (
                  SELECT uid, lastname, firstname, organization, email,
                  COUNT(*) OVER (PARTITION BY LOWER(email)) email_count,
                  MAX(uid) OVER (PARTITION BY LOWER(email)) max_uid
                  FROM {$this->table}
                ) q
                 WHERE email_count > 1
                   AND uid <> max_uid";
            $rows = $this->db
                ->query($sql)
                ->result_array();

            foreach ($rows as $row) {
                // Create hash and salt for the new password, which is 'password' plus user's uid (e.g. password747)
                $hashed = encrypt_password('password' . $row['uid']);
                // Update user's record with new email, salt and password
                $data = array_merge($hashed, array('email' => $row['new_email']));
                $this->db
                    ->where('uid', $row['uid'])
                    ->update($this->table, $data);
            }
            $this->db->trans_complete();

            // Create unique index to prevent duplicate emails for good
            $this->db->query("CREATE UNIQUE INDEX {$this->table}_email_uqe ON {$this->table} (email)");
            // Create an index that supports queries that filter by email and status columns
            $this->db->query("CREATE INDEX {$this->table}_email_status_idx ON {$this->table} (email, status)");
            // Create index to support searches in list form
            $this->db->query("CREATE INDEX {$this->table}_country_membertype_status_idx ON {$this->table} (country, membertype, status)");
            $this->db->query("CREATE INDEX {$this->table}_sector_membertype_status_idx ON {$this->table} (sector, membertype, status)");
            $this->db->query("CREATE INDEX {$this->table}_discipline_membertype_status_idx ON {$this->table} (discipline, membertype, status)");
        }
    }

    public function down()
    {
        $this->db->query("DROP INDEX IF EXISTS {$this->table}_discipline_membertype_status_idx");
        $this->db->query("DROP INDEX IF EXISTS {$this->table}_sector_membertype_status_idx");
        $this->db->query("DROP INDEX IF EXISTS {$this->table}_country_membertype_status_idx");

        $this->db->query("DROP INDEX IF EXISTS {$this->table}_email_status_idx");
        $this->db->query("DROP INDEX IF EXISTS {$this->table}_email_uqe");
    }
}