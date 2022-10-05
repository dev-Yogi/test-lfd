<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lesson extends Instructor_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('date');
        $this->load->model('lesson_model');
        $this->load->model('course_model');
        $this->load->model('video_model');
        $this->load->model('assignment_model');
    }

    public function create($course_id, $copy_from = null)
    {
        $course = $this->course_model->get($course_id);
        $this->check_valid($course);

        $create_from_copy = false;
        if ($copy_from) {
            $lesson = $this->lesson_model->get($copy_from);
            if (!empty($lesson)) {
                $create_from_copy = true;
            }
        }

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('title', 'Title', 'required|trim|max_length[2000]|strip_tags');
            $this->form_validation->set_rules('content', 'Content', 'required|trim');
            $this->form_validation->set_rules('length', 'Length', 'required|trim|numeric');
            $this->form_validation->set_rules('order', 'Order', 'required|trim|numeric');
            $this->form_validation->set_rules('has_assignment', 'Attach Assignment', 'trim|numeric');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $data = array(
                    'course_id' => $course_id,
                    'title' => $this->input->post('title'),
                    'content' => $this->input->post('content'),
                    'length' => $this->input->post('length'),
                    'order' => $this->input->post('order'),
                    'created_by' => $this->member->id
                );
                $lesson_id = $this->lesson_model->add($data);

                if ($lesson_id) {
                    if ($create_from_copy) {
                        $this->video_model->copy_all($copy_from, $lesson_id);
                        $this->assignment_model->copy_all($copy_from, $lesson_id);
                    }
                    if ($this->input->post('has_assignment')) {
                        $data = array(
                            'lesson_id' => $lesson_id,
                            'label' => 'Assignment',
                            'type' => 'textarea',
                            'created_by' => $this->member->id
                        );
                        $assignment_id = $this->assignment_model->add($data);
                    }
                    $this->session->set_flashdata('success', 'Lesson successfully created.');
                    redirect("admin/lesson/view/$lesson_id");
                } else {
                    $this->session->set_flashdata('error', 'The lesson could not be created.');
                }
            }
        }

        set_title('New Lesson');
        $this->load->view('admin/lesson-form', compact('course', 'create_from_copy'));
    }

    public function view($lesson_id)
    {
        $lesson = $this->lesson_model->get($lesson_id);
        $this->check_valid($lesson);
        $count_students_started = count($this->lesson_model->get_students_started($lesson_id));
        $count_students_completed = count($this->lesson_model->get_students_completed($lesson_id));
        $videos = $this->video_model->get_all($lesson_id);
        $assignments = $this->assignment_model->get_all_with_counts($lesson_id);
        set_title($lesson->title);
        $this->load->view('admin/lesson-view', compact('lesson', 'count_students_completed', 'count_students_started', 'videos', 'assignments'));
    }

    public function edit($lesson_id)
    {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('title', 'Title', 'required|trim|max_length[2000]|strip_tags');
            $this->form_validation->set_rules('content', 'Content', 'required|trim');
            $this->form_validation->set_rules('length', 'Length', 'required|trim|numeric');
            $this->form_validation->set_rules('order', 'Order', 'required|trim|numeric');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $data = array(
                    'title' => $this->input->post('title'),
                    'content' => $this->input->post('content'),
                    'length' => $this->input->post('length'),
                    'order' => $this->input->post('order'),
                    'modified_by' => $this->member->id,
                    'modified' => date('Y-m-d H:i:s')
                );
                $this->lesson_model->update($lesson_id, $data);
                $this->session->set_flashdata('success', 'Lesson updated.');
                redirect("admin/lesson/view/$lesson_id");
            }
        }

        $lesson = $this->lesson_model->get($lesson_id);
        $course = $this->course_model->get($lesson->course_id);
        $this->check_valid($lesson);
        set_title('Lesson');
        $this->load->view('admin/lesson-form', compact('lesson', 'course'));
    } 

    public function remove($lesson_id)
    {
        $lesson = $this->lesson_model->get($lesson_id);
        $course = $this->course_model->get($lesson->course_id);
        $this->check_valid($lesson);
        $deleted = $this->lesson_model->remove($lesson_id, $this->member->id);
        if ($deleted) {
            $this->session->set_flashdata('success', 'Lesson has been removed.');
        } else {
            $this->session->set_flashdata('error', 'Lesson could not be removed.');
        }
        redirect("admin/course/view/{$lesson->course_id}");
    }

    public function check_valid($lesson)
    {
        if (empty($lesson)) {
            $this->session->set_flashdata('error', 'Invalid lesson or course.');
            redirect("admin/course/view/{$lesson->course_id}");
        }
        if ($lesson->removed) {
            $this->session->set_flashdata('error', 'The lesson is a removed lesson and cannot be viewed.');
            redirect("admin/course/view/{$lesson->course_id}");
        }
    }

    public function students($lesson_id) {
        $lesson = $this->lesson_model->get($lesson_id);
        $this->check_valid($lesson);
        $students_started = $this->lesson_model->get_students_started($lesson_id);
        $students_completed = $this->lesson_model->get_students_completed($lesson_id);
        set_title($lesson->title);
        $this->load->view('admin/lesson-view-students', compact('lesson', 'students_completed', 'students_started'));
    }
}
