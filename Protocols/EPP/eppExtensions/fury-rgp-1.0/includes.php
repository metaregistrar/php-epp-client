<?php
$this->addExtension('fury-rgp-1.0','urn:ietf:params:xml:ns:fury-rgp-1.0');

include_once(dirname(__FILE__) . '/eppResponses/eppRgpInfoDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\eppInfoDomainRequest', 'Metaregistrar\EPP\furyRgpInfoDomainResponse');
