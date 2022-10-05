<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Eoc extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('eoc_model');
    }

    public function index()
    {
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
            $this->form_validation->set_rules('parent_1_degree', 'Parent 1 education information', 'trim|required');
            $this->form_validation->set_rules('parent_2_degree', 'Parent 2 education information', 'trim|required');
            $this->form_validation->set_rules('currently_enrolled_course_name', 'Currently enrolled course', 'trim');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Please provide an answer for all questions');
            } else {
                $data = [
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'parent_1_degree' => $this->input->post('parent_1_degree'),
                    'parent_2_degree' => $this->input->post('parent_2_degree'),
                    'currently_enrolled_course_name' => empty($this->input->post('currently_enrolled_course_name')) ? null : $this->input->post('currently_enrolled_course_name'),
                ];
                $id = $this->eoc_model->add($data);
                if ($id) {

                    $data['parent_1_degree'] = $data['parent_1_degree'] == 1 ? 'Yes' : ($data['parent_1_degree'] == 0 ? 'No' : 'N/A');
                    $data['parent_2_degree'] = $data['parent_2_degree'] == 1 ? 'Yes' : ($data['parent_2_degree'] == 0 ? 'No' : 'N/A');

                    $logo = base_url('img/email_logo.png');
                    $message = "<!DOCTYPE html><html><body>";
                    $message .= "<img src='$logo'>";
                    $message .= "<p>A new EOC submission has been made.</p>";
                    $message .= "<div><b>First Name</b></div>";
                    $message .= $data['first_name'];
                    $message .= "<div><b>Last Name</b></div>";
                    $message .= $data['last_name'];
                    $message .= "<div><b>Email</b></div>";
                    $message .= $data['email'];
                    $message .= "<div><b>Phone</b></div>";
                    $message .= $data['phone'];
                    $message .= "<div><b>Has parent/guardian 1 completed a 4-year (Bachelor's) Degree from a college/university?</b></div>";
                    $message .= $data['parent_1_degree'];
                    $message .= "<div><b>Has parent/guardian 2 completed a 4-year (Bachelor's) Degree from a college/university?</b></div>";
                    $message .= $data['parent_2_degree'];
                    $message .= "<div><b>Is the student currently enrolled in a college prep or other federal program? If yes, please list the name of the course. Otherwise, leave blank.</b></div>";
                    $message .= "<div><b>Phone</b></div>";
                    $message .= $data['currently_enrolled_course_name'] ?? "-";
                    $message .= "<br><br>";
                    $message .= anchor(base_url("admin/eoc/view/$id"), "View EOC Submission");
                    $message .= "</body></html>";
                    $this->load->library('email');
                    $this->email->from('noreply@aiminstitute.org', 'AIM Institute');
                    $this->email->to('eoc@aiminstitute.org');
                    $this->email->bcc('erika@omaha.org');
                    $this->email->subject('New EOC Submission');
                    $this->email->message($message);
                    $this->email->send();
                    redirect('eoc/thankyou');
                } else {
                    $this->session->set_flashdata('error', 'Sorry, there was an error submitting your form.');
                }
            }

        }
        $this->load->view('eoc/form');
    }

    public function thankyou()
    {
        $this->load->view('eoc/thankyou');
    }
}
