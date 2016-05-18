<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('intercom_secure_key'))
{
	/**
	 * Returns Segment Analytics Write Key based on the environment
	 * See .config/config.master.php
	 *
	 * @return string
	 */
	function intercom_secure_key() {
		$CI =& get_instance();
		return $CI->config->item('intercom_secure_key');
	}
}

if (! function_exists('sa_tracking_id'))
{
    /**
     * Returns Segment Analytics Write Key based on the environment
     * See .config/config.master.php
     *
     * @return string
     */
    function sa_tracking_id() {
        $CI =& get_instance();
        return $CI->config->item('sa_tracking_id');
    }
}

if (! function_exists('ga_tracking_id'))
{
    /**
     * Returns Google Analytics Tracking ID based on the environment
     * See .config/config.master.php
     *
     * @return string
     */
    function ga_tracking_id() {
        $CI =& get_instance();
        return $CI->config->item('ga_tracking_id');
    }
}

if (! function_exists('view_limit_values'))
{
    function view_limit_values()
    {
        return array(12, 24, 48);
    }
}

if (! function_exists('view_limit_options'))
{
    function view_limit_options()
    {
        $values = view_limit_values();
        return array_combine($values, $values);
    }
}

if (! function_exists('view_check_limit')) {
    function view_check_limit($value)
    {
        $allowed = view_limit_values();
        return in_array_default($allowed, $value, $allowed[0]);
    }
}

if (! function_exists('ul_custom'))
{
    /**
     * Customized Unordered List
     *
     * Generates an HTML unordered list from an single or multi-dimensional array.
     * Added Functionality for adding class,id etc to particular liist item
     *
     * @param array $list
     * @param string $attributes
     * @param string $listattributes
     * @param string $ulattributes
     * @return    string
     */
	function ul_custom($list, $attributes = '', $listattributes='', $ulattributes='')
	{
		return _list_custom('ul', $list, $attributes, 0, $listattributes, $ulattributes);
	}
}


if ( ! function_exists('_list_custom'))
{
    /**
     * Generates the list
     *
     * Generates an HTML ordered list from an single or multi-dimensional array.
     *
     * @param string $type
     * @param $list
     * @param string $attributes
     * @param int $depth
     * @param string $listattributes
     * @param string $ulattributes
     * @return    string
     */
    function _list_custom($type = 'ul', $list, $attributes = '', $depth = 0, $listattributes = '',$ulattributes='')
	{
		// If an array wasn't submitted there's nothing to do...
		if ( ! is_array($list))
		{
			return $list;
		}

		// Set the indentation based on the depth
		$out = str_repeat(" ", $depth);
		
		// Were any attributes submitted?  If so generate a string
		if (is_array($attributes))
		{
			$atts = '';
			foreach ($attributes as $key => $val)
			{
				$atts .= ' ' . $key . '="' . $val . '"';
			}
			$attributes = $atts;
		}
		elseif (is_string($attributes) AND strlen($attributes) > 0)
		{
			$attributes = ' '. $attributes;
		}
		

		// Write the opening list tag
		$out .= "<".$type.$attributes.">\n";
		
		// Were any list attributes submitted?  If so generate a string
		if (is_array($listattributes))
		{
			$listatts = array();
			foreach ($listattributes as $k => $v)
			{
				$listatts[] .= $v;
			}
			$listattributes = $listatts;
		}
		elseif (is_string($listattributes) AND strlen($listattributes) > 0)
		{
			$listattributes[] = ' '. $listattributes;
		}
		else
		{
			$listattributes = array();
		}

		// Cycle through the list elements.  If an array is
		// encountered we will recursively call _list()

		static $_last_list_item = '';
		foreach ($list as $key => $val)
		{
			$_last_list_item = $key;
			$additional_attrib = "";
			if(array_key_exists($key,$listattributes)) {
				$additional_attrib = $listattributes[$key];
			}
			
			$out .= str_repeat(" ", $depth + 2);
			$out .= "<li ".$additional_attrib.">";

			if ( ! is_array($val))
			{
				$out .= $val;
			}
			else
			{
				$out .= $_last_list_item."\n";
				$out .= _list_custom($type, $val, $ulattributes, $depth + 4);
				$out .= str_repeat(" ", $depth + 2);
			}

			$out .= "</li>\n";
		}

		// Set the indentation for the closing tag
		$out .= str_repeat(" ", $depth);

		// Write the closing list tag
		$out .= "</".$type.">\n";

		return $out;
	}
}

