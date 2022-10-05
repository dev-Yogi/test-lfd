<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Me extends Member_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('member_model');
        $this->load->library('form_validation');
    }

    public function index()
	{
		$this->general();
	}

    public function general()
    {
        $user = $this->member_model->get($this->member->id);
        
        set_title('My Account');
        $this->load->view('me/general', compact('user'));
    }

    public function password()
    {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('current_password', 'Current Password', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('password2', 'Password Confirmation', 'required|matches[password]');

            if ($this->form_validation->run() !== FALSE) {
                $password = $this->input->post('password');
                $user = $this->member_model->log_in($this->member->email, $this->input->post('current_password'));
                if ($user) {
                    $data = array('password' => password_hash($password, PASSWORD_DEFAULT));
                    $update = $this->member_model->update($user->id, $data);
                    if ($update) {
                        $this->session->set_flashdata('success', 'Password has been updated.');
                    } else {
                        $this->session->set_flashdata('success', 'Error updating password.');
                    }
                    redirect('me');
                } else {
                    $this->session->set_flashdata('error', 'Incorrect current password.');
                }
            }
        }

        set_title('Change Password');
        $this->load->view('me/password');
    }

    public function edit()
    {
        $member = $this->member;
        if($this->input->method() == 'post') {
            if ($this->member->email != $this->input->post('email')) {
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[members.email]');
            }
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');

            if ($this->form_validation->run() !== FALSE)
            {
                $data = array(
                    'email' => $this->input->post('email'),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'modified_by' => $this->member->id
                );
                $this->member_model->update($this->member->id, $data);
                $member = $this->member_model->get($this->member->id);
                $this->member_model->set_member_data($member);
                $this->session->set_flashdata('success', 'Account has been updated.');
                redirect('me');
            } else {
                $this->session->set_flashdata('error', 'Please fill in all fields.');
            }
        }
        $this->load->view('me/edit', compact('member'));
    }
}
