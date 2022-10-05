<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('member_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->load->view('home');
    }

    public function signup()
    {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[members.email]', array('is_unique' => 'This email already belongs to an account.'));
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('password2', 'Password Confirmation', 'required|matches[password]');
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('agreed_terms', 'Terms', 'required', array('required' => 'Tick the box if you agree to the terms & conditions.'));
            $this->form_validation->set_rules('g-recaptcha-response', 'Recaptcha', 'trim|callback_recaptcha_check');

            if ($this->form_validation->run() !== FALSE) {
                $data = array(
                    'email' => $this->input->post('email'),
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                );
                $id = $this->member_model->create($data);
                $member = $this->member_model->log_in($data['email'], $this->input->post('password'));
                if ($member) {
                    $this->member_model->set_member_data($member);
                    $this->redirect_after_login($member->id);
                } else {
                    $this->session->set_flashdata('error', 'Failed to create user account.');
                    redirect('user/signup');
                }
            }
        }
        set_title('Sign Up');
        $this->load->view('user/signup');
    }

    public function login()
    {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('email', 'Email', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() !== FALSE) {
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                $member = $this->member_model->log_in($email, $password);
                if ($member) {

                    $this->member_model->set_member_data($member);
                    $this->member_model->record_login('student', $member->id);
                    $this->redirect_after_login($member->id);
                } else {
                    $this->session->set_flashdata('error', 'Invalid login.');
                    redirect('user/login');
                }
            }
        }
        set_title('Log In');
        $this->load->view('user/login');
    }

    public function redirect_after_login($member_id)
    {
        if ($this->input->get('redirect')) {
            redirect($this->input->get('redirect'));
        }
        if (!$this->member_model->has_tag($member_id, Tag::STAFF) && $this->member_model->has_tag($member_id, Tag::STUDENT)) {
            redirect('http://' . $_SERVER['HTTP_HOST'] . '/student');
            return;
        }
        redirect('/');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->member = null;
        redirect('/');
    }

    public function forgot_password()
    {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('email', 'Email', 'required');
            $this->form_validation->set_rules('g-recaptcha-response', 'Recaptcha', 'trim|callback_recaptcha_check');

            if ($this->form_validation->run() !== FALSE) {
                $email = $this->input->post('email');
                $this->member_model->email_reset_link($email);
                $this->session->set_flashdata('info', 'If there is an account associated with this email, a reset link has been sent.');
                redirect('user/forgot_password');
            }
        }
        set_title('Forgot Password');
        $this->load->view('user/forgot_password');
    }

    public function reset_password($token)
    {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('password2', 'Password Confirmation', 'required|matches[password]');

            if ($this->form_validation->run() !== FALSE) {
                $user = $this->member_model->get_by_reset_token($token);
                if ($user) {
                    $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                    if ($this->member_model->set_password($user->id, $password)) {
                        $this->session->set_flashdata('success', 'Password has been reset. You may now log in.');
                    } else {
                        $this->session->set_flashdata('error', 'Password reset failed.');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Reset request has expired or is invalid.');
                }
                redirect('user/login');
            }
        }
        set_title('Reset Password');
        $this->load->view('user/reset_password');
    }

    public function recaptcha_check($recaptcha)
    {
        $this->load->model('recaptcha_model');
        if (!$this->recaptcha_model->validate($recaptcha)) {
            $this->form_validation->set_message('recaptcha_check', 'Please complete the captcha');
            return false;
        }
        return true;
    }
}
