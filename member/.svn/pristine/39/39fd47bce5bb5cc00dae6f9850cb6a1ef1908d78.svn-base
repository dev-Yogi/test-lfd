<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Offering_category_model extends CI_Model
{
    public function get_all()
    {
        $this->db->where('removed', 0);
        $this->db->order_by('id', 'asc');
        $this->db->from('offering_categories');
        $catgories = $this->db->get()->result();
        return $catgories;
    }

    public function get($id)
    {
        $this->db->where('id', $id);
        $this->db->from('offering_categories');
        $category = $this->db->get()->first_row();
        return $category;
    }
    
    public function add($data)
    {
        $this->db->insert('offering_categories', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update('offering_categories');
        return $this->db->affected_rows();
    }

    public function remove($category_id, $user_id)
    {
        $this->db->set('removed', 1);
        $this->db->set('modified_by', $user_id);
        $this->db->where('id', $category_id);
        $this->db->update('offering_categories');
        return $this->db->affected_rows();
    }

}
