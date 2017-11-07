<?php
$this->addExtension('dnsbe', 'http://www.dns.be/xml/epp/dnsbe-1.0');
#
# Load the DNSBE specific additions
#

include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppCreateResponse.php');

include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppCreateDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppCreateDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\dnsbeEppCreateDomainRequest', 'Metaregistrar\EPP\dnsbeEppCreateDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppCreateContactRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppCreateContactResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\dnsbeEppCreateContactRequest', 'Metaregistrar\EPP\dnsbeEppCreateContactResponse');

include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppAuthcodeRequest.php');
$this->addCommandResponse('Metaregistrar\EPP\dnsbeEppAuthcodeRequest', 'Metaregistrar\EPP\eppResponse');

include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppInfoDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppInfoDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\dnsbeEppInfoDomainRequest', 'Metaregistrar\EPP\dnsbeEppInfoDomainResponse');

include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppTransferRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppTransferResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\dnsbeEppTransferRequest', 'Metaregistrar\EPP\dnsbeEppTransferResponse');

include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppUpdateContactRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppUpdateContactResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\dnsbeEppUpdateContactRequest', 'Metaregistrar\EPP\dnsbeEppUpdateContactResponse');

include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppDeleteDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppDeleteDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\dnsbeEppDeleteDomainRequest', 'Metaregistrar\EPP\dnsbeEppDeleteDomainResponse');



