<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Program extends Instructor_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('date');
        $this->load->model('program_model');
    }

    public function index()
    {
        $programs = $this->program_model->get_all();
        set_title('Programs');
        $this->load->view('admin/program-list', compact('programs'));
    }

    public function create()
    {
        $this->check_tag(Tag::STAFF);
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Name', 'required|trim');
            $this->form_validation->set_rules('description', 'Description', 'required|trim');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $data = array(
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'created_by' => $this->member->id
                );
                $program_id = $this->program_model->add($data);

                if ($program_id) {
                    $this->session->set_flashdata('success', 'Program successfully created.');
                    redirect('admin/program');
                } else {
                    $this->session->set_flashdata('error', 'The program could not be created.');
                }
            }
        }

        set_title('New Program');
        $this->load->view('admin/program-form');
    }

    public function edit($program_id)
    {
        $this->check_tag(Tag::STAFF);
        $program = $this->program_model->get($program_id);

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Name', 'required|trim');
            $this->form_validation->set_rules('description', 'Description', 'required|trim');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $data = array(
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'modified_by' => $this->member->id,
                    'modified' => date('Y-m-d H:i:s')
                );
                $updated = $this->program_model->update($program_id, $data);

                $this->session->set_flashdata('success', 'Program updated.');
                redirect("admin/program");
            }
        }

        set_title('Edit Program');
        $this->load->view('admin/program-form', compact('program'));
    }
}
