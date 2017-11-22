<?php

include_once(dirname(__FILE__) . '/eppRequests/euridEppRegistrarInfoRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/euridEppRegistrarInfoResponse.php');

$this->addCommandResponse('Metaregistrar\EPP\euridEppRegistrarInfoRequest', 'Metaregistrar\EPP\euridEppRegistrarInfoResponse');


