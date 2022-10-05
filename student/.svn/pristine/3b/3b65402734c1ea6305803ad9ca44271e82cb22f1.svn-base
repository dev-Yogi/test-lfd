<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function video_is_live() {
    $now = date('Y-m-d H:i:s');
    $CI = &get_instance();
    $CI->db->select('COUNT(*) as count');
    $CI->db->where("start_time < '$now'");
    $CI->db->where("stop_time > '$now'");
    $CI->db->from('videos');
    $is_live = $CI->db->get()->first_row()->count ? 1 : 0;

    return $is_live ? '<span class="tag tag-red">LIVE</span>' : null;
}

function get_lesson_position($total, $current) {
	if (!$total) {
		return 0;
	}
	return 100 / count($total) * $current;
}

function get_inbox_unread() {
    $CI = &get_instance();
    $messages = $CI->message_model->get_unread($CI->member->id);
    return $messages;
}