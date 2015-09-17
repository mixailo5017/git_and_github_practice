<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Input Class
 *
 * Pre-processes global input data for security
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Input
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/input.html
 */
class VIP_Input extends CI_Input {

	function __construct()
	{


	}

	// --------------------------------------------------------------------

	/**
	* Fetch an item from either the GET array or the POST
	*
	* @access	public
	* @param	string	The index key
	* @param	bool	XSS cleaning
	* @return	string
	*/
	function get_post($index = '', $default = FALSE, $xss_clean = FALSE)
	{
		
		if ( ! isset($_POST[$index]) )
		{
			$return = $this->get($index, $xss_clean);
		}
		else
		{
			$return = $this->post($index, $xss_clean);
		}

		if( $return === false ) $return = $default;

		return $return;
	}

	// --------------------------------------------------------------------


}

/* End of file Input.php */
/* Location: ./system/core/Input.php */