<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Student_Controller {

	public function index()
	{
		$this->load->model('course_model');
		$this->load->model('forum_model');
		$my_courses = $this->course_model->get_my_courses($this->program->id);
		$recent_courses = $this->course_model->get_recent_courses($this->program->id);
		$threads = $this->forum_model->get_recent_threads($this->program->id, 4);
		$replies = $this->forum_model->get_recent_replies($this->program->id, 4);
        set_title('Welcome');
        $this->load->view('dashboard', compact('my_courses', 'recent_courses', 'threads', 'replies'));
	}

    public function switch_program($program_id)
    {
        $this->set_program($program_id);
        redirect("/");
    }
}
