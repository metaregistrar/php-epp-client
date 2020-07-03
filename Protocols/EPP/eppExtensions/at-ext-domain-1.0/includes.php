<?php

$this->addExtension('at-ext-domain', 'http://www.nic.at/xsd/at-ext-domain-1.0');

include_once(dirname(__FILE__) . '/eppRequests/atEppDeleteDomainRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\atEppDeleteDomainRequest', 'Metaregistrar\EPP\eppDeleteResponse');

include_once(dirname(__FILE__) . '/eppRequests/atEppWithdrawRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\atEppWithdrawRequest', 'Metaregistrar\EPP\eppResponse');

