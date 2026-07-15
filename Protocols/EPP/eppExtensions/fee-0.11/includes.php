<?php
$this->addExtension('fee-0.11','urn:ietf:params:xml:ns:fee-0.11');
#
# Load the fee-0.11 specific additions
# SOURCE: https://datatracker.ietf.org/doc/draft-ietf-regext-epp-fees/00/
#
include_once(dirname(__FILE__) . '/eppRequests/fee011EppCheckDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/fee011EppCheckDomainResponse.php');

$this->addCommandResponse('Metaregistrar\EPP\fee011EppCheckDomainRequest.php','Metaregistrar\EPP\fee011EppCheckDomainResponse');