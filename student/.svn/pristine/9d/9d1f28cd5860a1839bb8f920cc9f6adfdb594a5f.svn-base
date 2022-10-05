<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generate extends Instructor_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('student_model');
        $this->load->model('course_model');
        $this->load->model('forum_model');
    }

	public function index()
	{
        $dates = array();
        for($i = 0; $i < 30; $i++) {
            $dates[] = strtotime('-'. $i .' days');
        }

        // $this->generate_course_completions($dates);
        // $this->generate_lesson_completions($dates);
	}

    public function generate_logins($dates)
    {
        foreach ($dates as $date) {
            $date = date('Y-m-d H:i:s', $date);
            for ($i = 0; $i < rand(10,25); $i++) { 
                $data = array(
                    'site' => 'student',
                    'logged_in_at' => $date
                );
                $this->db->insert(MEMBER_TABLE . '.logins', $data);
            }
        }
    }

    public function generate_course_completions($dates)
    {
        foreach ($dates as $date) {
            $date = date('Y-m-d H:i:s', $date);
            for ($i = 0; $i < rand(5,13); $i++) { 
                $data = array(
                    'status' => 'complete',
                    'created' => $date,
                    'completed_at' => $date
                );
                $this->db->insert('courses_students', $data);
            }
        }
    }

    public function generate_lesson_completions($dates)
    {
        foreach ($dates as $date) {
            $date = date('Y-m-d H:i:s', $date);
            for ($i = 0; $i < rand(5,13); $i++) { 
                $data = array(
                    'status' => 'complete',
                    'created' => $date,
                    'completed_at' => $date
                );
                $this->db->insert('lessons_students', $data);
            }
        }
    }
}
