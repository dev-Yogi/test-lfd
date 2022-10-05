<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Offering extends Staff_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('offering_model');
        $this->load->model('offering_category_model');
        $this->load->helper('text');
    }

    public function check_offering_manager_tags()
    {
        if (!$this->tag_model->has_tag($this->member->id, Tag::OFFERING_ADMIN_SCIENCE) && 
            !$this->tag_model->has_tag($this->member->id, Tag::OFFERING_ADMIN_TECHNOLOGY) && 
            !$this->tag_model->has_tag($this->member->id, Tag::OFFERING_ADMIN_TECHNOLOGY) && 
            !$this->tag_model->has_tag($this->member->id, Tag::OFFERING_ADMIN_MATHEMATICS) && 
            !$this->tag_model->has_tag($this->member->id, Tag::OFFERING_ADMIN_INDUSTRIES)) {
            $this->session->set_flashdata('error', 'You do not have permission to view this page.');
            redirect('fail/unauthorized');
        }
    }

    public function init_role()
    {
        $offerings = $this->offering_model->get_all();

        foreach ($offerings as $offering) {
            $assigned = $this->offering_model->get_assigned($offering->id);
            foreach ($assigned as $member) {
                if (!$this->tag_model->has_tag($member->id, Tag::OFFERING_SUBMITTER)) {
                    $data = [
                        'tag_id' => Tag::OFFERING_SUBMITTER,
                        'member_id' => $member->id,
                        'created_by' => $this->member->id
                    ];
                    if ($this->tag_model->tag_member($data)) {
                    }
                }
            }
        }
    }

    public function init_assign()
    {
        $offerings = $this->offering_model->get_all();

        foreach ($offerings as $offering) {
            if ($offering->created_by) {
                $this->offering_model->assign($offering->id, $offering->created_by);
            }
        }
    }

    public function init_ose()
    {
        $this->load->model('ose_model');
        $offerings = $this->offering_model->get_published();
        foreach ($offerings as $offering) {
            $score = $this->ose_model->get_grade($offering->id);
            if ($score == null) {
                $questions = $this->ose_model->get_questions();
                foreach ($questions as $question) {
                    $this->ose_model->add_submission([
                        'offering_id' => $offering->id,
                        'question_id' => $question->id,
                        'answer' => 1,
                        'created_by' => $this->member->id ?? null,
                    ]);
                }
            }
        }
    }

    public function index()
    {
        $include_past = $this->input->get('past') ?? false;
        $offerings = $this->offering_model->get_published($include_past);
        
        set_title('Offerings');
        $this->load->view('admin/offering-list', compact('offerings'));
    }

    public function all_columns()
    {
        $offerings = $this->offering_model->get_published(true);
        set_title('Offerings');
        $this->load->view('admin/offering-list-all-columns', compact('offerings'));
    }

    public function organization()
    {
        $organization = $this->input->get('organization');
        if ($organization) {
            $offerings = $this->offering_model->search(['organization' => $organization]);
            set_title($organization);
            $this->load->view('admin/offering-list-organization-offerings', compact('offerings'));
        } else {
            $organizations = $this->offering_model->get_organizations();
            set_title('Organizations');
            $this->load->view('admin/offering-list-organization', compact('organizations'));
        }
    }

    public function remove($offering_id)
    {
        $this->check_offering_manager_tags();
        $deleted = $this->offering_model->remove($offering_id, $this->member->id);
        if ($deleted) {
            $this->session->set_flashdata('success', 'Offering has been removed.');
        } else {
            $this->session->set_flashdata('error', 'Offering could not be removed.');
        }
        redirect('admin/offering');
    }

    public function queue()
    {
        $submissions = $this->offering_model->get_queue();

        set_title('Offering Approval Queue');
        $this->load->view('admin/offering-queue', compact('submissions'));
    }

    public function zipcodes()
    {
        $records = $this->offering_model->get_zipcodes();

        set_title('STEM Platform Serviced ZIP Codes');
        $this->load->view('admin/zipcodes', compact('records'));
    }

    public function settings()
    {
        $columns = $this->offering_model->get_columns();
        $filters = $this->offering_model->get_filters();


        set_title('Catalog Settings');
        $this->load->view('admin/offering-settings', compact('columns', 'filters'));
    }

    public function update_columns()
    {

        if ($this->input->method() == 'post') {
            $order = explode(",", $this->input->post('columns'));
            if ($order) {
                foreach ($order as $key => $value) {
                    $data = array(
                        'order' => $key,
                        'active' => $this->input->post("$value-active") ?? 0
                    );
                    $this->offering_model->update_column($value, $data);
                }
            }
            $this->session->set_flashdata('success', 'Settings have been updated.');
            redirect('admin/offering/settings');
        }
        redirect('admin/offering/settings');
    }

    public function update_filters()
    {
        $filters = $this->offering_model->get_filters();

        if ($this->input->method() == 'post') {
            foreach ($filters as $filter) {
                if ($active = $this->input->post($filter->id)) {
                    $data = array(
                        'active' => $active,
                        'modified_by' => $this->member->id,
                        'modified' => date('Y-m-d H:i:s'),
                    );
                    $this->offering_model->update_filter($filter->id, $data);
                } else {
                    $data = array(
                        'active' => 0,
                        'modified_by' => $this->member->id,
                        'modified' => date('Y-m-d H:i:s'),
                    );
                    $this->offering_model->update_filter($filter->id, $data);
                }
            }
            $this->session->set_flashdata('success', 'Settings have been updated.');
            redirect('admin/offering/settings');
        }
        redirect('admin/offering/settings');
    }

    public function edit($id)
    {
        $this->check_offering_manager_tags();

        $offering = $this->offering_model->get_offering($id);


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

                $this->offering_model->update($id, $data);
                $this->session->set_flashdata('success', 'Offering has been updated.');
                redirect("admin/offering/edit/$id");
            }
        }

        set_title("{$offering->title} - Edit");
        $locations = json_decode(file_get_contents('./js/locations.json'));
        $organizations = $this->offering_model->get_organizations();
        $departments = $this->offering_model->get_departments();
        $this->load->view('admin/offering-edit', compact('offering', 'locations', 'organizations', 'departments'));
    }

    public function assign($offering_id)
    {
        $this->check_offering_manager_tags();

        $members = $this->member_model->get_all();
        $offering = $this->offering_model->get_offering($offering_id);

        if ($this->input->method() == 'post') {

            $this->form_validation->set_rules('member_id', 'Member', 'required|numeric');
            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {
                $member_id = $this->input->post('member_id');
                if ($this->offering_model->assign($offering_id, $member_id)) {
                    $data = [
                        'tag_id' => Tag::OFFERING_SUBMITTER,
                        'member_id' => $member_id,
                        'created_by' => $this->member->id
                    ];
                    $this->tag_model->tag_member($data);
                    $this->session->set_flashdata('success', 'Offering has been assigned.');
                    redirect("admin/offering/assign/$offering_id");
                }
            }
        }

        set_title("{$offering->title} - Assign");
        $assigned = $this->offering_model->get_assigned($offering_id);
        $this->load->view('admin/offering-assign', compact('offering', 'assigned', 'members'));
    }

    public function unassign($offering_id, $member_id)
    {
        $this->check_offering_manager_tags();

        $offering = $this->offering_model->get_offering($offering_id);
        if ($offering) {
            if ($this->offering_model->unassign($offering_id, $member_id)) {
                $this->session->set_flashdata('success', 'Member has been unassigned.');
            } else {
                $this->session->set_flashdata('error', 'There was an error unassigning the member.');
            }
            redirect("admin/offering/assign/$offering_id");
        }
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

    public function review($id)
    {
        $this->check_offering_manager_tags();

        $offering = $this->offering_model->get_queue_submission($id);


        if ($this->input->method() == 'post') {

            $data = $this->offering_model->get_data_from_post();
            $this->offering_model->set_rules($data, true);


            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Missing required fields.');
            } else {

                $data['start_date'] = $data['start_date'] ? date('Y-m-d', strtotime($data['start_date'])) : null;
                $data['end_date'] = $data['end_date'] ? date('Y-m-d', strtotime($data['end_date'])) : null;
                $data['repeat_end_on_date'] = $data['repeat_end_on_date'] ? date('Y-m-d', strtotime($data['repeat_end_on_date'])) : null;

                $data['modified_by'] = $this->member->id;
                $data['modified'] = date('Y-m-d H:i:s');

                foreach ($data as $key => $value) {
                    if (is_array($value)) {
                        $data[$key] = json_encode($value);
                    }
                }

                if ($this->input->post('approve')) {
                    $data['status'] = 'published';
                } else {
                    $data['status'] = 'rejected';
                }
                $this->offering_model->update($id, $data);
                $offering = $this->offering_model->get_offering($id);
                $this->notify_submitter($offering);
                if ($data['status'] == 'published') {
                    $this->session->set_flashdata('success', 'The submission has been published into the catalog.');
                    redirect('admin/offering/queue');
                } else {
                    $this->session->set_flashdata('success', 'The submission has been rejected.');
                    redirect('admin/offering/queue');
                }
            }
        }

        set_title("Pending - {$offering->title}");
        $locations = json_decode(file_get_contents('./js/locations.json'));
        $organizations = $this->offering_model->get_organizations();
        $departments = $this->offering_model->get_departments();
        $this->load->view('admin/offering-review', compact('offering', 'locations', 'organizations', 'departments'));
    }

    public function notify_submitter($offering)
    {
        $logo = base_url('img/email_logo.png');

        $description = nl2br($offering->description);
        if ($offering->status == 'published') {
            $subject = 'AIM Offering Catalog - Submission Approved';
            $message = "<img src='$logo' style='width:200px'>";
            $message .= "<p>Hello,</p>";
            $message .= "<p>The submission titled {$offering->title} has been approved, and has been published on the offering catalog.</p>";
            $message .= "<br>";
            $message .= anchor(base_url('offering'), "View Offering Catalog");
        } elseif ($offering->status == 'rejected') {
            $subject = 'AIM Offering Catalog - Submission Rejected';
            $message = "<img src='$logo' style='width:200px'>";
            $message .= "<p>Hello,</p>";
            $message .= "<p>The submission titled {$offering->title} has been rejected, and will not appear in the offering catalog.</p>";
        }

        if (isset($subject)) {
            $this->load->library('email');
            $this->email->to($offering->submitter_email);
            $this->email->cc($offering->contact_email);
            $this->email->from('noreply@aiminstitute.org', 'AIM Institute');
            $this->email->subject($subject);
            $this->email->message($message);
            return $this->email->send();
        }
    }

    public function ose_assessment($offering_id)
    {
        $this->load->model('ose_model');
        $this->load->helper('offering_helper');

        $submissions = $this->ose_model->get_submissions($offering_id);
        if (empty($submissions)) {
            $this->session->set_flashdata('error', 'The offering does not submitted an assessment.');
            redirect('admin/offering');
        }
        $submissions = array_combine(array_column($submissions, 'question_id'), $submissions);

        $results = array(
            'proficient' => 0,
            'developing' => 0,
            'neither' => 0
        );
        foreach ($submissions as $submission) {
            if ($submission->answer == 0) $results['neither']++;
            if ($submission->answer == 1) $results['developing']++;
            if ($submission->answer == 2) $results['proficient']++;
        }


        $offering = $this->offering_model->get($offering_id);
        $questions = $this->ose_model->get_questions();

        $categorized_questions = array();
        $categorized_questions['infrastructure'] = array_filter($questions, function ($question) {
            return $question->category == 'infrastructure';
        });
        $categorized_questions['goals and evaluation'] = array_filter($questions, function ($question) {
            return $question->category == 'goals and evaluation';
        });
        $categorized_questions['program components'] = array_filter($questions, function ($question) {
            return $question->category == 'program components';
        });
        $categorized_questions['stem practices'] = array_filter($questions, function ($question) {
            return $question->category == 'stem practices';
        });

        $questions = $categorized_questions;
        set_title("Best Practices Assessment for {$offering->title}");
        $this->load->view('admin/ose-assessment', compact('offering', 'submissions', 'questions', 'results'));
    }
}
