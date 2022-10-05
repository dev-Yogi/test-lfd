<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Assignment_model extends CI_Model
{
    public function get($id)
    {
        $this->db->select('assignments.*');
        $this->db->where('id', $id);
        $this->db->from('assignments');

        $assignment = $this->db->get()->first_row();
        return $assignment;
    }

    public function get_all($lesson_id = null)
    {
        $this->db->where('removed', 0);
        $this->db->from('assignments');

        if ($lesson_id) {
            $this->db->where('lesson_id', $lesson_id);
        }

        $assignments = $this->db->get()->result();
        return $assignments;
    }

    public function get_all_with_counts($lesson_id)
    {
        $this->db->select('assignments.*');
        $this->db->where('assignments.removed', 0);
        $this->db->where('lesson_id', $lesson_id);
        $this->db->from('assignments');

        $this->db->select('submissions.count');
        $this->db->join('(SELECT COUNT(*) AS count, assignment_id FROM assignments_students WHERE removed = 0 GROUP BY assignment_id) as submissions', 'assignments.id = submissions.assignment_id', 'left');

        $assignments = $this->db->get()->result();
        return $assignments;
    }

    public function get_students($lesson_id)
    {
        $this->db->select('assignments_students.*');
        $this->db->where('lesson_id', $lesson_id);
        $this->db->where('assignments_students.removed', 0);
        $this->db->from('assignments_students');

        $this->db->select('assignments.label, assignments.description, assignments.type, assignments.lesson_id');
        $this->db->join('assignments', 'assignments.id = assignments_students.assignment_id', 'left');

        $this->db->select('members.first_name, members.last_name, members.id as student_id');
        $this->db->join(MEMBER_TABLE . '.members', 'assignments_students.student_id = members.id', 'left');

        $this->db->group_by('assignments_students.student_id');
        $assignment = $this->db->get()->result();
        return $assignment;
    }

    public function get_all_submissions($assignment_id)
    {
        $this->db->select('assignments_students.*');
        $this->db->where('assignment_id', $assignment_id);
        $this->db->where('assignments_students.removed', 0);
        $this->db->from('assignments_students');

        $this->db->select('assignments.label, assignments.description, assignments.type');
        $this->db->join('assignments', 'assignments.id = assignments_students.assignment_id', 'left');

        $this->db->select('members.first_name, members.last_name');
        $this->db->join(MEMBER_TABLE . '.members', 'assignments_students.student_id = members.id', 'left');

        $assignment = $this->db->get()->result();
        return $assignment;
    }

    public function get_submission($assignment_id, $student_id)
    {
        $this->db->where('student_id', $student_id);
        $this->db->where('assignment_id', $assignment_id);
        $this->db->where('removed', 0);
        $this->db->from('assignments_students');
        $submission = $this->db->get()->first_row();
        return $submission;
    }

    public function has_submitted_to_lesson($lesson_id, $student_id)
    {
        $this->db->select('assignments_students.*');
        $this->db->where('student_id', $student_id);
        $this->db->where('lesson_id', $lesson_id);
        $this->db->where('assignments_students.removed', 0);
        $this->db->from('assignments_students');

        $this->db->select('assignments.label, assignments.lesson_id, assignments.description, assignments.type');
        $this->db->join('assignments', "assignments.id = assignments_students.assignment_id", 'left');
        $assignments = $this->db->get()->result();
        return $assignments;
    }
    
    public function add($data)
    {
        $this->db->insert('assignments', $data);
        return $this->db->insert_id();
    }
    
    public function submit($data)
    {
        $this->db->set('removed', 1);
        $this->db->set('modified_by', $data['created_by']);
        $this->db->where('student_id', $data['created_by']);
        $this->db->where('assignment_id', $data['assignment_id']);
        $this->db->update('assignments_students');

        $this->db->insert('assignments_students', $data);
    }

    public function update_feedback_id($assignment_id, $student_id, $message_id)
    {
        $data = array(
            'feedback_message_id' => $message_id,
            'modified_by' => $this->member->id,
        );
        $this->db->set($data);
        $this->db->where('assignment_id', $assignment_id);
        $this->db->where('student_id', $student_id);
        $this->db->update('assignments_students');
        return $this->db->affected_rows();
    }

    public function update($id, $data)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update('assignments');
        return $this->db->affected_rows();
    }

    public function remove($assignment_id, $user_id)
    {
        $this->db->set('removed', 1);
        $this->db->set('modified_by', $user_id);
        $this->db->where('id', $assignment_id);
        $this->db->update('assignments');
        return $this->db->affected_rows();
    }

    public function remove_for_lesson($lesson_id, $user_id)
    {
        $this->db->set('removed', 1);
        $this->db->set('modified_by', $user_id);
        $this->db->where('lesson_id', $lesson_id);
        $this->db->update('assignments');
        return $this->db->affected_rows();
    }

    public function copy_all($from_lesson_id, $to_lesson_id)
    {
        if (empty($from_lesson_id)) {
            return;
        }
        $assignments = $this->get_all($from_lesson_id);
        foreach ($assignments as $assignment) {
            $data = array(
                'lesson_id' => $to_lesson_id,
                'label' => $assignment->label,
                'description' => $assignment->description,
                'type' => $assignment->type,
                'created_by' => $this->member->id
            );
            $this->add($data);
        }
    }
}
