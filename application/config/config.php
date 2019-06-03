<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// use master config file
require_once('../.config/config.master.php');


// local overrides
$config['index_page'] = '';

// log files
$config['log_path'] = BASE . 'storage/logs/app/';

//$config['log_threshold'] = 4;