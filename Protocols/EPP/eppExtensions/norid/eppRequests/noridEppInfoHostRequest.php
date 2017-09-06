<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=hinf for example request/response

class noridEppInfoHostRequest extends eppInfoHostRequest {

    use noridEppHostRequestTrait;
    
    function __construct(noridEppHost $host, $namespacesinroot = true) {
        parent::__construct($host, $namespacesinroot);
        $this->setExtHost($host);
        $this->addSessionId();
    }

    public function setExtHost(noridEppHost $host) {
        // Add Norid contact if specified
        if (strlen($host->getExtContact())) {
            $this->getHostExtension('info')->appendChild($this->createElement('no-ext-host:contact', $host->getExtContact()));
        }

        // Add Norid sponsoring client ID if specified
        if (strlen($host->getExtSponsoringClientID())) {
            $this->getHostExtension('info')->appendChild($this->createElement('no-ext-host:sponsoringClientID', $host->getExtSponsoringClientID()));
        }
    }

}