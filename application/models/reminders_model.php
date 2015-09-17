<?php

class Reminders_model extends CI_Model
{
    /**
     * Insert a new reminder record
     *
     * @param $data
     * @return bool
     */
    public function create($data)
    {
        $insert = array(
            'email' => $data['email'],
            'token' => $data['token'],
        );

        if (! empty($data['created_at'])) $insert['created_at'] = $data['created_at'];

        $result = $this->db
            ->set($insert)
            ->insert('exp_password_reminders');

        if (! $result) return false;

        return true;
    }

    /**
     * Get a reminder record by token or by token and email
     *
     * @param string $token
     * @param string $email
     * @return array
     */
    public function find($token, $email = '')
    {
        $this->db->where('token', $token);
        if (! empty($email)) {
            $this->db->where('email', $email);
        }

        $row = $this->db
            ->get('exp_password_reminders')
            ->row_array();

        return $row;
    }

    /**
     * Delete a reminder record by token.
     *
     * @param $token
     * @return bool
     */
    public function delete($token)
    {
        $result = $this->db
            ->where('token', $token)
            ->delete('exp_password_reminders');

        if (! $result) return false;

        return true;
    }

    /**
     * Delete all expired reminders.
     *
     * @return bool
     */
    public function delete_expired()
    {
        $expired = date('Y-m-d H:i:s', time() - REMINDER_EXPIRES);

        $result = $this->db
            ->where('created_at <', $expired)
            ->delete('exp_password_reminders');

        if (! $result) return false;

        return true;
    }
}