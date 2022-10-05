<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Eoc_model extends CI_Model
{

    public function add($data)
    {
        $this->db->insert('eoc_submissions', $data);
        return $this->db->insert_id();
    }

    public function get($id)
    {
        $this->db->from('eoc_submissions');
        $this->db->where('id', $id);
        $submission = $this->db->get()->first_row();
        return $submission;
    }

    public function get_all()
    {
        $this->db->from('eoc_submissions');
        $submission = $this->db->get()->result();
        return $submission;
    }
}
