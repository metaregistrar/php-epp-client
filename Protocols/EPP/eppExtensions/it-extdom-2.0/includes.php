<?php
$this->addExtension('extdom-2.0', 'http://www.nic.it/ITNIC-EPP/extdom-2.0');

include_once(dirname(__FILE__) . '/eppResponses/itEppCreateDomainResponse.php');
include_once(dirname(__FILE__) . '/eppResponses/itEppInfoDomainResponse.php');
include_once(dirname(__FILE__) . '/eppRequests/itEppTransferRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/itEppPollResponse.php');

$this->addCommandResponse('Metaregistrar\EPP\eppCreateDomainRequest', 'Metaregistrar\EPP\itEppCreateDomainResponse');
$this->addCommandResponse('Metaregistrar\EPP\eppInfoDomainRequest', 'Metaregistrar\EPP\itEppInfoDomainResponse');
$this->addCommandResponse('Metaregistrar\EPP\itEppTransferRequest', 'Metaregistrar\EPP\eppTransferResponse');
$this->addCommandResponse('Metaregistrar\EPP\eppPollRequest', 'Metaregistrar\EPP\itEppPollResponse');
