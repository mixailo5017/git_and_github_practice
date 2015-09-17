<?php

//for scripts that are running via CLI
if (! isset($_SERVER['SERVER_NAME'])) {
    $_SERVER['SERVER_NAME'] = php_uname("n");
    define("CLI_SCRIPT",TRUE);
} else {
    define("CLI_SCRIPT",FALSE);
}

/*
 *---------------------------------------------------------------
 * BASE FOLDERS
 *---------------------------------------------------------------
 */
$_system_path	= '../system';
$_admin_folder	= '../backend';
$_public_folder	= '../application';

$_this_folder = str_replace(strrchr(__FILE__, '/'),'',__FILE__);

define('BASE',str_replace(strrchr( $_this_folder, '/'),'', $_this_folder).'/');
define('MASTER_CONFIG',		$_this_folder.'/');
define('MASTER_MIGRATION',	BASE.'.migrations/');

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 * Detect environment and load configuration values using .env
 */

try {
    Dotenv::load(BASE, '.env');
} catch (InvalidArgumentException $e) {
    // Ignore the exception if the file is not found
}

// If environment has not been defined it defaults to production
define('ENVIRONMENT', env('APP_ENV') ?: 'production');

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------*
 */

$app_debug = env('APP_DEBUG');
// If debug mode has not been defined if defaults to false in production and true for other environments
if (! is_bool($app_debug)) $app_debug = ENVIRONMENT !== 'production';

if ($app_debug === false) {
    error_reporting(0);
} else {
    error_reporting(E_ALL);
}
