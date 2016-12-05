<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=hcre for example request/response

class noridEppCreateHostRequest extends noridEppHostRequest {
    
    function __construct(noridEppHost $host, $namespacesinroot = true) {
        $this->setNamespacesinroot($namespacesinroot);
        parent::__construct(eppRequest::TYPE_CREATE);
        $this->setHost($host);
        $this->addSessionId();
    }

    public function setHost(noridEppHost $host) {
        if (!strlen($host->getHostname())) {
            throw new eppException('No valid hostname in create host request');
        }
        
        // Create host object structure
        $this->hostobject->appendChild($this->createElement('host:name', $host->getHostname()));
        $addresses = $host->getIpAddresses();
        if (is_array($addresses)) {
            foreach ($addresses as $address => $type) {
                $ipaddress = $this->createElement('host:addr', $address);
                $ipaddress->setAttribute('ip', $type);
                $this->hostobject->appendChild($ipaddress);
            }
        }

        // Add Norid contact extension
        if (strlen($host->getExtContact())) {
            $this->getHostExtension()->appendChild($this->createElement('no-ext-host:contact', $host->getExtContact()));
        }
    }

}