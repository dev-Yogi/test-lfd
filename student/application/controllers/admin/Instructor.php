<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instructor extends Instructor_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('instructor_model');
        $this->load->model('course_model');
        $this->load->model('forum_model');
    }

	public function index()
	{
        $instructors = $this->instructor_model->get_all();
        set_title('Instructors');
        $this->load->view('admin/instructor-list', compact('instructors'));
	}

    public function create()
    {
        $this->check_tag(Tag::STAFF);
        if ($this->input->method() == 'post') {
            $this->db = $this->load->database('member', TRUE);
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[members.email]', array('is_unique' => 'This email already belongs to an account.'));
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');

            if ($this->form_validation->run() == false) {
                $this->db = $this->load->database('default', TRUE);
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $this->db = $this->load->database('default', TRUE);
                $password = base64_encode(random_bytes(12));
                $data = array(
                    'email' => $this->input->post('email'),
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'created_by' => $this->member->id,
                );
                $member_id = $this->instructor_model->add($data);
                $email_sent = $this->email_new_instructor($data['email'], $password);

                if ($member_id) {
                    if ($email_sent) {
                        $this->session->set_flashdata('success', 'Instructor successfully created.');
                        redirect('admin/instructor');
                    } else {
                        $this->session->set_flashdata('warning', 'The instructor was successfully created, but the registration email could not be sent. The user may log in by using the "Forgot Password" link.');
                        redirect('admin/instructor');
                    }
                } else {
                    $this->session->set_flashdata('error', 'The instructor could not be created.');
                }
            }
        }

        set_title('New Instructor');
        $this->load->view('admin/instructor-form');
    }

    public function email_new_instructor($email, $password)
    {
        $logo = base_url('img/logo-white.png');
        $login_url = base_url();
        $message = <<<EOD
<!DOCTYPE html>
<html>
<body>
<img src="$logo">
<p>A new account has been created for use in AIM's Participant Portal.</p>
<p>Here are your initial login details for the Participant Portal:</p>
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

    public function edit($instructor_id)
    {
        $this->check_tag(Tag::STAFF);
        $instructor = $this->instructor_model->get($instructor_id);
        if (empty($instructor)) {
            $this->session->set_flashdata('error', 'Invalid instructor.');
            redirect('admin/instructor');
        }
        if ($this->input->method() == 'post') {
            $this->db = $this->load->database('member', TRUE);

            if ($instructor->email != $this->input->post('email')) {
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[members.email]', array('is_unique' => 'This email already belongs to an account.'));
            }
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');

            if ($this->form_validation->run() == false) {
                $this->db = $this->load->database('default', TRUE);
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $this->db = $this->load->database('default', TRUE);
                $data = array(
                    'email' => $this->input->post('email'),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'modified_by' => $this->member->id,
                    'modified' => date('Y-m-d H:i:s')
                );
                $member_id = $this->instructor_model->update($instructor->id, $data);

                if ($member_id) {
                    $this->session->set_flashdata('success', 'Instructor successfully updated.');
                    redirect('admin/instructor');
                } else {
                    $this->session->set_flashdata('error', 'The instructor could not be created.');
                }
            }
        }

        set_title('Edit Instructor');
        $this->load->view('admin/instructor-form', compact('instructor'));
    }
}
