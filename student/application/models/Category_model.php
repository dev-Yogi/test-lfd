<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category_model extends CI_Model
{
    public function get_all($program_id, $include_empty = true)
    {
        $this->db->where('program_id', $program_id);
        $this->db->where('removed', 0);
        $this->db->order_by('id', 'asc');
        $this->db->from('categories');
        $categories = $this->db->get()->result();
        if (!$include_empty) {
            foreach ($categories as $key => $category) {
                $category_courses = $this->course_model->get_by_category($category->id, false);
                if (!count($category_courses)) {
                    unset($categories[$key]);
                }
            }
            
        }
        return $categories;
    }

    public function get($id)
    {
        $this->db->where('id', $id);
        $this->db->from('categories');
        $category = $this->db->get()->first_row();
        return $category;
    }
    
    public function add($data)
    {
        $this->db->insert('categories', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update('categories');
        return $this->db->affected_rows();
    }

    public function remove($category_id, $user_id)
    {
        $this->db->set('removed', 1);
        $this->db->set('modified_by', $user_id);
        $this->db->where('id', $category_id);
        $this->db->update('categories');
        return $this->db->affected_rows();
    }

}
