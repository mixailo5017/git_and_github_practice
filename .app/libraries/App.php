<?php

class App {

    protected static $supported_languages = array(
        'english',
        'french',
        'spanish',
        'portuguese'
    );

    public static $languageToLocaleLookup = [
        'english'    => 'en',
        'french'     => 'fr',
        'spanish'    => 'es',
        'portuguese' => 'pt'
    ];

    protected static $default_language = 'english';

    public static function language($language = null)
    {
        $CI =& get_instance();

        if (empty($language)) {
            return strtolower($CI->session->userdata('lang') ?: 'english');
        }

        if (! in_array($language, static::$supported_languages, true))
            $language = static::$default_language;

        get_language_file($language);
    }

    public static function is_cli()
    {
        $CI =& get_instance();

        return $CI->input->is_cli_request();
    }

    public static function remote_addr()
    {
        $CI =& get_instance();

        return $CI->input->server('HTTP_X_FORWARDED_FOR') ?: $CI->input->server('REMOTE_ADDR') ?: '';
    }

    public static function is_down_for_maintenence()
    {
        $down_file = BASE.'.down';

        return file_exists($down_file);
    }

    public static function is_ip_allowed_when_down()
    {
        return in_array(static::remote_addr(), static::down_allowed_ip_list());
    }

    private static function down_allowed_ip_list()
    {
        $app_down_ip = env('APP_DOWN_IP', '');
        if (empty($app_down_ip)) return array();

        $list = explode(',', $app_down_ip);
        if (empty($list)) return array();

        return array_map('trim', $list);
    }
}