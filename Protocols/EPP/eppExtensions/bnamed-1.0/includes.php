<?php
include_once(dirname(__FILE__) . '/eppRequests/bNamedEppCreateDomainRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\bNamedEppCreateDomainRequest', 'Metaregistrar\EPP\eppCreateDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/bNamedEppUndeleteDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/bNamedEppUndeleteDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\bNamedEppUndeleteDomainRequest', 'Metaregistrar\EPP\bNamedEppUndeleteDomainResponse');

