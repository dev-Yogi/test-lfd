<?php
defined('BASEPATH') or exit('No direct script access allowed');

function alerts()
{
    $ci = &get_instance();
    $close = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>';
    if ($ci->session->flashdata('info')) {
        echo '<div class="alert alert-info alert-dismissible fade show" role="alert">' . $ci->session->flashdata('info') . $close . '</div>';
    }
    if ($ci->session->flashdata('success')) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . $ci->session->flashdata('success') . $close . '</div>';
    }
    if ($ci->session->flashdata('warning')) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">' . $ci->session->flashdata('warning') . $close . '</div>';
    }
    if ($ci->session->flashdata('error')) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $ci->session->flashdata('error') . $close . '</div>';
    }
}

function set_title($title)
{
    $ci = &get_instance();
    $ci->title = $title;
}

function get_title()
{
    $ci = &get_instance();
    return $ci->title ?? null ? strip_tags($ci->title) : null;
}

if (!function_exists('dd')) {
    function dd($item)
    {
        echo '<pre>';
        print_r($item);
        echo '</pre>';
    }

}

function paginate($count, $limit = RESULTS_PER_PAGE)
{
    $ci = &get_instance();
    $page = $ci->input->get('page') ?? 1;
    $pagination = new stdClass();
    $pagination->pages = floor(($count  - 1) / $limit) + 1;
    $pagination->current = $page;
    $pagination->current_url = current_url();
    $pagination->total = $count;

    return $pagination;
}

function forum_username($name) {
    $pattern = '/([0-9]{4})$/';
    $replacement = '<small class="text-muted">$1</small>';
    return preg_replace($pattern, $replacement, $name);
}

function display_name($member) {
    if (empty($member->first_name) || empty($member->last_name)) {
        return 'No Name';
    }
    return $member->last_name . ", " . $member->first_name;
}