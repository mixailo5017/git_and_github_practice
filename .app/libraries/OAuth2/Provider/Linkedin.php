<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * LinkedIn OAuth2 Provider
 * https://developer.linkedin.com/documents/authentication
 * 
 * @package    CodeIgniter/OAuth2
 * @category   Provider
 * @author     Benjamin Hill
 * @copyright  (c) None
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */
class OAuth2_Provider_Linkedin extends OAuth2_Provider {

  public $method = 'POST';
  public $scope_seperator = ' ';

  public function __construct(array $options = array()) {
    if (empty($options['scope'])) {
      $options['scope'] = array(
          'r_basicprofile',
          'r_emailaddress'
      );
    }

    // Array it if its string
    $options['scope'] = (array) $options['scope'];

    parent::__construct($options);
  }

  public function url_authorize() {
    return 'https://www.linkedin.com/uas/oauth2/authorization';
  }

  public function url_access_token() {
    return 'https://www.linkedin.com/uas/oauth2/accessToken';
  }

  public function get_company_info(OAuth2_Token_Access $token, $company_id, $fields)
  {
      if (is_array($fields)) $fields = implode(',', $fields);

      $url_company = "https://api.linkedin.com/v1/companies/$company_id:($fields)/?format=json&" . http_build_query(array(
              'oauth2_access_token' => $token->access_token,
          ));

      $company = json_decode(file_get_contents($url_company), true);

      return $company;
  }

  public function get_user_info2(OAuth2_Token_Access $token, $fields)
  {
      $uri = '';

      if (! empty($fields)) {
          if (is_array($fields)) $fields = implode(',', $fields);
          $uri = ":($fields)";
      }

      $url_profile = "https://api.linkedin.com/v1/people/~$uri?format=json&" . http_build_query(array(
              'oauth2_access_token' => $token->access_token,
          ));

      $user = json_decode(file_get_contents($url_profile), true);

      return $user;
  }

    public function get_user_info(OAuth2_Token_Access $token) {

    $url_profile = 'https://api.linkedin.com/v1/people/~?format=json&' . http_build_query(array(
                'oauth2_access_token' => $token->access_token,
    ));
    $user = json_decode(file_get_contents($url_profile), true);

    $url_email = 'https://api.linkedin.com/v1/people/~/email-address?format=json&' . http_build_query(array(
                'oauth2_access_token' => $token->access_token,
    ));
    $user_email = json_decode(file_get_contents($url_email), true);

    $args = array();
    parse_str(parse_url($user['siteStandardProfileRequest']['url'], PHP_URL_QUERY), $args);
    $user_id = $args['id'];
    return array(
        'id' => $user_id,
        'first_name' => $user['firstName'],
        'last_name' => $user['lastName'],
        'name' => $user['firstName'] . ' ' . $user['lastName'],
        'description' => $user['headline'],
        'email' => $user_email,
        'urls' => array(
            'LinkedIn' => $user['siteStandardProfileRequest']['url']
        ),
    );
  }
}
