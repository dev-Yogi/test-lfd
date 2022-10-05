<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Program_model extends CI_Model
{
    public function get($id)
    {
        $this->db->select('programs.*');
        $this->db->where('id', $id);
        $this->db->from('programs');

        $program = $this->db->get()->first_row();
        return $program;
    }

    public function get_all()
    {
        $this->db->select('programs.*');
        $this->db->where('programs.removed', 0);
        $this->db->from('programs');

        $programs = $this->db->get()->result();
        return $programs;
    }

    public function get_by_member_id($member_id)
    {
        $this->db->select('program_id as id');
        $this->db->where('member_id', $member_id);
        $this->db->where('programs_members.removed', 0);
        $this->db->from('programs_members');

        $this->db->select('programs.name');
        $this->db->join('programs', 'programs.id = programs_members.program_id AND programs_members.removed = 0', 'left');

        $programs = $this->db->get()->result();
        return $programs;
    }
    
    public function add($data)
    {
        $this->db->insert('programs', $data);
        return $this->db->insert_id();
    }
    
    public function add_member_to_program($data)
    {
        $this->db->where('program_id', $data['program_id']);
        $this->db->where('member_id', $data['member_id']);
        $this->db->from('programs_members');
        $has_had_program = $this->db->get()->first_row();

        if ($has_had_program) {
            $data['removed'] = 0;
            $this->db->set($data);
            $this->db->where('program_id', $data['program_id']);
            $this->db->where('member_id', $data['member_id']);
            $this->db->update('programs_members');
        } else {
            if (!isset($data['created_by']) && isset($data['modified_by'])) {
                $data['created_by'] = $data['modified_by'];
            }
            unset($data['modified_by']);
            $this->db->insert('programs_members', $data);
            $insert_id = $this->db->insert_id();

            // Notification
            $program = $this->get($data['program_id']);
            $message = array(
                'member_id' => $data['member_id'], 
                'program_id' => $data['program_id'],
                'label' => "Welcome to the <b>{$program->name}</b> Program!",
                'title' => "Welcome to the <b>{$program->name}</b> Program!",
                'message' => "
                    Get started with the program by going to <b>Courses</b> and browsing the course catalogue! <br>
                    To start a course, click on <b>Start Course</b> on the course page.<br>
                    This will allow you to take the lessons in the course.<br><br>
                    If you need any help, click on <b>Help</b> in the menu to get in touch with us.
                ",
                'created_by' => $this->member->id
            );
            $this->message_model->add($message);

            return $insert_id;
        }
    }
    
    public function add_program_member_field($data)
    {
        $this->db->where('program_id', $data['program_id']);
        $this->db->where('member_id', $data['member_id']);
        $this->db->where('field_id', $data['field_id']);
        $this->db->from('students_programs_fields');
        $has_had_program = $this->db->get()->first_row();

        if ($has_had_program) {
            if (!isset($data['modified_by']) && isset($data['created_by'])) {
                $data['modified_by'] = $data['created_by'];
            }
            unset($data['created_by']);
            $this->db->set($data);
            $this->db->where('program_id', $data['program_id']);
            $this->db->where('member_id', $data['member_id']);
            $this->db->where('field_id', $data['field_id']);
            $this->db->update('students_programs_fields');
        } else {
            if (!isset($data['created_by']) && isset($data['modified_by'])) {
                $data['created_by'] = $data['modified_by'];
            }
            unset($data['modified_by']);
            $this->db->insert('students_programs_fields', $data);
            return $this->db->insert_id();
        }
    }
    
    public function remove_member_from_program($data)
    {
        if (empty($data['program_id']) || empty($data['member_id'])) {
            return;
        }
        $data['removed'] = 1;
        $this->db->set($data);
        $this->db->where('program_id', $data['program_id']);
        $this->db->where('member_id', $data['member_id']);
        $this->db->update('programs_members');
        return $this->db->affected_rows();
    }

    public function update($id, $data)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update('programs');
        return $this->db->affected_rows();
    }
}
