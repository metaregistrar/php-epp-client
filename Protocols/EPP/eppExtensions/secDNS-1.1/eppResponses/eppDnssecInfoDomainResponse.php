<?php
/**
 * <?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:domain="urn:ietf:params:xml:ns:domain-1.0" xmlns:contact="urn:ietf:params:xml:ns:contact-1.0" xmlns:host="urn:ietf:params:xml:ns:host-1.0" xmlns:ext="http://www.metaregistrar.com/epp/ext-1.0" xmlns:secDNS="urn:ietf:params:xml:ns:secDNS-1.1">
<response xmlns:rgp="urn:ietf:params:xml:ns:rgp-1.0">
<result code="1000">
<msg>Command completed succesfully</msg>
</result>
<extension xmlns:ext="http://www.metaregistrar.com/epp/ext-1.0" xmlns:rgp="urn:ietf:params:xml:ns:rgp-1.0" xmlns:command-ext="http://www.metaregistrar.com/epp/command-ext-1.0" xmlns:command-ext-domain="http://www.metaregistrar.com/epp/command-ext-domain-1.0">
<rgp:upData>
<rgp:rgpStatus s="pendingRestore"></rgp:rgpStatus>
</rgp:upData>
</extension>
<trID>
<svTRID>MTR_f1587c2c6a1ad3ea8efc1ccf8ebeb0f522ec9e87</svTRID>
<clTRID>57a86cecf19bf</clTRID>
</trID>
</response>
</epp>

 */
namespace Metaregistrar\EPP;

/**
 * Class eppDnssecInfoDomainResponse
 * @package Metaregistrar\EPP
 */
class eppDnssecInfoDomainResponse extends eppInfoDomainResponse {

    public function getKeydata() {
        // Check if dnssec is enabled on this interface
        if ($this->findNamespace('secDNS')) {
            $xpath = $this->xPath();
            $result = $xpath->query('/epp:epp/epp:response/epp:extension/secDNS:infData/*');
            $keys = array();
            if ($result->length > 0) {
                foreach ($result as $keydata) {
                    /* @var $keydata \DOMElement */
                    $secdns = new eppSecdns();
                    $secdns->setFlags($keydata->getElementsByTagName('flags')->item(0)->nodeValue);
                    $secdns->setAlgorithm($keydata->getElementsByTagName('alg')->item(0)->nodeValue);
                    $secdns->setProtocol($keydata->getElementsByTagName('protocol')->item(0)->nodeValue);
                    $secdns->setPubkey($keydata->getElementsByTagName('pubKey')->item(0)->nodeValue);
                    $keys[] = $secdns;
                }
            }
            return $keys;
        }
        return null;
    }

}