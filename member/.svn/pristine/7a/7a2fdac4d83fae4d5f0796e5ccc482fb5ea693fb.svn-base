<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function index()
	{
		if ($this->member) {
			$data = array();
			if (has_tag(Tag::STAFF)) {
				$this->load->model('staff_model');
				$links = $this->staff_model->get_links();
				$data['staff_links'] = $links;
			}
			set_title('Welcome');
			$this->load->view('home', $data);
		} else {
			redirect('user/login');
		}
	}
}
