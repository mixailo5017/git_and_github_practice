<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

class VIP_Hooks {

    public function post_controller_constructor()
	{
        $CI =& get_instance();

        if (App::is_cli()) {
            // TBD
        } else {
            if (App::is_down_for_maintenence() &&
                ! App::is_ip_allowed_when_down()) show_503();
        }

	}
}