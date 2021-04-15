<?php
$this->addExtension('balance', 'http://www.verisign.com/epp/balance-1.0');

include_once(dirname(__FILE__) . '/eppRequests/eppBalanceInfoRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/eppBalanceInfoResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\eppBalanceInfoRequest', 'Metaregistrar\EPP\eppBalanceInfoResponse');