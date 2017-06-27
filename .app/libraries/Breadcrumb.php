<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Breadcrumb {
	private $breadcrumbs = array();
	private $_divider = '  �  ';
	private $_tag_open = '<li>';
	private $_tag_close = '</li>';

	public function __construct($params = array()){
		if (count($params) > 0){
			$this->initialize($params);
		}

		log_message('debug', "Breadcrumb Class Initialized");
	}

	/**
	 * Initialize Preferences
	 *
	 * @access public
	 * @param array initialization parameters
	 * @return void
	 */
	private function initialize($params = array()){
		if (count($params) > 0){
			foreach ($params as $key => $val)
			{
				if (isset($this->{'_' . $key}))
				{
					$this->{'_' . $key} = $val;
				}
			}
		}
	}


	/**
	 * Append crumb to stack
	 *
	 * @access public
	 * @param string $title
	 * @param string $href
	 * @return void
	 */
	function append_crumb($title, $href){
		// no title or href provided
		if (!$title OR !$href) return;
		// add to end
		$this->breadcrumbs[] = array('title' => $title, 'href' => $href);
	}


	/**
	 * Prepend crumb to stack
	 *
	 * @access public
	 * @param string $title
	 * @param string $href
	 * @return void
	 */
	function prepend_crumb($title, $href){
		// no title or href provided
		if (!$title OR !$href) return;

		// add to start
		array_unshift($this->breadcrumbs, array('title' => $title, 'href' => $href));
	}

	

	/**
	 * Generate breadcrumb
	 *
	 * @access public
	 * @return string
	 */
	function output(){
		// breadcrumb found
		if ($this->breadcrumbs) {

			$output = "";

			// Determine final breadcrumb key for subsequent testing
			$breadcrumbKeys = array_keys($this->breadcrumbs);
			$finalBreadcrumbKey = end($breadcrumbKeys);

			// add html to output
			foreach ($this->breadcrumbs as $key => $crumb) {
			
				// set output variable
				$output .= $this->_tag_open;

				// add divider
				//if ($key) $output .= $this->_divider;

				// if last element
				if ($finalBreadcrumbKey == $key) {
					$output .= '<a href="' . $crumb['href'] . '" style="background: none repeat scroll 0% 0% transparent;">' . $crumb['title'] . '</a>';

				// else add link and divider
				} else {
					$output .= '<a href="' . $crumb['href'] . '">' . $crumb['title'] . '</a>';
				}
				$output .= $this->_tag_close;
			}

			// return html
			return $output. PHP_EOL;
		}

		// return blank string
		return '';
	}

}