<?php
$this->addExtension('hr', 'http://www.dns.hr/epp/hr-1.0');

#
# For use with the HR connection
#
include_once(dirname(__FILE__) . '/eppRequests/hrEppInfoContactRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/hrEppInfoContactResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\hrEppInfoContactRequest', 'Metaregistrar\EPP\hrEppInfoContactResponse');
