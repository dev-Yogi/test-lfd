<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forum extends Instructor_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->session->set_flashdata('info', 'Forums are no longer available.');
        redirect('/admin/dashboard');

        $this->load->library('form_validation');
        $this->load->helper('date');
        $this->load->model('forum_model');
    }

	public function index()
	{
        $threads = $this->forum_model->get_threads_for_admin($this->program->id, 5);
        $replies = $this->forum_model->get_replies_for_admin($this->program->id, 5);
        $banned_users = $this->forum_model->get_banned(5);
        set_title('Forum');
        $this->load->view('admin/forum-list', compact('threads', 'replies', 'banned_users'));
	}

    public function delete($post_id)
    {
        $post = $this->forum_model->get($post_id);
        if (empty($post) || !is_moderator()) {
            $this->session->set_flashdata('error', 'Invalid thread to edit.');
            redirect('admin/forum');
        }
        $removed = $this->forum_model->remove($post_id, $this->member->id);
        if ($removed) {
            $this->session->set_flashdata('success', 'The post has been successfully removed.');
        } else {
            $this->session->set_flashdata('error', 'Post could not be removed.');
        }
        redirect('admin/forum');
    }

    public function ban($member_id)
    {
        if (!is_moderator()) {
            $this->session->set_flashdata('error', 'You are not a moderator.');
            redirect('admin/forum');
        }
        $data = array(
            'banned' => 1,
            'banned_at' => date("Y-m-d H:i:s"),
            'banned_by' => $this->member->id
        );
        $banned = $this->forum_model->update_profile($member_id, $data);
        if ($banned) {
            $this->session->set_flashdata('success', 'The user has been banned.');
        } else {
            $this->session->set_flashdata('error', 'User could not be banned.');
        }
        redirect('admin/forum');
    }

    public function unban($member_id)
    {
        if (!is_moderator()) {
            $this->session->set_flashdata('error', 'You are not a moderator.');
            redirect('admin/forum');
        }
        $data = array(
            'banned' => 0,
            'banned_at' => date("Y-m-d H:i:s"),
            'banned_by' => $this->member->id
        );
        $unbanned = $this->forum_model->update_profile($member_id, $data);
        if ($unbanned) {
            $this->session->set_flashdata('success', 'The user has been unbanned.');
        } else {
            $this->session->set_flashdata('error', 'User could not be unbanned.');
        }
        redirect('admin/forum');
    }

    public function threads()
    {
        $threads = $this->forum_model->get_threads_for_admin($this->program->id);
        set_title('Forum Threads');
        $this->load->view('admin/forum-threads-list', compact('threads'));
    }

    public function replies()
    {
        $replies = $this->forum_model->get_replies_for_admin($this->program->id);
        set_title('Forum Replies');
        $this->load->view('admin/forum-replies-list', compact('replies'));
    }

    public function banned()
    {
        $banned_users = $this->forum_model->get_banned();
        set_title('Banned Users');
        $this->load->view('admin/forum-banned-list', compact('banned_users'));
    }
}
