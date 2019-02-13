<?php
$this->addExtension('cozacontact', 'http://co.za/epp/extensions/cozacontact-1-0');
#
# Load the COZA specific additions
#

include_once(dirname(__FILE__) . '/eppRequests/cozaEppInfoContactRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/cozaEppInfoContactResponse.php');