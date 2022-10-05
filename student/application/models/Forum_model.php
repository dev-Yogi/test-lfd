<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Forum_model extends CI_Model
{
    public function get_by_category($category_id, $limit = 25)
    {
        $this->db->select('forum_posts.*');
        $this->db->where('category_id', $category_id);
        $this->db->where('forum_posts.removed', 0);
        $this->db->where('forum_posts.parent_id', null);
        $this->db->order_by('id', 'asc');
        $this->db->from('forum_posts');
        $this->db->limit($limit);

        $this->db->select('replies');
        $this->db->join('(SELECT COUNT(*) AS replies, parent_id FROM forum_posts GROUP BY parent_id) AS r', 'forum_posts.id = r.parent_id', 'left');

        $threads = $this->db->get()->result();
        return $threads;
    }

    public function get_by_course($course_id, $limit = 25)
    {
        $this->db->select('forum_posts.*');
        $this->db->where('course_id', $course_id);
        $this->db->where('forum_posts.removed', 0);
        $this->db->where('forum_posts.parent_id', null);
        $this->db->order_by('id', 'asc');
        $this->db->from('forum_posts');
        $this->db->limit($limit);

        $this->db->select('replies');
        $this->db->join('(SELECT COUNT(*) AS replies, parent_id FROM forum_posts GROUP BY parent_id) AS r', 'forum_posts.id = r.parent_id', 'left');

        $threads = $this->db->get()->result();
        return $threads;
    }

    public function get_forum_home_posts($program_id)
    {
        $categories = $this->get_categories($program_id);

        $threads = array();
        foreach ($categories as $category) {
            $threads[] = array(
                'category_id' => $category->id,
                'category_name' => $category->name,
                'threads' => $this->get_by_category($category->id, 5)
            );
        }

        return $threads;
    }

    public function get_categories($program_id)
    {
        $this->db->where('removed', 0);
        $this->db->where('program_id', $program_id);
        $this->db->order_by('order', 'asc');
        $this->db->from('forum_categories');

        $categories = $this->db->get()->result();
        return $categories;
    }

    public function get_category($category_id)
    {
        $this->db->where('id', $category_id);
        $this->db->where('removed', 0);
        $this->db->from('forum_categories');

        $category = $this->db->get()->first_row();
        return $category;
    }

    public function get($id)
    {
        $this->db->select('forum_posts.*');
        $this->db->where('forum_posts.id', $id);
        $this->db->from('forum_posts');

        $this->db->select('forum_profiles.avatar_icon, forum_profiles.avatar_color, forum_profiles.username, forum_profiles.post_count');
        $this->db->join('forum_profiles', 'forum_posts.created_by = forum_profiles.member_id', 'left');

        $this->db->select('forum_categories.name as category');
        $this->db->join('forum_categories', 'forum_posts.category_id = forum_categories.id', 'left');

        if (is_instructor()) {
            $this->db->select('members.first_name, members.last_name, members.created, members.id as member_id, members.email, members.last_login');
            $this->db->join(MEMBER_TABLE . '.members', 'members.id = forum_posts.created_by', 'left');
        }

        $post = $this->db->get()->first_row();
        if ($post) {
            $post->replies = $this->get_replies($id);
        }
        return $post;
    }

    public function get_replies($parent_id)
    {
        $this->db->select('forum_posts.*');
        $this->db->where('parent_id', $parent_id);
        $this->db->from('forum_posts');
        $this->db->order_by('forum_posts.created', 'asc');

        $this->db->select('forum_profiles.avatar_icon, forum_profiles.avatar_color, forum_profiles.username, forum_profiles.post_count');
        $this->db->join('forum_profiles', 'forum_posts.created_by = forum_profiles.member_id', 'left');

        if (is_instructor()) {
            $this->db->select('members.first_name, members.last_name, members.created, members.id as member_id, members.email, members.last_login');
            $this->db->join(MEMBER_TABLE . '.members', 'members.id = forum_posts.created_by', 'left');
        }

        $replies = $this->db->get()->result();
        return $replies;
    }

    public function get_profile($member_id = null)
    {
        if (!$member_id) {
            $member_id = $this->member->id;
        }
        $this->db->where('member_id', $member_id);
        $this->db->from('forum_profiles');
        $profile = $this->db->get()->first_row();
        if (empty($profile)) {
            return null;
        }
        return $profile;
    }

    public function get_post_count($member_id)
    {
        $this->db->select('COUNT(forum_posts.id) as count');
        $this->db->where('created_by', $member_id);
        $this->db->where('removed', 0);
        $this->db->from('forum_posts');
        $posts = $this->db->get()->first_row();
        return $posts->count;
    }

    public function is_thread($post_id)
    {
        $this->db->where('id', $post_id);
        $this->db->where('parent_id', null);
        $this->db->from('forum_posts');
        $post = $this->db->get()->first_row();
        return empty($post->parent_id);
    }
    
    public function add($data)
    {
        $this->db->insert('forum_posts', $data);
        $post_id = $this->db->insert_id();

        $this->update_post_count($post_id);
        return $post_id;
    }

    public function update($id, $data)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update('forum_posts');
        return $this->db->affected_rows();
    }

    public function remove($post_id, $user_id)
    {
        $this->db->set('removed', 1);
        $this->db->set('modified_by', $user_id);
        $this->db->where('id', $post_id);
        $this->db->update('forum_posts');
        $removed = $this->db->affected_rows();
        $this->update_post_count($post_id);

        return $removed;
    }
    
    public function add_profile($data)
    {
        $this->db->insert('forum_profiles', $data);
        return $this->db->insert_id();
    }

    public function update_profile($member_id, $data)
    {
        $this->db->set($data);
        $this->db->where('member_id', $member_id);
        $this->db->update('forum_profiles');
        return $this->db->affected_rows();
    }

    public function update_post_count($post_id)
    {
        log_message('debug', 'update_post_count');
        $post = $this->get($post_id);
        $data = array(
            'modified_by' => $this->member->id,
            'modified' => date('Y-m-d H:i:s'),
            'post_count' => $this->get_post_count($post->created_by)
        );
        $this->update_profile($post->created_by, $data);
    }

    public function get_threads_for_admin($program_id, $limit = null)
    {
        $this->db->select('forum_posts.*');
        $this->db->where('forum_posts.parent_id', null);
        $this->db->where('forum_posts.removed', 0);
        $this->db->where('forum_posts.program_id', $program_id);
        $this->db->from('forum_posts');
        $this->db->order_by('modified_by', 'desc');
        $this->db->order_by('created_by', 'desc');
        if ($limit) {
            $this->db->limit($limit);
        }

        $this->db->select('forum_profiles.avatar_icon, forum_profiles.avatar_color, forum_profiles.username');
        $this->db->join('forum_profiles', 'forum_posts.created_by = forum_profiles.member_id', 'left');

        $this->db->select('members.first_name, members.last_name');
        $this->db->join(MEMBER_TABLE . '.members', 'forum_posts.created_by = members.id', 'left');

        $this->db->select('replies');
        $this->db->join('(SELECT COUNT(*) AS replies, parent_id FROM forum_posts GROUP BY parent_id) AS r', 'forum_posts.id = r.parent_id', 'left');
        $replies = $this->db->get()->result();
        return $replies;
    }

    public function get_replies_for_admin($program_id, $limit = null)
    {
        $this->db->select('forum_posts.*');
        $this->db->where('forum_posts.parent_id IS NOT NULL');
        $this->db->where('forum_posts.removed', 0);
        $this->db->where('threads.removed', 0);
        $this->db->where('forum_posts.program_id', $program_id);
        $this->db->from('forum_posts');
        $this->db->order_by('modified_by', 'desc');
        $this->db->order_by('created_by', 'desc');
        if ($limit) {
            $this->db->limit($limit);
        }

        $this->db->select('forum_profiles.avatar_icon, forum_profiles.avatar_color, forum_profiles.username');
        $this->db->join('forum_profiles', 'forum_posts.created_by = forum_profiles.member_id', 'left');

        $this->db->select('threads.title as thread_title, threads.id as thread_id, threads.removed');
        $this->db->join('forum_posts as threads', 'threads.id = forum_posts.parent_id', 'left');
        $replies = $this->db->get()->result();
        return $replies;
    }

    public function get_recent_threads($program_id, $limit = null)
    {
        $this->db->select('forum_posts.*');
        $this->db->where('forum_posts.parent_id', null);
        $this->db->where('forum_posts.removed', 0);
        $this->db->where('forum_posts.program_id', $program_id);
        $this->db->from('forum_posts');
        $this->db->order_by('modified_by', 'desc');
        $this->db->order_by('created_by', 'desc');
        if ($limit) {
            $this->db->limit($limit);
        }

        $this->db->select('replies');
        $this->db->join('(SELECT COUNT(*) AS replies, parent_id FROM forum_posts GROUP BY parent_id) AS r', 'forum_posts.id = r.parent_id', 'left');
        $replies = $this->db->get()->result();
        return $replies;
    }

    public function get_recent_replies($program_id, $limit = null)
    {
        $this->db->select('forum_posts.*');
        $this->db->where('forum_posts.parent_id IS NOT NULL');
        $this->db->where('forum_posts.removed', 0);
        $this->db->where('threads.removed', 0);
        $this->db->where('forum_posts.program_id', $program_id);
        $this->db->from('forum_posts');
        $this->db->order_by('modified_by', 'desc');
        $this->db->order_by('created_by', 'desc');
        if ($limit) {
            $this->db->limit($limit);
        }

        $this->db->select('threads.title as thread_title, threads.id as thread_id, threads.removed');
        $this->db->join('forum_posts as threads', 'threads.id = forum_posts.parent_id', 'left');
        $replies = $this->db->get()->result();
        return $replies;
    }

    public function get_banned($limit = null)
    {
        $this->db->select('forum_profiles.*');
        $this->db->where('banned', 1);
        $this->db->from('forum_profiles');
        if ($limit) {
            $this->db->limit($limit);
        }

        $this->db->select('members.first_name, members.last_name');
        $this->db->join(MEMBER_TABLE . '.members', 'forum_profiles.member_id = members.id', 'left');

        $this->db->select('admins.first_name as admin_first_name, admins.last_name as admin_last_name');
        $this->db->join(MEMBER_TABLE . '.members as admins', 'forum_profiles.banned_by = admins.id', 'left');

        $users = $this->db->get()->result();
        return $users;
    }

    public function get_threads_by_user($member_id)
    {
        $this->db->select('forum_posts.*');
        $this->db->where('forum_posts.parent_id', null);
        $this->db->where('forum_posts.removed', 0);
        $this->db->where('forum_posts.created_by', $member_id);
        $this->db->from('forum_posts');
        $this->db->order_by('modified_by', 'desc');
        $this->db->order_by('created_by', 'desc');
        $this->db->limit(10);

        $this->db->select('forum_profiles.avatar_icon, forum_profiles.avatar_color, forum_profiles.username');
        $this->db->join('forum_profiles', 'forum_posts.created_by = forum_profiles.member_id', 'left');

        $this->db->select('replies');
        $this->db->join('(SELECT COUNT(*) AS replies, parent_id FROM forum_posts GROUP BY parent_id) AS r', 'forum_posts.id = r.parent_id', 'left');
        $replies = $this->db->get()->result();
        return $replies;
    }

    public function get_replies_by_user($member_id)
    {
        $this->db->select('forum_posts.*');
        $this->db->where('forum_posts.parent_id IS NOT NULL');
        $this->db->where('forum_posts.removed', 0);
        $this->db->where('forum_posts.created_by', $member_id);
        $this->db->from('forum_posts');
        $this->db->order_by('modified_by', 'desc');
        $this->db->order_by('created_by', 'desc');
        $this->db->limit(10);

        $this->db->select('forum_profiles.avatar_icon, forum_profiles.avatar_color, forum_profiles.username');
        $this->db->join('forum_profiles', 'forum_posts.created_by = forum_profiles.member_id', 'left');

        $this->db->select('threads.title as thread_title, threads.id as thread_id');
        $this->db->join('forum_posts as threads', 'threads.id = forum_posts.parent_id', 'left');
        $replies = $this->db->get()->result();
        return $replies;
    }
}
