<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends Staff_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('product_model');
		$this->load->model('invoice_model');
	}

	public function index()
	{
		$invoices = $this->invoice_model->get_all();
		set_title('Invoices');
		$this->load->view('admin/invoices', compact('invoices'));
	}

	public function create()
	{
		$products = $this->product_model->get_all();
		$members = $this->member_model->get_all();

		if ($this->input->method() == 'post') {
			$this->form_validation->set_rules('product_id', 'Product', 'required');
			$this->form_validation->set_rules('member_id', 'Recipient Member', 'required');
			$this->form_validation->set_rules('first_name', 'First Name', 'required');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required');
			$this->form_validation->set_rules('billing_address', 'Billing Address', 'required');
			$this->form_validation->set_rules('billing_city_st', 'Billing City, St', 'required');
			$this->form_validation->set_rules('billing_zip', 'Billing ZIP', 'required');

			if ($this->form_validation->run() == false) {
				$this->session->set_flashdata('error', 'Missing required fields.');
			} else {
				$product = $this->product_model->get($this->input->post('product_id'));
				$member = $this->member_model->get($this->input->post('member_id'));
				$type = $this->input->post('type');

				if (!empty($product && !empty($member))) {
					$this->session->set_flashdata('error', 'The invoice could not be created.');
				}

				// Create invoice
				$invoice = array(
					'product_id' => $this->input->post('product_id'),
					'member_id' => $this->input->post('member_id'),
					'status' => 'draft',
					'type' => $type,
					'amount' => $type == 'monthly' ? $product->price_monthly : $product->price_annual,
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'billing_address' => $this->input->post('billing_address'),
					'billing_city_st' => $this->input->post('billing_city_st'),
					'billing_zip' => $this->input->post('billing_zip'),
					'phone' => $this->input->post('phone'),
					'email' => $this->input->post('email'),
					'created_by' => $this->member->id,
				);
				$invoice_id = $this->invoice_model->add($invoice);

				if ($invoice_id) {
					$sent = $this->send($invoice_id);
					if ($sent) {
						$this->invoice_model->set_status($invoice_id, 'sent', $this->member->id);
						redirect("admin/invoice/success/$invoice_id");
					} else {
						$this->session->set_flashdata('error', 'The email failed to send.');
					}
				} else {
					$this->session->set_flashdata('error', 'The invoice could not be created.');
				}
			}
		}

		set_title('New Invoice');
		$this->load->view('admin/invoice-form', compact('products', 'members'));
	}

	public function send($invoice_id)
	{
		$this->load->library('email');

		$invoice = $this->invoice_model->get($invoice_id);

		$subject = "New Invoice";
		$hash = md5($invoice->created);
		$link = base_url("payment/pay/$invoice_id/$hash");
		$message = "Please visit the following link to make the payment: $link";

		$this->email->from('localhost', 'ATS');
		$this->email->to($invoice->email);
		$this->email->bcc(array(
			$this->member->email
		));
		$this->email->subject($subject);
		$this->email->message($message);
		$sent = $this->email->send();
		return $sent;
	}


	public function void($invoice_id)
	{
		$data = array(
			'status' => 'void',
			'modified_by' => $this->member->id,
		);
		$updated = $this->invoice_model->update($invoice_id, $data);

		if ($updated) {
			$this->session->set_flashdata('success', 'The invoice has been voided.');
			redirect("admin/invoice");
			return;
		}

		$this->session->set_flashdata('error', 'The invoice could not be voided.');
		redirect("admin/invoice");
		return;
	}

	public function success($invoice_id)
	{
		$invoice = $this->invoice_model->get($invoice_id);
		$product = $this->product_model->get($invoice->product_id);

		set_title('Invoice Sent');
		$this->load->view('admin/invoice-form-success', compact('invoice', 'product'));
	}
}
