<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends Instructor_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('date');
        $this->load->model('course_model');
        $this->load->model('category_model');
        $this->load->model('instructor_model');
    }

    public function index()
    {
        $this->all();
    }

    public function all()
    {
        $courses = $this->course_model->get_all($this->program->id);
        set_title('Courses');
        $this->load->view('admin/course-list', compact('courses'));
    }

    public function me()
    {
        $courses = $this->course_model->get_all_by_instructor($this->member->id);
        set_title('Courses');
        $this->load->view('admin/course-list-me', compact('courses'));
    }

    public function view($course_id)
    {
        $course = $this->course_model->get($course_id);
        $this->check_valid($course);
        set_title($course->name);
        $this->load->view('admin/course-view', compact('course'));
    }

    public function create()
    {
        $instructors = $this->instructor_model->get_all();
        $categories = $this->category_model->get_all($this->program->id);

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Name', 'required|trim|max_length[250]|strip_tags');
            $this->form_validation->set_rules('description', 'Description', 'required|trim|max_length[2000]|strip_tags');
            $this->form_validation->set_rules('whatyoulllearn', 'What You\'ll Learn', 'required|trim|max_length[2000]|strip_tags');
            $this->form_validation->set_rules('instructor_ids[]', 'Instructor', 'required');
            $this->form_validation->set_rules('category_ids[]', 'Categories', 'required');
            $this->form_validation->set_rules('is_assign_only', 'Assign Only Course', 'trim|strip_tags|numeric');
            $this->form_validation->set_rules('force_order_lesson_completion', 'Lesson Completion Order', 'trim|strip_tags|numeric');

            $upload = $this->upload_logo();
            if (!empty($upload['error'])) {
                $this->form_validation->set_rules('image', 'Image', 'required|trim', array('required' => $upload['error']));
            }

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $program_id = $this->program->id;
                $data = array(
                    'name' => $this->input->post('name'),
                    'status' => 'draft',
                    'description' => $this->input->post('description'),
                    'whatyoulllearn' => $this->input->post('whatyoulllearn'),
                    'image' => $upload,
                    'is_assign_only' => $this->input->post('is_assign_only') ?? 0,
                    'force_order_lesson_completion' => $this->input->post('force_order_lesson_completion') ?? 0,
                    'created_by' => $this->member->id
                );
                $course_id = $this->course_model->add($data, $program_id);
                $this->course_model->set_instructors($course_id, $this->input->post('instructor_ids[]'));
                $this->course_model->set_categories($course_id, $this->input->post('category_ids[]'));

                if ($course_id) {
                    $this->session->set_flashdata('success', 'Course successfully created.');
                    redirect("admin/course/view/$course_id");
                } else {
                    $this->session->set_flashdata('error', 'The course could not be created.');
                }
            }
        }

        set_title('New Course');
        $this->load->view('admin/course-form', compact('instructors', 'categories'));
    }    

    public function edit($course_id)
    {
        $course = $this->course_model->get($course_id);
        $this->check_valid($course);
        $instructors = $this->instructor_model->get_all();
        $categories = $this->category_model->get_all($this->program->id);

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Name', 'required|trim|max_length[250]|strip_tags');
            $this->form_validation->set_rules('description', 'Description', 'required|trim|max_length[2000]|strip_tags');
            $this->form_validation->set_rules('whatyoulllearn', 'What You\'ll Learn', 'required|trim|max_length[2000]|strip_tags');
            $this->form_validation->set_rules('instructor_ids[]', 'Instructor', 'required');
            $this->form_validation->set_rules('category_ids[]', 'Categories', 'required');
            $this->form_validation->set_rules('is_assign_only', 'Assign Only Course', 'trim|strip_tags|numeric');
            $this->form_validation->set_rules('force_order_lesson_completion', 'Lesson Completion Order', 'trim|strip_tags|numeric');

            if ($_FILES['image']['size']) {
                $upload = $this->upload_logo();
                if (!empty($upload['error'])) {
                    $this->form_validation->set_rules('image', 'Image', 'required', array('required' => $upload['error']));
                }
            }

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $this->course_model->set_instructors($course_id, $this->input->post('instructor_ids[]'));
                $this->course_model->set_categories($course_id, $this->input->post('category_ids[]'));

                $program_id = $this->program->id;
                $data = array(
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'whatyoulllearn' => $this->input->post('whatyoulllearn'),
                    'is_assign_only' => $this->input->post('is_assign_only') ?? 0,
                    'force_order_lesson_completion' => $this->input->post('force_order_lesson_completion') ?? 0,
                    'modified_by' => $this->member->id,
                    'modified' => date('Y-m-d H:i:s')
                );
                if ($this->input->post('publish')) {
                    $data['status'] = 'published';
                }
                if ($upload) {
                    $data['image'] = $upload;
                    unlink($course->image);
                }
                $updated = $this->course_model->update($course_id, $data, $program_id);

                $this->session->set_flashdata('success', 'Course updated.');
                redirect("admin/course/view/$course_id");
            }
        }

        set_title('Edit Course');
        $this->load->view('admin/course-form', compact('instructors', 'categories', 'course'));
    }

    public function upload_logo()
    {
        $config['upload_path'] = UPLOAD_FOLDER_COURSE_IMAGE;
        $config['allowed_types'] = 'jpg|png';
        $config['max_size'] = 50000;
        $config['max_width'] = 6000;
        $config['max_height'] = 6000;
        $config['file_name'] = 'cover-' . date('is');

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('image')) {
            $error = array('error' => $this->upload->display_errors());
        } else {
            $data = $this->upload->data();
            $processed = $this->process_logo($data['file_name']);
            unlink($data['full_path']);
            if ($processed) {
                return $data['file_name'];
            }
        }
        return $error;
    }

    public function process_logo($file_name)
    {
        $config['image_library'] = 'gd2';
        $config['source_image'] = UPLOAD_FOLDER_COURSE_IMAGE . $file_name;
        $config['new_image'] = FOLDER_COURSE_IMAGE . $file_name;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 400;

        $this->load->library('image_lib', $config);
        $resized = $this->image_lib->resize();
        return $resized;
    }

    public function remove($course_id)
    {
        $this->load->model('video_model');
        $this->load->model('assignment_model');
        $this->load->model('lesson_model');

        $course = $this->course_model->get($course_id);
        $this->check_valid($course);
        $deleted = $this->course_model->remove($course_id, $this->member->id);
        if ($deleted) {
            $this->session->set_flashdata('success', 'Course has been removed.');
        } else {
            $this->session->set_flashdata('error', 'Course could not be removed.');
        }

        $lessons = $this->course_model->get_lessons($course_id);
        foreach ($lessons as $lesson) {
            $videos = $this->video_model->remove_for_lesson($lesson->id, $this->member->id);
            $assignments = $this->assignment_model->remove_for_lesson($lesson->id, $this->member->id);
            $this->lesson_model->remove($lesson->id, $this->member->id);
        }
        redirect("admin/course");
    }

    public function assign($course_id)
    {
        $this->load->model('student_model');
        $course = $this->course_model->get($course_id);
        $this->check_valid($course);
        $students = $this->student_model->get_all($this->program->id);

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('student_ids[]', 'Students', 'required|trim');
            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $student_ids = $this->input->post('student_ids');
                foreach ($student_ids as $member_id) {
                    $updated = $this->course_model->start($course_id, $member_id);
                    if ($updated) {
                        // Notification
                        $url = base_url("course/lessons/$course_id");
                        $message = array(
                            'member_id' => $member_id, 
                            'program_id' => $this->program->id,
                            'label' => "A new course as been assigned to you -<b>{$course->name}</b>",
                            'title' => "Course: <b>{$course->name}</b> has been assigned to you",
                            'message' => "
                                An instructor has assigned this course to your account: <br>
                                <a href='$url'>{$course->name}</a><br>
                                To get started with the lessons, click on the course link and click <b>Start</b> on a lesson.<br>
                            ",
                            'created_by' => $this->member->id
                        );
                        $this->message_model->add($message);
                    }
                }

                $this->session->set_flashdata('success', 'Course assigned.');
                redirect("admin/course/view/$course_id");
            }
        }

        set_title('Assign Course');
        $this->load->view('admin/course-assign', compact('course', 'students'));
    }

    public function set_status($course_id, $status)
    {
        $course = $this->course_model->get($course_id);
        $this->check_valid($course);

        $program_id = $this->program->id;
        $data = array(
            'status' => $status == 'published' ? 'published' : 'draft',
            'modified_by' => $this->member->id,
            'modified' => date('Y-m-d H:i:s')
        );
        $updated = $this->course_model->update($course->id, $data, $program_id);
        if ($updated) {
            $this->session->set_flashdata('success', 'The course status has been updated.');
        } else {
            $this->session->set_flashdata('error', 'The course status could not be updated.');
        }
        redirect("admin/course/view/{$course->id}");
    }

    public function check_valid($course)
    {
        if (empty($course)) {
            $this->session->set_flashdata('error', 'Invalid course.');
            redirect("admin/course");
        }
        if ($course->removed) {
            $this->session->set_flashdata('error', 'The course is a removed course and cannot be viewed.');
            redirect("admin/course");
        }
    }
}
