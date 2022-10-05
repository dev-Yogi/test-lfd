<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Field_model extends CI_Model
{
    public function get($id)
    {
        $this->db->select('fields.*');
        $this->db->where('id', $id);
        $this->db->from('fields');

        $field = $this->db->get()->first_row();

        if ($field->type == 'select') {
            $options = $this->get_options($field->id);
            $field->options = $options;
        }
        return $field;
    }

    public function get_all()
    {
        $this->db->where('removed', 0);
        $this->db->from('fields');

        $fields = $this->db->get()->result();
        return $fields;
    }

    public function get_options($field_id)
    {
        $this->db->where('removed', 0);
        $this->db->from('fields_options');
        $this->db->where('field_id', $field_id);

        $options = $this->db->get()->result();
        return $options;
    }

    public function get_by_program($program_id)
    {
        $this->db->where('removed', 0);
        $this->db->from('programs_fields');
        $this->db->where('program_id', $program_id);
        $program_fields = $this->db->get()->result();

        foreach ($program_fields as $key => $program_field) {
            $field = $this->get($program_field->field_id);
            $program_fields[$key]->id = $field->id;
            $program_fields[$key]->label = $field->label;
            $program_fields[$key]->type = $field->type;
            if ($field->type == 'select') {
                $options = $this->get_options($field->id);
                $program_fields[$key]->options = $options;
            }
        }

        return $program_fields;
    }

    public function get_by_member($member_id, $program_id)
    {
        $this->db->select('programs_fields.field_id as field_id');
        $this->db->where('programs_fields.removed', 0);
        $this->db->from('programs_fields');
        $this->db->where('students_programs_fields.program_id', $program_id);
        $this->db->select('fields.label as field_label');
        $this->db->join('fields', 'programs_fields.field_id = fields.id', 'left');
        $this->db->select('students_programs_fields.value');
        $this->db->join('students_programs_fields', "students_programs_fields.member_id = $member_id AND students_programs_fields.field_id = fields.id", 'left');
        $this->db->select('fields_options.label as option_label');
        $this->db->join('fields_options', 'students_programs_fields.value = fields_options.id', 'left');
        $fields = $this->db->get()->result();

        $organized_fields = array();
        foreach ($fields as $field) {
            $organized_fields[$field->field_id] = $field;
        }
        return $organized_fields;
    }
    
    public function add($data)
    {
        $this->db->insert('fields', $data);
        return $this->db->insert_id();
    }
    
    public function add_option($data)
    {
        $this->db->insert('fields_options', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update('fields');
        return $this->db->affected_rows();
    }

    public function remove($field_id, $user_id)
    {
        $this->db->set('removed', 1);
        $this->db->set('modified_by', $user_id);
        $this->db->where('id', $field_id);
        $this->db->update('fields');
        return $this->db->affected_rows();
    }

    public function add_to_program($data)
    {
        if (!$this->has_field($data['field_id'], $data['program_id'])) {
            $this->db->insert('programs_fields', $data);
            return $this->db->insert_id();
        }
        return false;
    }

    public function remove_from_program($field_id, $program_id, $user_id)
    {
        $this->db->set('removed', 1);
        $this->db->set('modified_by', $user_id);
        $this->db->where('field_id', $field_id);
        $this->db->where('program_id', $program_id);
        $this->db->update('programs_fields');
        return $this->db->affected_rows();
    }

    public function has_field($field_id, $program_id)
    {
        $this->db->where('field_id', $field_id);
        $this->db->where('program_id', $program_id);
        $this->db->where('removed', 0);
        $this->db->from('programs_fields');
        $course = $this->db->get()->result();
        return !empty($course);
    }
}
