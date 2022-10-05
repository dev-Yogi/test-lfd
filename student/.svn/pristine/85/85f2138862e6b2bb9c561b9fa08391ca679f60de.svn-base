<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends Student_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('search_model');
    }

    public function index()
    {
        $results = array();
        $keywords = $this->input->post('keywords');
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('keywords', 'Search term', 'required|trim|strip_tags');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Please enter a valid keyword in the search.');
            } else {
                $results = $this->search_model->run($this->input->post());
            }
        }

        set_title('lessons');
        $this->load->view('search', compact('results', 'keywords'));
    }
}
