<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag {
    const STAFF = 1;
    const MANAGER = 2;
    const ADMIN = 3;
    const STUDENT = 4;
    const DONOR = 5;
    const INSTRUCTOR = 6;
    const UPWARD_BOUND = 7;
}

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