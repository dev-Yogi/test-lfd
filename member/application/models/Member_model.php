<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member_model extends CI_Model
{

    public function create($data)
    {
        $this->db->insert('members', $data);
        return $this->db->insert_id();
    }

    public function log_in($email, $password)
    {
        $this->db->where('email', $email);
        $this->db->from('members');
        $member = $this->db->get()->first_row();
        if ($member) {
            if (password_verify($password, $member->password)) {
                $this->update_login($member);
                return $member;
            }
        }
        return null;
    }

    public function get($id)
    {
        $this->db->where('id', $id);
        $this->db->where('removed', 0);
        $this->db->from('members');
        $member = $this->db->get()->first_row();

        if ($member) {
            $first_name = $member->first_name;
            $last_name = $member->last_name;
            $last_initial = substr($last_name, 0, 1);
            $name = "$first_name $last_initial.";
            $member->name = $name;
            $member->full_name = "$first_name $last_name";
            $member->tags = $this->tag_model->get_member_tags($member->id);
            return $member;
        }
        return null;
    }

    public function get_by_email($email)
    {
        if (empty($email)) {
            return null;
        }
        $this->db->where('email', $email);
        $this->db->from('members');
        $user = $this->db->get()->first_row();
        if ($user) {
            return $user;
        }
        return null;
    }

    public function get_by_reset_token($token)
    {
        $this->db->where('token', $token);
        $this->db->from('forgot_password');
        $this->db->order_by('created', 'desc');
        $reset_request = $this->db->get()->first_row();
        if ($reset_request) {
            if ($reset_request->created > date('Y-m-d H:i:s', strtotime('-2 hours'))) {
                return $this->get($reset_request->member_id);
            }
        }
        return null;
    }

    public function email_reset_link($email)
    {
        if ($user = $this->get_by_email($email)) {
            if (!$this->check_reset_limit($user->id)) {
                $this->session->set_flashdata('error', 'You have reached your password request limit for the day.');
                redirect('user/forgot_password');
            }
            $token = bin2hex(openssl_random_pseudo_bytes(10));
            $data = array(
                'member_id' => $user->id,
                'token' => $token
            );
            $this->db->insert('forgot_password', $data);

            $this->load->library('email');
            $this->email->to($user->email);
            $this->email->from('noreply@aiminstitute.org', 'AIM Platform');
            $this->email->subject('Password Reset');
            $this->email->message($this->load->view('email/password_reset', compact('token'), true));

            $this->email->send();

            return;
        }
    }

    private function check_reset_limit($member_id)
    {
        $this->db->where('member_id', $member_id);
        $this->db->where('created >= CURDATE()');
        $this->db->from('forgot_password');
        $requests = $this->db->get()->num_rows();
        if ($requests > 4) {
            return false;
        }
        return true;
    }

    public function set_password($id, $password)
    {
        $this->db->set('password', $password);
        $this->db->where('id', $id);
        $this->db->update('members');
        return $this->db->affected_rows();
    }

    public function update($id, $data)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update('members');
        return $this->db->affected_rows();
    }

    public function deactivate($id)
    {
        $data = array(
            'email' => null,
            'password' => null,
            'removed' => 1,
        );
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update('members');
        return $this->db->affected_rows();
    }

    public function update_login($user)
    {
        $data = array(
            'ip' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'last_login' => date("Y-m-d H:i:s"),
        );
        $this->db->set($data);
        $this->db->where('id', $user->id);
        $this->db->update('members');
        return $this->db->affected_rows();
    }

    public function has_role($member_id, $role_id)
    {
        $user = $this->get($member_id);
        if (empty($user)) {
            return false;
        }
        return $user->role_id >= $role_id;
    }

    public function get_all()
    {
        $this->db->from('members');
        $this->db->where('removed', 0);
        $this->db->order_by('last_name');
        $users = $this->db->get()->result();
        return $users;
    }

    public function get_by_role_id($role_id)
    {
        $this->db->from('members');
        $this->db->where('role_id', $role_id);
        $users = $this->db->get()->result();
        return $users;
    }

    public function has_tag($member_id, $tag_id) {
        return $this->tag_model->has_tag($member_id, $tag_id);
    }

    public function set_member_data($member) {
        $this->session->set_userdata('id', $member->id);
        unset($member->password);
        $member->tags = $this->tag_model->get_member_tags($member->id);
        $this->session->set_userdata('member', $member);
    }

    public function record_login($site, $member_id)
    {
        $data = array(
            'member_id' => $member_id,
            'site' => $site
        );
        $this->db->insert('logins', $data);
        return $this->db->insert_id();
    }
}
