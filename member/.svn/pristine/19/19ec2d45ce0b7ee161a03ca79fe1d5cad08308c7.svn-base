<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fail extends MY_Controller
{
    public function unauthorized()
    {
        set_title('Not Authorized');
        $this->output->set_status_header(401);
        $this->load->view('fail/401');
    }

    public function notfound()
    {
        set_title('Not Found');
        $this->output->set_status_header(404);
        $this->load->view('fail/404');
    }
}