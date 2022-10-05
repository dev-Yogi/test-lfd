<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends Staff_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('field_model');
    }

	public function index()
	{
        set_title('Settings');
        $this->load->view('admin/settings');
	}

    public function field($all = false)
    {
        if ($all) {
            $fields = $this->field_model->get_all();
            set_title('All Fields');
            $this->load->view('admin/field-list', compact('fields'));
        } else {
            $fields = $this->field_model->get_by_program($this->program->id);
            set_title('Fields');
            $this->load->view('admin/field-list-program', compact('fields'));
        }
    }

    public function field_add_to_program()
    {
        $fields = $this->field_model->get_all();
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('field_id', 'Field', 'required|trim');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $data = array(
                    'program_id' => $this->program->id,
                    'field_id' => $this->input->post('field_id'),
                    'created_by' => $this->member->id
                );
                $added = $this->field_model->add_to_program($data);

                if ($added) {
                    $this->session->set_flashdata('success', 'Field successfully added to the program.');
                    redirect("admin/settings/field");
                } else {
                    $this->session->set_flashdata('error', 'The field could not be added - does this program already have this field?');
                }
            }
        }

        set_title('Add Field to Program');
        $this->load->view('admin/field-form-program', compact('fields'));
    }

    public function field_remove_from_program($field_id)
    {
        $deleted = $this->field_model->remove_from_program($field_id, $this->program->id, $this->member->id);
        if ($deleted) {
            $this->session->set_flashdata('success', 'Field has been removed.');
        } else {
            $this->session->set_flashdata('error', 'Field could not be removed.');
        }
        redirect("admin/settings/field");
    }

    public function field_add()
    {
        $fields = $this->field_model->get_all();
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('label', 'Label', 'required|trim');
            $this->form_validation->set_rules('type', 'Type', 'required|trim');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $data = array(
                    'label' => $this->input->post('label'),
                    'type' => $this->input->post('type'),
                    'created_by' => $this->member->id
                );
                $field_id = $this->field_model->add($data);

                if ($field_id) {
                    $this->session->set_flashdata('success', 'Field successfully created. You may now add it to programs.');
                    redirect("admin/settings/field/all");
                } else {
                    $this->session->set_flashdata('error', 'The field could not be added.');
                }
            }
        }

        set_title('New Field');
        $this->load->view('admin/field-form');
    }

    public function field_edit($field_id)
    {
        $field = $this->field_model->get($field_id);
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('option_label', 'Label', 'required|trim');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $data = array(
                    'field_id' => $field_id,
                    'label' => $this->input->post('option_label'),
                    'created_by' => $this->member->id
                );
                $option_id = $this->field_model->add_option($data);

                if ($option_id) {
                    $this->session->set_flashdata('success', 'Option added.');
                    redirect("admin/settings/field_edit/$field_id");
                } else {
                    $this->session->set_flashdata('error', 'The option could not be added.');
                }
            }
        }

        set_title('Edit Field');
        $this->load->view('admin/field-form-edit', compact('field'));
    }

}
