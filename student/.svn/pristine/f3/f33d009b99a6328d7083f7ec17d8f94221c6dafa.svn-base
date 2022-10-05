<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student_model extends CI_Model
{
    public function get_all($program_id)
    {
        $this->load->model('field_model');
        $extra_field_id = $this->field_model->get_by_program($this->program->id)[0]->id ?? null;

        $this->db->where('tag_id', Tag::STUDENT);
        $this->db->where('programs_members.program_id', $program_id);
        $this->db->where('members.removed', 0);
        $this->db->from(MEMBER_TABLE . '.members_tags');
        $this->db->order_by('members.last_name', 'asc');

        $this->db->select('members.first_name, members.last_name, members.created, members.id, members.last_login, members.removed');
        $this->db->join(MEMBER_TABLE . '.members', 'members.id = members_tags.member_id', 'left');

        if ($extra_field_id) {
            // Get the school or company
            $this->db->join('students_programs_fields', "members.id = students_programs_fields.member_id AND field_id = $extra_field_id", 'left');
            $this->db->select('fields_options.label as extra_field_value');
            $this->db->join('fields_options', "fields_options.field_id = $extra_field_id AND students_programs_fields.value = fields_options.id", 'left');
        }

        $this->db->select('programs_members.program_id, programs_members.instructor_id');
        $this->db->join('programs_members', 'members.id = programs_members.member_id', 'left');

        $students = $this->db->get()->result();
        return $students;
    }

    public function get_recent($limit = null)
    {
        $this->db->where('tag_id', Tag::STUDENT);
        $this->db->where('members.removed', 0);
        $this->db->from(MEMBER_TABLE . '.members_tags');
        $this->db->order_by('members.last_name', 'asc');
        if ($limit) {
            $this->db->limit($limit);
        }

        $this->db->select('members.id, members.first_name, members.last_name, members.created, members.id, members.last_login, members.removed');
        $this->db->join(MEMBER_TABLE . '.members', 'members.id = members_tags.member_id', 'left');

        // Get the school or company
        $this->db->select('students_programs_fields.value as school_id');
        $this->db->join('students_programs_fields', 'members.id = students_programs_fields.member_id AND field_id = 1', 'left');
        $this->db->select('fields_options.label as school_name');
        $this->db->join('fields_options', 'fields_options.field_id = 1 AND students_programs_fields.value = fields_options.id', 'left');

        $students = $this->db->get()->result();
        return $students;
    }

    public function get($id)
    {
        $this->db->where('members_tags.member_id', $id);
        $this->db->where('members_tags.removed', 0);
        $this->db->group_start();
        $this->db->where('tag_id', Tag::STUDENT);
        $this->db->or_where('tag_id', Tag::INSTRUCTOR);
        $this->db->group_end();
        $this->db->from(MEMBER_TABLE . '.members_tags');

        $this->db->select('members.first_name, members.last_name, members.created, members.id, members.email, members.last_login');
        $this->db->join(MEMBER_TABLE . '.members', 'members.id = members_tags.member_id', 'left');

        $this->db->select('programs_members.program_id, programs_members.instructor_id');
        $this->db->join('programs_members', 'members.id = programs_members.member_id AND programs_members.removed = 0', 'left');

        $member = $this->db->get()->first_row();
        if ($member) {
            $this->load->model('program_model');
            $this->load->model('field_model');
            $member->programs = $this->program_model->get_by_member_id($member->id);
            if (!empty($this->program->id)) {
                $member->extra_fields = $this->field_model->get_by_member($member->id, $this->program->id);
            }
        }
        return $member;
    }

    public function add($data, $program_data, $extra_fields)
    {
        $this->db = $this->load->database('member', TRUE);

        $this->db->insert('members', $data);
        $id = $this->db->insert_id();
        $program_id = $program_data['program_id'];

        $this->add_tag_to_member($id, Tag::STUDENT);
        if ($program_id == 1) {
            $this->add_tag_to_member($id, Tag::UPWARD_BOUND);
        }

        $this->db = $this->load->database('default', TRUE);

        foreach ($extra_fields as $field) {
            $students_programs_fields_data = array(
                'program_id' => $program_id,
                'member_id' => $id,
                'field_id' => $field['field_id'],
                'value' => $field['value'],
                'created_by' => $this->member->id
            );
            $this->db->insert('students_programs_fields', $students_programs_fields_data);
        }
        
        $programs_members_data = array(
            'member_id' => $id,
            'program_id' => $program_data['program_id'],
            'instructor_id' => $program_data['instructor_id'],
            'created_by' => $this->member->id
        );
        $this->program_model->add_member_to_program($programs_members_data);

        return $id;
    }

    public function update($id, $data, $program_data, $extra_fields)
    {
        $program_id = $program_data['program_id'];

        $this->db = $this->load->database('member', TRUE);

        $this->db->where('id', $id);
        $this->db->update('members', $data);

        $this->db = $this->load->database('default', TRUE);

       foreach ($extra_fields as $field) {
            $this->db->where('program_id', $program_id);
            $this->db->where('member_id', $id);
            $this->db->where('field_id', $field['field_id']);
            $this->db->delete('students_programs_fields');

            $students_programs_fields_data = array(
                'program_id' => $program_id,
                'member_id' => $id,
                'field_id' => $field['field_id'],
                'value' => $field['value'],
                'created_by' => $this->member->id
            );
            $this->db->insert('students_programs_fields', $students_programs_fields_data);
        }

        $programs_members_data = array(
            'program_id' => $program_data['program_id'],
            'instructor_id' => $program_data['instructor_id'],
            'modified_by' => $this->member->id,
            'modified' => date('Y-m-d H:i:s')
        );
        $this->db->where('member_id', $id);
        $this->db->update('programs_members', $programs_members_data);

        return true;
    }


    public function get_schools()
    {
        $this->db->where('removed', 0);
        $this->db->order_by('name', 'asc');
        $this->db->from('schools');
        $schools = $this->db->get()->result();
        return $schools;
    }

    public function get_member_by_email($email)
    {
        $this->db = $this->load->database('member', TRUE);
        $this->db->where('email', $email);
        $this->db->from('members');
        $member = $this->db->get()->first_row();

        $this->db = $this->load->database('default', TRUE);
        return $member;
    }

    public function add_tag_to_member($member_id, $tag_id)
    {
        $this->db = $this->load->database('member', TRUE);
        $data = array(
            'member_id' => $member_id,
            'tag_id' => $tag_id,
            'created_by' => $this->member->id
        );
        $this->db->insert('members_tags', $data);
        $tagged = $this->db->insert_id();
        $this->db = $this->load->database('default', TRUE);
    }
}
