<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->member = null;
        $this->student = null;
        $this->program = null;
        if ($this->session->userdata('id')) {
            $this->member = $this->session->userdata('member');
        }
        
        if (!has_tag(Tag::STUDENT) && !has_tag(Tag::INSTRUCTOR) && !has_tag(Tag::STAFF)) {
            redirect('http://' . $_SERVER['HTTP_HOST'] . '/member');
        }

        if ($this->session->userdata('student')) {
            $this->student = $this->session->userdata('student');
        } else {
            $this->set_student();
        }

        if ($this->session->userdata('program')) {
            $this->program = $this->session->userdata('program');
        } else {
            $this->set_program();
        }
    }

    public function check_tag($tag_id)
    {
        if (!has_tag($tag_id)) {
            show_error('You do not have permission to view this page.', 410, 'Not Authorized');
        }
    }

    public function set_student()
    {
        $this->load->model('student_model');
        $this->student = $this->student_model->get($this->member->id);
        $this->session->set_userdata('student', $this->student);
    }

    public function set_program($program_id = null)
    {
        $this->load->model('program_model');
        $this->load->model('student_model');

        // Are you a instructor or staff member?
        if (has_tag(Tag::INSTRUCTOR) || has_tag(Tag::STAFF)) {
            if (!$program_id) {
                $program_id = 1;
            }
            $program = $this->program_model->get($program_id);
            $this->session->set_userdata('program', $program);
            $this->program = $program;
            return;
        }

        // Are you a student?
        if (empty($this->student)) {
            log_message('error', "DEV: Cannot login, not a student [member_id: {$this->member->id}]");
            show_error('Your account is not a student account.');
            return;
        }

        if (empty($this->student->programs)) {
            $programs_members_data = array(
                'member_id' => $this->member->id,
                'program_id' => 1,
            );
            $this->program_model->add_member_to_program($programs_members_data);
            $this->set_student();
            // log_message('error', "DEV: Cannot login, no program [member_id: {$this->member->id}]");
            // show_error('Your account does not belong to a program.');
            // return;
        }

        if (!$program_id) {
            $program = $this->program_model->get($this->student->programs[0]->id);
        } else {
            $program = $this->program_model->get($program_id);
        }
        $this->session->set_userdata('program', $program);
        $this->program = $program;
    }
}

class Student_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // First time logging in? Start the introduction course
        if (empty($this->member->last_login) && $this->program->id == 1) {
            $this->load->model('course_model');
            $this->course_model->start(63, $this->member->id);
        }
    }
}

class Instructor_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!has_tag(Tag::INSTRUCTOR) && !has_tag(Tag::STAFF)) {
            redirect('http://' . $_SERVER['HTTP_HOST'] . '/member');
        }
    }
}

class Staff_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_tag(Tag::STAFF);
    }
}
