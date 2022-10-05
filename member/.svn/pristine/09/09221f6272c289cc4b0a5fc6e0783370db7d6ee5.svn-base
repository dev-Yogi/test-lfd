<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Netsuite extends Staff_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('netsuiteapi');
	}

	public function contact_lookup()
	{
		$contacts = null;
		if ($this->input->method() == 'post') {
			$this->form_validation->set_rules('email', 'Email', 'required');
			if ($this->form_validation->run() == false) {
				$this->session->set_flashdata('error', 'Missing required fields.');
			} else {
				$email = $this->input->post('email');
				$results = $this->netsuiteapi->getContactsByEmail($email);
				$contacts = $results->recordList->record;
			}
		}

		set_title('NetSuite Lookup');
		$this->load->view('admin/staff/netsuite/contact-lookup', compact('contacts'));
	}

	public function contact_lookup_upload()
	{
		$contacts = null;
		if ($this->input->method() == 'post') {
		    // Check file size
		    if ($_SERVER["CONTENT_LENGTH"] > ((int)ini_get('post_max_size') * 1024 * 1024)) {
		        // File too big
		        $this->form_validation->set_rules('csv', "CSV file", 'required', array('required' => 'File too large.'));
		    } else {
		        // Check if file is there
		        if (!empty($_FILES['csv']['name'])) {
		            $upload = $this->upload_csv();
		            // Check for upload error
		            if (!empty($upload['error'])) {
		                // There was an upload error
		                $this->form_validation->set_rules('csv', "CSV file", 'required', array('required' => $upload['error']));
		            } else {
		                // Setting a rule here required to prevent empty form validation
		                $this->form_validation->set_rules('csv', 'file upload', 'required');
		                $_POST['csv'] = $_FILES['csv']['name'];

		                // Start Import
		                $handle = fopen(UPLOAD_FOLDER_ADMIN_CSV . $upload,"r");
		                $i = 0;
		                while (($row = fgetcsv($handle, 10000, ",")) != FALSE)
		                {
		                    $i++;
		                    $contact = new stdClass();
		                    $contact->firstName = $row[0];
		                    $contact->lastName = $row[1];
                    		$contact->title = $row[2];
                    		$contact->company_name = $row[3];
		                    $contact->email = $row[4];
		                    if (!empty($row[4])) {
		                    	$results = $this->netsuiteapi->getContactsByEmail($row[4], 'is');
		                    	$records = $results->recordList->record;
		                    	if (!empty($records[0])) {
		                    		$record = $records[0];
		                    		$contact->internalId = $record->internalId;
		                    	}
		                    }
		                    $contacts[] = $contact;
		                }
		                $this->session->set_flashdata('netsuite_contacts_results', $contacts);
		            }
		        } else {
		            // No file submitted
		            $this->form_validation->set_rules('csv', 'File upload', 'required');
		        }

		        if ($this->form_validation->run() == false) {
		            // $this->db = $this->load->database('default', TRUE);
		            // $this->session->set_flashdata('error', 'Missing required fields.');
		        } else {
		        }
		    }
		} else {
			if ($missing_only = $this->input->get('missing_only')) {
        		$this->session->keep_flashdata('netsuite_contacts_results');
				if ($missing_only == 'false') {
					$contacts = $this->session->flashdata('netsuite_contacts_results');
				} elseif ($missing_only == 'true') {
					$contacts = $this->session->flashdata('netsuite_contacts_results');
					foreach ($contacts as $key => $contact) {
						if (!empty($contact->internalId)) {
							unset($contacts[$key]);
						}
					}
				}
			}
		}
		set_title('NetSuite Lookup');
		$this->load->view('admin/staff/netsuite/contact-lookup', compact('contacts'));
	}


	public function upload_csv()
	{
	    $config['upload_path'] = UPLOAD_FOLDER_ADMIN_CSV;
	    $config['allowed_types'] = 'txt|csv';
	    $config['max_size'] = 50000;
	    $config['overwrite'] = TRUE;
	    $config['file_name'] = url_title($this->member->id . '-' . date('Y-m-d His'));

	    $this->load->library('upload', $config);

	    if (!$this->upload->do_upload('csv')) {
	        $error = array('error' => $this->upload->display_errors());
	    } else {
	        $data = $this->upload->data();
	        return $data['file_name'];
	    }
	    return $error;
	}
}
