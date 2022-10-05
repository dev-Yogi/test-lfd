<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Staff extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('staff_model');
    }

    public function index()
    {
        $this->all();
    }

    public function all()
    {
        $links = $this->staff_model->get_links();
        set_title("Staff Portal");
        $this->load->view('admin/staff/portal', compact('links'));
    }

    public function link_create()
    {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('url', 'URL', 'required|trim');
            $this->form_validation->set_rules('label', 'Label', 'required|trim');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $data = array(
                    'url' => $this->input->post('url'),
                    'label' => $this->input->post('label'),
                    'created_by' => $this->member->id
                );
                $link_id = $this->staff_model->create_link($data);

                if ($link_id) {
                    $this->session->set_flashdata('success', 'Link successfully created.');
                    redirect('admin/staff');
                } else {
                    $this->session->set_flashdata('error', 'The link could not be created.');
                }
            }
        }

        set_title('New Link');
        $this->load->view('admin/staff/link-form');
    }


    public function link_edit($link_id)
    {
        $link = $this->staff_model->get_link($link_id);
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('url', 'URL', 'required|trim');
            $this->form_validation->set_rules('label', 'Label', 'required|trim');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $data = array(
                    'url' => $this->input->post('url'),
                    'label' => $this->input->post('label'),
                    'modified_by' => $this->member->id,
                    'modified' => date('Y-m-d H:i:s')
                );
                $updated = $this->staff_model->update_link($link_id, $data);

                $this->session->set_flashdata('success', 'Link updated.');
                redirect('admin/staff');
            }
        }

        set_title('Edit Link');
        $this->load->view('admin/staff/link-form', compact('link'));
    }

    public function link_remove($link_id)
    {
        $deleted = $this->staff_model->remove_link($link_id, $this->member->id);
        if ($deleted) {
            $this->session->set_flashdata('success', 'Link has been removed.');
        } else {
            $this->session->set_flashdata('error', 'Link could not be removed.');
        }
        redirect("admin/staff");
    }

    public function set_order_links()
    {
        if ($this->input->method() == 'post') {
            $order = explode(",", $this->input->post('links_order'));
            if ($order) {
                foreach ($order as $key => $value) {
                    $data = array(
                        'order' => $key,
                        'modified_by' => $this->member->id,
                        'modified' => date('Y-m-d H:i:s')
                    );
                    $this->staff_model->update_link($value, $data);
                }
            }
            $this->session->set_flashdata('success', 'Link order has been updated.');
            redirect('admin/staff');
        }

        set_title('Offering Columns');
        $this->load->view('admin/offering-columns');
    }
}
