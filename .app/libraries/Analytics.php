<?php

namespace GViP;

/**
 * Wrapper around Segment. Initializes the Segment class with the key it requires
 */
class Analytics extends \Segment
{

	public function __construct()
	{
		$CI =& get_instance();
		parent::init($CI->config->item('segment_write_key'));
	}
}