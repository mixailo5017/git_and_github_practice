<?php

/*
|--------------------------------------------------------------------------
| GVIP specific settings
|--------------------------------------------------------------------------
*/
$config['ga_tracking_id'] = env('GOOGLE_ANALYTICS_ID');
$config['sa_tracking_id'] = env('SEGMENT_ID');
$config['intercom_secure_key'] = env('INTERCOM_KEY');
$config['linkedin'] = array(
    'api_key'    => env('LINKEDIN_API_KEY'),
    'secret_key' => env('LINKEDIN_SECRET_KEY')
);
$config['algolia'] = [
	'application_id' => env('ALGOLIA_APPLICATION_ID'),
	'admin_api_key'  => env('ALGOLIA_ADMIN_API_KEY'),
	'index_members'  => env('ALGOLIA_INDEX_MEMBERS')
];

//$config['queue_host'] = env('QUEUE_HOST');
