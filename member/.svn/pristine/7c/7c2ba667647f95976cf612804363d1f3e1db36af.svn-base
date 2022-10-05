<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Offering_category extends Staff_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('offering_category_model');
    }

    public function index()
    {
        $this->all();
    }

    public function all()
    {
        $categories = $this->offering_category_model->get_all();
        set_title('Categories');
        $this->load->view('admin/offering-category-list', compact('categories'));
    }

    public function create()
    {
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
                $category_id = $this->offering_category_model->add($data);

                if ($category_id) {
                    $this->session->set_flashdata('success', 'Category successfully created.');
                    redirect('admin/offering_category');
                } else {
                    $this->session->set_flashdata('error', 'The category could not be created.');
                }
            }
        }

        set_title('New Category');
        $this->load->view('admin/offering-category-form');
    }    

    public function edit($category_id)
    {
        $category = $this->offering_category_model->get($category_id);

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
                $updated = $this->offering_category_model->update($category_id, $data);

                $this->session->set_flashdata('success', 'Category updated.');
                redirect("admin/offering_category");
            }
        }

        set_title('Edit Category');
        $this->load->view('admin/offering-category-form', compact('category'));
    }    

    public function remove($category_id)
    {
        $category = $this->offering_category_model->get($category_id);
        $deleted = $this->offering_category_model->remove($category_id, $this->member->id);
        if ($deleted) {
            $this->session->set_flashdata('success', 'Category has been removed.');
        } else {
            $this->session->set_flashdata('error', 'Category could not be removed.');
        }
        redirect("admin/offering_category");
    }
}