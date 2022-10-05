<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forum extends Student_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->session->set_flashdata('info', 'Forums are no longer available.');
        redirect('/');

        $this->load->library('form_validation');
        $this->load->model('forum_model');
    }

    public function index()
    {
        $this->home();
    }

    public function home()
    {
        $categories = $this->forum_model->get_forum_home_posts($this->program->id);
        $profile = $this->forum_model->get_profile();
        set_title('Forum');
        $this->load->view('forum/home', compact('profile', 'categories'));
    }

    public function category($category_id)
    {
        $posts = $this->forum_model->get_by_category($category_id);
        $profile = $this->forum_model->get_profile();
        $category = $this->forum_model->get_category($category_id);
        set_title('Forum');
        $this->load->view('forum/category', compact('posts', 'profile', 'category'));
    }

    public function view($post_id)
    {
        $profile = $this->forum_model->get_profile();
        if (empty($profile)) {
            $this->session->set_userdata('redirect_url', current_url());
        }
        $post = $this->forum_model->get($post_id);
        if (!$post) {
            $this->session->set_flashdata('error', 'The post you\'re looking for has been removed, or does not exist.');
            redirect('forum');
        }
        if ($post && !empty($post->parent_id) || $post->removed) {
            $this->session->set_flashdata('error', 'Thread could not be found.');
            redirect("forum");
        }
        set_title('Forum');
        $this->load->view('forum/thread', compact('post', 'profile'));
    }

    public function post($type = null, $id = null)
    {
        $profile = $this->forum_model->get_profile();
        if (empty($profile)) {
            $this->session->set_userdata('redirect_url', current_url());
            redirect('forum/profile');
        }
        $this->check_banned();

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('title', 'Title', 'required|trim|min_length[4]|max_length[140]|strip_tags');
            $this->form_validation->set_rules('message', 'Message', 'required|trim|min_length[4]|max_length[2000]|strip_tags');
            if (!$id) {
                $this->form_validation->set_rules('category_id', 'Category', 'required|trim');
            }

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $data = array(
                    'title' => $this->input->post('title'),
                    'message' => $this->input->post('message'),
                    'category_id' => $this->input->post('category_id'),
                    'created_by' => $this->member->id
                );
                if ($type == 'course') {
                    $data['course_id'] = $id;
                }
                $post_id = $this->forum_model->add($data);

                if ($post_id) {
                    $this->session->set_flashdata('success', 'Thread successfully created.');
                    redirect("forum/view/$post_id");
                } else {
                    $this->session->set_flashdata('error', 'The post could not be created.');
                }
            }
        }

        $categories = null;
        if (!$id) {
            $categories = $this->forum_model->get_categories($this->program->id);
        }
        set_title('New Post');
        $this->load->view('forum/form', compact('categories'));
    }

    public function reply($parent_id)
    {
        $this->check_banned();
        if (!$this->forum_model->is_thread($parent_id)) {
            $this->session->set_flashdata('error', 'Not a valid thread to reply to.');
        }

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('message', 'Message', 'required|trim|min_length[4]|max_length[2000]|strip_tags');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $data = array(
                    'message' => $this->input->post('message'),
                    'parent_id' => $parent_id,
                    'created_by' => $this->member->id
                );
                $post_id = $this->forum_model->add($data);

                if ($post_id) {
                    $this->session->set_flashdata('success', 'Thread successfully created.');
                    redirect("forum/view/$parent_id#$post_id");
                } else {
                    $this->session->set_flashdata('error', 'The post could not be created.');
                }
            }
        }

        set_title('New Post');
        $this->load->view('forum/reply-form');
    }

    public function edit($post_id)
    {
        $this->check_banned();
        $post = $this->forum_model->get($post_id);
        $is_thread = empty($post->parent_id);
        if (empty($post) || ($post->created_by != $this->member->id && !is_moderator())) {
            $this->session->set_flashdata('error', 'Invalid thread to edit.');
            redirect('forum');
        }

        if ($this->input->method() == 'post') {
            if ($is_thread) {
                $this->form_validation->set_rules('title', 'Title', 'required|trim|min_length[4]|max_length[140]|strip_tags');
                $this->form_validation->set_rules('category_id', 'Category', 'required|trim');
            }
            $this->form_validation->set_rules('message', 'Message', 'required|trim|min_length[4]|max_length[2000]|strip_tags');

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } elseif ($is_thread && !$this->can_use_category($this->input->post('category_id'))) {
                $this->session->set_flashdata('error', 'Invalid category selection.');
            } else {
                $data = array(
                    'title' => $this->input->post('title'),
                    'message' => $this->input->post('message'),
                    'category_id' => $this->input->post('category_id'),
                    'modified_by' => $this->member->id,
                    'modified' => date('Y-m-d H:i:s')
                );
                $updated = $this->forum_model->update($post_id, $data);

                if ($updated) {
                    $this->session->set_flashdata('success', 'Thread successfully updated.');
                    if ($is_thread) {
                        redirect("forum/view/$post_id");
                    } else {
                        redirect("forum/view/{$post->parent_id}#$post_id");
                    }
                } else {
                    $this->session->set_flashdata('error', 'The post could not be updated.');
                }
            }
        }

        $categories = $this->forum_model->get_categories($this->program->id);
        set_title('Edit Post');
        $this->load->view('forum/form', compact('post', 'categories'));
    }

    public function delete($post_id)
    {
        $this->check_banned();
        $post = $this->forum_model->get($post_id);
        if (empty($post) || ($post->created_by != $this->member->id && !is_moderator())) {
            $this->session->set_flashdata('error', 'Invalid thread to edit.');
            redirect('forum');
        }
        $removed = $this->forum_model->remove($post_id, $this->member->id);
        if ($removed) {
            $this->session->set_flashdata('success', 'The post has been successfully removed.');
        } else {
            $this->session->set_flashdata('error', 'Post could not be removed.');
        }
        redirect('forum');
    }

    public function profile()
    {
        $profile = $this->forum_model->get_profile();
        if (!$profile) {
            $data = array(
                'member_id' => $this->member->id,
                'username' => strtolower($this->member->first_name) . sprintf('%04d', rand(0, 9999)),
                'avatar_icon' => 'smile',
                'avatar_color' => 'bg-blue',
                'created_by' => $this->member->id
            );
            $this->forum_model->add_profile($data);
            $profile = $this->forum_model->get_profile();
        }

        $icons = array(
            'car',
            'cat',
            'chess',
            'coffee',
            'crow',
            'crown',
            'dna',
            'dragon',
            'dove',
            'fish',
            'frog',
            'gem',
            'glasses',
            'guitar',
            'hamburger',
            'heart',
            'hippo',
            'horse',
            'ice-cream',
            'laugh-wink',
            'laugh',
            'meteor',
            'mountain',
            'paint-brush',
            'seedling',
            'smile',
            'umbrella',
            'vial'
        );

        $colors = array (
            'bg-blue',
            'bg-azure',
            'bg-indigo',
            'bg-purple',
            'bg-pink',
            'bg-red',
            'bg-orange',
            'bg-yellow',
            'bg-lime',
            'bg-green',
            'bg-teal',
            'bg-cyan',
            'bg-gray',
            'bg-gray-dark',
        );

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('avatar_icon', 'Avatar Icon', 'required|trim');
            $this->form_validation->set_rules('avatar_color', 'Avatar Color', 'required|trim');

            $avatar_icon = $this->input->post('avatar_icon');
            $avatar_color = $this->input->post('avatar_color');

            if (!in_array($avatar_icon, $icons) || !in_array($avatar_color, $colors)) {
                $this->session->set_flashdata('error', 'Invalid avatar selection.');
            } elseif ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $data = array(
                    'avatar_icon' => $avatar_icon,
                    'avatar_color' => $avatar_color,
                    'modified_by' => $this->member->id,
                    'modified' => date('Y-m-d H:i:s')
                );
                $updated = $this->forum_model->update_profile($this->member->id, $data);

                if ($updated) {
                    $this->session->set_flashdata('success', 'Profile updated.');
                    if ($this->session->userdata('redirect_url')) {
                        $redirect_url = $this->session->userdata('redirect_url');
                        $this->session->unset_userdata('redirect_url');
                        redirect($redirect_url);
                    }
                    redirect("forum/profile");
                } else {
                    $this->session->set_flashdata('error', 'Your profile could not be created.');
                }
            }
        }

        set_title('My Profile');
        $this->load->view('forum/profile-form', compact('icons', 'colors', 'profile'));
    }

    public function can_use_category($category_id)
    {
        $category = $this->forum_model->get_category($category_id);
        if (!empty($category) && ($category->name != "Announcements" || is_moderator())) {
            return true;
        }
        return false;
    }

    public function check_banned()
    {
        $profile = $this->forum_model->get_profile();
        if ($profile->banned) {
            $this->session->set_flashdata('error', 'Your account is banned from posting on the forums.');
            redirect("forum");
        }
    }

    public function me($type)
    {
        if ($type == "threads") {
            $posts = $this->forum_model->get_threads_by_user($this->member->id);
        } else {
            $posts = $this->forum_model->get_replies_by_user($this->member->id);
        }

        $profile = $this->forum_model->get_profile();
        set_title('Forum');
        $this->load->view('forum/me', compact('posts', 'profile'));
    }

    public function course($course_id)
    {
        $this->load->model('course_model');
        $course = $this->course_model->get($course_id);
        $posts = $this->forum_model->get_by_course($course_id);
        $profile = $this->forum_model->get_profile();
        set_title('Forum');
        $this->load->view('forum/course', compact('posts', 'course', 'profile'));
    }
}
