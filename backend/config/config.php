<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// use master config file
require_once('../.config/config.master.php');


// local overrides
$config['index_page'] = 'admin.php';


// log files
$config['log_path'] = BASE . 'storage/logs/admin/';

//$config['log_threshold'] = 4;