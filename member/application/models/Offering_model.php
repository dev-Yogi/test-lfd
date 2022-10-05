<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Offering_model extends CI_Model
{

    public function get($id)
    {
        $this->db->where('id', $id);
        $this->db->limit(1);
        $this->db->from('offerings');
        $offering = $this->db->get()->first_row();

        if ($offering->repeat) {
            $offering->next_date = $this->get_next_recurring_date($offering);
        }
        return $offering;
    }

    public function get_all()
    {
        $this->db->from('offerings');
        $offerings = $this->db->get()->result();
        return $offerings;
    }

    public function get_published($include_past = true)
    {
        $this->db->order_by('id');
        $this->db->where('removed', 0);
        $this->db->where('status', 'published');
        $this->db->from('offerings');
        $this->db->join('(SELECT offering_id AS "ose_submitted_offering_id" FROM ose_submissions WHERE removed = 0 GROUP BY offering_id) ose', 'offerings.id = ose.ose_submitted_offering_id', 'left');

        if (!$include_past) {
            $this->db->group_start();
            $this->db->where('end_date >= DATE(NOW())');
            $this->db->or_where('end_date IS NULL');
            $this->db->group_end();
        }
        $offerings = $this->db->get()->result();
        return $offerings;
    }

    public function get_queue()
    {
        $this->db->order_by('id');
        $this->db->where('removed', 0);
        $this->db->where('status', 'pending');
        $this->db->from('offerings');
        $this->db->join('(SELECT offering_id AS "ose_submitted_offering_id" FROM ose_submissions WHERE removed = 0 GROUP BY offering_id) ose', 'offerings.id = ose.ose_submitted_offering_id', 'left');
        $suggestions = $this->db->get()->result();
        return $suggestions;
    }

    public function get_by_member($member_id)
    {
        $this->db->select('offerings.*');
        $this->db->where('member_id', $member_id);
        $this->db->where('offerings_members.removed', 0);
        $this->db->where('offerings.removed', 0);
        $this->db->from('offerings_members');
        $this->db->join('offerings', 'offerings.id = offerings_members.offering_id', 'left');
        $offerings = $this->db->get()->result();
        return $offerings;
    }

    public function get_filters($active = false)
    {
        $this->db->where('removed', 0);

        if ($active) {
            $this->db->where('active', 1);
        }

        $this->db->from('offering_filters');
        $columns = $this->db->get()->result();
        return $columns;
    }

    public function get_columns($active = false)
    {
        $this->db->order_by('order');
        $this->db->where('removed', 0);

        if ($active) {
            $this->db->where('active', 1);
        }

        $this->db->from('offering_columns');
        $columns = $this->db->get()->result();
        return $columns;
    }

    public function get_offering($id)
    {
        $this->db->where('id', $id);
        $this->db->from('offerings');
        $offering = $this->db->get()->first_row();
        if ($offering) {
            foreach ($offering as $key => $value) {
                if (json_decode($value)) {
                    $offering->{$key} = json_decode($value);
                }
            }
        }
        return $offering;
    }

    public function get_queue_submission($id)
    {
        $this->db->where('id', $id);
        $this->db->where('status', 'pending');
        $this->db->from('offerings');
        $offering = $this->db->get()->first_row();
        if ($offering) {
            foreach ($offering as $key => $value) {
                if (json_decode($value)) {
                    $offering->{$key} = json_decode($value);
                }
            }
        }
        return $offering;
    }

    public function search($params)
    {
        $expects_array = [
            'format',
            'category',
            'subcategory',
            'audience_type',
            'fee',
            'fee_has_scholarship',
            'is_aim',
            'internship_type',
            'internship_term',
            'is_ada',
            'organization',
            'department',
            'repeat',
            'audience_is_supervision_required',
            'start_time',
            'group_size',
            'audience_age_group',
            'audience_educator_target',
        ];
        foreach ($params as $key => $value) {
            if (in_array($key, $expects_array) && !is_array($params[$key])) {
                $params[$key] = [$value];
            }
        }

        $this->db->select('id, title, description, url, registration_url, format, location, start_date, end_date, is_expire_after_start, start_time, end_time, repeat, repeat_every_value, repeat_every_unit, repeat_every_weekdays, repeat_end_type, category, subcategory, audience_type, organization, department, audience_age_group, audience_is_supervision_required, audience_educator_target, group_size, fee, fee_price, fee_has_scholarship, is_aim, internship_type, internship_term, is_ada');
        $this->db->where('removed', 0);
        $this->db->where('status', 'published');
        $this->db->group_start();
            $this->db->where('end_date >= DATE(NOW())');
            $this->db->or_where('end_date IS NULL');
        $this->db->group_end();
        $this->db->group_start();
            $this->db->group_start();
                $this->db->where('is_expire_after_start', 1);
                $this->db->where('start_date >= DATE(NOW())');
            $this->db->group_end();
            $this->db->or_where('is_expire_after_start', 0);
        $this->db->group_end();
        $this->db->from('offerings');

        if (isset($params['force_internship'])) {
            $this->db->where('internship_type IS NOT NULL');
        }

        if (isset($params['format'])) {
            $this->db->where_in('format', $params['format']);
        }

        if (isset($params['category'])) {
            $this->db->where_in('category', $params['category']);
        }

        if (isset($params['subcategory'])) {
            $this->db->where_in('subcategory', $params['subcategory']);
        }

        if (isset($params['audience_type'])) {
            $this->db->where_in('audience_type', $params['audience_type']);
        }

        if (isset($params['fee'])) {
            $this->db->where_in('fee', $params['fee']);
        }

        if (isset($params['fee_price'])) {
            $this->db->group_start();
            foreach ($params['fee_price'] as $fee_price) {
                if ($fee_price == '1-50') {
                    $this->db->or_where('fee_price > 50');
                } elseif ($fee_price == '50-100') {
                    $this->db->or_where('fee_price > 50');
                    $this->db->or_where('fee_price < 100');
                } elseif ($fee_price == '100-250') {
                    $this->db->or_where('fee_price > 100');
                    $this->db->or_where('fee_price < 250');
                } elseif ($fee_price == '250+') {
                    $this->db->or_where('fee_price > 250');
                }
            }
            
            $this->db->group_end();
        }

        if (isset($params['fee_has_scholarship'])) {
            $this->db->where_in('fee_has_scholarship', $params['fee_has_scholarship']);
        }

        if (isset($params['organization'])) {
            $this->db->where_in('organization', $params['organization']);
        }

        if (isset($params['department'])) {
            $this->db->where_in('department', $params['department']);
        }

        if (isset($params['is_aim'])) {
            $this->db->where_in('is_aim', $params['is_aim']);
        }

        if (isset($params['internship_type'])) {
            $this->db->where_in('internship_type', $params['internship_type']);
        }

        if (isset($params['internship_term'])) {
            $this->db->where_in('internship_term', $params['internship_term']);
        }

        if (isset($params['is_ada'])) {
            $this->db->where_in('is_ada', $params['is_ada']);
        }

        if (isset($params['repeat'])) {
            $this->db->where_in('repeat', $params['repeat']);
        }

        if (isset($params['audience_is_supervision_required'])) {
            $this->db->where_in('audience_is_supervision_required', $params['audience_is_supervision_required']);
        }

        if (isset($params['start_time'])) {
            $this->db->group_start();
            foreach ($params['start_time'] as $start_time) {
                if ($start_time == 'morning') {
                    $this->db->or_where('start_time BETWEEN "00:00" AND "11:59"');
                }
                if ($start_time == 'afternoon') {
                    $this->db->or_where('start_time BETWEEN "12:00" AND "16:59"');
                }
                if ($start_time == 'evening') {
                    $this->db->or_where('start_time BETWEEN "17:00" AND "23:59"');
                }
            }
            $this->db->group_end();
        }

        if (isset($params['group_size'])) {
            $this->db->group_start();
            foreach ($params['group_size'] as $group_size) {
                $group_size = str_replace("+", "", $group_size);
                $this->db->or_like('group_size', $group_size);
            }
            $this->db->group_end();
        }

        if (isset($params['audience_age_group'])) {
            $this->db->group_start();
            foreach ($params['audience_age_group'] as $audience_age_group) {
                $audience_age_group = str_replace("+", "", $audience_age_group);
                $this->db->or_like('audience_age_group', $audience_age_group);
            }
            $this->db->group_end();
        }

        if (isset($params['audience_educator_target'])) {
            $this->db->group_start();
            foreach ($params['audience_educator_target'] as $audience_educator_target) {
                $this->db->or_like('audience_educator_target', $audience_educator_target);
            }
            $this->db->group_end();
        }

        if (isset($params['limit'])) {
            $this->db->limit((int)$params['limit']);
        }

        $offerings = $this->db->get()->result();
        for ($i = 0; $i < count($offerings); $i++) { 
            $offerings[$i]->audience_age_group = json_decode($offerings[$i]->audience_age_group);
            $offerings[$i]->group_size = json_decode($offerings[$i]->group_size);
            $offerings[$i]->audience_educator_target = json_decode($offerings[$i]->audience_educator_target);
            if ($offerings[$i]->repeat) {
                $offerings[$i]->next_date = $this->get_next_recurring_date($offerings[$i]);
            } else {
                $offerings[$i]->next_date = $offerings[$i]->start_date;
            }
        }
        usort($offerings, function($a, $b)
        {
            return strcmp($a->next_date, $b->next_date);
        });
        // dd($this->db->last_query());
        return $offerings;
    }

    public function get_next_recurring_date($offering)
    {
        $next_occurence = $offering->start_date;

        if (strtotime(date('Y-m-d H:i')) < strtotime($offering->start_date . ' ' . $offering->start_time)) {
            return $offering->start_date;
        }

        if ($offering->repeat_every_unit == 'day') {
            $current_time = strtotime(date('Y-m-d H:i'));
            $today_event_time = strtotime(date('Y-m-d ' . $offering->start_time));
            if ($current_time < $today_event_time) {
                $next_occurence = date('Y-m-d');
            } else {
                $next_occurence = date('Y-m-d', strtotime('tomorrow'));
            }
        }
        if ($offering->repeat_every_unit == 'week') {
            $repeat_days = json_decode($offering->repeat_every_weekdays);
            $possible_next_dates = [];
            foreach ($repeat_days as $day) {
                $possible_next_dates[] = date('Y-m-d', strtotime('this ' . $day));
            }
            sort($possible_next_dates);
            foreach ($possible_next_dates as $date) {
                $possible_next_date_time = date($date . ' ' . $offering->start_time);
                if (strtotime($possible_next_date_time) > strtotime(date('Y-m-d H:i'))) {
                    $next_occurence = $date;
                    break;
                }
            }
        }
        if ($offering->repeat_every_unit == 'month') {
            $day = date('l', strtotime($offering->start_date));
            $index_of_day_in_month = 0;

            $month = date('F', strtotime($offering->start_date));
            $year = date('Y', strtotime($offering->start_date));

            $first_of_month = date('Y-m-d', strtotime("first day of $month $year"));
            $last_of_month = date('Y-m-d', strtotime("last day of $month $year"));

            $begin = new DateTime($first_of_month);
            $end = new DateTime($last_of_month);

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);

            foreach ($period as $dt) {
                if ($dt->format('l') == $day) {
                    if ($dt->format('Y-m-d') == $offering->start_date) {
                        break;
                    } else {
                        $index_of_day_in_month++;
                    }
                }
            }

            // Calculate this month's occurence
            $counter = 0;
            $first_of_month = date('Y-m-d', strtotime("first day of this month"));
            $last_of_month = date('Y-m-d', strtotime("last day of this month"));

            $begin = new DateTime($first_of_month);
            $end = (new DateTime($last_of_month))->add(new DateInterval('P1D'));

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);

            foreach ($period as $dt) {
                if ($dt->format('l') == $day) {
                    if ($counter == $index_of_day_in_month) {
                        $next_occurence = $dt->format('Y-m-d');
                        break;
                    } else {
                        $counter++;
                    }
                }
            }

            // Has this month's already passed? If so, calculate next month
            if (strtotime($next_occurence) < strtotime(date('Y-m-d'))) {

                // Calculate this month's occurence
                $counter = 0;
                $first_of_month = date('Y-m-d', strtotime("first day of next month"));
                $last_of_month = date('Y-m-d', strtotime("last day of next month"));

                $begin = new DateTime($first_of_month);
                $end = (new DateTime($last_of_month))->add(new DateInterval('P1D'));

                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($begin, $interval, $end);

                foreach ($period as $dt) {
                    if ($dt->format('l') == $day) {
                        if ($counter == $index_of_day_in_month) {
                            $next_occurence = $dt->format('Y-m-d');
                            break;
                        } else {
                            $counter++;
                        }
                    }
                }
            }
        }
        if ($offering->repeat_every_unit == 'year') {
            $day = date('d', strtotime($offering->start_date));
            $month = date('m', strtotime($offering->start_date));
            $year = date('Y') + 1;
            $next_occurence = "$year-$month-$day";
        }

        return $next_occurence;
    }

    public function add($data)
    {
        $this->db->insert('offerings', $data);
        return $this->db->insert_id();
    }

    public function remove($offering_id, $user_id)
    {
        $this->db->set('removed', 1);
        $this->db->set('modified_by', $user_id);
        $this->db->where('id', $offering_id);
        $this->db->update('offerings');
        return $this->db->affected_rows();
    }

    public function update($id, $data)
    {
        if (empty($data['status'])) {
            unset($data['status']);
        }
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update('offerings');
        return $this->db->affected_rows();
    }

    public function update_column($id, $data)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update('offering_columns');
        return $this->db->affected_rows();
    }

    public function update_filter($id, $data)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update('offering_filters');
        return $this->db->affected_rows();
    }

    public function set_rules(&$data, $is_admin_view = false)
    {
        $this->form_validation->set_rules('status', 'Status', 'trim|htmlspecialchars|callback_check_valid_status');
        $this->form_validation->set_rules('title', 'Title', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('url', 'URL', 'trim|htmlspecialchars|callback_check_valid_url');
        $this->form_validation->set_rules('registration_url', 'Registration URL', 'trim|htmlspecialchars|callback_check_valid_url');
        $this->form_validation->set_rules('internship_type', 'Internship type', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('format', 'Format', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('location', 'Location', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('start_date', 'Start date', 'trim|htmlspecialchars|required|callback_check_valid_dates[' . $this->input->post('end_date') . ']');
        $this->form_validation->set_rules('end_date', 'End date', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('is_expire_after_start', 'Expires after start date', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('repeat', 'Is repeating', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('repeat_every_value', 'Repeat every value', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('repeat_every_unit', 'Repeat every unit', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('repeat_every_weekdays', 'Repeat end on date', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('repeat_end_type', 'Repeat end', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('repeat_end_occurence_value', 'Repeat end value', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('start_time', 'Start time', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('end_time', 'End time', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('category', 'Category', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('subcategory', 'Subcategory', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('submitter_name', 'Submitter name', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('submitter_email', 'Submitter email', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('submitter_is_offering_contact', 'Whether submitter is offering contact', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('contact_name', 'Offering contact name', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('contact_email', 'Offering contact email', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('organization', 'Offering organization', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('department', 'Offering organization', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('audience_type', 'Audience type', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('audience_age_group', 'Audience age group', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('audience_is_supervision_required', 'Supervision type', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('audience_educator_target', 'Educator target', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('fee', 'Fee', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('fee_price', 'Fee type', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('fee_has_scholarship', 'Fee has scholarship', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('fee_scholarship_contact', 'Fee scholarship contact', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('group_size', 'Group size', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('is_ada', 'Whether offering is ADA compliant', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('ada_contact', 'ADA contact', 'trim|htmlspecialchars');
        $this->form_validation->set_rules('image', 'Image', 'trim|htmlspecialchars');

        // Require below fields if not internship
        if (empty($data['internship_type'])) {
            $this->form_validation->set_rules('start_time', 'Start time', 'trim|htmlspecialchars|required');
            $this->form_validation->set_rules('end_time', 'End time', 'trim|htmlspecialchars|required');
            $this->form_validation->set_rules('fee', 'Fee', 'trim|htmlspecialchars|required');

            if (empty($data['group_size'])) {
                $this->form_validation->set_rules('group_size', 'Group size', 'trim|required');
            }
        }

        // Require location if in-person
        if ($data['format'] == 'in-person') {
            $this->form_validation->set_rules('location', 'Location', 'trim|required');
        }

        // Require end date if doesn't repeat, or repeats "until end date"
        if (empty($data['repeat']) || ($data['repeat'] && ($data['repeat_end_type'] == 'end_date'))) {
            $this->form_validation->set_rules('end_date', 'End date', 'trim|required');
        }

        // Requirements for repeating events
        if ($data['repeat']) {
            $this->form_validation->set_rules('repeat_every_unit', 'Repeat every X', 'trim|required');
            $this->form_validation->set_rules('repeat_end_type', 'Repeat period', 'trim|required');
            if ($data['repeat_end_type'] == 'occurence') {
                $this->form_validation->set_rules('repeat_end_occurence_value', 'Repeat occurence', 'trim|required');
            }
            if (($data['repeat_every_unit'] ?? null) == 'week' && empty($data['repeat_every_weekdays'])) {
                $this->form_validation->set_rules('repeat_every_weekdays', 'Repeating week days', 'trim|required');
            }
        }

        // Require offering contact if submitter is not offering contact
        if (!$data['submitter_is_offering_contact']) {
            $this->form_validation->set_rules('contact_name', 'Offering contact name', 'trim|required');
            $this->form_validation->set_rules('contact_email', 'Offering contact email', 'trim|required');
        }

        // Require internship_term if internship
        if (!empty($data['internship_type'])) {
            $this->form_validation->set_rules('internship_term', 'Intenship term', 'trim|htmlspecialchars|required');
        }

        if (!$data['is_ada']) {
            $this->form_validation->set_rules('ada_contact', 'ADA contact', 'trim|required');
        }

        if ($data['fee']) {
            $this->form_validation->set_rules('fee_price', 'Price', 'trim|required');
            $this->form_validation->set_rules('fee_has_scholarship', 'Scholarship selection', 'trim|required');
            
            if ($data['fee_has_scholarship']) {
                $this->form_validation->set_rules('fee_scholarship_contact', 'Scholarship contact', 'trim|required');
            }
        }

        if ($data['audience_type'] == 'student') {
            if (empty($data['audience_age_group'])) {
                $this->form_validation->set_rules('audience_age_group', 'Age Group', 'trim|required');
            }
            if (empty($data['audience_is_supervision_required'])) {
                $this->form_validation->set_rules('audience_is_supervision_required', 'Supervision selection', 'trim|required');
            }
        }

        if ($data['audience_type'] == 'educator') {
            if (empty($data['audience_educator_target'])) {
                $this->form_validation->set_rules('audience_educator_target', 'Educator Target', 'trim|required');
            }
        }

        // This relies on this request being a POST request
        if ($_SERVER["CONTENT_LENGTH"] > ((int)ini_get('post_max_size') * 1024 * 1024)) {
            $this->form_validation->set_rules('image', 'Image', 'required|trim', array('required' => "The file size is too big."));
        }
        if ($_FILES['image']['size'] ?? null) {
            $upload = $this->upload_banner();
            if (!empty($upload['error'])) {
                $this->form_validation->set_rules('image', 'Image', 'required|trim', array('required' => $upload['error']));
            } else {
                $data['image'] = $upload;
            }
        }
    }

    public function upload_banner()
    {
        $config['upload_path'] = UPLOAD_FOLDER_OFFERING_BANNER;
        $config['allowed_types'] = 'jpg|png';
        $config['max_size'] = 50000;
        $config['max_width'] = 6000;
        $config['max_height'] = 6000;
        $config['file_name'] = 'banner-' . date('YmdHis');

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('image')) {
            $error = array('error' => $this->upload->display_errors());
        } else {
            $data = $this->upload->data();
            $processed = $this->process_banner($data['file_name']);
            unlink($data['full_path']);
            if ($processed) {
                return $data['file_name'];
            }
        }
        return $error;
    }

    public function process_banner($file_name)
    {
        $config['image_library'] = 'gd2';
        $config['source_image'] = UPLOAD_FOLDER_OFFERING_BANNER . $file_name;
        $config['new_image'] = FOLDER_OFFERING_BANNER . $file_name;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 1200;

        $this->load->library('image_lib', $config);
        $resized = $this->image_lib->resize();
        return $resized;
    }


    public function get_data_from_post() {
        $data = array(
            'status' => $this->input->post('status'),
            'title' => $this->input->post('title'),
            'description' => $this->input->post('description'),
            'url' => $this->input->post('url'),
            'registration_url' => $this->input->post('registration_url'),
            'format' => $this->input->post('format'),
            'location' => $this->input->post('location'),
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date'),
            'is_expire_after_start' => $this->input->post('is_expire_after_start') ? 1 : 0,
            'repeat' => $this->input->post('repeat'),
            'repeat_every_value' => $this->input->post('repeat_every_value'),
            'repeat_every_unit' => $this->input->post('repeat_every_unit'),
            'repeat_every_weekdays' => $this->input->post('repeat_every_weekdays'),
            'repeat_end_type' => $this->input->post('repeat_end_type'),
            'repeat_end_occurence_value' => $this->input->post('repeat_end_occurence_value'),
            'start_time' => $this->input->post('start_time'),
            'end_time' => $this->input->post('end_time'),
            'category' => $this->input->post('category'),
            'subcategory' => $this->input->post('subcategory'),
            'submitter_name' => $this->input->post('submitter_name'),
            'submitter_email' => $this->input->post('submitter_email'),
            'submitter_is_offering_contact' => $this->input->post('submitter_is_offering_contact'),
            'contact_name' => $this->input->post('contact_name'),
            'contact_email' => $this->input->post('contact_email'),
            'organization' => $this->input->post('organization'),
            'department' => $this->input->post('department'),
            'audience_type' => $this->input->post('audience_type'),
            'audience_age_group' => $this->input->post('audience_age_group'),
            'audience_is_supervision_required' => $this->input->post('audience_is_supervision_required'),
            'audience_educator_target' => $this->input->post('audience_educator_target'),
            'fee' => $this->input->post('fee'),
            'fee_price' => $this->input->post('fee_price'),
            'fee_has_scholarship' => $this->input->post('fee_has_scholarship'),
            'fee_scholarship_contact' => $this->input->post('fee_scholarship_contact'),
            'is_aim' => $this->input->post('is_aim'),
            'group_size' => $this->input->post('group_size'),
            'internship_type' => $this->input->post('internship_type') == '0' ? null : $this->input->post('internship_type'),
            'internship_term' => $this->input->post('internship_term') == '0' ? null : $this->input->post('internship_term'),
            'is_ada' => $this->input->post('is_ada'),
            'ada_contact' => $this->input->post('ada_contact')
        );

        $data['start_date'] = $data['start_date'] ? date('Y-m-d', strtotime($data['start_date'])) : null;
        $data['end_date'] = $data['end_date'] ? date('Y-m-d', strtotime($data['end_date'])) : null;

        if ($data['repeat'] && $data['repeat_end_type'] != 'end_date') {
            $data['end_date'] = null;
        }

        if ($data['repeat'] && $data['repeat_every_unit'] && $data['repeat_end_occurence_value'] && $data['repeat_end_type'] == 'occurence') {
            $start_date = new DateTime($data['start_date']);
            $interval_period = strtoupper(substr($data['repeat_every_unit'], 0, 1));
            $interval = new DateInterval('P' . $data['repeat_end_occurence_value'] . $interval_period);
            $end_date = $start_date->add($interval);
            $data['end_date'] = $end_date->format('Y-m-d H:i:s');
        }
        return $data;
    }

    public function get_question($id)
    {
        $this->db->where('id', $id);
        $this->db->where('removed', 0);
        $this->db->limit(1);
        $this->db->from('offering_quiz_questions');
        $question = $this->db->get()->first_row();
        if ($question) {
            $this->db->where('question_id', $question->id);
            $this->db->where('removed', 0);
            $this->db->from('offering_quiz_answers');
            $answers = $this->db->get()->result();
            $question->answers = $answers;
        }
        return $question;
    }

    public function get_answer($id)
    {
        $this->db->where('id', $id);
        $this->db->where('removed', 0);
        $this->db->limit(1);
        $this->db->from('offering_quiz_answers');
        $answer = $this->db->get()->first_row();
        return $answer;
    }

    public function get_questions()
    {
        $this->db->order_by('order');
        $this->db->where('removed', 0);
        $this->db->from('offering_quiz_questions');
        $questions = $this->db->get()->result();
        if ($questions) {
            foreach ($questions as &$question) {
                $this->db->where('question_id', $question->id);
                $this->db->where('removed', 0);
                $this->db->from('offering_quiz_answers');
                $answers = $this->db->get()->result();
                $question->answers = $answers;
            }
        }
        return $questions;
    }

    public function get_next_question_id($current_question_id)
    {
        $current_question = $this->get_question($current_question_id);

        $this->db->order_by('order');
        $this->db->where('removed', 0);
        $this->db->where('order > ' . $current_question->order);
        $this->db->from('offering_quiz_questions');
        $this->db->limit(1);
        $question = $this->db->get()->first_row();
        return $question->id ?? -1;
    }

    public function get_old_published_repeating()
    {
        $this->db->where('repeat', 1);
        $this->db->where('status', 'published');
        $this->db->group_start();
        $this->db->where('end_date > NOW()');
        $this->db->or_where('end_date IS NULL');
        $this->db->group_end();
        $this->db->where('start_date = DATE_SUB(CURDATE(),INTERVAL 6 MONTH)');
        $this->db->from('offerings');
        $offerings = $this->db->get()->result();
        return $offerings;
    }

    public function get_organizations()
    {
        $this->db->select('offerings.*, COUNT(*) as count');
        $this->db->where('removed', 0);
        $this->db->group_by('organization');
        $this->db->from('offerings');
        $organizations = $this->db->get()->result();
        return $organizations;
    }

    public function has_organization_posted($organization)
    {
        $this->db->where('organization', $organization);
        $this->db->from('offerings');
        $offering = $this->db->get()->first_row();
        return $offering->organization ?? false;
    }

    public function get_departments()
    {
        $this->db->select('offerings.*, COUNT(*) as count');
        $this->db->where('removed', 0);
        $this->db->group_by('department');
        $this->db->from('offerings');
        $departments = $this->db->get()->result();
        return $departments;
    }

    public function assign($offering_id, $member_id)
    {
        if ($this->is_assigned($offering_id, $member_id)) {
            return true;   
        }
        $data = [
            'offering_id' => $offering_id,
            'member_id' => $member_id,
            'created_by' => $this->member->id ?? null
        ];
        $this->db->insert('offerings_members', $data);
        return $this->db->insert_id();
    }

    public function unassign($offering_id, $member_id)
    {
        $data = array(
            'modified_by' => $this->member->id,
            'modified' => date('Y-m-d H:i:s'),
            'removed' => 1,
        );
        $this->db->set($data);
        $this->db->where('offering_id', $offering_id);
        $this->db->where('member_id', $member_id);
        $this->db->update('offerings_members');
        return $this->db->affected_rows();
    }

    public function is_assigned($offering_id, $member_id)
    {
        $this->db->where('offerings_members.removed', 0);
        $this->db->where('offering_id', $offering_id);
        $this->db->where('member_id', $member_id);
        $this->db->from('offerings_members');

        return $this->db->get()->num_rows() > 0;
    }

    public function get_assigned($offering_id)
    {
        $this->db->where('offerings_members.removed', 0);
        $this->db->where('offering_id', $offering_id);
        $this->db->from('offerings_members');
        $this->db->join('members', 'members.id = offerings_members.member_id', 'left');

        $assigned = $this->db->get()->result();
        return $assigned;
    }

    public function get_zipcodes()
    {
        $this->db->from('ip_to_zip');
        $records = $this->db->get()->result();
        return $records;
    }

    public function process_expired()
    {
        $query = $this->db->query("SELECT * FROM offerings WHERE status = 'published' AND ((is_expire_after_start = 1 AND start_date < DATE(NOW())) OR end_date < DATE(NOW()))");
        $offerings = $query->result();
        foreach($offerings as $offering) {
            $this->update($offering->id, ['status' => 'expired']);
            log_message('error', "[OFFERING] Expired ID:$offering->id");
        }
    }
}
