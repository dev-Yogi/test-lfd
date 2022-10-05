<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Instructor_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('student_model');
        $this->load->model('forum_model');
        $this->load->model('video_model');
        $this->load->model('assignment_model');
        $this->load->helper('date');
    }

	public function index()
	{
		$students = $this->student_model->get_recent(5);
		$replies = $this->forum_model->get_replies_for_admin(5);
        set_title('Dashboard');
        $this->load->view('admin/dashboard', compact('students', 'replies'));
	}

    public function switch_program($program_id)
    {
        $this->set_program($program_id);
        redirect("admin/course/all/$program_id");
    }
}
