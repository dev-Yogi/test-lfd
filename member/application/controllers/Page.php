<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends Member_Controller {

	public function index()
	{
        set_title('Page');
		$this->load->view('page');
	}
}
