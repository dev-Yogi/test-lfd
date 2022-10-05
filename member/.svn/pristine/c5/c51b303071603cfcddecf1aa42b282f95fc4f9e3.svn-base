<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Eoc extends Staff_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('eoc_model');
        $this->load->helper('text');
    }

    public function index()
    {
        $submissions = $this->eoc_model->get_all();
        set_title("EOC Submissions");
        $this->load->view('admin/eoc-list', compact('submissions'));
    }

    public function view($id)
    {
        $submission = $this->eoc_model->get($id);
        $submission->parent_1_degree = $submission->parent_1_degree == 1 ? 'Yes' : ($submission->parent_1_degree == 0 ? 'No' : 'N/A');
        $submission->parent_2_degree = $submission->parent_2_degree == 1 ? 'Yes' : ($submission->parent_2_degree == 0 ? 'No' : 'N/A');
        set_title("View EOC Submission");
        $this->load->view('admin/eoc-view', compact('submission'));
    }

}
