<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_model extends CI_Model {

    public function create($data) {
        $this->db->insert('contact_form', $data);
        return $this->db->insert_id();
    }

    public function get_all()
    {
        $this->db->from('contact_form');
        $submissions = $this->db->get()->result();
        return $submissions;
    }
}
