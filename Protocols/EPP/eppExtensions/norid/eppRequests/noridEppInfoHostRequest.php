<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=hinf for example request/response

class noridEppInfoHostRequest extends noridEppHostRequest {
    
    function __construct(noridEppHost $host, $namespacesinroot = true) {
        $this->setNamespacesinroot($namespacesinroot);
        parent::__construct(eppRequest::TYPE_INFO);
        $this->setHost($host);
        $this->addSessionId();
    }

    public function setHost(noridEppHost $host) {
        $this->hostobject->appendChild($this->createElement('host:name', $host->getHostname()));

        // Add Norid contact if specified
        if (strlen($host->getExtContact())) {
            $this->getHostExtension()->appendChild($this->createElement('no-ext-host:contact', $host->getExtContact()));
        }

        // Add Norid sponsoring client ID if specified
        if (strlen($host->getExtSponsoringClientID())) {
            $this->getHostExtension()->appendChild($this->createElement('no-ext-host:sponsoringClientID', $host->getExtSponsoringClientID()));
        }

    }

}