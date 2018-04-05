<?php
#
# Load the fee-1.0 specific additions
# SOURCE: https://tools.ietf.org/html/draft-ietf-regext-epp-fees-10#section-5.1
#
include_once(dirname(__FILE__) . '/eppRequests/feeEppCheckDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/feeEppCheckDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\feeEppCheckDomainRequest', 'Metaregistrar\EPP\feeEppCheckDomainResponse');
