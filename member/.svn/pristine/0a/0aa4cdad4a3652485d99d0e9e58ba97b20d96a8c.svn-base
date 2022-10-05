<?php
defined('BASEPATH') or exit('No direct script access allowed');

function has_ose_answer($question_id, $answer, $submissions) {
    return $submissions[$question_id]->answer == $answer;
}

function get_offering_table_header($label) {
	if ($label == "Target Audience") {
		return "Audience";
	}
	return $label;
}