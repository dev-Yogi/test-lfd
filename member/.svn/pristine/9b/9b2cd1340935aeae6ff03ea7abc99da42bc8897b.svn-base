<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->member = null;
        if ($this->session->userdata('id')) {
            $member_id = $this->session->userdata('id');
            $member = $this->member_model->get($member_id);
            $this->member_model->set_member_data($member);
            $this->member = $this->session->userdata('member');
        }
    }

    public function check_tag($tag_id)
    {
        if (!$this->tag_model->has_tag($this->member->id, $tag_id)) {
            $this->session->set_flashdata('error', 'You do not have permission to view this page.');
            redirect('fail/unauthorized');
        }
    }
}

class Member_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (empty($this->member)) {
            $redirect = str_replace(base_url(), '', current_url());
            $this->session->set_flashdata('info', 'Please log in.');
            redirect('user/login?redirect=' . $redirect);
        }
    }
}

class Staff_Controller extends Member_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->check_tag(Tag::STAFF);
    }
}

class Manager_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->check_tag(Tag::MANAGER);
    }
}

class Admin_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->check_tag(Tag::ADMIN);
    }
}
