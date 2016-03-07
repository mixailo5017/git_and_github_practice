<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Redirect extends CI_Controller {

  public function myvip()
	{
		// You have to load url helper before you can use redirect 
    // function in codeigniter
    $this->load->helper('url');
    
    redirect('/mygvip');
    
	}
    
}

