<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class redirect extends CI_Controller
{
    protected $uriLookup = [
    	'myvip' => 'mygvip',
    	'myvip/myfollowers' => 'mygvip/myfollowers',
    	'myvip/myprojects' => 'mygvip/myprojects',
    	'myvip/myexperts' => 'mygvip/myexperts',
    	'myvip/mydiscussions' => 'mygvip/mydiscussions'
    ];

    public function index()
    {
	    $requestedUri = $this->uri->uri_string();

	    // You have to load url helper before you can use redirect
	    // function in codeigniter
	    $this->load->helper('url');
    
        if (array_key_exists($requestedUri, $this->uriLookup)) {
	        redirect('/' . $this->uriLookup[$requestedUri]);	
        } else {
        	show_404($requestedUri);
        }
        
    }
}
