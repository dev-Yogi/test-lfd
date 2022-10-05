<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_model extends CI_Model
{
    public function get($id)
    {
        $this->db->select('invoices.*');
        $this->db->where('invoices.id', $id);
        $this->db->from('invoices');

        $this->db->select('products.label');
        $this->db->join('products', 'invoices.product_id = products.id', 'left');
        $invoice = $this->db->get()->first_row();
        return $invoice;
    }

    public function get_all()
    {
        $this->db->select('invoices.*');
        $this->db->order_by('created');
        $this->db->from('invoices');

        $this->db->select('products.label');
        $this->db->join('products', 'invoices.product_id = products.id', 'left');
        $invoices = $this->db->get()->result();

        return $invoices;
    }

    public function add($data)
    {
        $this->db->insert('invoices', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update('invoices');
        return $this->db->affected_rows();
    }

    public function set_status($id, $status, $member_id)
    {
        $this->db->set('status', $status);
        $this->db->set('modified_by', $member_id);
        $this->db->where('id', $id);
        $this->db->update('invoices');
        return $this->db->affected_rows();
    }

    public function create_recurring($invoice_id, $transaction_id)
    {
        $this->load->library('authorizenet');

        $invoice = $this->get($invoice_id);
        $customer_name = $invoice->last_name . ", " . $invoice->first_name;
        $profile = $this->authorizenet->createCustomerProfileFromTransaction($transaction_id, $invoice->member_id, $customer_name);
        sleep(15);
        $interval = $invoice->type == 'monthly' ? 1 : 12;
        $subscription = $this->authorizenet->createSubscriptionFromCustomerProfile($invoice->label, $invoice->amount, $profile->getCustomerProfileId(), $profile->getCustomerPaymentProfileIdList()[0], $interval);

        if (empty($profile->getCustomerProfileId()) || empty($subscription->getSubscriptionId())) {
            return false;
        }

        $data = array(
            'member_id' => $invoice->member_id,
            'authorize_net_profile_id' => $profile->getCustomerProfileId(),
            'created_by' => $invoice->modified_by,
        );
        $this->db->insert('authnet_profiles', $data);

        $data = array(
            'authorize_net_profile_id' => $profile->getCustomerProfileId(),
            'authorize_net_subscription_id' => $subscription->getSubscriptionId(),
            'invoice_id' => $invoice_id,
            'active' => 1,
            'amount' => $invoice->amount,
            'created_by' => $invoice->modified_by
        );
        $this->db->insert('authnet_subscriptions', $data);

        return true;
    }

    public function get_by_member_id($member_id)
    {
        $this->db->select('invoices.*');
        $this->db->where('member_id', $member_id);
        $this->db->order_by('created');
        $this->db->from('invoices');

        $this->db->select('products.label');
        $this->db->join('products', 'invoices.product_id = products.id', 'left');

        $this->db->select('employers.company_name');
        $this->db->join('employers', 'invoices.member_id = employers.id', 'left');
        $invoices = $this->db->get()->result();

        return $invoices;
    }
}
