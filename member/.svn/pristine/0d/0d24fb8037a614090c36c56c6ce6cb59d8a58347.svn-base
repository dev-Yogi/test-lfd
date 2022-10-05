<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function current_url()
{
    $CI =& get_instance();

    $url = $CI->config->site_url($CI->uri->uri_string());
    return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url . "?";
}

function logo_url($logo)
{
    $CI = &get_instance();

    return base_url() . LOGO_FOLDER . $logo;
}

function filter_url($filter_key, $filter_value, $query_array)
{
	if ($filter_value !== null) {
		$query_array[$filter_key] = $filter_value;
	} else {
		unset($query_array[$filter_key]);
		// If we're un-filtering organization, un-filter department too
		if ($filter_key == 'organization') {
			unset($query_array['department']);
		}
	}
	return http_build_query($query_array);
}