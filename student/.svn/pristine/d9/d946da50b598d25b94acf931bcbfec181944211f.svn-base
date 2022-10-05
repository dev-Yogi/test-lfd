<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends Instructor_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('student_model');
        $this->load->model('course_model');
        $this->load->model('program_model');
        $this->load->model('field_model');
        $this->load->model('forum_model');
        $this->load->model('instructor_model');
    }

    public function index()
    {
        $this->all();
    }

    public function all()
    {
        $students = $this->student_model->get_all($this->program->id);
        $extra_field_column = $this->field_model->get_by_program($this->program->id)[0]->label ?? null;
        set_title('Students');
        $this->load->view('admin/student-list', compact('students', 'extra_field_column'));
    }

    public function view($member_id)
    {
        $student = $this->student_model->get($member_id);
        $courses = $this->course_model->get_student_courses($this->program->id, $member_id);
        $lessons = $this->course_model->get_lessons($member_id);

        $profile = $this->forum_model->get_profile($member_id);
        $threads = $this->forum_model->get_threads_by_user($member_id);
        $replies = $this->forum_model->get_replies_by_user($member_id);

        $instructor = $this->instructor_model->get($student->instructor_id);
        $extra_fields = $this->field_model->get_by_program($this->program->id);

        set_title('Student');
        $this->load->view('admin/student-view', compact('student', 'courses', 'lessons', 'profile', 'threads', 'replies', 'extra_fields', 'instructor'));
    }

    public function create()
    {
        $this->check_tag(Tag::STAFF);

        $instructors = $this->instructor_model->get_all();
        $extra_fields = $this->field_model->get_by_program($this->program->id);
        if ($this->input->method() == 'post') {
            $this->db = $this->load->database('member', TRUE);
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[members.email]', array('is_unique' => 'This email already belongs to an account.'));
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('instructor_id', 'Instructor', 'numeric');
            $this->form_validation->set_rules('notify_instructor', 'Notify Instructor', 'trim');
            $this->form_validation->set_rules('notify_student', 'Notify Student', 'trim');
            foreach ($extra_fields as $field) {
                $this->form_validation->set_rules($field->id, $field->label, 'required');
            }

            if ($this->form_validation->run() == false) {
                $this->db = $this->load->database('default', TRUE);
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $this->db = $this->load->database('default', TRUE);
                $password = base64_encode(random_bytes(12));
                $extra_fields = $this->get_extra_field_answers($extra_fields, $this->input->post());
                $program_data = array(
                    'program_id' => $this->program->id,
                    'instructor_id' => $this->input->post('instructor_id') ?? null,
                );
                $data = array(
                    'email' => $this->input->post('email'),
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'created_by' => $this->member->id,
                );
                $member_id = $this->student_model->add($data, $program_data, $extra_fields);

                $notify_instructor = $this->input->post('notify_instructor');
                $notify_student = $this->input->post('notify_student');
                if ($notify_student) {
                    $this->email_new_student($data['email'], $password);
                }
                if ($notify_instructor) {
                    $this->email_instructor([[$data['email'], $password]], $program_data['instructor_id']);
                }

                if ($member_id) {
                    $this->session->set_flashdata('success', 'Student successfully created.');
                    redirect("admin/student/edit/$member_id");
                } else {
                    $this->session->set_flashdata('error', 'The student could not be created.');
                }
            }
        }

        set_title('New Student');
        $this->load->view('admin/student-form', compact('extra_fields', 'instructors'));
    }

    public function get_extra_field_answers($fields, $post)
    {
        $answers = array();
        foreach ($fields as $field) {
            $answers[] = array('field_id' => $field->id, 'value' => $post[$field->id]);
        }
        return $answers;
    }

    public function edit($member_id)
    {
        $this->check_tag(Tag::STAFF);
        $student = $this->student_model->get($member_id);
        $instructors = $this->instructor_model->get_all();
        $extra_fields = $this->field_model->get_by_program($this->program->id);
        if ($this->input->method() == 'post') {
            $this->db = $this->load->database('member', TRUE);

            if ($student->email != $this->input->post('email')) {
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[members.email]', array('is_unique' => 'This email already belongs to an account.'));
            }
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('instructor_id', 'Instructor', 'numeric');
            foreach ($extra_fields as $field) {
                $this->form_validation->set_rules($field->id, $field->label, 'required');
            }

            if ($this->form_validation->run() == false) {
                $this->db = $this->load->database('default', TRUE);
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $this->db = $this->load->database('default', TRUE);
                $extra_fields = $this->get_extra_field_answers($extra_fields, $this->input->post());
                $program_data = array(
                    'program_id' => $this->program->id,
                    'instructor_id' => $this->input->post('instructor_id'),
                );
                $data = array(
                    'email' => $this->input->post('email'),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'modified_by' => $this->member->id,
                    'modified' => date('Y-m-d H:i:s')
                );
                $updated = $this->student_model->update($student->id, $data, $program_data, $extra_fields);

                if ($updated) {
                    $this->session->set_flashdata('success', 'Student successfully updated.');
                    redirect("admin/student/edit/$member_id");
                } else {
                    $this->session->set_flashdata('error', 'The student could not be updated.');
                }
            }
        }

        set_title('Edit Student');
        $this->load->view('admin/student-form', compact('student', 'extra_fields', 'instructors'));
    }

    public function programs($member_id)
    {
        $this->check_tag(Tag::STAFF);
        $programs = $this->program_model->get_all();
        $student = $this->student_model->get($member_id);

        if ($this->input->method() == 'post') {
            $selected = $this->input->post('programs') ?? array();
            // Remove unselected programs
            foreach ($student->programs as $program) {
                if (!in_array($program->id, $selected)) {
                    $data = array(
                        'program_id' => $program->id,
                        'member_id' => $student->id,
                        'modified_by' => $this->member->id
                    );
                    $this->program_model->remove_member_from_program($data);
                }
            }

            // Add selected programs
            foreach ($selected as $program_id) {
                $data = array(
                    'program_id' => $program_id,
                    'member_id' => $student->id,
                    'modified_by' => $this->member->id
                );
                $this->program_model->add_member_to_program($data);
            }
            $this->session->set_flashdata('success', 'Student programs have been updated.');
            redirect("admin/student/programs/$member_id");
        }

        set_title('Student Programs');
        $this->load->view('admin/student-program-form', compact('student', 'programs'));
    }

    public function import()
    {
        $this->check_tag(Tag::STAFF);

        $instructors = $this->instructor_model->get_all();
        $programs = $this->program_model->get_all();
        $extra_fields = $this->field_model->get_by_program($this->program->id);

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('notify_instructor', 'Notify Instructor', 'trim');
            $this->form_validation->set_rules('notify_student', 'Notify Student', 'trim');

            // Check file size
            if ($_SERVER["CONTENT_LENGTH"] > ((int)ini_get('post_max_size') * 1024 * 1024)) {
                // File too big
                $this->form_validation->set_rules('csv', "CSV file", 'required', array('required' => 'File too large.'));
            } else {
                // Check if file is there
                if (!empty($_FILES['csv']['name'])) {
                    $upload = $this->upload_csv();
                    // Check for upload error
                    if (!empty($upload['error'])) {
                        // There was an upload error
                        $this->form_validation->set_rules('csv', "CSV file", 'required', array('required' => $upload['error']));
                    } else {
                        // Setting a rule here required to prevent empty form validation
                        $this->form_validation->set_rules('csv', 'file upload', 'required');
                        $_POST['csv'] = $_FILES['csv']['name'];

                        $notify_instructor = $this->input->post('notify_instructor');
                        $notify_student = $this->input->post('notify_student');
                        $new_students = array();

                        // Start Import
                        $handle = fopen(UPLOAD_FOLDER_ADMIN_CSV . $upload,"r");
                        $i = 0;
                        $new_members_created = 0;
                        $messages = array();
                        while (($row = fgetcsv($handle, 10000, ",")) != FALSE)
                        {
                            $i++;
                            if (!isset($row[0]) || empty($row[0])) {
                                $messages[] = array('skipped', "Row $i is missing last name in column 1");
                                continue;
                            }
                            if (!isset($row[1]) || empty($row[1])) {
                                $messages[] = array('skipped', "Row $i is missing first name in column 2");
                                continue;
                            }
                            if (!isset($row[2]) || !filter_var($row[2], FILTER_VALIDATE_EMAIL)) {
                                $messages[] = array('skipped', "Row $i has invalid email in column 3");
                                continue;
                            }

                            $submitted_extra_fields = $this->get_extra_field_answers($extra_fields, $this->input->post());
                            $program_data = array(
                                'program_id' => $this->program->id,
                                'instructor_id' => !empty($this->input->post('instructor_id')) ? $this->input->post('instructor_id') : NULL,
                            );
                            $password = base64_encode(random_bytes(12));
                            $data = array(
                                'email' => $row[2],
                                'password' => password_hash($password, PASSWORD_DEFAULT),
                                'first_name' => $row[1],
                                'last_name' => $row[0],
                                'created_by' => $this->member->id,
                            );

                            $member = $this->student_model->get_member_by_email($data['email']);
                            if ($member) {
                                $messages[] = array('update', "Row $i email is already in use, skipping user creation and updating user");
                                $student = $this->student_model->get($member->id);
                                if (!$student) {
                                    $this->student_model->add_tag_to_member($member->id, Tag::STUDENT);
                                }
                                // Program custom fields
                                foreach ($submitted_extra_fields as $field) {
                                    $students_programs_fields_data = array(
                                        'program_id' => $this->program->id,
                                        'member_id' => $member->id,
                                        'field_id' => $field['field_id'],
                                        'value' => $field['value'],
                                        'created_by' => $this->member->id
                                    );
                                    $this->program_model->add_program_member_field($students_programs_fields_data);
                                }
                                
                                // Add student to program
                                $programs_members_data = array(
                                    'member_id' => $member->id,
                                    'program_id' => $this->program->id,
                                    'instructor_id' => $program_data['instructor_id'],
                                    'created_by' => $this->member->id
                                );
                                $this->program_model->add_member_to_program($programs_members_data);
                            } else {
                                $member_id = $this->student_model->add($data, $program_data, $submitted_extra_fields);
                                if ($member_id) {

                                    // Notify student
                                    if ($notify_student) {
                                        $email_sent = $this->email_new_student($data['email'], $password);
                                    }
                                    $new_students[] = [$data['email'], $password];

                                    $new_members_created++;
                                    $messages[] = array('added', $data['email'] . " added as member ID $member_id.");
                                } else {
                                    $messages[] = array('error', $data['email'] . " could not be added for an unknown reason.");
                                }
                            }

                        }

                        // Notify instructor
                        if ($notify_instructor) {
                            $this->email_instructor($new_students, $program_data['instructor_id']);
                        }
                        $messages[] = array('COMPLETE', "$new_members_created members created from this import");
                        set_title('Import Students');
                        $this->load->view('admin/student-import-complete', compact('messages'));
                        return;
                    }
                } else {
                    // No file submitted
                    $this->form_validation->set_rules('csv', 'file upload', 'required');
                }
            }

            // $this->db = $this->load->database('member', TRUE);
            // $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[members.email]', array('is_unique' => 'This email already belongs to an account.'));
            // $this->form_validation->set_rules('first_name', 'First Name', 'required');
            // $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            // $this->form_validation->set_rules('instructor_id', 'Instructor', 'numeric');
            // foreach ($extra_fields as $field) {
            //     $this->form_validation->set_rules($field->id, $field->label, 'required');

            if ($this->form_validation->run() == false) {
                // $this->db = $this->load->database('default', TRUE);
                // $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
            }
        }

        set_title('Import Students');
        $this->load->view('admin/student-import', compact('instructors', 'extra_fields', 'programs'));
    }

    public function upload_csv()
    {
        $config['upload_path'] = UPLOAD_FOLDER_ADMIN_CSV;
        $config['allowed_types'] = 'txt|csv';
        $config['max_size'] = 50000;
        $config['overwrite'] = TRUE;
        $config['file_name'] = url_title(display_name($this->member) . '-' . date('Y-m-d His'));

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('csv')) {
            $error = array('error' => $this->upload->display_errors());
        } else {
            $data = $this->upload->data();
            return $data['file_name'];
        }
        return $error;
    }

    public function mass_create()
    {
        $users = array(
            array('Erika','Test','','erika+student1111@omaha.org','1','11th Grade'),
            array('Nate','Test','','nwork+student@aiminstitute.org','1','11th Grade'),
            array('Jonathan','Test','','jonathanh+student@aiminstitute.org','1','11th Grade'),
        );

        foreach ($users as $user) {
            $password = base64_encode(random_bytes(12));
            $school_id = $user[4];
            $data = array(
                'email' => $user[3],
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'first_name' => $user[0],
                'last_name' => $user[1],
                'created_by' => $this->member->id,
            );
        }
    }

    public function email_new_student($email, $password)
    {
        $logo = base_url('img/logo-white.png');
        $login_url = base_url();
        $message = <<<EOD
<!DOCTYPE html>
<html>
<body>
<img src="$logo">
<p>A new account has been created for you for the AIM Participant Portal.</p>
<p>Here are your initial login details:</p>
<table>
<tr>
<td><b>Username</b></td>
<td><b>Password</b></td>
</tr>
<tr>
<td>$email</td>
<td>$password</td>
</tr>
<p>Please change your password after logging in.</p>
<p><a href="$login_url">Go to AIM Participant Portal</a></p>
</body>
</html>
EOD;

        $this->load->library('email');
        $this->email->to($email);
        $this->email->from('noreply@aiminstitute.org', 'AIM Participant Portal');
        $this->email->subject('AIM Participant Portal - Your Account Has Been Created');
        $this->email->message($message);
        return $this->email->send();
    }

    public function email_instructor($data, $instructor_id)
    {
        $instructor = $this->instructor_model->get($instructor_id);
        if (empty($instructor) || empty($data)) {
            return;
        }

        $logo = base_url('img/logo-white.png');
        $login_url = base_url();
        $message = <<<EOD
<!DOCTYPE html>
<html>
<body>
<img src="$logo">
<p>Student account(s) have been created for the AIM Participant Portal.</p>
<p>You are assigned as the instructor of these students.</p>
<p>The following is a list of student details that they may use to log in to the portal:</p>
<table>
<tr><th>Email</th><th>Password</th></tr>
EOD;

        foreach ($data as $details) {
            $message .= "<tr><td>{$details[0]}</td><td>{$details[1]}</td></tr>";
        }

        $message .= <<<EOD
</table>
<p>Students should change their passwords after logging in.</p>
<p><a href="$login_url">Go to AIM Participant Portal</a></p>
</body>
</html>
EOD;

        $this->load->library('email');
        $this->email->to($instructor->email);
        $this->email->from('noreply@aiminstitute.org', 'AIM Participant Portal');
        $this->email->subject('AIM Participant Portal - Student Accounts Have Been Created');
        $this->email->message($message);
        return $this->email->send();
    }
}
