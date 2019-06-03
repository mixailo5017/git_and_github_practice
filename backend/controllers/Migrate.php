<?php if (! defined('BASEPATH')) {
    exit("No direct script access allowed");
}
/**
 * Created by PhpStorm.
 * User: goce
 * Date: 4/2/14
 * Time: 10:29
 *
 * Description: Provides a CLI way of initiating a migration
 */

class Migrate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if(!is_cli()) exit("Execute via command line: php admin.php migrate");

        $this->load->library('migration');
    }

    public function index()
    {
        if (!$this->migration->latest()) {
            show_error($this->migration->error_string());
        }
    }

    public function version($version)
    {
        $migration = $this->migration->version($version);
        if (!$migration) {
            echo $this->migration->error_string();
        } else {
            echo 'Migration(s) done'.PHP_EOL;
        }
    }
}
