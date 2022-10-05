<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tag {
    const STAFF = 1;
    const MANAGER = 2;
    const ADMIN = 3;
    const STUDENT = 4;
    const DONOR = 5;
    const INSTRUCTOR = 6;
    const UPWARD_BOUND = 7;
    // const PROGRAM_CATALOG_MANAGER = 8;
    const OFFERING_ADMIN_SCIENCE = 9;
    const OFFERING_ADMIN_TECHNOLOGY = 10;
    const OFFERING_ADMIN_ENGINEERING = 11;
    const OFFERING_ADMIN_MATHEMATICS = 12;
    const OFFERING_ADMIN_INDUSTRIES = 13;
    const OFFERING_SUBMITTER = 14;

}

class Tag_model extends CI_Model
{

    public function get($id)
    {
        $this->db->where('id', $id);
        $this->db->from('tags');
        $tag = $this->db->get()->first_row();
        return $tag;
    }

    public function has_tag($member_id, $tag_id)
    {
        $this->db->where('member_id', $member_id);
        $this->db->where('tag_id', $tag_id);
        $this->db->where('removed', 0);
        $this->db->from('members_tags');
        $result = $this->db->get()->first_row();
        return !empty($result);
    }

    public function tag_member($data)
    {
        if (!$this->has_tag($data['member_id'], $data['tag_id'])) {
            $this->db->insert('members_tags', $data);
            return $this->db->insert_id();
        }
    }

    public function untag_member($member_id, $tag_id, $modified_by)
    {
        $this->db->set('removed', 1);
        $this->db->set('modified_by', $member_id);
        $this->db->where('member_id', $member_id);
        $this->db->where('tag_id', $tag_id);
        $this->db->update('members_tags');
        return $this->db->affected_rows();
    }

    public function get_member_tags($member_id) {
        $this->db->where('member_id', $member_id);
        $this->db->where('members_tags.removed', 0);
        $this->db->from('members_tags');
        $this->db->select('tags.id, tags.label, tags.assignable');
        $this->db->join('tags', 'members_tags.tag_id = tags.id', 'left');
        $tags = $this->db->get()->result();
        return $tags;
    }

    public function get_members_with_tag($tag_id) {
        if ($tag_id < 1) {
            return null;
        }
        $this->db->select('members.id, members.email, members.first_name, members.last_name');
        $this->db->where('tag_id', $tag_id);
        $this->db->where('members_tags.removed', 0);
        $this->db->from('members_tags');
        $this->db->join('members', 'members_tags.member_id = members.id', 'left');
        $tags = $this->db->get()->result();
        return $tags;
    }

    public function get_all()
    {
        $this->db->order_by('created');
        $this->db->from('tags');
        $this->db->where('removed', 0);
        $tags = $this->db->get()->result();

        return $tags;
    }
}
