<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ose extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('ose_model');
        $this->load->model('offering_model');
    }

    public function index($table = NULL)
    {
        $this->intro();
    }

    public function intro()
    {
        set_title('Introduction - Best Practices Assessment');
        $this->load->view('offering/ose/intro');
    }

    public function definitions()
    {
        set_title('Definitions - Best Practices Assessment');
        $this->load->view('offering/ose/definitions');
    }

    public function form()
    {
        $offering_id = $this->session->userdata('submitted_offering_id');
        if (!$offering_id) {
            redirect('offering/submit');
        }

        $offering = $this->offering_model->get_offering($offering_id);
        $questions = $this->ose_model->get_questions();

        if ($this->input->method() == 'post') {
            foreach ($questions as $question) {
                $this->form_validation->set_rules($question->id, '', 'trim|numeric|required');
            }

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Please select an answer for all questions');
            } else {
                if ($this->ose_model->get_submissions($offering_id)) {
                    $this->ose_model->remove_submissions($offering_id);
                }
                $post = $this->input->post();
                foreach ($post as $question_id => $answer) {
                    $this->ose_model->add_submission([
                        'offering_id' => $offering_id,
                        'question_id' => $question_id,
                        'answer' => $answer,
                        'created_by' => $this->member->id ?? null,
                    ]);
                }
                $results = array(
                    'proficient' => 0,
                    'developing' => 0,
                    'neither' => 0
                );
                foreach ($post as $key => $value) {
                    if ($value == 0) $results['neither']++;
                    if ($value == 1) $results['developing']++;
                    if ($value == 2) $results['proficient']++;
                }
                $this->session->set_userdata('submitted_offering_ose_results', $results);
                redirect('ose/thankyou');
            }
        }

        $categorized_questions = array();
        $categorized_questions['infrastructure'] = array_filter($questions, function ($question) {
            return $question->category == 'infrastructure';
        });
        $categorized_questions['goals and evaluation'] = array_filter($questions, function ($question) {
            return $question->category == 'goals and evaluation';
        });
        $categorized_questions['program components'] = array_filter($questions, function ($question) {
            return $question->category == 'program components';
        });
        $categorized_questions['stem practices'] = array_filter($questions, function ($question) {
            return $question->category == 'stem practices';
        });

        $questions = $categorized_questions;

        $submissions = $this->ose_model->get_submissions($offering_id);
        // if ($submissions) {
        //     $this->session->set_flashdata('info', 'An assessment has already been submitted for this offering. Re-submitting a new assessment will overwrite the previously completed assessment.');
        // }

        set_title('Best Practices Assessment');
        $this->load->view('offering/ose/form', compact('questions', 'offering', 'submissions'));
    }

    public function thankyou()
    {
        $offering_id = $this->session->userdata('submitted_offering_id');
        if (!$offering_id) {
            redirect('offering/submit');
        }

        $offering = $this->offering_model->get_offering($offering_id);
        $results = $this->session->userdata('submitted_offering_ose_results');
        set_title('Thank you - Best Practices Assessment');
        $this->load->view('offering/ose/thankyou', compact('offering', 'results'));
    }
}
