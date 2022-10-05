<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ose_model extends CI_Model
{

    public function get_questions()
    {
        $this->db->order_by('id');
        $this->db->where('removed', 0);
        $this->db->from('ose_questions');
        $questions = $this->db->get()->result();
        if ($questions) {
            foreach ($questions as $key => $question) {
                $questions[$key]->options = new stdClass;
                $options = $this->get_options($question->id);
                foreach ($options as $option) {
                    $questions[$key]->options->{$option->type} = $option;
                }
                
            }
        }
        return $questions;
    }

    public function get_options($question_id)
    {
        $this->db->where('question_id', $question_id);
        $this->db->where('removed', 0);
        $this->db->order_by('id');
        $this->db->from('ose_options');

        $options = $this->db->get()->result();
        return $options;
    }

    public function add_submission($data)
    {
        $this->db->insert('ose_submissions', $data);
        return $this->db->insert_id();
    }

    public function get_submissions($offering_id)
    {
        $this->db->order_by('question_id');
        $this->db->where('removed', 0);
        $this->db->from('ose_submissions');
        $this->db->where('offering_id', $offering_id);
        $submissions = $this->db->get()->result();
        return $submissions;
    }

    public function remove_submissions($offering_id)
    {
        $this->db->where('offering_id', $offering_id);
        $this->db->set('removed', 1);
        $this->db->from('ose_submissions');
        return $this->db->update();
    }

    public function get_grade($offering_id)
    {
        $this->db->select('count(*), answer');
        $this->db->where('removed', 0);
        $this->db->where('offering_id', $offering_id);
        $this->db->from('ose_submissions');
        $this->db->order_by('answer', 'asc');
        $this->db->group_by('answer');
        $answers = $this->db->get()->result();
        if (empty($answers)) {
            return null;
        }
        if ($answers) {
            if (count($answers) == 1 && $answers[0]->answer == 2) {
                return 2;
            } elseif ($answers[0]->answer == 0) {
                return 0;
            } else {
                return 1;
            }
        }
        return 0;
    }
}
