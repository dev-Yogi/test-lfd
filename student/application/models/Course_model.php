<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Course_model extends CI_Model
{
    public function get($id, $status = null)
    {
        $this->db->select('courses.*');
        $this->db->where('courses.id', $id);
        $this->db->where('courses.removed', 0);
        if ($status) {
            $this->db->where('courses.status', $status);
        }
        $this->db->from('courses');

        $course = $this->db->get()->first_row();
        if ($course) {
            $this->load->model('category_model');
            $course->lessons = $this->get_lessons($id);
            $course->categories = $this->get_categories($id);
            $course->instructors = $this->get_instructors($id);
        }
        return $course;
    }

    /**
     * $program_id int Program ID
     * $with_lessons bool Only show courses with lessons
     * $status string Show courses with status
     * $show_assign_only Show courses which are assign-only courses
     */
    public function get_all($program_id, $with_lessons = false, $status = null, $show_assign_only = true)
    {
        $this->db->select('courses.*');
        $this->db->where('courses.removed', 0);
        $this->db->where('programs_courses.program_id', $program_id);
        if ($status) {
            $this->db->where('courses.status', $status);
        }
        if (!$show_assign_only) {
            $this->db->where('courses.is_assign_only', 0);
        }
        $this->db->order_by('courses.name', 'asc');
        $this->db->from('courses');

        $this->db->select('programs_courses.program_id');
        $this->db->join('programs_courses', 'courses.id = programs_courses.course_id', 'left');

        $this->db->select('lessons');
        $this->db->join('(SELECT course_id, COUNT(*) AS lessons FROM lessons WHERE removed = 0 GROUP BY course_id) lessons', 'courses.id = lessons.course_id', 'left');
        if ($with_lessons) {
            $this->db->having('lessons > 0');
        }

        $courses = $this->db->get()->result();

        if (!empty($courses)) {
            foreach ($courses as $key => $course) {
                $courses[$key]->categories = $this->get_categories($course->id);
                $courses[$key]->instructors = $this->get_instructors($course->id);
            }
        }
        return $courses;
    }

    public function get_all_by_instructor($instructor_id)
    {
        $this->db->select('courses.*');
        $this->db->where('courses.removed', 0);
        $this->db->where('courses_instructors.instructor_id', $instructor_id);
        $this->db->order_by('courses.id', 'desc');
        $this->db->from('courses');

        $this->db->join('courses_instructors', 'courses.id = courses_instructors.course_id AND courses_instructors.removed = 0', 'left');

        $this->db->select('members.first_name as instructor_first_name, members.last_name as instructor_last_name');
        $this->db->join(MEMBER_TABLE . '.members', 'courses_instructors.instructor_id = members.id', 'left');

        $this->db->select('COUNT(lessons.id) as lessons');
        $this->db->join('lessons', 'courses.id = lessons.course_id AND lessons.removed = 0', 'left');

        $this->db->select('categories_courses.category_id');
        $this->db->join('categories_courses', 'courses.id = categories_courses.course_id', 'left');

        $this->db->select('categories.name as category_name, categories.id as category_id');
        $this->db->join('categories', 'categories_courses.category_id = categories.id', 'left');

        $this->db->group_by('courses.id');
        $courses = $this->db->get()->result();

        return $courses;
    }

    public function get_lessons($course_id, $member_id = null)
    {
        $this->db->select('lessons.*');
        $this->db->where('lessons.course_id', $course_id);
        $this->db->where('lessons.removed', 0);
        $this->db->order_by('order');
        $this->db->from('lessons');


        if ($member_id) {
            $this->db->select('IFNULL(lessons_students.status, 0) as status');
            $this->db->join('lessons_students', "lessons_students.lesson_id = lessons.id AND lessons_students.student_id = {$member_id}", 'left');
        } else if (!empty($this->member->id)) {
            $this->db->select('IFNULL(lessons_students.status, 0) as status');
            $this->db->join('lessons_students', "lessons_students.lesson_id = lessons.id AND lessons_students.student_id = {$this->member->id}", 'left');
        }

        $lessons = $this->db->get()->result();

        if (!empty($lessons)) {
            foreach ($lessons as $key => $lesson) {
                $lessons[$key]->order = $key + 1;
            }
        }
        return $lessons;
    }

    public function get_instructors($course_id)
    {
        $this->db->select('members.id, members.first_name as instructor_first_name, members.last_name as instructor_last_name, email');
        $this->db->where('course_id', $course_id);
        $this->db->where('courses_instructors.removed', 0);
        $this->db->from ('courses_instructors');
        $this->db->join(MEMBER_TABLE . '.members', 'courses_instructors.instructor_id = members.id', 'left');

        $instructors = $this->db->get()->result();
        return $instructors;
    }

    public function get_categories($course_id)
    {
        $this->db->select('categories.id, categories.name');
        $this->db->where('course_id', $course_id);
        $this->db->where('categories_courses.removed', 0);
        $this->db->from ('categories_courses');
        $this->db->join('categories', 'categories_courses.category_id = categories.id', 'left');

        $categories = $this->db->get()->result();
        return $categories;
    }

    public function add($data, $program_id)
    {
        $this->db->insert('courses', $data);
        $course_id = $this->db->insert_id();

        $data_program = array(
            'course_id' => $course_id,
            'program_id' => $program_id,
            'created_by' => $this->member->id,
        );
        $this->db->insert('programs_courses', $data_program);

        return $course_id;
    }

    public function start($course_id, $student_id)
    {
    	if ($this->student_has_course($student_id, $course_id)) {
    		return;
    	}
    	
    	$data = array(
    		'course_id' => $course_id,
    		'student_id' => $student_id,
    		'status' => 'started',
    		'created_by' => $this->member->id,
    	);
        $this->db->insert('courses_students', $data);
        return $course_id;
    }

    public function student_has_course($student_id, $course_id)
    {
    	$this->db->where('student_id', $student_id);
    	$this->db->where('course_id', $course_id);
    	$this->db->from('courses_students');
        $course = $this->db->get()->result();
        return !empty($course);
    }

    public function get_course_progress($course_id, $student_id = null)
    {
        if (!$student_id) {
            $student_id = $this->member->id;
        }
        $this->db->where('student_id', $student_id);
        $this->db->where('course_id', $course_id);
        $this->db->from('courses_students');
        $progress = $this->db->get()->first_row();
        return $progress;
    }

    public function update($course_id, $data, $program_id)
    {
        $this->db->set($data);
        $this->db->where('id', $course_id);
        $this->db->update('courses');

        $data_program = array(
            'program_id' => $program_id,
            'modified_by' => $this->member->id,
            'modified' => date('Y-m-d H:i:s')
        );
        $this->db->where('course_id', $course_id);
        $this->db->update('programs_courses', $data_program);

        return $this->db->affected_rows();
    }

    public function get_student_courses($program_id, $student_id, $completed = null)
    {
        $this->db->select('courses_students.*');
        $this->db->where('student_id', $student_id);
        $this->db->where('courses.removed', 0);
        $this->db->where('courses.status', 'published');
        $this->db->where('programs_courses.program_id', $program_id);
        if ($completed === true) {
            $this->db->where('courses_students.status', 'complete');
        } else if ($completed === false) {
            $this->db->where('courses_students.status', 'started');
        }
        $this->db->from('courses_students');

        $this->db->select('courses.id, courses.name, courses.description, courses.image');
        $this->db->join('courses', 'courses_students.course_id = courses.id', 'left');

        $this->db->select('programs_courses.program_id');
        $this->db->join('programs_courses', 'courses_students.course_id = programs_courses.course_id', 'left');

        $this->db->group_by('courses.id');
        $courses = $this->db->get()->result();

        foreach ($courses as $course) {
            $course->lessons = $this->get_lessons($course->id, $student_id);
            $course->completed_lessons_count = $this->get_student_completed_lessons_in_course($student_id, $course->id);
        }
        return $courses;
    }

    public function get_student_lessons($student_id, $completed = null)
    {
        $this->db->select('courses_students.*');
        $this->db->where('student_id', $student_id);
        $this->db->from('courses_students');

        if ($completed === true) {
            $this->db->where('status', 'complete');
        } else if ($completed === false) {
            $this->db->where('status', 'started');
        }

        $this->db->select('COUNT(lessons.id) as lessons');
        $this->db->join('lessons', 'courses_students.course_id = lessons.course_id AND lessons.removed = 0', 'left');

        $this->db->select('courses.*');
        $this->db->join('courses', 'courses_students.course_id = courses.id', 'left');
        $this->db->group_by('courses.id');
        $courses = $this->db->get()->result();
        return $courses;
    }

    public function get_student_completed_lessons_in_course($student_id, $course_id)
    {
        $this->db->select('COUNT(*) AS count');
        $this->db->where('course_id', $course_id);
        $this->db->where('student_id', $student_id);
        $this->db->where('status', 'complete');
        $this->db->from('lessons_students');

        $this->db->join('lessons', 'lessons.id = lessons_students.lesson_id', 'left');
        $completed = $this->db->get()->first_row();
        return $completed->count;
    }

    /**
     * $category_id int Category ID
     * $with_lessons bool Only show courses with lessons
     * $show_assign_only Show courses which are assign-only courses
     */
    public function get_by_category($category_id, $with_lessons = false, $show_assign_only = true)
    {
        $this->db->select('courses.*');
        $this->db->where('category_id', $category_id);
        $this->db->where('courses.removed', 0);
        $this->db->where('courses.status', 'published');
        if (!$show_assign_only) {
            $this->db->where('courses.is_assign_only', 0);
        }
        $this->db->from('courses');

        $this->db->select('categories_courses.category_id');
        $this->db->join('categories_courses', 'courses.id = categories_courses.course_id', 'left');

        $this->db->select('categories.name as category, categories.id as category_id');
        $this->db->join('categories', 'categories_courses.category_id = categories.id', 'left');

        $this->db->select('COUNT(lessons.id) as lessons');
        $this->db->join('lessons', 'courses.id = lessons.course_id AND lessons.removed = 0', 'left');
        if ($with_lessons) {
            $this->db->having('lessons > 0');
        }
        $this->db->group_by('courses.id');
        $courses = $this->db->get()->result();
        return $courses;
    }

    public function get_my_courses($program_id)
    {
        return $this->get_student_courses($program_id, $this->member->id);
    }

    public function remove($course_id, $user_id)
    {
        $this->db->set('removed', 1);
        $this->db->set('modified_by', $user_id);
        $this->db->where('id', $course_id);
        $this->db->update('courses');
        return $this->db->affected_rows();
    }

    public function get_recent_courses($program_id)
    {
        $program_id = is_numeric($program_id) ? $program_id : 1;
        $query = $this->db->query("
            SELECT * 
            FROM courses 
            LEFT JOIN programs_courses ON courses.id = programs_courses.course_id 
            WHERE courses.removed = 0 
            AND courses.status = 'published' 
            AND programs_courses.program_id = $program_id 
            ORDER BY 
            CASE courses.modified WHEN NOT NULL THEN courses.modified 
            ELSE courses.created 
            END DESC, courses.created
            LIMIT 4
            ");

        $courses = $query->result();
        return $courses;
    }

    public function complete($course_id, $student_id = null)
    {
        if (!$student_id) {
            $student_id = $this->member->id;
        }

        $lessons = $this->get_lessons($course_id);
        foreach ($lessons as $lesson) {
            if ($lesson->status != 'complete') {
                return false;
            }
        }

        $data = array(
            'status' => 'complete',
            'completed_at' => date("Y-m-d H:i:s"),
            'modified_by' => $this->member->id,
            'modified' => date('Y-m-d H:i:s')
        );

        $this->db->set($data);
        $this->db->where('course_id', $course_id);
        $this->db->where('student_id', $student_id);
        $this->db->update('courses_students');
        return $this->db->affected_rows();
    }

    public function set_instructors($course_id, $instructor_ids)
    {
        if (empty($instructor_ids)) {
            return null;
        }

        $course = $this->get($course_id);
        $course_instructor_ids = array_column($course->instructors, 'id');

        // Check for difference between course's instructors and provided instructor list
        if ((count($course_instructor_ids) == count($instructor_ids)) && count($course_instructor_ids) == count(array_intersect($course_instructor_ids, $instructor_ids))) {
            return null;
        }

        $data = array(
            'removed' => 1,
            'modified_by' => $this->member->id,
            'modified' => date('Y-m-d H:i:s')
        );

        $this->db->set($data);
        $this->db->where('course_id', $course_id);
        $this->db->where('removed', 0);
        $this->db->update('courses_instructors');

        foreach ($instructor_ids as $instructor_id) {
            $data = array(
                'course_id' => $course_id,
                'instructor_id' => $instructor_id,
                'created_by' => $this->member->id,
            );
            $this->db->insert('courses_instructors', $data);
        }
    }

    public function set_categories($course_id, $category_ids)
    {
        if (empty($category_ids)) {
            return null;
        }

        $course = $this->get($course_id);
        $course_category_ids = array_column($course->categories, 'id');

        // Check for difference between course's categories and provided category list
        if ((count($course_category_ids) == count($category_ids)) && count($course_category_ids) == count(array_intersect($course_category_ids, $category_ids))) {
            return null;
        }

        $data = array(
            'removed' => 1,
            'modified_by' => $this->member->id,
            'modified' => date('Y-m-d H:i:s')
        );

        $this->db->set($data);
        $this->db->where('course_id', $course_id);
        $this->db->where('removed', 0);
        $this->db->update('categories_courses');

        foreach ($category_ids as $category_id) {
            $data = array(
                'course_id' => $course_id,
                'category_id' => $category_id,
                'created_by' => $this->member->id,
            );
            $this->db->insert('categories_courses', $data);
        }
    }
}
