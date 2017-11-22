<?php

include_once(dirname(__FILE__) . '/eppRequests/siEppRegistrarInfoRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/siEppRegistrarInfoResponse.php');

$this->addCommandResponse('Metaregistrar\EPP\siEppRegistrarInfoRequest', 'Metaregistrar\EPP\siEppRegistrarInfoResponse');


