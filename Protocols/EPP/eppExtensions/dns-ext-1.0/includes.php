<?php
$this->addExtension('dns-ext', 'http://www.metaregistrar.com/epp/dns-ext-1.0');
include_once(dirname(__FILE__) . '/eppRequests/metaregDnsRequest.php');

include_once(dirname(__FILE__) . '/eppRequests/metaregCreateDnsRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/metaregCreateDnsResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\metaregCreateDnsRequest', 'Metaregistrar\EPP\metaregCreateDnsResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregDeleteDnsRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/metaregDeleteDnsResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\metaregDeleteDnsRequest', 'Metaregistrar\EPP\metaregDeleteDnsResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregUpdateDnsRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/metaregUpdateDnsResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\metaregUpdateDnsRequest', 'Metaregistrar\EPP\metaregUpdateDnsResponse');

include_once(dirname(__FILE__) . '/eppRequests/metaregInfoDnsRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/metaregInfoDnsResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\metaregInfoDnsRequest', 'Metaregistrar\EPP\metaregInfoDnsResponse');
