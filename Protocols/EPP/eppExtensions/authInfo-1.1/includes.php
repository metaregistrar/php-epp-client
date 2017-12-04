<?php
$this->addExtension('authInfo', 'http://www.eurid.eu/xml/epp/authInfo-1.1');
#
# For use with the EURID connection
#
include_once(dirname(__FILE__) . '/eppRequests/euridEppInfoDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/euridEppInfoDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\euridEppInfoDomainRequest', 'Metaregistrar\EPP\euridEppInfoDomainResponse');
