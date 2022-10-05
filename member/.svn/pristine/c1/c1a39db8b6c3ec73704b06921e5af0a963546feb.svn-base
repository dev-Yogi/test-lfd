<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Event_model extends CI_Model
{
    public function add($data)
    {
    	$data['ip'] = $_SERVER['REMOTE_ADDR'];
        $this->db->insert('events', $data);
        return $this->db->insert_id();
    }
}
