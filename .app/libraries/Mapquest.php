<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MAPQUEST LIB
 *
 * Work with remote servers via cURL much easier than using the native PHP bindings.
 *
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @author        	Jerry Price / Vim Interactive
 * @link         	http://viminteractive.com
 */

use GViP\Curl;

class Mapquest extends Curl {

	public $error_code;             // Error code returned as an int
	public $error_string;           // Error message returned as a string
	public $info;                   // Returned after request (elapsed time, etc)
	public $status;

	public $json_raw		= false;
	public $json_obj		= false;
	public $json_array		= false;

	private $endpoint		= 'http://open.mapquestapi.com/';
	private $version		= 'v1';
	private $format			= 'json';
	private $map_options = array(
		'maxResults'		=> '1',
		'thumbMaps'			=> 'false',
		'ignoreLatLngInput'	=> 'true',
		);
	public $time = 0;

	// ----------------------------------------------------------------
	//
	// ----------------------------------------------------------------

	public function __construct()
	{
		$this->API_KEY = defined('MAPQUEST_KEY') ? MAPQUEST_KEY : '';
	}

	private function _simple_get( $url )
	{
		$time_start = microtime();

		$return = parent::simple_get( $url );

		$time_end = microtime();

		$this->time = $time_end - $time_start;

		return $return;
	}

	public function geocode($addy)
	{
		$this->_reset();

		$this->type 	= 'address';

		$map_options = array_merge($this->map_options,array('location'=>$addy));

		$this->_create_call( $map_options );

		$this->simple_get( $this->url );

		$this->_parse_response();

		return $this;

	}

	public function reverse_geocode($addy)
	{
		$this->_reset();

		$this->type 	= 'reverse';

		$map_options = array_merge($this->map_options,array('location'=>$addy));

		$this->_create_call( $map_options );

		$this->simple_get( $this->url );

		$this->_parse_response();

		return $this;

	}

	public function batch_geocode(array $addys)
	{
		$this->_reset();

		$this->type 	= 'batch';

		$locations = implode('', array_map( function($v){return "&location={$v}";}, $addys ));

		$this->_create_call( $this->map_options, $locations );

		$this->simple_get( $this->url );

		$this->_parse_response();

		return $this;


	}

	private function _create_call( array $params = array(), $extra = false)
	{
		$this->url = $this->endpoint . 'geocoding/' . $this->version . '/'.$this->type.'/';

		$this->url .= '?key='.$this->API_KEY;

		foreach( $params as $key => $value )
		{
			//$value = urlencode($value);
			$this->url .= "&{$key}={$value}";
		}

		if( $extra )
		{
			$this->url .= $extra;
		}
	}

	public function _reset()
	{
		$this->set_defaults();

		// Set Auth
		// $this->http_header('Content-Type: application/xml');
		// $this->http_header('Accept: application/xml');
		// $this->http_header('User-Agent',$this->user_agent);
		// $this->http_login($this->apikey,'X');
	}
	// --------------------------------------------------------------------

	/**
	 *  _create_post
	 *
	 */
	private function _create_post($data=array(),$xml=false)
	{

		$post = '<request>';
		if( $xml )
		{
			$post .= $xml;
		}
		else
		{
			$post .= $this->make_tag($data);
		}

		$post .=	'</request>';

		$this->post = $post;

	}
	// --------------------------------------------------------------------


	/**
	 *  make_tag
	 *
	 */
	private function _make_tag($data)
	{
		$tag = '';

		foreach( $data as $key => $value )
		{

			$tag .= "<{$key}>";

			if( is_array($value) )
			{
				$tag .= $this->make_tag($value);
			}
			else
			{
				$tag .= $value;
			}

			$tag .= "</{$key}>";
		}

		return $tag;

	}
	// --------------------------------------------------------------------


	/**
	 *  parse_response
	 *
	 */
	private function _parse_response()
	{
		if( empty($this->last_response))
		{
			$this->status = 'No Response';
			return;
		}

		$resp = $this->last_response;

		$json = json_decode($resp, $this->json_array);

		if( ! $json )
		{
			$this->status = 'Bad JSON';
			return;
		}

		$this->status = 'Success';

		$this->json_raw = $resp;
		$this->json_obj = $json;

		return $json;

	}
	// --------------------------------------------------------------------


	/**
	 * objectToArray
	 */
	private function _objectToArray($d)
	{
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}

		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(__METHOD__, $d);
		}
		else {
			// Return array
			return $d;
		}
	}
	// --------------------------------------------------------------------


	/**
	 * _flipDiagonally
	 */
	private function _flipDiagonally($arr)
	{
		$out = array();
		foreach ($arr as $key => $subarr)
		{
			foreach ($subarr as $subkey => $subvalue)
			{
				$out[$subkey][$key] = $subvalue;
			}
		}
		return $out;
	}

	/**
	 * _attr_to_array
	 */
	private function _attr_to_array($node)
	{
		$atts_object = $node->attributes(); //- get all attributes, this is not a real array
		$atts_array = (array) $atts_object; //- typecast to an array

		//- grab the value of '@attributes' key, which contains the array your after
		$atts_array = $atts_array['@attributes'];

		return $atts_array;
	}
	// ------------------------------------------------------------------------

}
/* End of file Curl.php */
/* Location: ./application/libraries/Curl.php */