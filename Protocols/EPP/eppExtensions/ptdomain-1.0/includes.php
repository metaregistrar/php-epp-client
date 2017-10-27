<?php
$this->addExtension('ptdomain', 'http://eppdev.dns.pt/schemas/ptdomain-1.0');

include_once(dirname(__FILE__) . '/eppRequests/ptEppInfoDomainRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\ptEppInfoDomainRequest', 'Metaregistrar\EPP\eppInfoDomainResponse');
