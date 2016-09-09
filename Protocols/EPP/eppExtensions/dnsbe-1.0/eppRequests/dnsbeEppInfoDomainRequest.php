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
        $this->addExtension('xmlns:dnsbe', 'http://www.dns.be/xml/epp/dnsbe-1.0');
        $ext = $this->createElement('extension');
        $sidnext = $this->createElement('dnsbe:ext');
        $info = $this->createElement('dnsbe:info');
        $infodomain = $this->createElement('dnsbe:domain');
        $infodomain->setAttribute('version', '2.0');
        $info->appendChild($infodomain);
        $sidnext->appendChild($info);
        $ext->appendChild($sidnext);
        $this->getCommand()->appendChild($ext);

    }

}