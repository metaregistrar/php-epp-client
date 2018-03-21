<?php
$this->addService('registrar','http://www.eurid.eu/xml/epp/registrarFinance-1.0');

include_once(dirname(__FILE__) . '/eppRequests/euridEppRegistrarInfoRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/euridEppRegistrarInfoResponse.php');

$this->addCommandResponse('Metaregistrar\EPP\euridEppRegistrarInfoRequest', 'Metaregistrar\EPP\euridEppRegistrarInfoResponse');


