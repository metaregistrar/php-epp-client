<?php
$this->addExtension('no-ext-domain', 'http://www.norid.no/xsd/no-ext-domain-1.1');

// Domain
include_once(dirname(__FILE__) . '/eppData/noridEppDomain.php');
include_once(dirname(__FILE__) . '/eppRequests/noridEppDomainRequestTrait.php');

// Domain Create/Withdraw
include_once(dirname(__FILE__) . '/eppRequests/noridEppCreateDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppCreateDomainResponse.php');
$this->addCommandResponse('Metaregistrar\\EPP\\noridEppCreateDomainRequest', 'Metaregistrar\\EPP\\noridEppCreateDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/noridEppWithdrawDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppWithdrawDomainResponse.php');
$this->addCommandResponse('Metaregistrar\\EPP\\noridEppWithdrawDomainRequest', 'Metaregistrar\\EPP\\noridEppWithdrawDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/noridEppTransferRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppTransferResponse.php');
$this->addCommandResponse('Metaregistrar\\EPP\\noridEppTransferRequest', 'Metaregistrar\\EPP\\noridEppTransferResponse');

include_once(dirname(__FILE__) . '/eppRequests/noridEppDeleteDomainRequest.php');
$this->addCommandResponse('Metaregistrar\\EPP\\noridEppDeleteDomainRequest', 'Metaregistrar\\EPP\\noridEppDeleteResponse');

include_once(dirname(__FILE__) . '/eppRequests/noridEppInfoDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppInfoDomainResponse.php');
$this->addCommandResponse('Metaregistrar\\EPP\\noridEppInfoDomainRequest', 'Metaregistrar\\EPP\\noridEppInfoDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/noridEppUpdateDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/noridEppUpdateDomainResponse.php');
$this->addCommandResponse('Metaregistrar\\EPP\\noridEppUpdateDomainRequest', 'Metaregistrar\\EPP\\noridEppUpdateDomainResponse');




