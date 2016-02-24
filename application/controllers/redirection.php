<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class redirection extends CI_Controller {

  public function index()
	{
		// You have to load url helper before you can use redirect 
    // function in codeigniter
    $this->load->helper('url');
    
    redirect('/mygvip');
    
	}
  
  public function redirected_page()
  {
		echo 'You have been redirected to another page! Welcome to the redirected page.';
	}
  
    
}

