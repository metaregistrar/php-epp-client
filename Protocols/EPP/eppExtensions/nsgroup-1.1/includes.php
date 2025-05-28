<?php

include_once(dirname(__FILE__) . '/eppRequests/euridEppInfoNsgroupRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/euridEppInfoNsgroupResponse.php');

$this->addCommandResponse('Metaregistrar\EPP\euridEppInfoNsgroupRequest', 'Metaregistrar\EPP\euridEppInfoNsgroupResponse');
