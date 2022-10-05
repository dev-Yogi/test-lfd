<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends Staff_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('report_model');
        $this->load->model('category_model');
        $this->load->model('student_model');
        $this->load->model('instructor_model');
    }

    public function index()
    {
        $start_date = date('Y-m-d', strtotime('-30 days'));
        $stop_date = date('Y-m-d', strtotime('+1 Day'));
        $report_logins = $this->report_model->get_logins($this->program->id, $start_date, $stop_date);
        $report_course_completions = $this->report_model->get_course_completions($this->program->id, $start_date, $stop_date);
        $report_lesson_completions = $this->report_model->get_lesson_completions($this->program->id, $start_date, $stop_date);

        $report_logins = array_combine(array_column($report_logins, 'date'), $report_logins);
        $report_course_completions = array_combine(array_column($report_course_completions, 'date'), $report_course_completions);
        $report_lesson_completions = array_combine(array_column($report_lesson_completions, 'date'), $report_lesson_completions);

        $data = array();
        $period = new DatePeriod(
            new DateTime($start_date),
            new DateInterval('P1D'),
            new DateTime($stop_date)
        );

        foreach ($period as $key => $value) {
            $date = $value->format('Y-m-d');
            $data['report_logins'][] = array('date' => $date, 'count' => $report_logins[$date]->count ?? 0);
            $data['report_course_completions'][] = array('date' => $date, 'count' => $report_course_completions[$date]->count ?? 0);
            $data['report_lesson_completions'][] = array('date' => $date, 'count' => $report_lesson_completions[$date]->count ?? 0);
        }
        set_title('Reports');
        $this->load->view('admin/report', compact('data'));
    }

    public function logins($csv = false)
    {
        $input_start_date = $this->input->get('start_date');
        $input_stop_date = $this->input->get('stop_date');
        if ($input_start_date && $input_stop_date) {
            $start_date = date('Y-m-d', strtotime($input_start_date));
            $stop_date = date('Y-m-d', strtotime($input_stop_date));
        } else {
            $start_date = date('Y-m-d', strtotime('-30 days'));
            $stop_date = date('Y-m-d', strtotime('now'));
        }
        $logins = $this->report_model->get_logins($this->program->id, $start_date, $stop_date);

        if (!$csv) {
            set_title('Reports');
            $this->load->view('admin/report/logins', compact('logins', 'start_date', 'stop_date'));
        } else {
            $data[] = array('date', 'logins');
            foreach ($logins as $login) {
                $row = array();
                $row[] = date('Y-m-d', strtotime($login->logged_in_at));
                $row[] = $login->count ?? 0;
                $data[] = $row;
            }

            // Intentionally not written to Linux's tmp - "Disk is full" error
            $filename = "tmp/" . url_title('logins' . $start_date . '_' . $stop_date) . '.csv';
            $fp = fopen($filename, 'w');

            foreach ($data as $fields) {
                fputcsv($fp, $fields);
            }

            fclose($fp);

            $this->load->helper('download');
            force_download($filename, NULL);
        }
    }

    public function date_valid($date){
        $month = (int) substr($date, 0, 2);
        $day = (int) substr($date, 3, 2);
        $year = (int) substr($date, 6, 4);
        return checkdate($month, $day, $year);
    }


    public function courses($csv = false)
    {
        $filters = array(
            'search' => $this->input->get('search'),
            'name' => $this->input->get('name'),
            'category_id' => (int) $this->input->get('category_id'),
            'instructor_id' => (int) $this->input->get('instructor_id'),
            'lesson_count_mode' => $this->input->get('lesson_count_mode'),
            'lesson_count' => (int) $this->input->get('lesson_count'),
            'student_count_mode' => $this->input->get('student_count_mode'),
            'student_count' => (int) $this->input->get('student_count'),
            'created_start' => $this->date_valid($this->input->get('created_start')) ? date('Y-m-d', strtotime($this->input->get('created_start'))) : null,
            'created_stop' => $this->date_valid($this->input->get('created_stop')) ? date('Y-m-d', strtotime($this->input->get('created_stop'))) : null,
        );
        $courses = $this->report_model->get_courses($this->program->id, $filters);
        $instructors = $this->instructor_model->get_all();
        $categories = $this->category_model->get_all($this->program->id);

        if (!$csv) {
            set_title('Reports');
            $this->load->view('admin/report/courses', compact('courses', 'filters', 'categories', 'instructors'));
            return;
        } else {
            $data[] = array('course_id', 'course', 'category', 'category_id', 'instructor', 'instructor_id', 'lessons', 'created');
            foreach ($courses as $course) {
                $row = array();
                $row[] = $course->id;
                $row[] = $course->name;
                $row[] = $course->category_name;
                $row[] = $course->category_id;
                $row[] = $course->instructor_last_name . ', ' . $course->instructor_first_name;
                $row[] = $course->instructor_id;
                $row[] = $course->lessons;
                $row[] = $course->created;
                $data[] = $row;
            }

            // Intentionally not written to Linux's tmp - "Disk is full" error
            $filename = "tmp/" . url_title('courses' . date('ymdhis')) . '.csv';
            $fp = fopen($filename, 'w');

            foreach ($data as $fields) {
                fputcsv($fp, $fields);
            }

            fclose($fp);

            $this->load->helper('download');
            force_download($filename, NULL);
        }
    }


    public function students($csv = false)
    {
        $filters = array(
            'search' => $this->input->get('search'),
            'name' => $this->input->get('name'),
            'created_start' => $this->date_valid($this->input->get('created_start')) ? date('Y-m-d', strtotime($this->input->get('created_start'))) : null,
            'created_stop' => $this->date_valid($this->input->get('created_stop')) ? date('Y-m-d', strtotime($this->input->get('created_stop'))) : null,
            'last_login_start' => $this->date_valid($this->input->get('last_login_start')) ? date('Y-m-d', strtotime($this->input->get('last_login_start'))) : null,
            'last_login_stop' => $this->date_valid($this->input->get('last_login_stop')) ? date('Y-m-d', strtotime($this->input->get('last_login_stop'))) : null,
        );
        $students = $this->report_model->get_students($this->program->id, $filters);

        if (!$csv) {
            set_title('Reports');
            $this->load->view('admin/report/students', compact('students', 'filters'));
            return;
        } else {
            $data[] = array('student_id', 'name', 'school', 'school_id', 'courses', 'lessons', 'created', 'last_login');
            foreach ($students as $student) {
                $row = array();
                $row[] = $student->id;
                $row[] = display_name($student);
                $row[] = $student->school_name;
                $row[] = $student->school_id;
                $row[] = $student->count_courses ?? 0;
                $row[] = $student->count_lessons ?? 0;
                $row[] = $student->created;
                $row[] = $student->last_login;
                $data[] = $row;
            }

            // Intentionally not written to Linux's tmp - "Disk is full" error
            $filename = "tmp/" . url_title('students' . date('ymdhis')) . '.csv';
            $fp = fopen($filename, 'w');

            foreach ($data as $fields) {
                fputcsv($fp, $fields);
            }

            fclose($fp);

            $this->load->helper('download');
            force_download($filename, NULL);
        }
    }

    public function instructors($csv = false)
    {
        $filters = array(
            'search' => $this->input->get('search'),
            'name' => $this->input->get('name'),
            'school_id' => (int) $this->input->get('school_id'),
            'created_start' => $this->date_valid($this->input->get('created_start')) ? date('Y-m-d', strtotime($this->input->get('created_start'))) : null,
            'created_stop' => $this->date_valid($this->input->get('created_stop')) ? date('Y-m-d', strtotime($this->input->get('created_stop'))) : null,
            'last_login_start' => $this->date_valid($this->input->get('last_login_start')) ? date('Y-m-d', strtotime($this->input->get('last_login_start'))) : null,
            'last_login_stop' => $this->date_valid($this->input->get('last_login_stop')) ? date('Y-m-d', strtotime($this->input->get('last_login_stop'))) : null,
        );
        $instructors = $this->report_model->get_instructors($this->program->id, $filters);

        if (!$csv) {
            set_title('Reports');
            $this->load->view('admin/report/instructors', compact('instructors', 'filters'));
            return;
        } else {
            $data[] = array('instructor_id', 'name', 'courses', 'created', 'last_login');
            foreach ($instructors as $instructor) {
                $row = array();
                $row[] = $instructor->id;
                $row[] = display_name($instructor);
                $row[] = $instructor->count_courses ?? 0;
                $row[] = $instructor->created;
                $row[] = $instructor->last_login;
                $data[] = $row;
            }
            
            // Intentionally not written to Linux's tmp - "Disk is full" error
            $filename = "tmp/" . url_title('instructors' . date('ymdhis')) . '.csv';
            $fp = fopen($filename, 'w');

            foreach ($data as $fields) {
                fputcsv($fp, $fields);
            }

            fclose($fp);

            $this->load->helper('download');
            force_download($filename, NULL);
        }
    }

    public function activity($csv = false)
    {
        $input_start = $this->input->get('start_date');
        $input_stop = $this->input->get('stop_date');
        if ($input_start && $input_stop) {
            $start = date('Y-m-d', strtotime($input_start));
            $stop = date('Y-m-d', strtotime($input_stop));
        } else {
            $start = date('Y-m-d', strtotime('-30 Day'));
            $stop = date('Y-m-d', strtotime('+2 Day'));
        }

        $report_logins = $this->report_model->get_logins($this->program->id, $start, $stop);
        $report_lesson_completions = $this->report_model->get_lesson_completions($this->program->id, $start, $stop);
        $report_lesson_starts = $this->report_model->get_lesson_starts($this->program->id, $start, $stop);
        $report_assignment_submissions = $this->report_model->get_assignment_submissions($this->program->id, $start, $stop);

        $report_lesson_completions = array_combine(array_column($report_lesson_completions, 'date'), $report_lesson_completions);
        $report_lesson_starts = array_combine(array_column($report_lesson_starts, 'date'), $report_lesson_starts);
        $report_assignment_submissions = array_combine(array_column($report_assignment_submissions, 'date'), $report_assignment_submissions);

        $data = array();
        $period = new DatePeriod(
            new DateTime($start),
            new DateInterval('P1D'),
            new DateTime($stop)
        );

        if (!$csv) {
            foreach ($period as $key => $value) {
                $date = $value->format('Y-m-d');
                $data[$date] = array();
                $data[$date]['count_lesson_completions'] = $report_lesson_completions[$date]->count ?? 0;
                $data[$date]['count_lesson_starts'] = $report_lesson_starts[$date]->count ?? 0;
                $data[$date]['count_assignment_submissions'] = $report_assignment_submissions[$date]->count ?? 0;
            }

            $this->load->view('admin/report/activity', compact('data'));
            return;
        } else {
            $data[] = array('date', 'lesson_starts', 'lesson_completions', 'forum_posts', 'assignment_submissions');
            foreach ($period as $key => $value) {
                $date = $value->format('Y-m-d');
                $row = array();
                $row[] = $date;
                $row[] = $report_lesson_completions[$date]->count ?? 0;
                $row[] = $report_lesson_starts[$date]->count ?? 0;
                $row[] = $report_assignment_submissions[$date]->count ?? 0;
                $data[] = $row;
            }
 
            // Intentionally not written to Linux's tmp - "Disk is full" error
            $filename = "tmp/" . url_title('activity' . $start . '_' . $stop) . '.csv';
            $fp = fopen($filename, 'w');

            foreach ($data as $fields) {
                fputcsv($fp, $fields);
            }

            fclose($fp);

            $this->load->helper('download');
            force_download($filename, NULL);
        }
    }

    public function students_completion_lessons($csv = false)
    {
        $filters = array(
            'created_start' => $this->date_valid($this->input->get('start_date')) ? date('Y-m-d', strtotime($this->input->get('start_date'))) : null,
            'created_stop' => $this->date_valid($this->input->get('stop_date')) ? date('Y-m-d', strtotime($this->input->get('stop_date'))) : null,
        );

        $completion_lessons = $this->report_model->students_completion_lessons($this->program->id, $filters);

        if (!$csv) {
            set_title('Reports');
            $this->load->view('admin/report/students_completion_lessons', compact('completion_lessons'));
            return;
        } else {
            $data[] = array('ID', 'Last Name', 'First Name', 'School', 'Category', 'Course', 'Lesson', 'Lesson Started', 'Lesson Completed');
            foreach ($completion_lessons as $completion) {
                $row = array();
                $row[] = $completion->id;
                $row[] = $completion->last_name;
                $row[] = $completion->first_name;
                $row[] = $completion->school_name;
                $row[] = $completion->category;
                $row[] = $completion->course;
                $row[] = $completion->lesson;
                $row[] = $completion->lesson_started ? date('j M, Y', strtotime($completion->lesson_started)) : NULL;
                $row[] = $completion->lesson_completed ? date('j M, Y', strtotime($completion->lesson_completed)) : NULL;
                $data[] = $row;
            }

            // Intentionally not written to Linux's tmp - "Disk is full" error
            $filename = "tmp/" . url_title('students_completion_lessons' . date('ymdhis')) . '.csv';
            $fp = fopen($filename, 'w');

            foreach ($data as $fields) {
                fputcsv($fp, $fields);
            }

            fclose($fp);

            $this->load->helper('download');
            force_download($filename, NULL);
        }
    }
}
