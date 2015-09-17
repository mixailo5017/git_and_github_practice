<?php if ( ! defined('BASEPATH')) exit("No direct script access allowed");
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

        if(!$this->input->is_cli_request()) exit("Execute via command line: php admin.php migrate");

        $this->load->library('migration');
    }

    public function index()
    {
        if(!$this->migration->latest())
        {
            show_error($this->migration->error_string());
        }
    }
} 