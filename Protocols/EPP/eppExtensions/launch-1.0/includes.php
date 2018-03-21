<?php
$this->addExtension('launch','urn:ietf:params:xml:ns:launch-1.0');

include_once(dirname(__FILE__) . '/eppRequests/eppLaunchCheckRequest.php');
include_once(dirname(__FILE__) . '/eppRequests/eppLaunchCreateDomainRequest.php');
$this->addCommandResponse('Metaregistrar\\EPP\\eppLaunchCheckRequest', 'Metaregistrar\\EPP\\eppLaunchCheckResponse');

include_once(dirname(__FILE__) . '/eppResponses/eppLaunchCheckResponse.php');
include_once(dirname(__FILE__) . '/eppResponses/eppLaunchCreateDomainResponse.php');
$this->addCommandResponse('Metaregistrar\\EPP\\eppLaunchCreateDomainRequest', 'Metaregistrar\\EPP\\eppLaunchCreateDomainResponse');

