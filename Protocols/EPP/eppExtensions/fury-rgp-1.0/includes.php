<?php
// Documentation: https://www.cira.ca/en/resources/documents/registry/cira-fury-rgp-1-epp-extension/
$this->addExtension('fury-rgp-1.0','urn:ietf:params:xml:ns:fury-rgp-1.0');

include_once(dirname(__FILE__) . '/eppResponses/furyRgpInfoDomainResponse.php');
$this->addCommandResponse('Metaregistrar\EPP\eppInfoDomainRequest', 'Metaregistrar\EPP\furyRgpInfoDomainResponse');
