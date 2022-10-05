<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Staff_model extends CI_Model
{

    public function get_links()
    {
        $this->db->where('removed', 0);
        $this->db->from('staff_links');
        $this->db->order_by('order, created');
        $offering = $this->db->get()->result();
        return $offering;
    }

    public function get_link($link_id)
    {
        $this->db->where('id', $link_id);
        $this->db->from('staff_links');
        $link = $this->db->get()->first_row();
        return $link;
    }

    public function create_link($data) {
        $this->db->insert('staff_links', $data);
        return $this->db->insert_id();
    }

    public function remove_link($link_id, $user_id)
    {
        $this->db->set('removed', 1);
        $this->db->set('modified_by', $user_id);
        $this->db->where('id', $link_id);
        $this->db->update('staff_links');
        return $this->db->affected_rows();
    }

    public function update_link($link_id, $data)
    {
        $this->db->set($data);
        $this->db->where('id', $link_id);
        $this->db->update('staff_links');
        return $this->db->affected_rows();
    }
}
