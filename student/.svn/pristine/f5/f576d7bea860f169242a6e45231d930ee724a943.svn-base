<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Message_model extends CI_Model
{
    public function get_threads($member_id)
    {
        $this->db->select('messages.*');
        $this->db->group_start();
        $this->db->where('messages.member_id', $member_id);
        $this->db->or_where('messages.created_by', $member_id);
        $this->db->group_end();
        $this->db->where('messages.removed', 0);
        $this->db->where('messages.parent_id IS NULL');
        $this->db->order_by('messages.id', 'desc');
        $this->db->from('messages');

        $this->db->select('programs.name as program_name');
        $this->db->join('programs', 'programs.id = messages.program_id', 'left');
        $messages = $this->db->get()->result();
        return $messages;
    }

    public function get_unread($member_id)
    {
        $this->db->select('messages.*');
        $this->db->where('messages.member_id', $member_id);
        $this->db->where('messages.read', 0);
        $this->db->where('messages.removed', 0);
        $this->db->order_by('messages.id', 'asc');
        $this->db->from('messages');

        $this->db->select('members.first_name, members.last_name');
        $this->db->join(MEMBER_TABLE . '.members', 'members.id = messages.created_by', 'left');
        $messages = $this->db->get()->result();
        return $messages;
    }

    public function get($member_id, $message_id)
    {
        $this->db->select('messages.*');
        $this->db->where('messages.id', $message_id);
        $this->db->group_start();
        $this->db->where('messages.member_id', $member_id);
        $this->db->or_where('messages.created_by', $member_id);
        $this->db->group_end();
        $this->db->where('messages.removed', 0);
        $this->db->from('messages');

        $this->db->select('programs.name as program_name');
        $this->db->join('programs', 'programs.id = messages.program_id', 'left');
        $message = $this->db->get()->first_row();
        if (empty($message->parent_id)) {
            $message->replies = $this->get_replies($message->id);
        }
        return $message;
    }

    public function get_replies($parent_id)
    {
        if (empty($parent_id)) {
            return null;
        }
        $this->db->select('messages.*');
        $this->db->where('messages.parent_id', $parent_id);
        $this->db->where('messages.removed', 0);
        $this->db->from('messages');
        $this->db->order_by('messages.created', 'asc');

        $this->db->select('members.first_name, members.last_name');
        $this->db->join(MEMBER_TABLE . '.members', 'members.id = messages.created_by', 'left');

        $messages = $this->db->get()->result();
        return $messages;
    }

    public function mark_all_as_read($member_id)
    {
        $this->db->set('read', 1);
        $this->db->where('member_id', $member_id);
        $this->db->update('messages');
        return $this->db->affected_rows();
    }

    public function mark_as_read($member_id, $message_id)
    {
        $this->db->set('read', 1);
        $this->db->where('member_id', $member_id);
        $this->db->where('id', $message_id);
        $this->db->update('messages');
        return $this->db->affected_rows();
    }
    
    public function add($data)
    {
        $this->db->insert('messages', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update('messages');
        return $this->db->affected_rows();
    }

    public function remove($message_id, $user_id)
    {
        $this->db->set('removed', 1);
        $this->db->set('modified_by', $user_id);
        $this->db->where('id', $message_id);
        $this->db->update('messages');
        return $this->db->affected_rows();
    }

}
