<?php

$this->addExtension('command-ext', 'http://www.metaregistrar.com/epp/command-ext-1.0');
$this->addExtension('command-ext-domain', 'http://www.metaregistrar.com/epp/command-ext-domain-1.0');
$this->addExtension('command-ext-contact', 'http://www.metaregistrar.com/epp/command-ext-contact-1.0');

include_once(dirname(__FILE__) . '/eppRequests/metaregEppPrivacyRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\metaregEppPrivacyRequest', 'Metaregistrar\EPP\eppUpdateDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregEppAutorenewRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\metaregEppAutorenewRequest', 'Metaregistrar\EPP\eppUpdateDomainResponse');

include_once(dirname(__FILE__) . '/eppData/metaregInfoDomainOptions.php');
include_once(dirname(__FILE__) . '/eppRequests/metaregInfoDomainRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\metaregInfoDomainRequest', 'Metaregistrar\EPP\eppInfoDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregEppAuthcodeRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\metaregEppAuthcodeRequest', 'Metaregistrar\EPP\eppInfoDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregEppTransferExtendedRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\metaregEppTransferExtendedRequest', 'Metaregistrar\EPP\eppTransferResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregEppUpdateContactRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\metaregEppUpdateContactRequest', 'Metaregistrar\EPP\eppUpdateContactResponse');

include_once(dirname(__FILE__) . '/eppResponses/metaregEppInfoContactResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\eppInfoContactRequest', 'Metaregistrar\EPP\metaregEppInfoContactResponse');