<?php
$this->addExtension('poll', 'http://www.eurid.eu/xml/epp/poll-1.2');
#
# For use with the EURID connection
#
include_once(dirname(__FILE__) . '/eppRequests/euridEppPollRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/euridEppPollResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\euridEppPollRequest', 'Metaregistrar\EPP\euridEppPollResponse');