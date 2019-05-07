<?php
$this->addExtension('cozacontact', 'http://co.za/epp/extensions/cozacontact-1-0');
$this->addExtension('cozac', 'http://co.za/epp/extensions/cozacontact-1-0');

#
# Load the COZA specific additions
#
include_once(dirname(__FILE__) . '/eppRequests/cozaEppInfoContactRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/cozaEppInfoContactResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\cozaEppInfoContactRequest', 'Metaregistrar\EPP\cozaEppInfoContactResponse');


include_once(dirname(__FILE__) . '/eppRequests/cozaEppBalanceCheckRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/cozaEppBalanceCheckResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\cozaEppBalanceCheckRequest', 'Metaregistrar\EPP\cozaEppBalanceCheckResponse');

include_once(dirname(__FILE__) . '/eppRequests/cozaEppCancelPendingActionRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\cozaEppCancelPendingActionRequest', 'Metaregistrar\EPP\eppUpdateContactResponse');
