<?php

$this->addExtension('secDNS-2.1', 'http://www.dns.pl/nask-epp-schema/secDNS-2.1');
$this->removeExtension('urn:ietf:params:xml:ns:secDNS-1.1');

include_once(dirname(__FILE__) . '/eppRequests/plEppDnssecUpdateDomainRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\plEppDnssecUpdateDomainRequest', 'Metaregistrar\EPP\eppUpdateDomainResponse');

include_once(dirname(__FILE__) . '/eppResponses/plEppDnssecInfoDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\plEppInfoDomainRequest', 'Metaregistrar\EPP\plEppDnssecInfoDomainResponse');
