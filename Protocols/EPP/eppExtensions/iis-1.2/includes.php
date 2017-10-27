<?php
$this->addExtension('iis', 'urn:se:iis:xml:epp:iis-1.2');

// Add the commands and responses specific to this registry
// Please make sure the corresponding PHP files are present!
include_once(dirname(__FILE__) . '/eppRequests/iisEppUpdateDomainClientDeleteRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\iisEppUpdateDomainClientDeleteRequest', 'Metaregistrar\EPP\eppUpdateDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/iisEppCreateContactRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\iisEppCreateContactRequest', 'Metaregistrar\EPP\eppCreateContactResponse');

include_once(dirname(__FILE__) . '/eppResponses/iisEppInfoDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\eppInfoDomainRequest', 'Metaregistrar\EPP\iisEppInfoDomainResponse');

