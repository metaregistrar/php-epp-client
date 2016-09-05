<?php
$this->addExtension('rgp','urn:ietf:params:xml:ns:rgp-1.0');

include_once(dirname(__FILE__) . '/eppRequests/eppRgpRestoreRequest.php');
include_once(dirname(__FILE__) . '/eppResponses/eppRgpRestoreResponse.php');
$this->responses['Metaregistrar\\EPP\\eppRgpRestoreRequest'] = 'Metaregistrar\\EPP\\eppRgpRestoreResponse';