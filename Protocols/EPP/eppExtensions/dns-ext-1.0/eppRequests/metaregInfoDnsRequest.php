<?php
namespace Metaregistrar\EPP;

/*
 * <epp xmlns='urn:ietf:params:xml:ns:epp-1.0'  >
    <command>
        <info>
            <dns-ext:info xmlns:dns-ext='http://www.metaregistrar.com/epp/dns-ext-1.0'>
                <dns-ext:name>john-test-01-02-2017-01.com</dns-ext:name>
            </dns-ext:info>
        </info>
        <clTRID>ABC-12345</clTRID>
    </command>
</epp>
 */

/**
 * Class metaregInfoDnsRequest
 * @package Metaregistrar\EPP
 */
class metaregInfoDnsRequest extends metaregDnsRequest {
    public function __construct(eppDomain $domain) {
        parent::__construct(eppRequest::TYPE_INFO);
        if (!strlen($domain->getDomainname())) {
            throw new eppException('Domain object does not contain a valid domain name');
        }
        $dname = $this->createElement('dns-ext:name', $domain->getDomainname());
        $this->dnsObject->appendChild($dname);
    }

}