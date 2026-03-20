<?php

$this->addService('domain', 'http://www.dns.pl/nask-epp-schema/domain-2.1');

include_once(dirname(__FILE__) . '/eppRequests/plEppCreateDomainRequest.php');
include_once(dirname(__FILE__) . '/eppRequests/plEppUpdateDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/plEppInfoDomainResponse.php');

$this->addCommandResponse('Metaregistrar\EPP\plEppCreateDomainRequest', 'Metaregistrar\EPP\eppCreateDomainResponse');
$this->addCommandResponse('Metaregistrar\EPP\plEppUpdateDomainRequest', 'Metaregistrar\EPP\eppUpdateDomainResponse');
$this->addCommandResponse('Metaregistrar\EPP\eppInfoDomainRequest', 'Metaregistrar\EPP\plEppInfoDomainResponse');
