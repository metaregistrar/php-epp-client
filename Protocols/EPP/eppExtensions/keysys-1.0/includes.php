<?php
$this->addExtension('keysys','http://www.key-systems.net/epp/keysys-1.0');

include_once(dirname(__FILE__) . '/eppRequests/rrpproxyEppUpdateDomainRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\rrpproxyEppUpdateDomainRequest', 'Metaregistrar\EPP\eppUpdateDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/rrpproxyEppWhoisPrivacyRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\rrpproxyEppWhoisPrivacyRequest', 'Metaregistrar\EPP\eppUpdateDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/rrpproxyEppTrusteeRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\rrpproxyEppTrusteeRequest', 'Metaregistrar\EPP\eppUpdateDomainResponse');
