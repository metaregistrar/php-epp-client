<?php
$this->addService('http://www.eurid.eu/xml/epp/registrarFinance-1.0', 'registrar');

include_once(dirname(__FILE__) . '/eppRequests/euridEppRegistrarInfoRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/euridEppRegistrarInfoResponse.php');

$this->addCommandResponse('Metaregistrar\EPP\euridEppRegistrarInfoRequest', 'Metaregistrar\EPP\euridEppRegistrarInfoResponse');


