<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function timespan($seconds = 1, $time = '', $units = 7)
{
    $CI = &get_instance();
    $CI->lang->load('date');

    is_numeric($seconds) or $seconds = 1;
    is_numeric($time) or $time = time();
    is_numeric($units) or $units = 7;

    if ($time > $seconds) {
        $seconds = $time - $seconds;
        $suffix = ' from now';
    } else {
        $seconds = $seconds - $time;
        $suffix = ' ago';
    }

    $str = array();
    $years = floor($seconds / 31557600);

    if ($years > 0) {
        $str[] = $years . ' ' . ($years > 1 ? 'years' : 'year');
    }

    $seconds -= $years * 31557600;
    $months = floor($seconds / 2629743);

    if (count($str) < $units && ($years > 0 or $months > 0)) {
        if ($months > 0) {
            $str[] = $months . ' ' . ($months > 1 ? 'months' : 'month');
        }

        $seconds -= $months * 2629743;
    }

    // $weeks = floor($seconds / 604800);

    // if (count($str) < $units && ($years > 0 or $months > 0 or $weeks > 0)) {
    //     if ($weeks > 0) {
    //         $str[] = $weeks . ' ' . ($weeks > 1 ? 'weeks' : 'week');
    //     }

    //     $seconds -= $weeks * 604800;
    // }

    $days = floor($seconds / 86400);

    // if (count($str) < $units && ($months > 0 or $weeks > 0 or $days > 0)) {
    if (count($str) < $units && ($months > 0 or $days > 0)) {
        if ($days > 0) {
            $str[] = $days . ' ' . ($days > 1 ? 'days' : 'day');
        }

        $seconds -= $days * 86400;
    }

    $hours = floor($seconds / 3600);

    if (count($str) < $units && ($days > 0 or $hours > 0)) {
        if ($hours > 0) {
            $str[] = $hours . ' ' . ($hours > 1 ? 'hours' : 'hour');
        }

        $seconds -= $hours * 3600;
    }

    $minutes = floor($seconds / 60);

    if (count($str) < $units && ($days > 0 or $hours > 0 or $minutes > 0)) {
        if ($minutes > 0) {
            $str[] = $minutes . ' ' . ($minutes > 1 ? 'minutes' : 'minute');
        }

        $seconds -= $minutes * 60;
    }

    if (count($str) === 0) {
        $str[] = $seconds . ' ' . ($seconds > 1 ? 'seconds' : 'second');
    }

    return implode(' ', $str) . $suffix;
}

function time_length_label($minutes) {
    if (empty($minutes)) {
        return '';
    }
    if ($minutes < 60) {
        return $minutes . " mins";
    }
    if (floor($minutes / 60) > 1) {
        return floor($minutes / 60) . " hrs";
    }
    return "1 hr";
}

