<?php
$this->addExtension('fury-2.1','urn:ietf:params:xml:ns:fury-2.1');

include_once(dirname(__FILE__) . '/eppRequests/furyCreateDomainRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\furyCreateDomainRequest', 'Metaregistrar\EPP\eppCreateDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/furyUpdateContactRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\furyUpdateContactRequest', 'Metaregistrar\EPP\eppUpdateContactResponse');


include_once(dirname(__FILE__) . '/eppResponses/furyInfoDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\eppInfoDomainRequest', 'Metaregistrar\EPP\furyInfoDomainResponse');
