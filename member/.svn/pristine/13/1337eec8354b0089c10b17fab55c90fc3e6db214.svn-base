<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meeting extends Member_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('member_model');
        $this->load->library('form_validation');
    }

    public function index()
	{
		$this->meeting();
	}

    public function meeting()
    {
        $user = new stdClass();
        $user->first_name = 'Erika';
        $user->last_name = 'Dempster';
        $user->title = 'Software Developer';
        $user->calendly_url = 'https://calendly.com/toaster/meeting';
        $user->avatar = base_url('img/profile.png');

        $users = array(
            $user,
            $user,
            $user,
            $user
        );
        
        set_title('Organize A Meeting');
        $this->load->view('meeting', compact('users'));
    }
}
