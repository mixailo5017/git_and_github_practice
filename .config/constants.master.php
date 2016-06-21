<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|-------------------------------------------------------------------------
| Upload Image Paths
|-------------------------------------------------------------------------
|
| These paths are used while displaying image of Projects/User Profile/Projects Listing
|
*/

define('IMAGE_PATH', '/images');
//define('IMAGE_PATH',IMAGE_PATH.'/made/images');
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
define("DATEFORMAT","%m/%d/%y");
define("DATEFORMATDB","%y-%m-%d");

/*
|------------------------------------------------------------------------
| Application specific constants
|------------------------------------------------------------------------
*/
define('SITE_NAME', 'GViP');
define('DATEFORMATVIEW', 'M d, Y');
define('ADMIN_EMAIL', 'no-reply@gvip.io');
define('ADMIN_EMAIL_NAME', SITE_NAME);
define('CGLA_NAME', 'CG/LA Infrastructure');
define('CGLA_SITE', 'http://www.cg-la.com/');

// Concierge email
//define("CONCIERGE_EMAIL","norman@cg-la.com,scott@cg-la.com,erik@cg-la.com,cristina@cg-la.com");
define('CONCIERGE_EMAIL', 'gvip911@cg-la.com');
define('CONCIERGE_EMAIL_TITLE', 'New Concierge Question');

// Max number of experts shown in a sidebar of the forum detail view
define("FORUM_EXPERT_LIMIT",  15);
// Max number of projects shown in a sidebar of the forum detail view
define("FORUM_PROJECT_LIMIT", 8);

define('MAX_SUB_SECTOR', 6);  // Maximum number of subsectors of an expert
define('MAX_CASE_STUDIES', 7); // Maximum number of case studies per user (expert advert)
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
define('SITEMAP_TTL', 120);

// Allows to enable or disable public project profiles feature
define('PROJECT_PROFILES_ENABLED', TRUE);

// Used to determine which users' projects will be shown to the public, 
// which project profiles will not show the project developer, etc.
// Arrays in constants requires PHP 5.6. Defining them using the define statement only available from PHP 7.0.
const INTERNAL_USERS = [24, 28, 37, 222, 492, 298, 426, 583, 586, 684, 741, 813, 986, 1121, 1307, 1554, 1589, 1641, 1742, 1610, 2104];

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