if ( ! function_exists('form_hidden_custom'))
{
    /**
     * Custom Hidden Input Field
     *
     * Generates hidden fields.  You can pass a simple key/value string or an associative
     * array with multiple values.
     * Added Customization : Hidden Fields Attributes
     *
     * @param string $name
     * @param string $value
     * @param bool $recursing
     * @param string $attributes
     * @return string
     */
	function form_hidden_custom($name, $value = '', $recursing = FALSE, $attributes='')
	{
		static $form;

		if ($recursing === FALSE)
		{
			$form = "\n";
		}

		if (is_array($name))
		{
			foreach ($name as $key => $val)
			{
				form_hidden_custom($key, $val, TRUE);
			}
			return $form;
		}

		if ( ! is_array($value))
		{
			$form .= '<input type="hidden" name="'.$name.'" value="'.form_prep($value, $name).'" '.$attributes.' />'."\n";
		}
		else
		{
			foreach ($value as $k => $v)
			{
				$k = (is_int($k)) ? '' : $k;
				form_hidden_custom($name.'['.$k.']', $v, TRUE);
			}
		}

		return $form;
	}
}

if ( ! function_exists('form_custom_dropdown'))
{
    /**
     * Customized Drop-down Menu
     *
     * @param string $name
     * @param array $options
     * @param array $selected
     * @param string $extra
     * @param array $opt
     * @param array $first
     * @param array $last
     * @return    string
     */
	function form_custom_dropdown($name = '', $options = array(), $selected = array(), $extra = '', $opt = array(),$first=array(),$last=array())
	{
		if ( ! is_array($selected))
		{
			$selected = array($selected);
		}

		// If no selected state was submitted we will attempt to set it automatically
		if (count($selected) === 0)
		{
			// If the form name appears in the $_POST array we have a winner!
			if (isset($_POST[$name]))
			{
				$selected = array($_POST[$name]);
			}
		}

		if ($extra != '') $extra = ' '.$extra;

		$multiple = (count($selected) > 1 && strpos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

		$form = '<select name="'.$name.'"'.$extra.$multiple.">\n";
		
		if(count($first)> 0)
		{
			$form .= '<option value="'.$first['value'].'" class="'.$first['class'].'" >'.(string) $first['text']."</option>\n";
		}

		foreach ($options as $key => $val)
		{
			$key = (string) $key;
			$okey = isset($opt[$key]) ? $opt[$key] : '';
			
			$sel = (in_array($key, $selected)) ? ' selected="selected"' : '';

			$form .= '<option value="'.$key.'"'.$sel.' '.$okey.'>'.(string) $val."</option>\n";
		}
		
		if(count($last)> 0)
		{
			$form .= '<option value="'.$last['value'].'" class="'.$last['class'].'" >'.(string) $last['text']."</option>\n";
		}

		$form .= '</select>';

		return $form;
	}
}

if ( ! function_exists('form_category_dropdown'))
{
    /**
     * Customized Drop-down Menu using option groups
     *
     * @param string $name
     * @param array $categories
     * @param array $options
     * @param string $extra
     * @param array $opt
     * @param array $opt2
     * @param array $last
     * @return    string
     */
    function form_category_dropdown($name = '', $categories = array(), $options = array(), $extra = '', $opt=array(), $opt2=array(), $last = array())
    {

        if ($extra != '') $extra = ' '.$extra;

        $multiple = (strpos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : ''; // For now, always outputs the multiple option. TODO: detect whether more than one option is selected

        $form = '<select name="'.$name.'"'.$extra.$multiple.">\n";

        foreach ($categories as $key => $val) {
            $key = (string) $key;
            $okey = isset($opt[$key]) ? $opt[$key] : '';

            $form .= '<optgroup label="'.$key.'"'.$okey.'>';

            foreach ($options as $key2 => $val2)
            {
                if (substr($opt2[$key2], 26) === substr($opt[$key], 19)) {
                    $key2 = (string) $key2;
                    $okey2 = isset($opt2[$key2]) ? $opt2[$key2] : '';

                    $form .= '<option value="'.$key.':'.$key2.'"'.set_select($name, $key.':'.$key2).' '.$okey2.'>'.(string) $val2."</option>\n";
                }             
            }

            $form .= '<option value="'.$key.':'.$last['value'].'" class="'.$last['class'].'" >'.(string) $last['text']."</option>\n";

            $form .= '</optgroup>';            
        }

        $form .= '</select>';

        return $form;
    }

}

if ( ! function_exists('script_tag')) {
    /**
     * Script Tag
     *
     * Similar to the CSS link_tag but can be used for short had js script tags
     *
     * @access	public
     * @param	string
     * @param	string
     * @param	string
     * @param	bool
     * @return	string
     */
    function script_tag($src = '', $language = 'javascript', $type = 'text/javascript', $index_page = FALSE)
    {


        $CI =& get_instance();


		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
		{
			$base_url = str_ireplace('http://', 'https://', $CI->config->config['base_url']);	
			$CI->config->set_item('base_url',$base_url);
		}


        $script = '<scr'.'ipt';
        if (is_array($src)) {
            foreach ($src as $k=>$v) {
                if ($k == 'src' AND strpos($v, '://') === FALSE) {
                    if ($index_page === TRUE) {
                        $script .= ' src="'.$CI->config->site_url($v).'"';
                    }
                    else {
                        $script .= ' src="'.$CI->config->slash_item('base_url').$v.'"';
                    }
                }
                else {
                    $script .= "$k=\"$v\"";
                }
            }

            $script .= "></scr"."ipt>\n";
        }
        else {
            if ( strpos($src, '://') !== FALSE) {
                $script .= ' src="'.$src.'" ';
            }
            elseif ($index_page === TRUE) {
                $script .= ' src="'.$CI->config->site_url($src).'" ';
            }
            else {
                $script .= ' src="'.$CI->config->slash_item('base_url').$src.'" ';
            }

           // $script .= 'language="'.$language.'"';
            $script .= 'type="'.$type.'"';
            $script .= ' ></scr'.'ipt>'."\n";
        }



        return $script;
    }
}

if (! function_exists('form_paging')) {
    /**
     * Renders pagination controls (top or bootom)
     *
     * @param boolean $top
     * @param integer $from From page
     * @param integer $to To page
     * @param integer $total Total number of records
     * @param string $title What these records are (like Forums, Project, Experts etc.)
     * @param $links CI paging
     * @return string
     */
    function form_paging($top, $from, $to, $total, $title, $links) {
        $html  = '<div class="result_info_' . (($top) ? 'top' : 'bottom') . '">';
        $html .= '<p>' . lang('Showing') . ' ' . $from . ' - ' . $to . ' of ' . $total .  ' ' . $title . '</p>';
        $html .= '<div class="buttons clearfix">' . $links . '</div>';
        $html .= '</div>';

        return $html;
    }
}

if (! function_exists('form_list_empty')) {
    /**
     * Renders 'Not found' block for a list view (Projects, Experts, Forums, ...)
     *
     * @param string $message
     * @return string
     */
    function form_list_empty($message) {
        $html  = '<div>';
        $html .= '<div class="clear">&nbsp;</div>';
        $html .= heading($message, 3, 'align="center"');
        $html .= '<div class="clear">&nbsp;</div>';
        $html .= '</div>';

        return $html;
    }
}

if (! function_exists('form_list_block')) {
    /**
     * Renders a list block (e.g. for projects, experts, forums ...)
     *
     * @param string $title Entity's title
     * @param string $url A link url to the entity being displayed
     * @param array $image (array('url' => '/path/to/image.jpg', 'alt' => 'alt text', 'pad' => 1))
     * @param array $properties Entity's properties
     * @param boolean $last Is it a last block in a row
     * @return string
     */
    function form_list_block($title, $url, $image, $properties = array(), $last = false) {

        $html  = '<div class="project_listing ' . (($last) ?  'project_listing_last' : '') . ' left">';
        $html .= '<a href="' . $url . '">';
        if (! is_null($image)) {
            $html .= '<img src="' . $image['url'] . '" alt="' . (isset($image['alt']) ? $image['alt'] : ''). '" style="margin: 0px;">';
        }
        $html .= '</a>';
        $html .= '<div style="font-size:13px;padding:8px 12px 0px 12px;">' . $title . '</div>';

        $html .= '<div style="padding: 8px 12px;">';
        foreach ($properties as $property) {
            $html .= '<strong>' . $property[0] . ':' . '</strong>' . str_repeat('&nbsp;', $property[2]) . ($property[1] != '') ? $property[1] : '&mdash;' . '<br/>';
        }
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}