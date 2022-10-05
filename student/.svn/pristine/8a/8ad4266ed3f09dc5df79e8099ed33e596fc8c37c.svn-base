<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lesson_model extends CI_Model
{
    public function get($id)
    {
        $this->db->select('lessons.*');
        $this->db->where('id', $id);
        $this->db->where('lessons.removed', 0);
        $this->db->from('lessons');

        if (!empty($this->member->id)) {
            $this->db->select('lessons_students.status');
            $this->db->join('lessons_students', "lessons_students.lesson_id = lessons.id AND lessons_students.student_id = {$this->member->id}", 'left');
        }

        $lesson = $this->db->get()->first_row();

        if ($lesson) {
            $lesson->instructors = $this->course_model->get_instructors($lesson->course_id);
        }
        return $lesson;
    }
    
    public function add($data)
    {
        $this->db->insert('lessons', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update('lessons');
        return $this->db->affected_rows();
    }

    public function remove($lesson_id, $user_id)
    {
        $this->load->model('video_model');
        $this->load->model('assignment_model');

        $this->db->set('removed', 1);
        $this->db->set('modified_by', $user_id);
        $this->db->set('modified', date("Y-m-d H:i:s"));
        $this->db->where('id', $lesson_id);
        $this->db->update('lessons');
        $removed = $this->db->affected_rows();

        $videos = $this->video_model->remove_for_lesson($lesson_id, $this->member->id);
        $assignments = $this->assignment_model->remove_for_lesson($lesson_id, $this->member->id);

        return $removed;
    }

    public function get_progress($lesson_id, $student_id)
    {
        $this->db->where('student_id', $student_id);
        $this->db->where('lesson_id', $lesson_id);
        $this->db->from('lessons_students');
        $course = $this->db->get()->first_row();
        return $course;
    }

    public function start($lesson_id, $student_id)
    {
        if ($this->get_progress($lesson_id, $student_id)) {
            return;
        }
        
        $data = array(
            'lesson_id' => $lesson_id,
            'student_id' => $student_id,
            'status' => 'started',
            'created_by' => $this->member->id,
        );
        $this->db->insert('lessons_students', $data);
        return $lesson_id;
    }

    public function complete($lesson_id, $student_id)
    {
        $this->db->set('status', 'complete');
        $this->db->set('modified_by', $this->member->id);
        $this->db->set('completed_at', date("Y-m-d H:i:s"));
        $this->db->set('modified', date("Y-m-d H:i:s"));
        $this->db->where('lesson_id', $lesson_id);
        $this->db->where('student_id', $student_id);
        $this->db->update('lessons_students');
        return $this->db->affected_rows();
    }

    public function get_students_completed($lesson_id)
    {
        $this->db->select('lessons_students.*');
        $this->db->where('status', 'complete');
        $this->db->where('lesson_id', $lesson_id);
        $this->db->from('lessons_students');

        $this->db->select('members.first_name, members.last_name');
        $this->db->join(MEMBER_TABLE . '.members', 'lessons_students.student_id = members.id', 'left');
        $students = $this->db->get()->result();
        return $students;
    }

    public function get_students_started($lesson_id)
    {
        $this->db->select('lessons_students.*');
        $this->db->where('status', 'started');
        $this->db->where('lesson_id', $lesson_id);
        $this->db->from('lessons_students');

        $this->db->select('members.first_name, members.last_name');
        $this->db->join(MEMBER_TABLE . '.members', 'lessons_students.student_id = members.id', 'left');
        $students = $this->db->get()->result();
        return $students;
    }

}
