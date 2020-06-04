<?php
$this->addExtension('ficora','http://www.ficora.fi/epp/ficora');

include_once(dirname(__FILE__) . '/eppData/ficoraEppContactPostalInfo.php');
include_once(dirname(__FILE__) . '/eppData/ficoraEppDomain.php');

include_once(dirname(__FILE__) . '/eppRequests/ficoraEppRenewRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\ficoraEppRenewRequest', 'Metaregistrar\EPP\eppRenewResponse');

include_once(dirname(__FILE__) . '/eppRequests/ficoraEppInfoDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/ficoraEppInfoDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\ficoraEppInfoDomainRequest', 'Metaregistrar\EPP\ficoraEppInfoDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/ficoraEppInfoContactRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/ficoraEppInfoContactResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\ficoraEppInfoContactRequest', 'Metaregistrar\EPP\ficoraEppInfoContactResponse');

include_once(dirname(__FILE__) . '/eppRequests/ficoraEppUpdateContactRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\ficoraEppUpdateContactRequest', 'Metaregistrar\EPP\eppUpdateContactResponse');

include_once(dirname(__FILE__) . '/eppRequests/ficoraEppCheckBalanceRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/ficoraEppCheckBalanceResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\ficoraEppCheckBalanceRequest', 'Metaregistrar\EPP\ficoraEppCheckBalanceResponse');

include_once(dirname(__FILE__) . '/eppRequests/ficoraEppUpdateDomainRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\ficoraEppUpdateDomainRequest', 'Metaregistrar\EPP\eppUpdateDomainResponse');

include_once(dirname(__FILE__) . '/eppResponses/ficoraEppCheckContactResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\eppCheckContactRequest', 'Metaregistrar\EPP\ficoraEppCheckContactResponse');

include_once(dirname(__FILE__) . '/eppRequests/ficoraEppCreateContactRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\ficoraEppCreateContactRequest', 'Metaregistrar\EPP\eppCreateContactResponse');

include_once(dirname(__FILE__). '/eppRequests/ficoraEppDnssecUpdateDomainRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\ficoraEppDnssecUpdateDomainRequest', 'Metaregistrar\EPP\eppUpdateDomainResponse');
