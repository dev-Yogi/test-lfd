<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends Instructor_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('category_model');
    }

    public function index()
    {
        $this->all();
    }

	public function all()
	{
        $categories = $this->category_model->get_all($this->program->id);
        set_title('Categories');
        $this->load->view('admin/category-list', compact('categories'));
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
                    'program_id' => $this->program->id,
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'created_by' => $this->member->id
                );
                $category_id = $this->category_model->add($data);

                if ($category_id) {
                    $this->session->set_flashdata('success', 'Category successfully created.');
                    redirect('admin/category');
                } else {
                    $this->session->set_flashdata('error', 'The category could not be created.');
                }
            }
        }

        set_title('New Category');
        $this->load->view('admin/category-form');
    }    

    public function edit($category_id)
    {
        $category = $this->category_model->get($category_id);

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Name', 'required|trim');
            $this->form_validation->set_rules('description', 'Description', 'required|trim');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $data = array(
                    'program_id' => $this->program->id,
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'modified_by' => $this->member->id,
                    'modified' => date('Y-m-d H:i:s')
                );
                $updated = $this->category_model->update($category_id, $data);

                $this->session->set_flashdata('success', 'Category updated.');
                redirect("admin/category");
            }
        }

        set_title('Edit Category');
        $this->load->view('admin/category-form', compact('category'));
    }    

    public function remove($category_id)
    {
        $category = $this->category_model->get($category_id);
        $deleted = $this->category_model->remove($category_id, $this->member->id);
        if ($deleted) {
            $this->session->set_flashdata('success', 'Category has been removed.');
        } else {
            $this->session->set_flashdata('error', 'Category could not be removed.');
        }
        redirect("admin/category");
    }
}
