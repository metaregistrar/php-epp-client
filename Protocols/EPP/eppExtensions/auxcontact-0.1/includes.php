<?php
$this->addExtension('auxcontact', 'urn:ietf:params:xml:ns:auxcontact-0.1');

include_once(__DIR__ . '/eppResponses/AuxContactInfoDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\eppInfoDomainRequest', 'Metaregistrar\EPP\AuxContactInfoDomainResponse');

include_once(__DIR__ . '/eppRequests/AuxContactCreateDomainRequest.php');
include_once(__DIR__ . '/eppRequests/AuxContactUpdateDomainRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\AuxContactCreateDomainRequest', 'Metaregistrar\EPP\eppCreateDomainResponse');
$this->addCommandResponse('Metaregistrar\EPP\AuxContactUpdateDomainRequest', 'Metaregistrar\EPP\eppUpdateDomainResponse');
