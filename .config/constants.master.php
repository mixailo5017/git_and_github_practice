<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

// END CodeIgniter boilerplate (code below is GViP-specific)

/*
|-------------------------------------------------------------------------
| Upload Image Paths
|-------------------------------------------------------------------------
|
| These paths are used while displaying image of Projects/User Profile/Projects Listing
|
*/

define('IMAGE_PATH', '/images');
define('IMAGE_RETRIEVAL_PATH', '/img');
define('IMAGE_CACHE_PATH', '/cache/made/');

define('PROJECT_IMAGE_PATH', IMAGE_PATH.'/content_projects/');
define('PROJECT_NO_IMAGE_PATH', IMAGE_PATH.'/site/');
define('PROJECT_IMAGE_PLACEHOLDER', 'placeholder_project.jpg');

define('USER_IMAGE_PATH', IMAGE_PATH.'/member_photos/');
define('USER_THUMB_IMAGE_PATH', IMAGE_PATH.'/member_photos/thumb/');
define('USER_BIG_THUMB_IMAGE_PATH', IMAGE_PATH.'/member_photos/thumb/big/');
define('USER_NO_IMAGE_PATH', IMAGE_PATH.'/site/');
define('USER_IMAGE_PLACEHOLDER', 'profile_image_placeholder.png');
define('ORGANIZATION_IMAGE_PLACEHOLDER', 'placeholder_organization.png');

define('AD_IMAGE_PATH',IMAGE_PATH.'/ad_images/');
define('SITE_IMAGE_PATH', IMAGE_PATH.'/site/');
define('FORUM_IMAGE_PATH', IMAGE_PATH.'/forum/');
define('FORUM_NO_IMAGE_PATH', IMAGE_PATH.'/site/');
define('FORUM_IMAGE_PLACEHOLDER', 'placeholder_forum.png');

define('FORUM_REGISTER_OFFSITE', 0);
define('FORUM_REGISTER_ON_GVIP', 1);

define('STORE_IMAGE_PATH', IMAGE_PATH.'/store/');
define('STORE_NO_IMAGE_PATH', IMAGE_PATH.'/site/');
define('STORE_ITEM_IMAGE_PLACEHOLDER', 'placeholder_store_item.png');

define('DISCUSSION_IMAGE_PLACEHOLDER', 'placeholder_discussion.png');

define('SIGNUP_IMAGE_PATH', IMAGE_PATH.'/signup/');

/*
|------------------------------------------------------------------------
| General Variable
|------------------------------------------------------------------------
|
| General Variables used in Site like currency, date format etc
|
*/

define("CURRENCY","$");
define("DATEFORMAT","%m/%d/%y"); // Display (on edit screen) and transmission format for dates with day-level accuracy
define("DATEFORMATDB","%y-%m-%d"); // Format used to store dates in DB

/*
|------------------------------------------------------------------------
| Application specific constants
|------------------------------------------------------------------------
*/
define('SITE_NAME', 'GViP');
define('DATEFORMAT_MONTHONLY', '%m/%y'); // Display (on edit screen) and transmission format for dates with month-level accuracy
define('DATEFORMATVIEW', 'M j, Y'); // Display format for dates with day-level accuracy. E.g., used for project profile view Files section
define('DATEFORMATVIEW_MONTHONLY', 'F Y'); // Display format for dates with month-level accuracy. E.g., used for project profile view
define('ADMIN_EMAIL', 'info@gvip.io');
define('ADMIN_EMAIL_NAME', SITE_NAME);
define('CGLA_NAME', 'CG/LA Infrastructure');
define('CGLA_SITE', 'https://www.cg-la.com/');

// Concierge email
//define("CONCIERGE_EMAIL","norman@cg-la.com,scott@cg-la.com,erik@cg-la.com,cristina@cg-la.com");
define('CONCIERGE_EMAIL', 'gvip911@cg-la.com');
define('CONCIERGE_EMAIL_TITLE', 'New Concierge Question');

// Max number of experts shown in a sidebar of the forum detail view
define("FORUM_EXPERT_LIMIT",  15);
// Max number of projects shown in a sidebar of the forum detail view
define("FORUM_PROJECT_LIMIT", 8);

