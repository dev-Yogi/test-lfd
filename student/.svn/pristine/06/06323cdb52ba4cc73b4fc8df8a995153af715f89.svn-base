<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Help extends Student_Controller {

	public function index()
	{
        set_title('Help');
        $this->load->view('help/help');
	}

	public function faq()
	{
        set_title('FAQ');
        $this->load->view('help/faq');
	}

	public function contact()
	{
		$this->load->model('help_model');
        $this->load->library('form_validation');

		if ($this->input->method() == 'post') {
		    $this->form_validation->set_rules('message', 'Message', 'required|trim|strip_tags|min_length[10]');

		    if ($this->form_validation->run() == false) {
		        $this->session->set_flashdata('error', 'Invalid message.');
		    } else {
		        $data = array(
		            'message' => $this->input->post('message'),
		            'created_by' => $this->member->id
		        );
		        $contact_id = $this->help_model->add($data);
		        $this->notify($contact_id);

		        if ($contact_id) {
		            $this->session->set_flashdata('success', 'Thank you, we will send you an email reply shortly.');
		            redirect("help/contact");
		        } else {
		            $this->session->set_flashdata('error', 'Sorry, the message could not be submitted.');
		        }
		    }
		}
        set_title('Contact');
        $this->load->view('help/contact');
	}

	public function notify($contact_id)
	{
        $logo = base_url('img/logo-white.png');
        $url = base_url("admin/contact/view/$contact_id");
        $message = <<<EOD
<!DOCTYPE html>
<html>
<body>
<img src="$logo">
<p>A student has submitted a help request.</p>
<p>You may view it by clicking the link below.</p>
<p><a href="$url">View contact form submission</a></p>
</body>
</html>
EOD;

        $this->load->library('email');
        $this->email->to('bclements@aiminstitute.org');
        $this->email->bcc('erika@omaha.org');
        $this->email->from('noreply@aiminstitute.org', 'AIM Participant Portal');
        $this->email->subject('AIM Participant Portal - Student has submitted a question');
        $this->email->message($message);
        return $this->email->send();
	}
}
