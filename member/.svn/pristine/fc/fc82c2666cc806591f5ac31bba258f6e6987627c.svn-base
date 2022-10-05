<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Offering extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('text');
        $this->load->helper('offering');
        $this->load->model('offering_model');
        $this->load->model('ose_model');
        $this->load->model('offering_category_model');
    }

    public function index($table = NULL)
    {
        $this->catalog();
    }

    public function all()
    {
        if (empty($this->member)) {
            $redirect = str_replace(base_url(), '', current_url());
            $this->session->set_flashdata('info', 'Please log in.');
            redirect('user/login?redirect=' . $redirect);
        }

        $offerings = $this->offering_model->get_by_member($this->member->id);
        $this->load->view('offering/all', compact('offerings'));
    }

    public function edit($offering_id)
    {
        if (empty($this->member)) {
            $redirect = str_replace(base_url(), '', current_url());
            $this->session->set_flashdata('info', 'Please log in.');
            redirect('user/login?redirect=' . $redirect);
        }

        $offering = $this->offering_model->get_offering($offering_id);
        if (!$offering) {
            show_404();
        }
        if (!$this->offering_model->is_assigned($offering_id, $this->member->id)) {
            redirect('fail/unauthorized');
        }

        if ($this->input->method() == 'post') {

            $data = $this->offering_model->get_data_from_post();
            $this->offering_model->set_rules($data, true);


            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $data['modified_by'] = $this->member->id;
                $data['modified'] = date('Y-m-d H:i:s');
                if ($offering->status == 'pending') {
                     $data['status'] = 'pending';
                } else {
                    $data['status'] = $data['status'] == 'published' ? 'published' : 'draft';
                }

                foreach ($data as $key => $value) {
                    if (is_array($value)) {
                        $data[$key] = json_encode($value);
                    }
                }

                $this->offering_model->update($offering_id, $data);
                $this->session->set_flashdata('success', 'Your offering has been updated.');
                redirect("offering/edit/$offering_id");
            }
        }

        $locations = json_decode(file_get_contents('./js/locations.json'));
        $organizations = $this->offering_model->get_organizations();
        $departments = $this->offering_model->get_departments();
        $this->load->view('offering/edit', compact('offering', 'locations', 'organizations', 'departments'));
    }


    public function remove($offering_id)
    {
        if (empty($this->member)) {
            $redirect = str_replace(base_url(), '', current_url());
            $this->session->set_flashdata('info', 'Please log in.');
            redirect('user/login?redirect=' . $redirect);
        }

        $offering = $this->offering_model->get_offering($offering_id);
        if (!$offering) {
            show_404();
        }
        if (!$this->offering_model->is_assigned($offering_id, $this->member->id)) {
            redirect('fail/unauthorized');
        }
        $deleted = $this->offering_model->remove($offering_id, $this->member->id);
        if ($deleted) {
            $this->session->set_flashdata('success', 'Your offering has been removed.');
        } else {
            $this->session->set_flashdata('error', 'Sorry, the offering could not be removed due to an unknown error.');
        }
        redirect('offering/all');
    }


    public function catalog()
    {
        $params = $this->input->get();

        $offerings = $this->offering_model->search($params);
        $filters = $this->filters($offerings, $params);
        $columns = $this->offering_model->get_columns(true);
        $active_filters = $this->offering_model->get_filters(true);
        foreach ($offerings as $key => $offering) {
            $offerings[$key]->ose_grade = $this->ose_model->get_grade($offering->id);
        }
        shuffle($offerings);

        $data = [
            'category' => 'offering',
            'type' => 'view',
        ];
        $this->event_model->add($data);

        set_title('Offerings');
        $this->load->view('offering/catalog-table', compact('offerings', 'columns', 'params', 'filters', 'active_filters'));
    }


    public function internship()
    {
        $params = $this->input->get();

        // This has to be its own param and not a internship_type param to allow for further filtering
        $params['force_internship'] = true;

        $offerings = $this->offering_model->search($params);
        // Unset so that it doesn't get passed as a filter
        unset($params['force_internship']);
        $filters = $this->filters($offerings, $params);
        $columns = $this->offering_model->get_columns(true);
        $active_filters = $this->offering_model->get_filters(true);
        foreach ($offerings as $key => $offering) {
            $offerings[$key]->ose_grade = $this->ose_model->get_grade($offering->id);
        }
        shuffle($offerings);

        $data = [
            'category' => 'offering',
            'type' => 'view',
            'label' => 'internship',
        ];
        $this->event_model->add($data);

        set_title('Internship Offerings');
        $this->load->view('offering/catalog-table', compact('offerings', 'columns', 'params', 'filters', 'active_filters'));
    }


    public function organization()
    {
        $params = $this->input->get();

        if (empty($params['organization'])) {
            redirect('offering');
        }

        $offerings = $this->offering_model->search($params);
        if (empty($offerings)) {
            $organization_exists = $this->offering_model->has_organization_posted($params['organization'][0] ?? $params['organization']);
            if (!$organization_exists) {
                redirect('offering');
            }
            $organization_name = $organization_exists;
        } else {
            $organization_name = $offerings[0]->organization;
        }

        // Unset so that it doesn't get passed as a filter
        unset($params['organization']);
        $filters = $this->filters($offerings, $params);
        unset($filters['organization']);
        $columns = $this->offering_model->get_columns(true);
        $active_filters = $this->offering_model->get_filters(true);
        foreach ($offerings as $key => $offering) {
            $offerings[$key]->ose_grade = $this->ose_model->get_grade($offering->id);
        }

        $data = [
            'category' => 'offering',
            'type' => 'view',
            'label' => 'organization',
        ];
        $this->event_model->add($data);

        set_title("{$organization_name} Offerings");
        $this->load->view('offering/catalog-table', compact('offerings', 'columns', 'params', 'filters', 'active_filters'));
    }

    public function view($id)
    {
        $offering = $this->offering_model->get($id);
        $offering->ose_grade = $this->ose_model->get_grade($id);

        $data = [
            'category' => 'offering',
            'type' => 'view',
            'data_id' => $id,
        ];
        $this->event_model->add($data);

        set_title($offering->title);
        $this->load->view('offering/view', compact('offering'));
    }

    public function ajax_touch()
    {
        $data = [
            'category' => 'stemplatform',
            'type' => 'view',
        ];
        echo $this->event_model->add($data) ? 1 : 0;
    }

    public function filters($offerings, $params = null)
    {
        $filters = array(
            'format' => array(),
            'category' => array(),
            'subcategory' => array(),
            'audience_type' => array(),
            'audience_age_group' => array('3-6' => 0, '7-10' => 0, '11-13' => 0, '14-18' => 0, 'post-secondary' => 0),
            'audience_is_supervision_required' => array(),
            'audience_educator_target' => array('k-12' => 0, 'post secondary' => 0, 'tech trainer' => 0),
            'organization' => array(),
            'group_size' => array('1-4' => 0, '5-10' => 0, '11-20' => 0, '21-35' => 0, '36+' => 0),
            'fee' => array('free' => 0, 'paid' => 0),
            'fee_price' => array('1-50' => 0, '50-100' => 0, '100-250' => 0, '250+' => 0),
            'fee_has_scholarship' => array(),
            'is_aim' => array(),
            'internship_type' => array(),
            'is_ada' => array(),
            'start_time' => array('morning' => 0, 'afternoon' => 0, 'evening' => 0),
            'repeat' => array(),
        );

        // Show department filter only when organization is filtered
        if (!empty($params['organization'])) {
            $filters['department'] = array();
        }

        // Show internship term filter only when internship is filtered
        if (!empty($params['internship_type'])) {
            $filters['internship_term'] = array();
        }

        foreach ($offerings as $offering) {
            foreach ($filters as $key => $array) {
                if (isset($offering->{$key}))
                {
                    $value = $offering->{$key};
                    if ($key == 'fee') {
                        if ($value == 0) {
                            $filters['fee']['free']++;
                        } else {
                            $filters['fee']['paid']++;
                        }
                    }
                    elseif ($key == 'group_size') {
                        foreach ($value as $group_size) {
                            if ($group_size == '1-4') {
                                $filters['group_size']['1-4']++;
                            } elseif ($group_size == '5-10') {
                                $filters['group_size']['5-10']++;
                            } elseif ($group_size == '11-20') {
                                $filters['group_size']['11-20']++;
                            } elseif ($group_size == '21-35') {
                                $filters['group_size']['21-35']++;
                            } elseif ($group_size == '36') {
                                $filters['group_size']['36+']++;
                            }
                        }
                    }
                    elseif ($key == 'audience_age_group') {
                        foreach ($value as $audience_age_group) {
                            if ($audience_age_group == '3-6') {
                                $filters['audience_age_group']['3-6']++;
                            } elseif ($audience_age_group == '7-10') {
                                $filters['audience_age_group']['7-10']++;
                            } elseif ($audience_age_group == '11-13') {
                                $filters['audience_age_group']['11-13']++;
                            } elseif ($audience_age_group == '14-18') {
                                $filters['audience_age_group']['14-18']++;
                            } elseif ($audience_age_group == 'post-secondary') {
                                $filters['audience_age_group']['post-secondary']++;
                            }
                        }
                    }
                    elseif ($key == 'audience_educator_target') {
                        foreach ($value as $audience_educator_target) {
                            if ($audience_educator_target == 'k-12') {
                                $filters['audience_educator_target']['k-12']++;
                            } elseif ($audience_educator_target == 'post secondary') {
                                $filters['audience_educator_target']['post secondary']++;
                            } elseif ($audience_educator_target == 'tech trainer') {
                                $filters['audience_educator_target']['tech trainer']++;
                            }
                        }
                    }
                    elseif ($key == 'fee_price') {
                        if ($value <= 50) {
                            $filters['fee_price']['1-50']++;
                        } elseif ($value <= 100) {
                            $filters['fee_price']['50-100']++;
                        } elseif ($value <= 250) {
                            $filters['fee_price']['100-250']++;
                        } elseif ($value > 250) {
                            $filters['fee_price']['250+']++;
                        }
                    }
                    elseif ($key == 'start_time') {
                        if (strtotime($value) >= strtotime('00:00:00') && strtotime($value) < strtotime('12:00:00')) {
                            $filters['start_time']['morning']++;
                        }
                        if (strtotime($value) >= strtotime('12:00:00') && strtotime($value) < strtotime('17:00:00')) {
                            $filters['start_time']['afternoon']++;
                        }
                        if (strtotime($value) >= strtotime('17:00:00') && strtotime($value) < strtotime('24:00:00')) {
                            $filters['start_time']['evening']++;
                        }
                    }
                    elseif ($key == 'department') {
                        // Only include departments for the currently filtered organization
                        if ($offering->organization == $params['organization'][0]) {
                            if (!isset($filters[$key][$value])) {
                                $filters[$key][$value] = 1;
                            } else {
                                $filters[$key][$value]++;
                            }
                        }
                    }
                    elseif (!isset($filters[$key][$value]) && $value != null) {
                        $filters[$key][$value] = 1;
                    } elseif ($value != null) {
                        $filters[$key][$value]++;
                    }
                }
                // ksort($filters[$key]);
            }
        }

        // Remove keys with 0 results
        foreach ($filters as $key => $filter) {
            $filters[$key] = array_filter($filter, function($f) {
                return $f > 0;
            });
        }
        return $filters;
    }

    public function submit()
    {
        if (empty($this->member)) {
            $this->session->set_flashdata('info', 'Please log in or sign up to submit an offering.');
            redirect('user/login?redirect=offering/submit');
        }

        if ($this->input->method() == 'post') {

            $data = $this->offering_model->get_data_from_post();
            $this->offering_model->set_rules($data);

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Required fields are missing for this offering. <br>Please review your submission below and fill in all required fields and resubmit.');
            } else {
                foreach ($data as $key => $value) {
                    if (is_array($value)) {
                        $data[$key] = json_encode($value);
                    }
                }
                $data['created_by'] = $this->member->id;
                $data['status'] = 'pending';

                $offering_id = $this->offering_model->add($data);
                $this->offering_model->assign($offering_id, $this->member->id);
                $offering = $this->offering_model->get_offering($offering_id);
                $this->notify_admins($offering);
                // $this->notify_submitter($offering);

                // Tag member as offering submitter
                $tag_data = array(
                    'member_id' => $this->member->id,
                    'tag_id' => Tag::OFFERING_SUBMITTER,
                );
                $this->tag_model->tag_member($tag_data);

                if ($offering_id) {
                    $this->session->set_userdata('submitted_offering_id', $offering_id);
                    redirect('offering/thankyou');
                }
            }
        }
        set_title('Submit an Offering');
        $locations = json_decode(file_get_contents('./js/locations.json'));
        $organizations = $this->offering_model->get_organizations();
        $departments = $this->offering_model->get_departments();
        $this->load->view('offering/submit', compact('locations', 'organizations', 'departments'));
    }

    public function thankyou()
    {
        $offering_id = $this->session->userdata('submitted_offering_id');
        if (!$offering_id) {
            redirect('offering/submit');
        }

        set_title('Thank you for your submission');
        $this->load->view('offering/thankyou', compact('offering_id'));

    }

    public function check_valid_dates($start_date, $end_date)
    {
        // If the end date is empty, it could be a repeating offering. Let the other validation take care of it.
        if (empty($end_date)) {
            return TRUE;
        }
        $start = strtotime($start_date);
        $end = strtotime($end_date);
        if ($start > $end) {
            $this->form_validation->set_message('check_valid_dates', 'The start date must be before the end date');
            return FALSE;
        }
        return TRUE;
    } 

    public function check_valid_url($param)
    {
        if (!empty($param) && !filter_var($param, FILTER_VALIDATE_URL)) {
            $this->form_validation->set_message('check_valid_url', 'The {field} must be a valid url');
            return FALSE;
        } else {
            return TRUE;
        }
    } 

    public function check_valid_status($param)
    {
        if (!empty($param) && $param !== 'published') {
            $this->form_validation->set_message('check_valid_status', 'Invalid status selection');
            return FALSE;
        } else {
            return TRUE;
        }
    } 

    public function recaptcha_check($recaptcha)
    {
        $this->load->model('recaptcha_model');
        if (!$this->recaptcha_model->validate($recaptcha)) {
            $this->form_validation->set_message('recaptcha_check', 'Please complete the captcha');
            return false;
        }
        return true;
    }

    public function notify_admins($offering)
    {
        $this->load->library('table');
        $logo = base_url('img/email_logo.png');

        $emails = array();
        $staff = array();
        switch ($offering->category) {
            case 'science':
            $staff = $this->tag_model->get_members_with_tag(Tag::OFFERING_ADMIN_SCIENCE);
            break;
            case 'technology':
            $staff = $this->tag_model->get_members_with_tag(Tag::OFFERING_ADMIN_TECHNOLOGY);
            break;
            case 'engineering':
            $staff = $this->tag_model->get_members_with_tag(Tag::OFFERING_ADMIN_ENGINEERING);
            break;
            case 'mathematics':
            $staff = $this->tag_model->get_members_with_tag(Tag::OFFERING_ADMIN_MATHEMATICS);
            break;
            case 'industries':
            $staff = $this->tag_model->get_members_with_tag(Tag::OFFERING_ADMIN_INDUSTRIES);
            break;
        }
        if (empty($staff)) {
            return;
        }
        foreach ($staff as $member) {
            $emails[] = $member->email;
        }
        foreach ($offering as $key => $value) {
            $this->table->add_row(ucwords(str_replace('_', ' ', $key)), $value);
        }
        $table = $this->table->generate();
        $description = nl2br($offering->description);
        $message = "<img src='$logo' style='width:200px'>";
        $message .= "<p>A submission has been made for the AIM Offering Catalog.</p>";
        $message .= "<blockquote>";
        $message .= "<b>{$offering->title}</b>";
        $message .= "<br>";
        $message .= "<i>{$description}</i>";
        $message .= "</blockquote>";
        $message .= "<p>Approving the submission will allow it to be displayed in the catalog.</p>";
        // $message .= $table;
        $message .= "<br>";
        $message .= anchor(base_url("admin/offering/review/{$offering->id}"), "Review This Offering");
        $message .= "<br>";
        $message .= "<br>";
        $message .= anchor(base_url('admin/offering/queue'), "View Offering Queue");

        $this->load->library('email');
        $this->email->to($emails);
        $this->email->from('noreply@aiminstitute.org', 'AIM Institute');
        $this->email->subject('AIM Offering Catalog - Request Submitted');
        $this->email->message($message);
        return $this->email->send();
    }

    public function notify_submitter($offering)
    {
        $logo = base_url('img/email_logo.png');

        $description = nl2br($offering->description);
        $message = "<img src='$logo' style='width:200px'>";
        $message .= "<p>Hi {$offering->submitter_name},</p>";
        $message .= "<p>Thank you for your submission to the AIM offering catalog.</p>";
        $message .= "<p>Your submission:</p>";
        $message .= "<blockquote>";
        $message .= "<b>{$offering->title}</b>";
        $message .= "<br>";
        $message .= "<i>{$description}</i>";
        $message .= "</blockquote>";
        $message .= "<p>We will notify you when the submission has been processed. <br>Once it is approved, it will appear in the offering catalog.</p>";

        $this->load->library('email');
        $this->email->to($offering->submitter_email);
        $this->email->cc($offering->contact_email);
        $this->email->from('noreply@aiminstitute.org', 'AIM Institute');
        $this->email->subject('AIM Offering Catalog - Submission Confirmation');
        $this->email->message($message);
        return $this->email->send();
    }

    public function json() {
        $params = $this->input->get();
        $offerings = $this->offering_model->search($params);

        header('Content-Type: application/json');
        echo json_encode($offerings);
    }

    public function quiz_start()
    {
        $this->session->set_userdata('offering_quiz_current', null);
        $this->session->set_userdata('offering_quiz_answers', null);
        redirect('offering/quiz');
    }

    public function quiz($question_id = null)
    {
        $questions = [
            [
                'question' => 'Would like to attend offering Online, In-person, or both?',
                'filter_key' => 'format',
                'answers' => [
                    ['label' => 'Online', 'filter_value' => 'online'],
                    ['label' => 'In-person', 'filter_value' => 'in-person'],
                    ['label' => 'Both', 'filter_value' => 'hybrid'],
                ]
            ],
            [
                'question' => 'What topic sounds the most interesting to you? (You may select multiple)',
                'filter_key' => 'category[]',
                'answers' => [
                    ['label' => 'Science', 'filter_value' => 'science'],
                    ['label' => 'Technology', 'filter_value' => 'technology'],
                    ['label' => 'Mathematics', 'filter_value' => 'mathematics'],
                    ['label' => 'Engineering', 'filter_value' => 'engineering'],
                    ['label' => 'Industries', 'filter_value' => 'industries'],
                ]
            ],
            [
                'question' => 'Which best describes you?',
                'filter_key' => 'audience_type',
                'answers' => [
                    ['label' => 'Professional', 'filter_value' => 'professional'],
                    ['label' => 'Student', 'filter_value' => 'student'],
                    ['label' => 'Educator', 'filter_value' => 'educator'],
                ]
            ],
            [
                'question' => 'Which best describes you?',
                'filter_key' => 'audience_age_group',
                'answers' => [
                    ['label' => '3-6 years old', 'filter_value' => '3-6'],
                    ['label' => '7-10 years old', 'filter_value' => '7-10'],
                    ['label' => '11-13 years old', 'filter_value' => '11-13'],
                    ['label' => '14-18 years old', 'filter_value' => '14-18'],
                    ['label' => 'I am a post-secondary student', 'filter_value' => 'post-secondary'],
                ]
            ],
            [
                'question' => 'Are you looking for a large group activity or a small group acitvity?',
                'filter_key' => 'group_size',
                'answers' => [
                    ['label' => 'Large (more than 10 people)', 'filter_value' => '21-35,36+'],
                    ['label' => 'Small (less than 10 people)', 'filter_value' => '1-4,5-10'],
                ]
            ]
        ];

        $filters = $this->session->userdata('offering_quiz_answers') ?? [];
        $current_question_id = $this->session->userdata('offering_quiz_current') ?? 0;
        if ($current_question_id == 0) {
            $this->session->set_userdata('offering_quiz_answers', null);
        }
        if ($this->input->method() == 'post') {
            $post = $this->input->post();
            $key = key($post);
            $value = reset($post);

            if (empty($value)) {
                $this->session->set_flashdata('error', 'Please select an answer.');
                redirect('offering/quiz');
            } else {
                if ($key == 'group_size') {
                    $filters[$key] = explode(',', $value);
                } else {
                    $filters[$key] = $value;
                }
                if ($key == 'audience_type' && $value != 'youth/student') {
                    $current_question_id++;
                }
                $current_question_id++;

                $this->session->set_userdata('offering_quiz_answers', $filters);
                $this->session->set_userdata('offering_quiz_current', $current_question_id);
                redirect('offering/quiz');
            }
        }
        $question = $questions[$current_question_id] ?? null;
        if (empty($question)) {
            $url = base_url('offering?' . http_build_query($filters));
            $this->load->view('offering/quiz_complete', compact('url'));
            return;
        }
        set_title('Quiz');
        $this->load->view('offering/quiz', compact('question'));
    }

    public function quiz_results()
    {
        $session = $this->session->userdata('offering_quiz_user_answers');
        $current_question_id = $this->session->userdata('offering_quiz_current_question_id') ?? 1;

        if ($current_question_id != -1) {
            redirect("offering/quiz");
        }

        $results = array(
            "points_science" => 0,
            "points_technology" => 0,
            "points_engineering" => 0,
            "points_mathematics" => 0,
            "points_industries" => 0,
        );

        foreach ($session as $question_id => $answer_id) {
            $answer = $this->offering_model->get_answer($answer_id);
            foreach ($results as $key => $value) {
                $results[$key] += $answer->{$key};
            }
        }

        $this->session->set_userdata('offering_quiz_user_answers', null);
        $this->session->set_userdata('offering_quiz_current_question_id', null);

        set_title('Quiz');
        $this->load->view('offering/quiz_results', compact('results'));
    }

    public function url($offering_id)
    {
        $offering = $this->offering_model->get($offering_id);
        if ($offering && ($offering->url ?? null)) {
            $data = [
                'category' => 'offering',
                'type' => 'click',
                'label' => 'go_to_url',
                'data_id' => $offering_id,
            ];
            $this->event_model->add($data);
            redirect($offering->url);
        }
        redirect("offering/view/{$offering->id}");
    }

    public function registration_url($offering_id)
    {
        $offering = $this->offering_model->get($offering_id);
        if ($offering && ($offering->registration_url ?? null)) {
            $data = [
                'category' => 'offering',
                'type' => 'click',
                'label' => 'go_to_registration_url',
                'data_id' => $offering_id,
            ];
            $this->event_model->add($data);
            redirect($offering->registration_url);
        }
        redirect("offering/view/{$offering->id}");
    }

    public function create_notification()
    {
        $this->load->model('offering_notification_model');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('params', 'Search Parameters', 'trim');
        $this->form_validation->set_rules('type', 'Search Type', 'trim');
        dd($this->input->post());

        if ($this->form_validation->run() !== false) {
            $type = $this->input->post('type') == 'index' ? 'all' : $this->input->post('type');
            $data = array(
                'type' => $type,
                'email' => $this->input->post('email'),
                'ip' => $_SERVER['REMOTE_ADDR'],
                'params' => $this->input->post('params'),
            );
            $this->offering_notification_model->create($data);
            echo "Yes";
        } else {
            echo "No";
        }
    }

}
