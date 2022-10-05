<?php
defined('BASEPATH') or exit('No direct script access allowed');

require './vendor/netsuite/NetSuiteService.php';

class Netsuiteapi
{
    public function getDataCenterUrls()
    {
        $service = new NetSuiteService();

        $request = new GetDataCenterUrlsRequest();
        $request->account = NS_ACCOUNT;
        $dataCenterUrls = $service->getDataCenterUrls($request);
        return $dataCenterUrls;
    }

    public function getContactsByEmail($email, $operator = 'contains')
    {
        $service = new NetSuiteService();
        
        $emailSearchField = new SearchStringField();
        $emailSearchField->operator = $operator;
        $emailSearchField->searchValue = $email;

        $search = new ContactSearchBasic();
        $search->email = $emailSearchField;

        $request = new SearchRequest();
        $request->searchRecord = $search;

        $searchResponse = $service->search($request);

        $result = $searchResponse->searchResult;
        return $result;
    }
}
