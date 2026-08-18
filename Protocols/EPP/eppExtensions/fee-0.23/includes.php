<?php
$this->addExtension('fee-0.23','urn:ietf:params:xml:ns:fee-0.23');
#
# Load the fee-0.9 specific additions
# SOURCE: https://tools.ietf.org/html/draft-brown-epp-fees-06
#
include_once(dirname(__FILE__) . '/eppRequests/fee0EppCheckDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/fee0EppCheckDomainResponse.php');
