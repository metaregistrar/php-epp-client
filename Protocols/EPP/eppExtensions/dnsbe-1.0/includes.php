<?php
#
# Load the DNSBE specific additions
#
include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppCreateDomainRequest.php');
include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppCreateContactRequest.php');
include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppCreateNsgroupRequest.php');
include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppAuthcodeRequest.php');
include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppInfoDomainRequest.php');
include_once(dirname(__FILE__) . '/eppRequests/dnsbeEppTransferRequest.php');

include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppCreateResponse.php');
include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppCreateContactResponse.php');
include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppInfoDomainResponse.php');
include_once(dirname(__FILE__) . '/eppResponses/dnsbeEppCreateNsgroupResponse.php');
