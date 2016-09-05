<?php
$this->addExtension('secDNS','urn:ietf:params:xml:ns:secDNS-1.1');

include_once(dirname(__FILE__) . '/eppData/eppSecdns.php');

include_once(dirname(__FILE__) . '/eppRequests/eppDnssecUpdateDomainRequest.php');
$this->responses['Metaregistrar\\EPP\\eppDnssecUpdateDomainRequest'] = 'Metaregistrar\\EPP\\eppUpdateDomainResponse';
