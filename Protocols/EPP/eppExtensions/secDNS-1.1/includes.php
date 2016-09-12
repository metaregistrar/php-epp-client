<?php
$this->addExtension('secDNS','urn:ietf:params:xml:ns:secDNS-1.1');

include_once(dirname(__FILE__) . '/eppData/eppSecdns.php');

include_once(dirname(__FILE__) . '/eppRequests/eppDnssecCreateDomainRequest.php');
$this->responses['Metaregistrar\\EPP\\eppDnssecCreateDomainRequest'] = 'Metaregistrar\\EPP\\eppCreateDomainResponse';

include_once(dirname(__FILE__) . '/eppRequests/eppDnssecUpdateDomainRequest.php');
$this->responses['Metaregistrar\\EPP\\eppDnssecUpdateDomainRequest'] = 'Metaregistrar\\EPP\\eppUpdateDomainResponse';

include_once(dirname(__FILE__) . '/eppResponses/eppDnssecInfoDomainResponse.php');
$this->responses['Metaregistrar\\EPP\\eppInfoDomainRequest'] = 'Metaregistrar\\EPP\\eppDnssecInfoDomainResponse';
