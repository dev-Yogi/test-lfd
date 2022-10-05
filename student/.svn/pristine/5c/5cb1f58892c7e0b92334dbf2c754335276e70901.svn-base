<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends Student_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('course_model');
        $this->load->model('category_model');
        $this->load->helper('date');
    }

	public function index()
	{
        $this->category('all');
	}

    public function category($category_id)
    {
        if ($category_id == 'all') {
            $category = null;
            $courses = $this->course_model->get_all($this->program->id, true, 'published', false);
            set_title('All Courses');
        } else {
            $category = $this->category_model->get($category_id);
            $courses = $this->course_model->get_by_category($category_id, true, false);
            set_title($category->name);
        }
        $categories = $this->category_model->get_all($this->program->id, false);
        $pagination = paginate(count($courses));
        $courses = array_slice($courses, ($pagination->current - 1) * RESULTS_PER_PAGE, RESULTS_PER_PAGE);
        $this->load->view('course/list', compact('courses', 'category', 'categories', 'pagination'));
    }

    public function me($completed = false)
    {
        $courses = $this->course_model->get_student_courses($this->program->id, $this->member->id, $completed !== false);
        $categories = $this->category_model->get_all($this->program->id, false);
        set_title($completed ? 'Completed Courses' : 'My Courses');
        $this->load->view('course/me', compact('courses', 'categories'));
    }

	public function view($course_id)
	{
        $course = $this->course_model->get($course_id, 'published');
        if (!$course) {
            $this->session->set_flashdata('error', 'The course you\'re looking for has been removed, or does not exist.');
            redirect('course');
        }
        if ($course->is_assign_only) {
            $this->session->set_flashdata('error', 'This course must be assigned by an instructor in order to see it.');
            redirect('course');
        }
        $progress = $this->course_model->get_course_progress($course_id);
        set_title('Courses');
        $this->load->view('course/view', compact('course', 'progress'));
	}

    public function lessons($course_id)
    {
        if (!$this->course_model->student_has_course($this->member->id, $course_id)) {
            redirect("course/view/$course_id");
        }
        $course = $this->course_model->get($course_id, 'published');
        if (!$course) {
            $this->session->set_flashdata('error', 'The course you\'re looking for has been removed, or does not exist.');
            redirect('course');
        }
        $progress = $this->course_model->get_course_progress($course_id);
        set_title($course->name);
        $this->load->view('course/lessons', compact('course', 'progress'));
    }

    public function start($course_id)
    {
        $started = $this->course_model->start($course_id, $this->member->id);
        if ($started) {
            redirect("course/lessons/{$course_id}");
        } else {
            redirect("course/view/{$course_id}");
        }
    }

    public function complete($course_id)
    {
        $complete = $this->course_model->complete($course_id);
        if ($complete) {
            $this->session->set_flashdata('success', 'Course completed.');
            redirect("course/me/completed");
        } else {
            $this->session->set_flashdata('error', 'Please complete all lessons before completing the course.');
            redirect("course/lessons/{$course_id}");
        }
    }
}