define('MAX_SUB_SECTOR', 6);  // Maximum number of subsectors of an expert
define('MAX_CASE_STUDIES', 15); // Maximum number of case studies per user (expert advert)
define('MAX_IMAGE_DIMENSIONS', 10915904); // Max width * hight before using fallback (placeholder) image

define('STATUS_INACTIVE', '0');
define('STATUS_ACTIVE', '1');
define('STATUS_PENDING', '2');

define('MEMBER_TYPE_ADMIN', 1);
define('MEMBER_TYPE_MEMBER', 5);
define('MEMBER_TYPE_EXPERT_ADVERT', 8);

// Updates model specific constants
define('MEMBER_UPDATE',  1);
define('COMPANY_UPDATE', 2);
define('PROJECT_UPDATE', 3);

define('UPDATE_TYPE_STATUS',  1);
define('UPDATE_TYPE_COMMENT', 2);
define('UPDATE_TYPE_PROFILE', 3);
define('UPDATE_TYPE_NEWPROJECT', 4);

// How many updates (comments) to show in Project Feed
define('MAX_UPDATES',  5);
// How long (in minutes) to retain a public profile in cache
define('PUBLIC_PROFILE_TTL', 120);
// How long (in minutes) to retain sitemap in cache
define('SITEMAP_TTL', 0);

// Allows to enable or disable public project profiles feature
define('PROJECT_PROFILES_ENABLED', TRUE);

// Used to determine which users' projects will be shown to the public, 
// which project profiles will not show the project developer, etc.
// Arrays in constants requires PHP 5.6. Defining them using the define statement only available from PHP 7.0.
const INTERNAL_USERS = [24, 28, 37, 195, 198, 222, 298, 492, 562, 583, 684, 741, 813, 824, 840, 1138, 1301,
    1342, 1495, 1986, 3453, 3456, 3684, 3823, 3845, 3871, 3903, 3943, 3963, 4005, 4030, 4033, 4036, 4133, 4170, 4345, 4349];

const PWD_FOR_STIM = ['6Z&N!]B7nB\CGTq_', '+8^em#K2p@qBU_PK', 'K=FZxqvt_7NV%M2K', 'Q#M+wb6Se?Xz8eTS', 'gqLYMuc$7#w4#6m_', '64Wf@Scp3-62&N+M',
                      'uTG+?b6%6*gJ7BUC', 'cQ7TS=GmL7hbatVw', 'qZA_yCBn9G6--rCW', 'XDtux28?*EfMZRXp', 'Rmkr49=hCCbKNKNv', 'c2ZnG5m@U62D_5-g',
                      '4Kr1@yU3O$Hv!Fk6', 'adX!LQxNDiF71PsR', 'U_Tdxtm1#DylP+uC', 'Egapd?cchpWw6W85', 'Cz=le_&0D8Xz7cc6', '7^UIOCRJgPZaPp@n',
                      'KJ3aGzDi%Mq9^7s!', '#Rab1q3#=cJWAz67', '$a7N+Vtt8y-t#=ye', 'hW$di|OaA6g!aGW-', '_^NT8#Cs*2tVXk90', '@Sp$Dz1suwMxK335'
                     ];

const PREMIUM_COMPANIES = [4340, 4070, 4489];


// The user ID of the official Brazilian government user account
// Used to determine whether to display the Project Feed
define('BRAZIL_USER_ID', 2812);
// The forum ID of the official Brazilian government 
// community page. Used for the /brazil route
define('BRAZIL_FORUM_ID', 20);

// Emergency Projects screen for Dan Slate (Trump administration)
define('EMERGENCY_PROJECTS_FORUM_ID', 19);

// The number of seconds a reminder lasts
define('REMINDER_EXPIRES', 60*60*2);
// Constant representing a successfully sent reminder.
define('REMINDER_SENT', 'reminders.sent');
// Constant representing a successfully reset password.
define('REMINDER_PASSWORD_RESET', 'reminders.reset');
// Constant representing the user not found response.
define('REMINDER_INVALID_USER', 'reminders.invalid_user');
// Constant representing an invalid password.
define('REMINDER_INVALID_PASSWORD', 'reminders.invalid_password');
// Constant representing an invalid token.
define('REMINDER_INVALID_TOKEN', 'reminders.invalid_token');

