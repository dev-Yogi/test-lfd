<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function has_tag($tag_id) {
    $CI = &get_instance();
    if (empty($CI->member)) {
        return false;
    }
    foreach ($CI->member->tags as $tag) {
        if ($tag_id == $tag->id) {
            return true;
        }
    }
    return false;
}

function is_staff() {
    $CI = &get_instance();
    if (empty($CI->member)) {
        return false;
    }
    foreach ($CI->member->tags as $tag) {
        if ($tag->id == Tag::STAFF) {
            return true;
        }
    }
    return false;
}

function is_instructor() {
    $CI = &get_instance();
    if (empty($CI->member)) {
        return false;
    }
    foreach ($CI->member->tags as $tag) {
        if ($tag->id == Tag::INSTRUCTOR) {
            return true;
        }
    }
    return false;
}

function is_moderator() {
    $CI = &get_instance();
    if (empty($CI->member)) {
        return false;
    }
    foreach ($CI->member->tags as $tag) {
        if ($tag->id == Tag::STAFF) {
            return true;
        }
    }
    return false;
}