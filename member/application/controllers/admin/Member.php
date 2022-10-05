<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member extends Staff_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('product_model');
    }

    public function index()
    {
        $members = $this->member_model->get_all();

        set_title('Members');
        $this->load->view('admin/member-list', compact('members'));
    }

    public function view($member_id)
    {
        $this->switch_employer($member_id);
        $account_manager = $this->member_model->get($this->employer->account_manager);

        set_title('Account');
        $this->load->view('admin/account/home', compact('account_manager'));
    }

    public function create()
    {
        if($this->input->method() == 'post') {
            if ($member->email != $this->input->post('email')) {
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[members.email]');
            }
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');

            if ($this->form_validation->run() !== FALSE)
            {
                $password = base64_encode(random_bytes(12));
                $data = array(
                    'email' => $this->input->post('email'),
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'created_by' => $this->member->id,
                );
                $member_id = $this->member_model->create($data);
                $tag_data = array(
                    'member_id' => $member_id,
                    'tag_id' => Tag::STUDENT,
                );
                $this->tag_model->tag_member($tag_data);
                $email_sent = $this->notify_new_user($data['email'], $password);

                if ($member_id) {
                    if ($email_sent) {
                        $this->session->set_flashdata('success', 'Member has been created - Tags may now be added to the account.');
                        redirect("admin/member/edit/$member_id");
                    } else {
                        $this->session->set_flashdata('warning', 'Member has been created, but the password email could not be sent. The user may log in by using the "Forgot Password" link');
                        redirect("admin/member/edit/$member_id");
                    }
                } else {
                    $this->session->set_flashdata('error', 'The user could not be created.');
                }

            } else {
                $this->session->set_flashdata('error', 'Please fill in all fields.');
            }
        }
        set_title('New Member');
        $this->load->view('admin/member-form');
    }

    public function edit($member_id)
    {
        $member = $this->member_model->get($member_id);

        if (empty($member)) {
            $this->session->set_flashdata('error', 'Invalid member.');
            redirect("admin/member");
        }

        $tags = $this->tag_model->get_all();
        if($this->input->method() == 'post') {
            if ($member->email != $this->input->post('email')) {
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[members.email]');
            }
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');

            if ($this->form_validation->run() !== FALSE)
            {
                $data = array(
                    'email' => $this->input->post('email'),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'modified_by' => $this->member->id
                );
                $this->member_model->update($member->id, $data);
                $this->session->set_flashdata('success', 'Account has been updated - the updated user must relog to see the changes.');
                redirect("admin/member/edit/$member_id");
            } else {
                $this->session->set_flashdata('error', 'Please fill in all fields.');
            }
        }
        set_title('Edit Account');
        $this->load->view('admin/member-form', compact('member', 'tags'));
    }

    public function tag_add($member_id)
    {
        if($this->input->method() == 'post') {
            $this->form_validation->set_rules('tag_id', 'Tag ID', 'required');
            $tag_id = $this->input->post('tag_id');
            if ($this->form_validation->run() !== FALSE)
            {
                if ($this->tag_model->has_tag($member_id, $tag_id)) {
                    redirect("admin/member/edit/$member_id");
                }

                $member = $this->member_model->get($member_id);
                $tag = $this->tag_model->get($tag_id);

                if (empty($member) || empty($tag)) {
                    $this->session->set_flashdata('error', 'Invalid member or tag.');
                    redirect("admin/member/edit/$member_id");
                }

                $data = array(
                    'member_id' => $member->id,
                    'tag_id' => $tag_id,
                    'created_by' => $this->member->id
                );
                $this->tag_model->tag_member($data);
                $this->session->set_flashdata('success', 'Tag added to member.');
                $this->notify_tag_updated($member_id, $tag_id);
                redirect("admin/member/edit/$member_id");
            } else {
                $this->session->set_flashdata('error', 'Error adding tag to member.');
            }
        }

        redirect("admin/member/edit/$member_id");
    }

    public function tag_remove($member_id, $tag_id)
    {
        if (!$this->tag_model->has_tag($member_id, $tag_id)) {
            redirect("admin/member/edit/$member_id");
        }
        $this->tag_model->untag_member($member_id, $tag_id, $this->member->id);
        $this->session->set_flashdata('success', 'Tag removed from member.');

        redirect("admin/member/edit/$member_id");
    }

    public function all()
    {
        set_title('Account');
        $this->load->view('admin/accounts');
    }

    public function search()
    {
        $keywords = $this->input->post('keywords');
        if (empty($keywords)) {
            redirect('admin/account');
        }
        $accounts = $this->employer_model->search($keywords);

        set_title('Search');
        $this->load->view('admin/accounts-results', compact('accounts', 'keywords'));
    }

    public function reset_password($member_id)
    {
        $member = $this->member_model->get($member_id);
        if (empty($member)) {
            $this->session->set_flashdata('error', 'Invalid member.');
            redirect("admin/member");
        }
        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('password2', 'Password Confirmation', 'required|matches[password]');
            if ($this->form_validation->run() !== FALSE) {
                $data = array(
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'modified_by' => $this->member->id
                );

                $updated = $this->member_model->update($member->id, $data);
                if ($updated) {
                    $this->session->set_flashdata('success', 'Password has been set.');
                    redirect("admin/member/edit/{$member->id}");
                } else {
                    $this->session->set_flashdata('error', 'Failed to update password.');
                }
            }
        }
        set_title('Reset Password');
        $this->load->view('admin/reset_password', compact('member'));
    }

    public function notify_new_user($email, $password)
    {
        $logo = base_url('img/email_logo.png');
        $login_url = base_url();
        $message = "<!DOCTYPE html><html><body>";
        $message .= "<img src='$logo'>";
        $message .= "<p>Here are your initial login details for your AIM account:</p>";
        $message .= "
            <table>
            <tr>
            <td><b>Username</b></td>
            <td><b>Password</b></td>
            </tr>
            <tr>
            <td>$email</td>
            <td>$password</td>
            </tr>
            </table>
        ";
        $message .= "<p>Please change your password after logging in.</p>";
        $message .= "<p><a href='$login_url'>Log into AIM</a></p>";
        $message .= "</body></html>";

        $this->load->library('email');
        $this->email->to($email);
        $this->email->from('noreply@aiminstitute.org', 'AIM Institute');
        $this->email->subject('AIM - Your Account Has Been Created');
        $this->email->message($message);
        return $this->email->send();
    }

    public function notify_tag_updated($member_id, $tag_id)
    {
        $exclude_list = [
            Tag::STUDENT,
            Tag::DONOR,
            Tag::UPWARD_BOUND,
        ];
        $member = $this->member_model->get($member_id);
        $tag = $this->tag_model->get($tag_id);
        if (in_array($tag_id, $exclude_list)) {
            return;
        }
        $tag_label = ucwords($tag->label);
        $logo = base_url('img/email_logo.png');
        $login_url = base_url();
        $message = "<!DOCTYPE html><html><body>";
        $message .= "<img src='$logo'>";
        $message .= "<p>A role has been added to your account: <b>{$tag_label}</b></p>";
        $message .= "<p></p>";
        $message .= anchor(base_url(), "Go to AIM Platform");
        $message .= "</body></html>";

        $this->load->library('email');
        $this->email->to($member->email);
        $this->email->from('noreply@aiminstitute.org', 'AIM Institute');
        $this->email->subject('Your account roles have been updated');
        $this->email->message($message);
        return $this->email->send();
    }
}
