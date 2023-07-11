<?php
$this->addExtension('finance', 'http://www.unitedtld.com/epp/finance-1.0');

#
# For use with the HR connection
#
include_once(dirname(__FILE__) . '/eppRequests/eppFinanceInfoRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/eppFinanceInfoResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\eppFinanceInfoRequest', 'Metaregistrar\EPP\eppFinanceInfoResponse');
