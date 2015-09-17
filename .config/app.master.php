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
    'api_key' => env('LINKEDIN_API_KEY'),
    'secret_key' => env('LINKEDIN_SECRET_KEY')
);

//$config['queue_host'] = env('QUEUE_HOST');
