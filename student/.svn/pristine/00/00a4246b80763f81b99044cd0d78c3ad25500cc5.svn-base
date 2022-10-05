<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report_model extends CI_Model
{
    public function get_logins($program_id, $start, $stop)
    {
        $start_date = date('Y-m-d', strtotime($start));
        $stop_date = date('Y-m-d', strtotime($stop));

        $this->db->select('DATE(logged_in_at) AS date, logged_in_at, logins.member_id, COUNT(*) AS count');
        $this->db->where('site', 'student');
        $this->db->where("logged_in_at BETWEEN '$start_date' AND '$stop_date'");
        $this->db->where("logged_in_at >", '2020-06-22');
        $this->db->from(MEMBER_TABLE . '.logins');
        $this->db->group_by('DATE(logged_in_at)');
        $this->db->order_by('date');

        $this->db->where('program_id', $program_id);
        $this->db->select('programs_members.program_id');
        $this->db->join('programs_members', MEMBER_TABLE . '.logins.member_id = programs_members.member_id', 'left');

        $this->db->select('members.first_name, members.last_name');
        $this->db->join(MEMBER_TABLE . '.members', MEMBER_TABLE . '.logins.member_id = members.id', 'left');

        $report = $this->db->get()->result();
        return $report;
    }

    public function get_course_completions($program_id, $start_date = NULL, $stop_date = NULL)
    {
        $this->db->select('DATE(completed_at) AS date, COUNT(*) AS count');
        $this->db->where('status', 'complete');
        if ($start_date && $stop_date) {
            $start_date = date('Y-m-d', strtotime($start_date));
            $stop_date = date('Y-m-d', strtotime($stop_date));
            $this->db->where("completed_at BETWEEN '$start_date' AND '$stop_date'");
        } else {
            $this->db->where("completed_at >", '2020-06-22');
        }
        $this->db->from('courses_students');
        $this->db->group_by('DATE(completed_at)');

        $this->db->where('program_id', $program_id);
        $this->db->select('programs_courses.program_id');
        $this->db->join('programs_courses', 'courses_students.course_id = programs_courses.course_id', 'left');

        $report = $this->db->get()->result();
        return $report;
    }

    public function get_lesson_completions($program_id, $start_date = NULL, $stop_date = NULL)
    {
        $this->db->select('DATE(completed_at) AS date, COUNT(*) AS count');
        $this->db->where('status', 'complete');
        if ($start_date && $stop_date) {
            $start_date = date('Y-m-d', strtotime($start_date));
            $stop_date = date('Y-m-d', strtotime($stop_date));
            $this->db->where("completed_at BETWEEN '$start_date' AND '$stop_date'");
        } else {
            $this->db->where("completed_at >", '2020-06-22');
        }
        $this->db->from('lessons_students');
        $this->db->group_by('DATE(completed_at)');

        $this->db->select('lessons.course_id');
        $this->db->join('lessons', 'lessons.id = lessons_students.lesson_id', 'left');
        $this->db->where('program_id', $program_id);
        $this->db->select('programs_courses.program_id');
        $this->db->join('programs_courses', 'lessons.course_id = programs_courses.course_id', 'left');

        $report = $this->db->get()->result();
        return $report;
    }

    public function get_lesson_starts($program_id)
    {
        $this->db->select('DATE(lessons_students.created) AS date, COUNT(*) AS count');
        $this->db->where("lessons_students.created >", '2020-06-22');
        $this->db->from('lessons_students');
        $this->db->group_by('DATE(lessons_students.created)');

        $this->db->select('lessons.course_id');
        $this->db->join('lessons', 'lessons.id = lessons_students.lesson_id', 'left');
        $this->db->where('program_id', $program_id);
        $this->db->select('programs_courses.program_id');
        $this->db->join('programs_courses', 'lessons.course_id = programs_courses.course_id', 'left');

        $report = $this->db->get()->result();
        return $report;
    }

    public function get_forum_posts($program_id)
    {
        $this->db->select('DATE(created) AS date, COUNT(*) AS count');
        $this->db->where("created >", '2020-06-22');
        $this->db->where('program_id', $program_id);
        $this->db->from('forum_posts');
        $this->db->group_by('DATE(created)');
        $report = $this->db->get()->result();
        return $report;
    }

    public function get_assignment_submissions($program_id)
    {
        $this->db->select('DATE(assignments_students.created) AS date, COUNT(*) AS count');
        $this->db->where("assignments_students.created >", '2020-06-22');
        $this->db->from('assignments_students');
        $this->db->group_by('DATE(assignments_students.created)');

        $this->db->join('assignments', 'assignments.id = assignments_students.assignment_id', 'left');
        $this->db->join('lessons', 'lessons.id = assignments.lesson_id', 'left');
        $this->db->join('programs_courses', 'lessons.course_id = programs_courses.course_id', 'left');
        $this->db->where('program_id', $program_id);

        $report = $this->db->get()->result();
        return $report;
    }

    public function get_courses($program_id, $filters)
    {
        $this->db->select('courses.*');
        $this->db->where('courses.removed', 0);
        $this->db->where('programs_courses.program_id', $program_id);
        $this->db->order_by('courses.name', 'asc');
        $this->db->from('courses');

        $this->db->select('programs_courses.program_id');
        $this->db->join('programs_courses', 'courses.id = programs_courses.course_id', 'left');

        $this->db->select('lessons');
        $this->db->join('(SELECT course_id, COUNT(*) AS lessons FROM lessons WHERE removed = 0 GROUP BY course_id) lessons', 'courses.id = lessons.course_id', 'left');

        $this->db->select('started.students_started');
        $this->db->join('(SELECT course_id, COUNT(*) as students_started FROM courses_students WHERE courses_students.status = "started" GROUP BY course_id) started', 'courses.id = started.course_id', 'left');

        $this->db->select('completed.students_complete');
        $this->db->join('(SELECT course_id, COUNT(*) as students_complete FROM courses_students WHERE courses_students.status = "complete" GROUP BY course_id) completed', 'courses.id = completed.course_id', 'left');

        // if (!empty($filters['name'])) {
        //     $this->db->like('courses.name', $filters['name']);
        // }
        // if (!empty($filters['category_id'])) {
        //     $this->db->where('categories_courses.category_id', $filters['category_id']);
        // }
        // if (!empty($filters['instructor_id'])) {
        //     $this->db->where('courses.instructor_id', $filters['instructor_id']);
        // }
        // if (!empty($filters['created_start'])) {
        //     $this->db->where('courses.created >', $filters['created_start']);
        // }
        // if (!empty($filters['created_stop'])) {
        //     $this->db->where('courses.created <', $filters['created_stop']);
        // }

        $this->db->group_by('courses.id');
        $courses = $this->db->get()->result();
        if (!empty($courses)) {
            foreach ($courses as $key => $course) {
                $courses[$key]->categories = $this->course_model->get_categories($course->id);
                $courses[$key]->instructors = $this->course_model->get_instructors($course->id);
            }
        }
        return $courses;
    }

    public function get_students($program_id, $filters)
    {
        $this->db->where('tag_id', Tag::STUDENT);
        $this->db->where('programs_members.program_id', $program_id);
        $this->db->from(MEMBER_TABLE . '.members_tags');
        $this->db->order_by('members.created', 'desc');

        $this->db->select('programs_members.program_id');
        $this->db->join('programs_members', 'members_tags.member_id = programs_members.member_id', 'left');

        $this->db->select('members.first_name, members.last_name, members.created, members.id, members.last_login');
        $this->db->join(MEMBER_TABLE . '.members', 'members.id = members_tags.member_id', 'left');

        // Get the school or company
        $this->db->select('students_programs_fields.value as school_id');
        $this->db->join('students_programs_fields', 'members.id = students_programs_fields.member_id AND field_id = 1', 'left');
        $this->db->select('fields_options.label as school_name');
        $this->db->join('fields_options', 'fields_options.field_id = 1 AND students_programs_fields.value = fields_options.id', 'left');

        $this->db->select('count_lessons');
        $this->db->join('(SELECT COUNT(*) AS count_lessons, student_id FROM lessons_students WHERE STATUS = "complete" GROUP BY student_id) lessons_students', 'members.id = lessons_students.student_id', 'left');

        $this->db->select('count_courses');
        $this->db->join('(SELECT COUNT(*) AS count_courses, student_id FROM courses_students WHERE STATUS = "complete" GROUP BY student_id) courses_students', 'members.id = courses_students.student_id', 'left');

        if (!empty($filters['name'])) {
            $this->db->group_start();
            $this->db->like('members.first_name', $filters['name']);
            $this->db->or_like('members.last_name', $filters['name']);
            $this->db->group_end();
        }
        if (!empty($filters['created_start'])) {
            $this->db->where('members.created >', $filters['created_start']);
        }
        if (!empty($filters['created_stop'])) {
            $this->db->where('members.created <', $filters['created_stop']);
        }
        if (!empty($filters['last_login_start'])) {
            $this->db->where('members.last_login >', $filters['last_login_start']);
        }
        if (!empty($filters['last_login_stop'])) {
            $this->db->where('members.last_login <', $filters['last_login_stop']);
        }

        $students = $this->db->get()->result();

        return $students;
    }

    public function students_completion_lessons($program_id, $filters)
    {
        $this->db->select('members.id, members.first_name, members.last_name, categories.name as category, courses.name as course, lessons.title as lesson, lessons_students.created as lesson_started, lessons_students.completed_at as lesson_completed');
        $this->db->where('lessons_students.status', 'complete');
        $this->db->where('programs_courses.program_id', $program_id);
        $this->db->where('tag_id', Tag::STUDENT);
        $this->db->from('lessons_students');
        $this->db->order_by('members.id', 'asc');

        $this->db->join(MEMBER_TABLE . '.members', 'members.id = lessons_students.student_id', 'left');
        $this->db->join(MEMBER_TABLE . '.members_tags', 'members.id = members_tags.member_id', 'left');
        $this->db->join('lessons', 'lessons.id = lessons_students.lesson_id', 'left');
        $this->db->join('courses', 'courses.id = lessons.course_id', 'left');
        $this->db->join('programs_courses', 'courses.id = programs_courses.course_id', 'left');
        $this->db->join('categories_courses', 'courses.id = categories_courses.course_id', 'left');
        $this->db->join('categories', 'categories_courses.category_id = categories.id', 'left');
        $this->db->join('programs_members', 'members.id = programs_members.member_id', 'left');
        // Get the school or company
        $this->db->select('students_programs_fields.value as school_id');
        $this->db->join('students_programs_fields', 'members.id = students_programs_fields.member_id AND field_id = 1', 'left');
        $this->db->select('fields_options.label as school_name');
        $this->db->join('fields_options', 'fields_options.field_id = 1 AND students_programs_fields.value = fields_options.id', 'left');

        if (!empty($filters['created_start'])) {
            $this->db->where('lessons_students.created >', $filters['created_start']);
        }
        if (!empty($filters['created_stop'])) {
            $this->db->where('lessons_students.created <', $filters['created_stop']);
        }

        $completions = $this->db->get()->result();
        return $completions;
    }

    public function get_instructors($program_id, $filters)
    {
        $this->db->where('tag_id', Tag::INSTRUCTOR);
        $this->db->where('program_id', $program_id);
        $this->db->from(MEMBER_TABLE . '.members_tags');
        $this->db->order_by('members.created', 'desc');

        $this->db->select('programs_members.program_id');
        $this->db->join('programs_members', 'members_tags.member_id = programs_members.member_id', 'left');

        $this->db->select('members.first_name, members.last_name, members.created, members.id, members.last_login');
        $this->db->join(MEMBER_TABLE . '.members', 'members.id = members_tags.member_id', 'left');

        $this->db->select('count_courses');
        $this->db->join('(SELECT COUNT(*) AS count_courses, instructor_id FROM courses GROUP BY instructor_id) courses', 'members.id = courses.instructor_id', 'left');

        if (!empty($filters['name'])) {
            $this->db->group_start();
            $this->db->like('members.first_name', $filters['name']);
            $this->db->or_like('members.last_name', $filters['name']);
            $this->db->group_end();
        }
        if (!empty($filters['school_id'])) {
            $this->db->where('instructors.school_id', $filters['school_id']);
        }
        if (!empty($filters['created_start'])) {
            $this->db->where('members.created >', $filters['created_start']);
        }
        if (!empty($filters['created_stop'])) {
            $this->db->where('members.created <', $filters['created_stop']);
        }
        if (!empty($filters['last_login_start'])) {
            $this->db->where('members.last_login >', $filters['last_login_start']);
        }
        if (!empty($filters['last_login_stop'])) {
            $this->db->where('members.last_login <', $filters['last_login_stop']);
        }

        $instructors = $this->db->get()->result();

        return $instructors;
    }
}
