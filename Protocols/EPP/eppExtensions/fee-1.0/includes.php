<?php
$this->addExtension('fee','urn:ietf:params:xml:ns:fee-1.0');
#
# Load the fee-1.0 specific additions
# https://datatracker.ietf.org/doc/rfc8748/
# SOURCE: https://tools.ietf.org/html/draft-ietf-regext-epp-fees-15
#
include_once(dirname(__FILE__) . '/eppRequests/feeEppCheckDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/feeEppCheckDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\feeEppCheckDomainRequest', 'Metaregistrar\EPP\feeEppCheckDomainResponse');
