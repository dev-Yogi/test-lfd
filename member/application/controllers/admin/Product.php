<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product extends Staff_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('product_model');
    }

    public function index()
    {
        $products = $this->product_model->get_all();
        set_title('Products');
        $this->load->view('admin/products', compact('products'));
    }

    public function create()
    {
        $this->check_tag(Tag::MANAGER);
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('label', 'Label', 'required');
            $this->form_validation->set_rules('description', 'Description', 'required');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $data = array(
                    'label' => $this->input->post('label'),
                    'description' => $this->input->post('description'),
                    'price_annual' => $this->input->post('price_annual'),
                    'price_monthly' => $this->input->post('price_monthly'),
                    'price_one_time' => $this->input->post('price_one_time'),
                    'created_by' => $this->member->id
                );
                $product_id = $this->product_model->add($data);

                if ($product_id) {
                    $this->session->set_flashdata('success', 'The new product was created and can now be used in an invoice.');
                    redirect('admin/product');
                } else {
                    $this->session->set_flashdata('error', 'The product could not be created.');
                }
            }
        }

        set_title('New Product');
        $this->load->view('admin/product-form');
    }    

    public function edit($product_id)
    {
        $this->check_tag(Tag::MANAGER);
        $product = $this->product_model->get($product_id);

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('label', 'Label', 'required');
            $this->form_validation->set_rules('description', 'Description', 'required');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $data = array(
                    'label' => $this->input->post('label'),
                    'description' => $this->input->post('description'),
                    'price_annual' => $this->input->post('price_annual'),
                    'price_monthly' => $this->input->post('price_monthly'),
                    'price_one_time' => $this->input->post('price_one_time'),
                    'created_by' => $this->member->id
                );
                $product_id = $this->product_model->update($product_id, $data);

                if ($product_id) {
                    $this->session->set_flashdata('success', 'Product updated.');
                    redirect('admin/product');
                } else {
                    $this->session->set_flashdata('error', 'The product could not be updated.');
                }
            }
        }

        set_title('Edit Product');
        $this->load->view('admin/product-form', compact('product'));
    }

    public function remove($product_id)
    {
        $this->check_tag(Tag::MANAGER);
        $deleted = $this->product_model->remove($product_id, $this->member->id);
        if ($deleted) {
            $this->session->set_flashdata('success', 'Product has been removed.');
        } else {
            $this->session->set_flashdata('error', 'Product could not be removed.');
        }
        redirect('admin/product');
    }
}
