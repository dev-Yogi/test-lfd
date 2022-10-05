<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Video_model extends CI_Model
{
    public function get($id)
    {
        $this->db->select('videos.*');
        $this->db->where('id', $id);
        $this->db->from('videos');

        $video = $this->db->get()->first_row();
        return $video;
    }

    public function get_conferences($program_id)
    {
        $this->db->select('videos.*');
        $this->db->where('videos.removed', 0);
        $this->db->where('courses.status', 'published');
        $this->db->where('programs_courses.program_id', $program_id);
        $this->db->where('start_time IS NOT NULL');
        $this->db->where('stop_time >', date('Y-m-d H:i:s'));
        $this->db->order_by('start_time', 'ASC');
        $this->db->from('videos');

        $this->db->select('lessons.title as lesson_title, lessons.id as lesson_id');
        $this->db->join('lessons', 'lessons.id = videos.lesson_id', 'left');

        $this->db->select('courses.name as course_name, courses.id as course_id, courses.status');
        $this->db->join('courses', 'courses.id = lessons.course_id', 'left');

        $this->db->select('programs_courses.program_id');
        $this->db->join('programs_courses', 'courses.id = programs_courses.course_id', 'left');

        $videos = $this->db->get()->result();
        return $videos;
    }

    public function get_all($lesson_id = null)
    {
        $this->db->where('removed', 0);
        $this->db->from('videos');

        if ($lesson_id) {
            $this->db->where('lesson_id', $lesson_id);
        }

        $videos = $this->db->get()->result();
        return $videos;
    }
    
    public function add($data)
    {
        $this->db->insert('videos', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update('videos');
        return $this->db->affected_rows();
    }

    public function remove($video_id, $user_id)
    {
        $this->db->set('removed', 1);
        $this->db->set('modified_by', $user_id);
        $this->db->where('id', $video_id);
        $this->db->update('videos');
        return $this->db->affected_rows();
    }

    public function remove_for_lesson($lesson_id, $user_id)
    {
        $this->db->set('removed', 1);
        $this->db->set('modified_by', $user_id);
        $this->db->where('lesson_id', $lesson_id);
        $this->db->update('videos');
        return $this->db->affected_rows();
    }

    public function copy_all($from_lesson_id, $to_lesson_id)
    {
        if (empty($from_lesson_id)) {
            return;
        }
        $videos = $this->get_all($from_lesson_id);
        foreach ($videos as $video) {
            $data = array(
                'lesson_id' => $to_lesson_id,
                'label' => $video->label,
                'description' => $video->description,
                'url' => $video->url,
                'start_time' => $video->start_time,
                'stop_time' => $video->stop_time,
                'created_by' => $this->member->id
            );
            $this->add($data);
        }
    }
}
