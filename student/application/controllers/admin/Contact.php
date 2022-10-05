<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends Instructor_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('help_model');
    }

	public function index()
	{
        $submissions = $this->help_model->get_all();
        set_title('Contact Form Submissions');
        $this->load->view('admin/contact-list', compact('submissions'));
	}

    public function view($id)
    {
        $submission = $this->help_model->get($id);
        set_title('Contact Form Submissions');
        $this->load->view('admin/contact-view', compact('submission'));
    }
}
