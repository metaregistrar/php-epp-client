<?php
$this->addExtension('ext-message', 'http://www.nic.at/xsd/at-ext-message-1.0');
#
# For use with the NICAT connection
#
include_once(dirname(__FILE__) . '/eppRequests/atEppPollRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/atEppPollResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\atEppPollRequest', 'Metaregistrar\EPP\atEppPollResponse');