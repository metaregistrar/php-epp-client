<?php
namespace Metaregistrar\EPP;
/*
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
    <extension>
        <dnsbe:ext xmlns:dnsbe="http://www.dns.be/xml/epp/dnsbe-1.0">
            <dnsbe:command>
                 <dnsbe:requestAuthCode>
                    <dnsbe:domainName>test</dnsbe:domainName>
                    <dnsbe:url>http://www.agent-website.be/transfer?name=test</dnsbe:url>
                </dnsbe:requestAuthCode>
                <dnsbe:clTRID>request-code-test</dnsbe:clTRID>
            </dnsbe:command>
        </dnsbe:ext>
    </extension>
</epp>



*/
class dnsbeEppAuthcodeRequest extends eppRequest {
    function __construct($domainname) {
        parent::__construct();
        if (is_string($domainname)) {
            if (strlen($domainname) > 0) {
                $this->addDnsbeExtension($domainname);
            } else {
                throw new eppException("Domain name length may not be 0 on eppAuthcodeRequest");
            }
        } else {
            throw new eppException("Domain name must be string on eppAuthcodeRequest");
        }

    }

    private function addDnsbeExtension($domainname) {
        $this->addExtension('xmlns:dnsbe', 'http://www.dns.be/xml/epp/dnsbe-1.0');
        $ext = $this->createElement('extension');
        $dnsext = $this->createElement('dnsbe:ext');
        $command = $this->createElement('dnsbe:command');
        $authcode = $this->createElement('dnsbe:requestAuthCode');
        $authcode->appendChild($this->createElement('dnsbe:domainName', $domainname));
        $authcode->appendChild($this->createElement('dnsbe:url', 'http://www.metaregistrar.com/tranfer?domainname=' . $domainname));
        $command->appendChild($authcode);
        $dnsext->appendChild($command);
        $ext->appendChild($dnsext);
        $this->epp->appendChild($ext);
    }

}