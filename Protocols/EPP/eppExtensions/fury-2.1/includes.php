<?php
// Documentation: https://www.cira.ca/en/resources/documents/registry/cira-fury-21-epp-extension/#info
$this->addExtension('fury-2.1','urn:ietf:params:xml:ns:fury-2.1');

include_once(dirname(__FILE__) . '/eppRequests/furyPropertiesRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/furyPropertiesResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\furyPropertiesRequest', 'Metaregistrar\EPP\furyPropertiesResponse');

include_once(dirname(__FILE__) . '/eppRequests/furyCreateDomainRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\furyCreateDomainRequest', 'Metaregistrar\EPP\eppCreateDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/furyUpdateContactRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\furyUpdateContactRequest', 'Metaregistrar\EPP\eppUpdateContactResponse');


include_once(dirname(__FILE__) . '/eppResponses/furyInfoDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\eppInfoDomainRequest', 'Metaregistrar\EPP\furyInfoDomainResponse');
