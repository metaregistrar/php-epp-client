<?php
$this->addExtension('authInfo', 'http://www.eurid.eu/xml/epp/authInfo-1.1');
#
# For use with the EURID connection
#
include_once(dirname(__FILE__) . '/eppRequests/authEppInfoDomainRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/authEppInfoDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\authEppInfoDomainRequest', 'Metaregistrar\EPP\authEppInfoDomainResponse');
