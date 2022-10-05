<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Staff_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        set_title('Dashboard');
        $this->load->view('admin/dashboard');
    }

    public function featured_employers()
    {
        $featured = $this->employer_model->get_featured();
        $employers = $this->employer_model->get_all();

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('employer_id', 'Employer', 'required');
            if ($this->form_validation->run() !== false) {
                $data = array(
                    'employer_id' => $this->input->post('employer_id'),
                    'created_by' => $this->member->id
                );
                $this->employer_model->add_featured($data);
                $this->session->set_flashdata('success', 'Featured employer added');
                redirect('admin/dashboard/featured_employers');
            }
        }

        set_title('Featured Employers');  
        $this->load->view('admin/featured_employers', compact('featured', 'employers'));
    }

    public function remove_featured_employer($employer_id)
    {
        $this->employer_model->remove_featured($employer_id, $this->member->id);
        $this->session->set_flashdata('success', 'Featured employer removed');
        redirect('admin/dashboard/featured_employers');
    }

    public function contact_form()
    {
        $this->load->model('contact_model');
        $submissions = $this->contact_model->get_all();

        set_title('Contact Form');
        $this->load->view('admin/contact_form', compact('submissions'));
    }

    public function staff_users()
    {
        $staff = $this->member_model->get_by_role_id(Role::STAFF);
        $managers = $this->member_model->get_by_role_id(Role::MANAGER);
        $admins = $this->member_model->get_by_role_id(Role::ADMIN);
        $users = $this->users;

        if ($this->input->method() == 'post') {
            $this->role_check(Role::MANAGER);
            $this->form_validation->set_rules('member_id', 'User', 'required');
            $this->form_validation->set_rules('role_id', 'Role', 'required|numeric');
            if ($this->form_validation->run() !== false) {
                $data = array(
                    'role_id' => $this->input->post('role_id'),
                    'modified_by' => $this->member->id,
                );
                $member_id = $this->input->post('member_id');
                $this->member_model->update($member_id, $data);
                $this->session->set_flashdata('success', 'User updated');
                redirect('admin/dashboard/staff_users');
            } else {
                $this->session->set_flashdata('error', 'Missing required fields.');
            }
        }

        set_title('Staff Users');
        $this->load->view('admin/staff_users', compact('staff', 'managers', 'admins', 'members'));
    }

    public function staff_users_demote($member_id)
    {
        $this->role_check(Role::MANAGER);

        if ($this->member_model->has_role($member_id, Role::STAFF)) {
            $user = $this->member_model->get($member_id);
            $data = array(
                'role_id' => Role::STAFF,
                'modified_by' => $this->member->id,
            );
            $this->member_model->update($member_id, $data);
            $this->session->set_flashdata('success', 'User updated');
            redirect('admin/dashboard/staff_users');
        }
    }
}
