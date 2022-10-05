<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function current_url()
{
    $CI =& get_instance();

    $url = $CI->config->site_url($CI->uri->uri_string());
    return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url . "?";
}

function course_image_url($image)
{
    $CI = &get_instance();

    if (empty($image)) {
    	return "";
    }

    return base_url() . FOLDER_COURSE_IMAGE . $image;
}