<?php
defined('BASEPATH') or exit('No direct script access allowed');

function filter_category($category)
{
    $labels = array(
        'is_aim' => "AIM Offering",
        'is_ada' => "ADA Compliant",
        'fee_has_scholarship' => "Has Scholarship",
        'audience_age_group' => "Youth Age Group",
        'audience_is_supervision_required' => 'Supervision Required',
        'audience_educator_target' => 'Educator Target Group'

    );
    if (isset($labels[$category])) {
        return $labels[$category];
    }
    $label = str_replace("_", " ", $category);
    $label = ucwords($label);
    return $label;
}

function filter_label($label)
{
    if ($label == "0") {
        return "No";
    }
    if ($label == "1") {
        return "Yes";
    }
    $label = str_replace("_", " ", $label);
    $label = ucwords($label, '/');
    return $label;
}
