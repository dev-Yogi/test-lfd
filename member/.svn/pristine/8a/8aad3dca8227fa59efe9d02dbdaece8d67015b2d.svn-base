<?php
defined('BASEPATH') or exit('No direct script access allowed');

require './vendor/authorize-net-php-sdk/autoload.php';

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class Authorizenet
{
    public function getAuthentication()
    {
        /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName("7G7F5gc2");
        $merchantAuthentication->setTransactionKey("825ANk823cD9T5nT");
        return $merchantAuthentication;
    }

    public function getEnvironment()
    {
        // return \net\authorize\api\constants\ANetEnvironment::PRODUCTION;
        return \net\authorize\api\constants\ANetEnvironment::SANDBOX;
    }

    function getAnAcceptPaymentPage($data)
    {
        // Use this object format
        // $data = new stdClass();
        // $data->description = "";
        // $data->first_name = "";
        // $data->last_name = "";
        // $data->company_name = "";
        // $data->address = "";
        // $data->city_st = "";
        // $data->zip = "";
        // $data->phone = "";
        // $data->email = "";
        // $data->amount = "";

        $merchantAuthentication = $this->getAuthentication();

        // Set the transaction's refId
        $refId = 'ref' . time();

        // Order description
        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber($data->invoice_id);

        $customerAddress = new AnetAPI\CustomerAddressType();
        $customerAddress->setFirstName($data->first_name);
        $customerAddress->setLastName($data->last_name);
        $customerAddress->setAddress($data->address);
        $city_state = explode(",", $data->city_st);
        $customerAddress->setCity(trim($city_state[0]));
        $customerAddress->setState(trim($city_state[1] ?? null));
        $customerAddress->setZip($data->zip);
        $customerAddress->setCountry("USA");
        $customerAddress->setPhoneNumber($data->phone);

        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        $customerData->setEmail($data->email);

        //create a transaction
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($data->amount);
        $transactionRequestType->setOrder($order);
        $transactionRequestType->setBillTo($customerAddress);
        $transactionRequestType->setCustomer($customerData);

        // Set Hosted Form options
        $setting1 = new AnetAPI\SettingType();
        $setting1->setSettingName("hostedPaymentButtonOptions");
        $setting1->setSettingValue('{"text": "Pay"}');

        $setting2 = new AnetAPI\SettingType();
        $setting2->setSettingName("hostedPaymentOrderOptions");
        $setting2->setSettingValue('{"show": false}');

        $setting3 = new AnetAPI\SettingType();
        $setting3->setSettingName("hostedPaymentReturnOptions");
        $setting3->setSettingValue(
            '{"url": "https://mysite.com/receipt", "cancelUrl": "https://mysite.com/cancel", "showReceipt": false}'
        );

        $url = base_url("payment/iframecommunicator");
        $setting4 = new AnetAPI\SettingType();
        $setting4->setSettingName("hostedPaymentIFrameCommunicatorUrl");
        $setting4->setSettingValue(
            '{"url": "' . $url . '"}'
        );

        $setting5 = new AnetAPI\SettingType();
        $setting5->setSettingName("hostedPaymentCustomerOptions");
        $setting5->setSettingValue(
            '{"showEmail": true, "requiredEmail": true, "addPaymentProfile": true}'
        );

        $setting6 = new AnetAPI\SettingType();
        $setting6->setSettingName("hostedPaymentBillingAddressOptions");
        $setting6->setSettingValue(
            '{"show": false, "required": false}'
        );

        // Build transaction request
        $request = new AnetAPI\GetHostedPaymentPageRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);

        $request->addToHostedPaymentSettings($setting1);
        $request->addToHostedPaymentSettings($setting2);
        $request->addToHostedPaymentSettings($setting3);
        $request->addToHostedPaymentSettings($setting4);
        $request->addToHostedPaymentSettings($setting5);
        $request->addToHostedPaymentSettings($setting6);

        //execute request
        $controller = new AnetController\GetHostedPaymentPageController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            // $response->getToken();
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            log_message('error', 'ERROR :  Failed to get hosted payment page token');
            log_message('error', "RESPONSE : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText());
        }
        return $response;
    }

    function getTransaction($transactionId)
    {
        $merchantAuthentication = $this->getAuthentication();

        $request = new AnetAPI\GetTransactionDetailsRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setTransId($transactionId);

        $controller = new AnetController\GetTransactionDetailsController($request);

        $response = $controller->executeWithApiResponse($this->getEnvironment());

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            log_message('debug', "SUCCESS: Transaction Status:" . $response->getTransaction()->getTransactionStatus() . "\n");
            log_message('debug', "Auth Amount:" . $response->getTransaction()->getAuthAmount() . "\n");
            log_message('debug', "Trans ID:" . $response->getTransaction()->getTransId() . "\n");
            return $response;
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            log_message('error', "ERROR :  Invalid response");
            log_message('error', "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText());
            return null;
        }
    }

    function createCustomerProfileFromTransaction(
        $transId,
        $customerId,
        $customerName
    ) {
        $merchantAuthentication = $this->getAuthentication();

        // Set the transaction's refId
        $refId = 'ref' . time();

        $customerProfile = new AnetAPI\CustomerProfileBaseType();
        $customerProfile->setMerchantCustomerId($customerId);
        // $customerProfile->setEmail($customerEmail);
        $customerProfile->setDescription($customerName);

        $request = new AnetAPI\CreateCustomerProfileFromTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setTransId($transId);

        // Order description
        // $order = new AnetAPI\OrderType();
        // $order->setInvoiceNumber($data->invoice_id);

        // You can either specify the customer information in form of customerProfileBaseType object
        $request->setCustomer($customerProfile);
        //  OR   
        // You can just provide the customer Profile ID
        //$request->setCustomerProfileId("123343");

        $controller = new AnetController\CreateCustomerProfileFromTransactionController($request);

        $response = $controller->executeWithApiResponse($this->getEnvironment());

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            log_message('debug', "SUCCESS: PROFILE ID : " . $response->getCustomerProfileId() . "\n");
        } else {
            log_message('error', "ERROR :  Invalid response\n");
            $errorMessages = $response->getMessages()->getMessage();
            log_message('error', "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n");
        }
        return $response;
    }

    function createSubscriptionFromCustomerProfile(
        $name,
        $price,
        $customerProfileId,
        $customerPaymentProfileId,
        $intervalLength = 1,
        $totalOccurences = 10000
    ) {
        /* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */

        $merchantAuthentication = $this->getAuthentication();

        // Set the transaction's refId
        $refId = 'ref' . time();

        // Subscription Type Info
        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setName($name);

        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength($intervalLength);
        $interval->setUnit("months");

        $startDate = new DateTime(date("Y-m-d"));
        $nextMonth = new DateTime($startDate->format('d-m-Y H:i:s'));
        $nextMonth->modify('last day of +1 month');

        if ($startDate->format('d') > $nextMonth->format('d')) {
            $startDate->add($startDate->diff($nextMonth));
        } else {
            $startDate->add(new DateInterval('P1M'));
        }

        // Subtract one month if limited time sub since first month has already been paid for by initial transaction
        if (empty($totalOccurences)) {
            $totalOccurences = 9999;
        } else {
            $totalOccurences = $totalOccurences - 1;
        }
        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate($startDate);
        $paymentSchedule->setTotalOccurrences($totalOccurences);
        $paymentSchedule->setTrialOccurrences("0");

        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setAmount($price);
        $subscription->setTrialAmount("0.00");

        $profile = new AnetAPI\CustomerProfileIdType();
        $profile->setCustomerProfileId($customerProfileId);
        $profile->setCustomerPaymentProfileId($customerPaymentProfileId);

        $subscription->setProfile($profile);

        $request = new AnetAPI\ARBCreateSubscriptionRequest();
        $request->setmerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($request);

        $response = $controller->executeWithApiResponse($this->getEnvironment());

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            log_message('debug', "SUCCESS: Subscription ID : " . $response->getSubscriptionId() . "\n");
        } else {
            log_message('error', "ERROR :  Invalid response\n");
            $errorMessages = $response->getMessages()->getMessage();
            log_message('error', "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n");
        }

        return $response;
    }
}
