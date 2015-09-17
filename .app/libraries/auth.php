<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth {

    private $CI;

    private $members;
    private $session;

    private $user_fields = 'uid,email,password,salt,firstname,lastname,membertype,userphoto,organization,lastlogin';
    private $user;

    public function __construct()
    {
        $this->CI =& get_instance();

        $this->CI->load->model('members_model');
        $this->members = $this->CI->members_model;

        $this->session = $this->CI->session;
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public static function check()
    {
        $CI =& get_instance();

        return $CI->session->userdata('logged_in') == true &&
            (int) $CI->session->userdata('uid') > 0;
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public static function guest()
    {
        return ! static::check();
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|null
     */
    public static function id()
    {
        $CI =& get_instance();

        $id = $CI->session->userdata('uid');

        return $id ? (int) $id : null;
    }


    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array  $credentials
     * @param  bool   $remember
     * @param  bool   $login
     * @return bool
     */
    public function attempt($credentials, $remember = false, $login = true)
    {
        $user = $this->retrieve_by_credentials($credentials);

        if (! $this->has_valid_credentials($user, $credentials)) return false;

        if ($login) $this->login($user, $remember);

        return true;
    }

    /**
     * Log the given user ID into the application.
     *
     * @param  mixed  $id
     * @param  bool   $remember
     * @return bool
     */
    public function login_by_id($id, $remember = false)
    {
        $user = $this->retrieve_by_id($id);

        if (empty($user)) return false;

        $this->login($user, $remember);

        return true;
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout()
    {
        $this->members->update($this->id(), array('lastlogout' => time()));

        $session_data = array(
            'logged_in' => '',
            'uid' => '',
            'name' => '',
            'lastlogin' => '',
            'usertype' => '',
            'userphoto' => ''
        );
        $this->session->unset_userdata($session_data);
    }
        /**
     * Log a user into the application.
     *
     * @param  array $user
     * @param  bool  $remember
     * @return void
     */
    private function login($user, $remember)
    {
        $lastlogin = time();

        // Set the auth session data
        $data = array(
            'logged_in'	=> true,
            'uid' => (int) $user['uid'],
            'remember' => $remember, // ???
            'name' => ($user['membertype'] == MEMBER_TYPE_EXPERT_ADVERT) ? $user['organization'] : $user['firstname'],
            'lastlogin' => (int) $user['lastlogin'],
            'usertype' => (int) $user['membertype'],
            'userphoto' => $user['userphoto']
        );
        $this->session->set_userdata($data);

        // Update last login time stamp
        $this->members->update((int) $user['uid'], array('lastlogin' => $lastlogin));

        $this->user = $user;
    }

    private function retrieve_by_credentials($credentials)
    {
        return $this->members->find_by_email(strtolower($credentials['email']), $this->user_fields);
    }

    private function retrieve_by_id($id)
    {
        return $this->members->find($id, $this->user_fields);
    }

    // TODO: Redo this legacy password hashing scheme
    // Get rid of storing salt in a separate column
    public function has_valid_credentials($user, $credentials)
    {
        if (empty($user)) return false;

        return $user['password'] == hash('sha512', $user['salt'] . $credentials['password']);
    }
}