<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Instructor_model extends CI_Model
{
    public function get_all()
    {
        $this->db->where('tag_id', Tag::INSTRUCTOR);
        $this->db->from(MEMBER_TABLE . '.members_tags');
        $this->db->order_by('members.last_name', 'asc');

        $this->db->select('members.first_name, members.last_name, members.created, members.id, members.last_login');
        $this->db->join(MEMBER_TABLE . '.members', 'members.id = members_tags.member_id', 'left');

        $instructors = $this->db->get()->result();
        return $instructors;
    }

    public function get($id)
    {
        $this->db->where('members_tags.member_id', $id);
        $this->db->group_start();
        $this->db->where('tag_id', Tag::STUDENT);
        $this->db->or_where('tag_id', Tag::INSTRUCTOR);
        $this->db->group_end();
        $this->db->from(MEMBER_TABLE . '.members_tags');

        $this->db->select('members.first_name, members.last_name, members.created, members.id, members.email, members.last_login');
        $this->db->join(MEMBER_TABLE . '.members', 'members.id = members_tags.member_id', 'left');

        $instructor = $this->db->get()->first_row();
        return $instructor;
    }

    public function add($data)
    {
        $this->db = $this->load->database('member', TRUE);

        $this->db->insert('members', $data);
        $id = $this->db->insert_id();

        $data = array(
            'member_id' => $id,
            'tag_id' => Tag::INSTRUCTOR,
            'created_by' => $this->member->id
        );
        $this->db->insert('members_tags', $data);
        $tagged = $this->db->insert_id();

        $this->db = $this->load->database('default', TRUE);
        return $id;
    }

    public function update($id, $data)
    {
        $this->db = $this->load->database('member', TRUE);

        $this->db->where('id', $id);
        $this->db->update('members', $data);

        $this->db = $this->load->database('default', TRUE);
        return true;
    }
}