// Updates model specific constants
define('RATING_OVERALL',  0);
define('RATING_HELPFUL',  1);
define('RATING_RESPONSIVE', 2);
define('RATING_KNOWLEDGEABLE', 3);

/*
|------------------------------------------------------------------------
| CE Image: custom filters
|------------------------------------------------------------------------
*/

define('IMG_FILTER_SHARPEN_CUSTOM', "unsharp_mask");
define('IMG_FILTER_SOBEL_EDGIFY_CUSTOM', "prep_edgify");
define('IMG_FILTER_OPACITY_CUSTOM', "opacity");
define('IMG_FILTER_SEPIA_CUSTOM', "sepia");
define('IMG_FILTER_REPLACE_COLORS_CUSTOM', "replace_colors");


/*
|------------------------------------------------------------------------
| Third Party API
|------------------------------------------------------------------------
*/
define('MAPQUEST_KEY','Fmjtd%7Cluub2h0bn1%2C82%3Do5-9utsdw');


/*
|------------------------------------------------------------------------
| Algorithm
|------------------------------------------------------------------------
*/

/* Table Names */
defined("QUEUE_TABLE") || define("QUEUE_TABLE",'exp_queue');
defined("MEMBER_PROJECT_TABLE") || define("MEMBER_PROJECT_TABLE",'exp_member_project_scores');
defined("MEMBER_MEMBER_TABLE") || define("MEMBER_MEMBER_TABLE",'exp_member_member_scores'); //reserved
defined("PROJECT_PROJECT_TABLE") || define("PROJECT_PROJECT_TABLE",'exp_project_project_scores'); //reserved

/* Types */
defined("MEMBER_PROJECT_TYPE") || define("MEMBER_PROJECT_TYPE", 1);
defined("PROJECT_PROJECT_TYPE") || define("PROJECT_PROJECT_TYPE", 2);
defined("MEMBER_MEMBER_TYPE") || define("MEMBER_MEMBER_TYPE", 3);

defined("PROJECT_TYPE") || define("PROJECT_TYPE", 4);
defined("MEMBER_TYPE") || define("MEMBER_TYPE", 5);

/* QUEUE ID */
defined("MEMBER_PROJECT_MATCH_SCORE_QUEUE") || define("MEMBER_PROJECT_MATCH_SCORE_QUEUE",'10');
defined("MEMBER_MEMBER_MATCH_SCORE_QUEUE") || define("MEMBER_MEMBER_MATCH_SCORE_QUEUE",'11');
defined("PROJECT_PROJECT_MATCH_SCORE_QUEUE") || define("PROJECT_PROJECT_MATCH_SCORE_QUEUE",'12');

defined("QUEUE_TYPE_PROJECT") || define("QUEUE_TYPE_PROJECT",'13');
defined("QUEUE_TYPE_MEMBER") || define("QUEUE_TYPE_MEMBER",'14');

/* QUEUE PROCESSING BATCH SIZE */
//The number of items to process at one time from the queue. This means that by default the cron job will process 3000 queue items in one run
//OLD//defined("PROJ_EXP_QUEUE_BATCH_SIZE") || define("PROJ_EXP_QUEUE_BATCH_SIZE", 3000);
defined("MEMBER_PROJECT_QUEUE_BATCH_SIZE") || define("MEMBER_PROJECT_QUEUE_BATCH_SIZE", 5000);
defined("MEMBER_MEMBER_QUEUE_BATCH_SIZE") || define("MEMBER_MEMBER_QUEUE_BATCH_SIZE", 5000);
defined("PROJECT_PROJECT_QUEUE_BATCH_SIZE") || define("PROJECT_PROJECT_QUEUE_BATCH_SIZE", 5000);

//defines the number of project or members to process at one time. Used during the initial score tables seeding.
defined("SEED_BATCH_SIZE") || define("SEED_BATCH_SIZE", 50);

//postgresql the name of the function that updates an updated_at timestamp
defined("PSQL_FUNC_TIMESTAMP_UPDATE_NAME") || define("PSQL_FUNC_TIMESTAMP_UPDATE_NAME", "update_changed_timestamp");

/* End of file constants.php */
/* Location: ./application/config/constants.php */
