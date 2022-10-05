<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Help_model extends CI_Model
{
    public function get($id)
    {
        $this->db->select('contact.*');
        $this->db->where('contact.id', $id);
        $this->db->from('contact');

        $this->db->select('members.first_name, members.last_name, members.email');
        $this->db->join(MEMBER_TABLE . '.members', 'contact.created_by = members.id', 'left');

        $lesson = $this->db->get()->first_row();
        return $lesson;
    }
    
    public function add($data)
    {
        $this->db->insert('contact', $data);
        return $this->db->insert_id();
    }

    public function get_all()
    {
        $this->db->select('contact.*');
        $this->db->from('contact');

        $this->db->select('members.first_name, members.last_name');
        $this->db->join(MEMBER_TABLE . '.members', 'contact.created_by = members.id', 'left');

        $submission = $this->db->get()->result();
        return $submission;
    }
}
