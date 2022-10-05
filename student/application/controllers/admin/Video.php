<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends Instructor_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('date');
        $this->load->model('video_model');
        $this->load->model('lesson_model');
        $this->load->model('course_model');
    }

    public function create($lesson_id)
    {
        $lesson = $this->lesson_model->get($lesson_id);

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('label', 'Label', 'required|trim|max_length[250]|strip_tags');
            $this->form_validation->set_rules('description', 'Description', 'trim|max_length[250]|strip_tags');
            $this->form_validation->set_rules('url', 'URL', 'required|trim|valid_url');

            $is_live_conference = $this->input->post('is_live_conference');
            if ($is_live_conference) {
                $start_date = $this->input->post('start_date');
                $start_time_hour = $this->input->post('start_time_hour');
                $start_time_minute = $this->input->post('start_time_minute');
                $stop_time_hour = $this->input->post('stop_time_hour');
                $stop_time_minute = $this->input->post('stop_time_minute');
                $this->form_validation->set_rules('start_date', 'Start Date', 'required|trim|callback_date_valid');
                $this->form_validation->set_rules('start_time_hour', 'Hour', 'required|trim|numeric');
                $this->form_validation->set_rules('start_time_minute', 'Minute', 'required|trim|numeric');
                $this->form_validation->set_rules('stop_time_hour', 'Hour', 'required|trim|numeric');
                $this->form_validation->set_rules('stop_time_minute', 'Minute', 'required|trim|numeric');
            }

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                if ($is_live_conference) {
                    $start_time = date('Y-m-d H:i:s', strtotime("$start_date $start_time_hour:$start_time_minute"));
                    $stop_time = date('Y-m-d H:i:s', strtotime("$start_date $stop_time_hour:$stop_time_minute"));
                }
                $url = $this->input->post('url');
                if (substr($url, 0, 4) != 'http') {
                    $url = "https://" . $url;
                }
                $data = array(
                    'lesson_id' => $lesson_id,
                    'label' => $this->input->post('label'),
                    'description' => $this->input->post('description'),
                    'url' => $url,
                    'start_time' => $start_time ?? null,
                    'stop_time' => $stop_time ?? null,
                    'created_by' => $this->member->id
                );
                $video_id = $this->video_model->add($data);

                if ($video_id) {
                    $this->session->set_flashdata('success', 'Video successfully added.');
                    redirect("admin/video/list/$lesson_id");
                } else {
                    $this->session->set_flashdata('error', 'The video could not be added.');
                }
            }
        }

        set_title('New Video');
        $this->load->view('admin/video-form', compact('lesson'));
    }

    public function date_valid($date){
        $month = (int) substr($date, 0, 2);
        $day = (int) substr($date, 3, 2);
        $year = (int) substr($date, 6, 4);
        return checkdate($month, $day, $year);
    }

    public function list($lesson_id)
    {
        $videos = $this->video_model->get_all($lesson_id);
        $lesson = $this->lesson_model->get($lesson_id);
        set_title('Videos');
        $this->load->view('admin/video-list', compact('videos', 'lesson'));
    }

    public function edit($video_id)
    {
        $video = $this->video_model->get($video_id);
        $lesson = $this->lesson_model->get($video->lesson_id);
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('label', 'Label', 'required|trim|max_length[250]|strip_tags');
            $this->form_validation->set_rules('description', 'Description', 'trim|max_length[250]|strip_tags');
            $this->form_validation->set_rules('url', 'URL', 'required|trim|valid_url');

            $is_live_conference = $this->input->post('is_live_conference');
            if ($is_live_conference) {
                $start_date = $this->input->post('start_date');
                $start_time_hour = $this->input->post('start_time_hour');
                $start_time_minute = $this->input->post('start_time_minute');
                $stop_time_hour = $this->input->post('stop_time_hour');
                $stop_time_minute = $this->input->post('stop_time_minute');
                $this->form_validation->set_rules('start_date', 'Start Date', 'required|trim|callback_date_valid');
                $this->form_validation->set_rules('start_time_hour', 'Hour', 'required|trim|numeric');
                $this->form_validation->set_rules('start_time_minute', 'Minute', 'required|trim|numeric');
                $this->form_validation->set_rules('stop_time_hour', 'Hour', 'required|trim|numeric');
                $this->form_validation->set_rules('stop_time_minute', 'Minute', 'required|trim|numeric');
            }

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                if ($is_live_conference) {
                    $start_time = date('Y-m-d H:i:s', strtotime("$start_date $start_time_hour:$start_time_minute"));
                    $stop_time = date('Y-m-d H:i:s', strtotime("$start_date $stop_time_hour:$stop_time_minute"));
                }
                $url = $this->input->post('url');
                if (substr($url, 0, 4) != 'http') {
                    $url = "https://" . $url;
                }
                $data = array(
                    'label' => $this->input->post('label'),
                    'description' => $this->input->post('description'),
                    'url' => $url,
                    'start_time' => $start_time ?? null,
                    'stop_time' => $stop_time ?? null,
                    'modified_by' => $this->member->id,
                    'modified' => date('Y-m-d H:i:s')
                );
                $video_id = $this->video_model->update($video_id, $data);

                if ($video_id) {
                    $this->session->set_flashdata('success', 'Video successfully updated.');
                    redirect("admin/video/list/{$video->lesson_id}");
                } else {
                    $this->session->set_flashdata('error', 'The video could not be updated.');
                }
            }
        }

        set_title('Edit Video');
        $this->load->view('admin/video-form', compact('video', 'lesson'));
    } 

    public function remove($video_id)
    {
        $video = $this->video_model->get($video_id);
        $lesson = $this->lesson_model->get($video->lesson_id);
        $deleted = $this->video_model->remove($video_id, $this->member->id);
        if ($deleted) {
            $this->session->set_flashdata('success', 'Video has been removed.');
        } else {
            $this->session->set_flashdata('error', 'Video could not be removed.');
        }
        redirect("admin/video/list/{$video->lesson_id}");
    }
}
