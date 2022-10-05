<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('authorizenet');
		$this->load->model('agreement_model');
		$this->load->model('invoice_model');
		$this->load->model('product_model');
	}

	public function index()
	{
		$token = $this->authorizenet->getAnAcceptPaymentPage();
		if ($token->getToken()) {
			$this->load->view('payment/form', compact('token'));
		}
	}

	public function pay($invoice_id, $hash = null)
	{
        if (empty($this->member)) {
            $this->session->set_flashdata('error', 'Please log in.');
            redirect('user/login');
        }

		$invoice = $this->invoice_model->get($invoice_id);
		$product = $this->product_model->get($invoice->product_id);
		$hash_confirm = md5($invoice->created);
		if ($hash != $hash_confirm) {
			redirect('fail/unauthorized');
		}

		$data = new stdClass();
		$data->invoice_id = $invoice->id;
		$data->first_name = $invoice->first_name;
		$data->last_name = $invoice->last_name;
		$data->address = $invoice->billing_address;
		$data->city_st = $invoice->billing_city_st;
		$data->zip = $invoice->billing_zip;
		$data->phone = $invoice->phone;
		$data->email = $invoice->email;
		$data->amount = $invoice->amount;

		$token = $this->authorizenet->getAnAcceptPaymentPage($data);
		if ($token->getToken()) {
			$this->load->view('payment/form', compact('token', 'invoice', 'product'));
		}
	}

	// public function pay($agreement_id)
	// {
	// 	$agreement = $this->agreement_model->get($agreement_id);
	// 	$product = $this->product_model->get($agreement->invoice->product_id);

	// 	$data = new stdClass();
	// 	$data->invoice_id = $agreement->invoice->id;
	// 	$data->first_name = $agreement->employer->first_name;
	// 	$data->last_name = $agreement->employer->last_name;
	// 	$data->company_name = $agreement->employer->company_name;
	// 	$data->address = $agreement->invoice->billing_address;
	// 	$data->city_st = $agreement->invoice->billing_city_st;
	// 	$data->zip = $agreement->invoice->billing_zip;
	// 	$data->phone = $agreement->employer->phone;
	// 	$data->email = $agreement->employer->email;
	// 	$data->amount = $agreement->invoice->amount;

	// 	$token = $this->authorizenet->getAnAcceptPaymentPage($data);
	// 	if ($token->getToken()) {
	// 		$this->load->view('payment/form', compact('token', 'agreement', 'product'));
	// 	}
	// }

	public function iframecommunicator()
	{
		$this->load->view('payment/iframecommunicator');
	}

	public function complete($transaction_id)
	{
        if (empty($this->member)) {
            $this->session->set_flashdata('error', 'Please log in.');
            redirect('user/login');
        }

		$transaction = $this->authorizenet->getTransaction($transaction_id);
		if ($transaction) {
			$invoice_id = $transaction->getTransaction()->getOrder()->getInvoiceNumber();
			$data = array(
				'status' => 'paid',
				'transaction_id' => $transaction->getTransaction()->getTransId(),
				'modified_by' => $this->member->id ?? null,
			);
			$this->invoice_model->update($invoice_id, $data);

			$invoice = $this->invoice_model->get($invoice_id);

			if ($invoice->type == 'monthly' || $invoice->type == 'annual') {
				$this->invoice_model->create_recurring($invoice_id, $transaction_id);
			}

			$this->load->view('payment/complete', array('transaction' => $data));
		}
	}
}
