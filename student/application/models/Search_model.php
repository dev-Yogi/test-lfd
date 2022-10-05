<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Search_model extends CI_Model
{
    public function run($params)
    {
        $results = array();
        if (!empty($params['keywords'])) {

            // Lessons
            $this->db->select('lessons.*');
            $this->db->group_start();
            $this->db->like('title', $params['keywords']);
            $this->db->like('description', $params['keywords']);
            $this->db->group_end();
            $this->db->where('lessons.removed', 0);
            $this->db->from('lessons');
            $this->db->select('courses.name');
            $this->db->join('courses', 'lessons.course_id = courses.id AND courses.status = "published"', 'left');
            $lessons = $this->db->get()->result();

            foreach ($lessons as $lesson) {
                $results[] = array(
                    'type' => 'lesson',
                    'title' => $lesson->title,
                    'subtitle' => $lesson->name,
                    'description' => character_limiter(strip_tags($lesson->content), 100),
                    'url' => base_url("lesson/view/{$lesson->id}")
                );
            }

            // Courses
            $this->db->select('courses.*');
            $this->db->group_start();
            $this->db->like('courses.name', $params['keywords']);
            $this->db->or_like('courses.description', $params['keywords']);
            $this->db->group_end();
            $this->db->where('courses.removed', 0);
            $this->db->where('courses.is_assign_only', 0);
            $this->db->where('courses.status', 'published');
            $this->db->from('courses');

            $this->db->select('categories_courses.category_id');
            $this->db->join('categories_courses', 'courses.id = categories_courses.course_id', 'left');

            $this->db->select('categories.name as category_name, categories.id as category_id');
            $this->db->join('categories', 'categories_courses.category_id = categories.id', 'left');
            $courses = $this->db->get()->result();

            foreach ($courses as $course) {
                $results[] = array(
                    'type' => 'course',
                    'title' => $course->name,
                    'subtitle' => $course->category_name,
                    'description' => character_limiter(strip_tags($course->description), 100),
                    'url' => base_url("course/view/{$course->id}")
                );
            }
        }
        return $results;
    }
}
