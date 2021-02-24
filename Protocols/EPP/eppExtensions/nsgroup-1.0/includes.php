<?php
$this->addExtension('nsgroup', 'http://www.dns.be/xml/epp/nsgroup-1.0');

include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppInfoNsgroupRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppInfoNsgroupResponse.php');

$this->addCommandResponse('Metaregistrar\EPP\dnsbeEppInfoNsgroupRequest', 'Metaregistrar\EPP\dnsbeEppInfoNsgroupResponse');

include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppCreateNsgroupRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppCreateNsgroupResponse.php');

$this->addCommandResponse('Metaregistrar\EPP\dnsbeEppCreateNsgroupRequest', 'Metaregistrar\EPP\dnsbeEppCreateNsgroupResponse');

include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppDeleteNsgroupRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppDeleteNsgroupResponse.php');

$this->addCommandResponse('Metaregistrar\EPP\dnsbeEppDeleteNsgroupRequest', 'Metaregistrar\EPP\dnsbeEppDeleteNsgroupResponse');

include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppUpdateNsgroupRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppUpdateNsgroupResponse.php');

$this->addCommandResponse('Metaregistrar\EPP\dnsbeEppUpdateNsgroupRequest', 'Metaregistrar\EPP\dnsbeEppUpdateNsgroupResponse');
