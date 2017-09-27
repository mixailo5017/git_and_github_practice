<?php

class Signup_model extends CI_Model
{
    private $key = 'signup';

    private $fields = array(
        'is_developer' => false,
        'linkedin' => false,
        'email' => '',
        'firstname' => '',
        'lastname' => '',
        'title' => '',
        'organization' => '',
        'public_status' => '',
        'discipline' => '',
        'sub-sector' => array(),
        'country' => '',
        'city' => '',
        'password' => '',
        'userphoto' => ''
    );

    private $optional_fields = [
        'is_developer' => '', 
        'linkedin' => ''
    ];

    /**
     * Clears signup data from session
     *
     */
    public function clear()
    {
        $this->session->unset_userdata($this->key);
    }

    /**
     * Returns all signup data
     *
     * @return array
     */
    public function get()
    {
        $signup = $this->session->userdata($this->key);

        if (! $signup) return $this->fields;

        return $signup;
    }

    /**
     * Update signup data and return updated data back
     *
     * @param $data
     * @return array
     */
    public function update($data)
    {

        $current = $this->get();

        $updated = array_merge($current, array_intersect_key($data, $this->fields));

        $this->session->set_userdata($this->key, $updated);

        return $updated;
    }

    /**
     * Returns true if all required fileds have been filled
     *
     * @return bool
     */
    public function validate_required()
    {
        $required = array_intersect_key($this->get(), $this->get_required_fields());

        foreach ($required as $key => $value) {
            if (empty($value)) return false;
        }

        return true;
    }

    /**
     * Returns assoc array of required fields
     *
     * @return array
     */
    public function get_required_fields()
    {
        return array_diff_key($this->fields, $this->optional_fields);
    }

    /**
     * Returns assoc array of all fields
     * it may be convinient to use to filter out POST input
     *
     * @return array
     */
    public function get_fields()
    {
        return $this->fields;
    }
}