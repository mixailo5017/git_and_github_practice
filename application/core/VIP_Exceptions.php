<?php

class VIP_Exceptions extends CI_Exceptions
{
    function show_404($page = '', $log_error = TRUE)
    {
        set_status_header(404);

        // By default we log this, but allow a dev to skip it
        if ($log_error) {
            if (empty($page)) {
                $page  = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
                $page .= isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
            }
            log_message('error', '404 Page Not Found --> ' . $page);
        }

        $CI =& get_instance();

        $view = array(
            'view' => 'pages/error_404',
            'title' => build_title('404 Page not Found'),
            'bodyclass' => '',
            'header' => array(),
            'content' => array(),
            'footer' => array(),
            'styles' => array('error_404.css')
        );

        $response  = $CI->load->view('layouts/default', $view, true);
        echo $response;

        exit;
    }

    function show_503()
    {
        set_status_header(503);

        $CI =& get_instance();

        $view = array(
            'view' => 'pages/error_503',
            'title' => build_title('Site Temporarily Down'),
            'bodyclass' => '',
            'header' => array(),
            'content' => array(),
            'footer' => array(),
            'styles' => array('error_404.css')
        );

        $response  = $CI->load->view('layouts/default', $view, true);
        echo $response;

        exit;
    }
}