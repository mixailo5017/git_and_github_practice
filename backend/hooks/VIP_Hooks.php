<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

class VIP_Hooks {

	public function post_controller_constructor()
	{
        $CI =& get_instance();
		$CI->ce_image->set_default_settings(array(
			'cache_dir'		=>'/cache/made/',
			'remote_dir'	=>'/cache/remote/',));
	}
}