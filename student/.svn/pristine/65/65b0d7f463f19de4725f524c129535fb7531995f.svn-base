<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends Student_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('video_model');
        $this->load->helper('date');
    }

    public function index()
    {
        $this->home();
    }

    public function home()
    {
        $videos = $this->video_model->get_conferences($this->program->id);
        $live_videos = array();
        foreach ($videos as $key => $video) {
            if (strtotime('now') > strtotime($video->start_time) && strtotime('now') < strtotime($video->stop_time)) {
                $live_videos[] = $video;
                unset($videos[$key]);
            }
        }
        $pagination = paginate(count($videos));
        $videos = array_slice($videos, ($pagination->current - 1) * RESULTS_PER_PAGE, RESULTS_PER_PAGE);
        set_title('Videos');
        $this->load->view('videos', compact('videos', 'live_videos','pagination'));
    }

    public function view($post_id)
    {
    }
}
