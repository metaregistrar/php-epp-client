<?php
namespace Metaregistrar\EPP;
/*
<extension>
    <dnsbe:ext xmlns:dnsbe="http://www.dns.be/xml/epp/dnsbe-1.0">
        <dnsbe:info>
            <dnsbe:domain version="2.0"/>
        </dnsbe:info>
    </dnsbe:ext>
</extension>


*/
class dnsbeEppInfoDomainRequest extends eppInfoDomainRequest {
    function __construct($infodomain, $hosts = null) {
        parent::__construct($infodomain, $hosts);
        $this->addDnsbeExtension();
        $this->addSessionId();
    }


    public function addDnsbeExtension() {
        $dnsbeext = $this->createElement('dnsbe:ext');
        $this->setNamespace('xmlns:dnsbe', 'http://www.dns.be/xml/epp/dnsbe-1.0',$dnsbeext);
        $info = $this->createElement('dnsbe:info');
        $infodomain = $this->createElement('dnsbe:domain');
        $infodomain->setAttribute('version', '2.0');
        $info->appendChild($infodomain);
        $dnsbeext->appendChild($info);
        $this->getExtension()->appendChild($dnsbeext);
    }

}