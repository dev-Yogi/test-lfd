<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lesson extends Student_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('course_model');
        $this->load->model('lesson_model');
        $this->load->model('video_model');
        $this->load->model('assignment_model');
        $this->load->helper('date');
    }

    public function check_course_started($course_id)
    {
        $has_course = $this->course_model->student_has_course($this->member->id, $course_id);
        if (!$has_course) {
            $this->session->set_flashdata('lesson_course_warning', true);
            redirect("course/view/$course_id");
        }
    }

    public function view($lesson_id)
    {
        $lesson = $this->lesson_model->get($lesson_id);
        if ($lesson) {
            $course = $this->course_model->get($lesson->course_id);
        }

        if (empty($lesson) || empty($course)) {
            $this->session->set_flashdata('error', 'The lesson you\'re looking for has been removed, or does not exist.');
            redirect('course');
        }

        // Check that they've completed the previous lesson
        if ($course->force_order_lesson_completion) {
            $previous_lesson_status = null;
            foreach ($course->lessons as $course_lesson) {
                if ($course_lesson->id == $lesson->id) {
                    if ($previous_lesson_status != 'complete' && $previous_lesson_status != null) {
                        $this->session->set_flashdata('error', 'You have not completed the previous lesson.');
                        redirect('course');
                    }
                } else {
                    $previous_lesson_status = $course_lesson->status;
                }
            }
        }

        // Check if course has been started before lesson
        $this->check_course_started($lesson->course_id);

        $progress = $this->lesson_model->get_progress($lesson_id, $this->member->id);
        if (empty($progress)) {
            $this->lesson_model->start($lesson_id, $this->member->id);
        }
        foreach ($course->lessons as $key => $value) {
            if ($value->id == $lesson_id) {
                $lesson->order = $value->order;
            }
        }
        $videos = $this->video_model->get_all($lesson_id);
        $assignments = $this->assignment_model->get_all($lesson_id);
        $assignment_submission = array();
        foreach ($assignments as $key => $assignment) {
            $submission = $this->assignment_model->get_submission($assignment->id, $this->member->id);
            $assignment_submission[$key] = $assignment;
            $assignment_submission[$key]->submission = $submission;
            if (!empty($submission)) {
                $has_submission = true;
            }
        }
        if (empty($has_submission)) {
            $assignment_submission = null;
        }
        set_title($lesson->title);
        $this->load->view('course/lesson', compact('course', 'lesson', 'videos', 'assignments', 'assignment_submission'));
    }

    public function complete($lesson_id)
    {
        $lesson = $this->lesson_model->get($lesson_id);
        $assignments = $this->assignment_model->get_all($lesson_id);
        if (!empty($assignments)) {
            echo "This lesson has an assignment, and can only be marked as complete by the instructor.";
            return;
        }
        $progress = $this->lesson_model->get_progress($lesson_id, $this->member->id);
        if ($progress->status == 'started') {
            $this->lesson_model->complete($lesson_id, $this->member->id);
        }
        redirect("course/lessons/{$lesson->course_id}");
    }

    public function submit_assignment($lesson_id)
    {
        $this->load->library('form_validation');
        $assignments = $this->assignment_model->get_all($lesson_id);
        $has_submitted = $this->assignment_model->has_submitted_to_lesson($lesson_id, $this->member->id);
        if ($this->input->method() == 'post') {
            foreach ($assignments as $assignment) {
                if ($assignment->type == 'text' || $assignment->type == 'textarea') {
                    $this->form_validation->set_rules($assignment->id, 'text', 'required|trim|max_length[4000]|strip_tags');
                } elseif ($assignment->type == 'upload') {
                    // Check file size
                    if ($_SERVER["CONTENT_LENGTH"] > ((int)ini_get('post_max_size') * 1024 * 1024)) {
                        // File too big
                        $this->form_validation->set_rules($assignment->id, $assignment->label, 'required', array('required' => 'File too large.'));
                    } else {
                        // Check if file is there
                        if (!empty($_FILES[$assignment->id]['name'])) {
                            $upload = $this->upload_assignment($assignment);
                            // Check for upload error
                            if (!empty($upload['error'])) {
                                // There was an upload error
                                $this->form_validation->set_rules($assignment->id, $assignment->label, 'required', array('required' => $upload['error']));
                            } else {
                                // Setting a rule here required to prevent empty form validation
                                $this->form_validation->set_rules($assignment->id, 'file upload', 'required');
                                $_POST[$assignment->id] = $_FILES[$assignment->id]['name'];
                            }
                        } else {
                            // No file submitted
                            $this->form_validation->set_rules($assignment->id, 'file upload', 'required');
                        }
                    }
                }
            }

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                foreach ($assignments as $assignment) {
                    $data = array(
                        'assignment_id' => $assignment->id,
                        'student_id' => $this->member->id,
                        'created_by' => $this->member->id
                    );
                    if ($assignment->type == 'text' || $assignment->type == 'textarea') {
                        $data['data'] = $this->input->post($assignment->id);
                    } elseif ($assignment->type == 'upload') {
                        $data['data'] = $upload;
                    }
                    $assignment_id = $this->assignment_model->submit($data);
                }
                $this->session->set_flashdata('success', 'Assignment successfully submitted.');
                redirect("lesson/view/$lesson_id");
            }
        }

        $lesson = $this->lesson_model->get($lesson_id);
        $course = $this->course_model->get($lesson->course_id);
        $videos = $this->video_model->get_all($lesson_id);
        $assignments = $this->assignment_model->get_all($lesson_id);
        $assignments_submissions = $this->assignment_model->get_all($lesson_id);
        set_title('Submit Assignment');
        $this->load->view('course/assignment', compact('course', 'lesson', 'videos', 'assignments', 'has_submitted'));
    }

    public function upload_assignment($assignment)
    {
        $config['upload_path'] = UPLOAD_FOLDER_ASSIGNMENT;
        $config['allowed_types'] = 'txt|pdf|doc|docx|csv|html|css|odt|ods|xls|xlsx|ppt|pptx|jpg|png|gif|tiff|psd|raw|bmp|jpeg|svg|mp4|mp3|mov|wmv|flv|avi';
        $config['max_size'] = 200000;
        $config['overwrite'] = TRUE;
        $config['file_name'] = url_title(display_name($this->member) . '-' . $assignment->label) . '-' . $assignment->id;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($assignment->id)) {
            $error = array('error' => $this->upload->display_errors());
        } else {
            $data = $this->upload->data();
            return $data['file_name'];
        }
        return $error;
    }

    public function download_assignment($assignment_id, $student_id)
    {
        $this->load->helper('download');
        if (!has_tag(Tag::INSTRUCTOR) && !has_tag(Tag::STAFF) && $this->member->id != $student_id) {
            echo "You do not have permission to view this file.";
            return;
        }
        $submission = $this->assignment_model->get_submission($assignment_id, $student_id);
        if ($submission) {
            force_download(UPLOAD_FOLDER_ASSIGNMENT . $submission->data, NULL);
        } else {
            echo "Assignment could not be found.";
            return;
        }
    }
}
