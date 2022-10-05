<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inbox extends Student_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('program_model');
        $this->load->library('form_validation');
    }

	public function index()
	{
		$messages = $this->message_model->get_threads($this->member->id);

        set_title('Inbox');
        $this->load->view('inbox', compact('messages'));
	}

	public function view($message_id)
	{
		$message = $this->message_model->get($this->member->id, $message_id);
        $this->message_model->mark_as_read($this->member->id, $message->id);
        if ($message->parent_id) {
            $message = $this->message_model->get($this->member->id, $message->parent_id);
            $message_id = $message->id;
        }

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('message', 'Message', 'required|trim|strip_tags');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Please enter a valid message.');
            } else {
                // Set target member - it's either going to be the target of the original thread or the creator
                $target_member_id = $message->member_id;
                if ($this->member->id == $target_member_id) {
                    $target_member_id = $message->created_by;
                }
                $data = array(
                    'member_id' => $target_member_id, 
                    'program_id' => $this->program->id,
                    'message' => $this->input->post('message'),
                    'parent_id' => $message_id,
                    'created_by' => $this->member->id
                );
                $message_id = $this->message_model->add($data);

                if ($message_id) {
                    $this->session->set_flashdata('success', 'Message has been sent.');
                    redirect("inbox/view/$message_id");
                } else {
                    $this->session->set_flashdata('error', 'Error - Message could not be sent.');
                }
            }
        }

        set_title($message->title ?? 'Message not found');
        $this->load->view('inbox-message', compact('message'));
	}

    public function mark_all_as_read()
    {
        $this->message_model->mark_all_as_read($this->member->id);
    }
}
