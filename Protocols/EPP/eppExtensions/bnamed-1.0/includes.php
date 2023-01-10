<?php
include_once(dirname(__FILE__) . '/eppRequests/bNamedEppCreateDomainRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\bNamedEppCreateDomainRequest', 'Metaregistrar\EPP\eppCreateDomainResponse');
$this->addCommandResponse('Metaregistrar\EPP\bNamedEppUndeleteDomainRequest', 'Metaregistrar\EPP\eppUndeleteDomainResponse');

