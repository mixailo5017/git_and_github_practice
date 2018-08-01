<?php

class Index_model extends CI_Model {

    /**
     * Validate Admin Login
     *
     * @param $username
     * @param $password
     * @return    boolean
     */
    public function validate_login($username, $password)
    {
        $user = $this->db
            ->select('uid, email, password, firstname, lastname, salt, membertype')
            ->where('email', strtolower($username))// change case to lower before comparing
            ->where('status', STATUS_ACTIVE)
            ->where('membertype', MEMBER_TYPE_ADMIN)
            ->get('exp_members')
            ->row_array();

        // User doesn't exist
        if (empty($user)) {
            return false;
        }

        $hash = hash('sha512', $user['salt'] . $password);

        // If hashes match
        if ($hash == $user['password']) {
            $data = array(
                'admin_logged_in' => TRUE,
                'admin_uid' => $user['uid'],
                'admin_name' => $user['firstname'] . ' ' . $user['lastname'],
                'admin_super' => FALSE,
                'admin_type' => $user['membertype'],
            );
            $this->session->set_userdata($data);

            return true;
        }

        return false;
    }
}
