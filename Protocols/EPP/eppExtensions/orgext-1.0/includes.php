<?php
$this->addExtension('orgext', 'urn:ietf:params:xml:ns:epp:orgext-1.0');

include_once(dirname(__FILE__) . '/eppRequests/orgextEppUpdateDomainRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\orgextEppUpdateDomainRequest', 'Metaregistrar\EPP\eppUpdateDomainResponse');