<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contact extends Member_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('contact_model');
        $this->load->library('email');
    }

    public function index()
    {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('type', 'Last Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required');
            $this->form_validation->set_rules('message', 'Message', 'required');

            if ($this->form_validation->run() !== false) {
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'type' => $this->input->post('type'),
                    'email' => $this->input->post('email'),
                    'message' => $this->input->post('message'),
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                );
                $this->contact_model->create($data);
                $this->email_staff($data);
                $this->session->set_flashdata('success', 'Contact form submitted - Thank you for contacting us.');
                redirect('contact');
            } else {
                $this->session->set_flashdata('error', 'Contact form not submitted - Please fill in all the required fields.');
            }
        }
        $this->load->view('contact');
    }

    public function email_staff($data)
    {
        $message = "<table>";
        foreach ($data as $key => $row) {
            $message .= "<tr>";
            $message .= "<td>" . $key . "</td>";
            $message .= "<td>" . $row . "</td>";
            $message .= "</tr>";
        }
        $message .= "</table>";

        // $this->email->from('noreply@careerlink.com', 'ATS');
        $this->email->to('erika@omaha.org');
        $this->email->subject('Member Portal Contact Form Submission');
        $this->email->message($message);
        $this->email->send();
    }
}
