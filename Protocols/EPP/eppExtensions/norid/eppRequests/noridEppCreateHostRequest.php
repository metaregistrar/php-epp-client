<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=hcre for example request/response

class noridEppCreateHostRequest extends eppCreateHostRequest {

    use noridEppHostRequestTrait;
    
    function __construct(noridEppHost $host, $namespacesinroot = true) {
        parent::__construct($host, $namespacesinroot);
        $this->setExtHost($host);
        $this->addSessionId();
    }

    public function setExtHost(noridEppHost $host) {
        // Add Norid contact extension
        if (strlen($host->getExtContact())) {
            $this->getHostExtension('create')->appendChild($this->createElement('no-ext-host:contact', $host->getExtContact()));
        }
    }

}