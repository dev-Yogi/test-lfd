<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assignment extends Instructor_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('date');
        $this->load->model('assignment_model');
        $this->load->model('student_model');
        $this->load->model('lesson_model');
    }

    public function create($lesson_id)
    {
        $lesson = $this->lesson_model->get($lesson_id);

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('label', 'Label', 'required|trim|max_length[250]|strip_tags');
            $this->form_validation->set_rules('description', 'Description', 'trim|max_length[1000]|strip_tags');
            $this->form_validation->set_rules('type', 'type', 'required|trim|in_list[text,textarea,upload]');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                if ($this->input->post('type') == 'upload') {
                    $assignments = $this->assignment_model->get_all($lesson_id);
                    foreach ($assignments as $assignment) {
                        if ($assignment->type == 'upload') {
                            $this->session->set_flashdata('error', 'File upload assignments can only be used once per lesson.');
                            set_title('New Assignment');
                            $this->load->view('admin/assignment-form', compact('lesson'));
                            return;
                        }
                    }
                }
                $data = array(
                    'lesson_id' => $lesson_id,
                    'label' => $this->input->post('label'),
                    'description' => $this->input->post('description'),
                    'type' => $this->input->post('type'),
                    'created_by' => $this->member->id
                );
                $assignment_id = $this->assignment_model->add($data);

                if ($assignment_id) {
                    $this->session->set_flashdata('success', 'Assignment successfully added.');
                    redirect("admin/assignment/list/$lesson_id");
                } else {
                    $this->session->set_flashdata('error', 'The assignment could not be added.');
                }
            }
        }

        set_title('New Assignment');
        $this->load->view('admin/assignment-form', compact('lesson'));
    }

    public function list($lesson_id)
    {
        $assignments = $this->assignment_model->get_all_with_counts($lesson_id);
        $lesson = $this->lesson_model->get($lesson_id);
        set_title('Assignments');
        $this->load->view('admin/assignment-list', compact('assignments', 'lesson'));
    }

    public function student($lesson_id, $student_id = null)
    {
        $lesson = $this->lesson_model->get($lesson_id);
        if ($student_id) {
            $student = $this->student_model->get($student_id);
            $assignments = $this->assignment_model->get_all($lesson_id);
            $submissions = array();
            $feedback_message_id = null;
            foreach ($assignments as $key => $assignment) {
                $submissions[$key] = $assignment;
                $submissions[$key]->submission = $this->assignment_model->get_submission($assignment->id, $student_id);
                $feedback_message_id = $submissions[$key]->submission->feedback_message_id;
            }
            if ($feedback_message_id) {
                $message = $this->message_model->get($this->member->id, $feedback_message_id);
            } else {
                $message = null;
            }
            set_title('Assignments');
            $this->load->view('admin/assignment-student', compact('student', 'submissions', 'lesson', 'message'));
        } else {
            $assignments = $this->assignment_model->get_students($lesson_id);
            set_title('Assignments');
            $this->load->view('admin/assignment-list-student', compact('assignments', 'lesson'));
        }
    }

    public function complete($lesson_id, $student_id)
    {
        $assignments = $this->assignment_model->get_all($lesson_id);
        if (empty($assignments)) {
            echo "This lesson does not have an assignment, and can only be completed by the student.";
            return;
        }

        $progress = $this->lesson_model->get_progress($lesson_id, $student_id);
        if ($progress->status == 'started') {
            if ($this->lesson_model->complete($lesson_id, $student_id)) {
                $this->session->set_flashdata('success', 'Lesson has been marked as complete for this student.');
                redirect("admin/assignment/student/{$lesson_id}/{$student_id}");
            }
        }
        $this->session->set_flashdata('error', 'Lesson could not be marked as complete for this student.');
        redirect("admin/assignment/student/{$lesson_id}/{$student_id}");
    }

    public function edit($assignment_id)
    {
        $assignment = $this->assignment_model->get($assignment_id);
        $lesson = $this->lesson_model->get($assignment->lesson_id);
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('label', 'Label', 'required|trim|max_length[250]|strip_tags');
            $this->form_validation->set_rules('description', 'Description', 'trim|max_length[1000]|strip_tags');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $data = array(
                    'label' => $this->input->post('label'),
                    'description' => $this->input->post('description'),
                    'modified_by' => $this->member->id,
                    'modified' => date('Y-m-d H:i:s')
                );
                $assignment_id = $this->assignment_model->update($assignment_id, $data);

                if ($assignment_id) {
                    $this->session->set_flashdata('success', 'Assignment successfully updated.');
                    redirect("admin/assignment/list/{$lesson->id}");
                } else {
                    $this->session->set_flashdata('error', 'The assignment could not be updated.');
                }
            }
        }

        set_title('Edit Assignment');
        $this->load->view('admin/assignment-form', compact('lesson', 'assignment'));
    } 

    public function remove($assignment_id)
    {
        $assignment = $this->assignment_model->get($assignment_id);
        $lesson = $this->lesson_model->get($assignment->lesson_id);
        $deleted = $this->assignment_model->remove($assignment_id, $this->member->id);
        if ($deleted) {
            $this->session->set_flashdata('success', 'Assignment has been removed.');
        } else {
            $this->session->set_flashdata('error', 'Assignment could not be removed.');
        }
        redirect("admin/assignment/list/{$assignment->lesson_id}");
    }

    public function submissions($assignment_id)
    {
        $assignment = $this->assignment_model->get($assignment_id);
        $lesson = $this->lesson_model->get($assignment->lesson_id);
        $assignments = $this->assignment_model->get_all_submissions($assignment_id);
        $this->load->view('admin/assignment-submissions', compact('assignments', 'assignment', 'lesson'));
    }

    public function submit_feedback($lesson_id, $student_id)
    {
        $lesson = $this->lesson_model->get($lesson_id);
        $student = $this->student_model->get($student_id);
        $assignments = $this->assignment_model->get_all($lesson_id);

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('message', 'Message', 'required|trim|strip_tags');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Please enter a valid message.');
                redirect("admin/assignment/student/{$lesson->id}/{$student->id}");
            } else {
                // Does this assignment already have a feedback thread?
                $feedback_message_id = null;
                $submissions = array();
                foreach ($assignments as $key => $assignment) {
                    $submissions[$key] = $assignment;
                    $submissions[$key]->submission = $this->assignment_model->get_submission($assignment->id, $student_id);
                    if ($submissions[$key]->submission->feedback_message_id) {
                        $feedback_message_id = $submissions[$key]->submission->feedback_message_id;
                    }
                }
                if (!$feedback_message_id) {
                    $assignment_html = $this->load->view('admin/assignment-student-inbox', compact('student', 'submissions', 'lesson'), true);
                    $message = array(
                        'member_id' => $student_id, 
                        'program_id' => $this->program->id,
                        'label' => "You've received new feedback on an assignment.",
                        'title' => "Feedback on assignment for <b>{$lesson->title}</b>",
                        'message' => $assignment_html,
                        'read' => 1,
                        'can_reply' => 1,
                        'created_by' => $this->member->id
                    );
                    $feedback_message_id = $this->message_model->add($message);
                    foreach ($assignments as $assignment) {
                        $this->assignment_model->update_feedback_id($assignment->id, $student_id, $feedback_message_id);
                    }
                }

                $message = array(
                    'member_id' => $student_id, 
                    'program_id' => $this->program->id,
                    'label' => "You've received new feedback on an assignment.",
                    'title' => "Feedback on assignment for <b>{$lesson->name}</b>",
                    'message' => $this->input->post('message'),
                    'parent_id' => $feedback_message_id,
                    'created_by' => $this->member->id
                );
                $message_id = $this->message_model->add($message);

                if ($message_id) {
                    $this->session->set_flashdata('success', 'Feedback submitted.');
                } else {
                    $this->session->set_flashdata('error', 'There was an error with submitting feedback.');
                }
            }
        }
        redirect("admin/assignment/student/$lesson_id/$student_id");
    }
}
