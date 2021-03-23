<?php

namespace Metaregistrar\EPP;
/*
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0"
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xmlns:epp="urn:ietf:params:xml:ns:epp-1.0"
     xmlns:domain="urn:ietf:params:xml:ns:domain-1.0"
     xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd
                urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd">
<command>
  <check>
     <domain:check>
       <domain:name>semaphore.be</domain:name>
       <domain:name>greatdomain.be</domain:name>
       <domain:name>test-v2.be</domain:name>
       <domain:name>dns-domain-22.be</domain:name>
       <domain:name>dnà</domain:name>
       <domain:name>xn--belgi-rsa</domain:name>
       <domain:name>$$$</domain:name>
       <domain:name>belgië</domain:name>
    </domain:check>
  </check>
<extension>
  <dnsbe:ext xmlns:dnsbe="http://www.dns.be/xml/epp/dnsbe-1.0">
    <dnsbe:check>
      <dnsbe:domain version="2.0"/>
    </dnsbe:check>
  </dnsbe:ext>
</extension>
  <clTRID>clientref-00029</clTRID>
</command>
</epp>
*/

class dnsbeEppCheckDomainRequest extends eppCheckDomainRequest {
   function __construct($checkrequest, $namespacesinroot = true) {
      parent::__construct($checkrequest, $namespacesinroot = true);
      $this->addDnsbeExtension();
   }

   private function addDnsbeExtension() {
      $this->addExtension('xmlns:dnsbe', 'http://www.dns.be/xml/epp/dnsbe-1.0');
      $ext = $this->createElement('extension');
      $dnsext = $this->createElement('dnsbe:ext');
      $dnsext->setAttribute("xmlns:dnsbe","http://www.dns.be/xml/epp/dnsbe-1.0");
      $check = $this->createElement('dnsbe:check');
      $version = $this->createElement('dnsbe:domain');
      $version->setAttribute("version","2.0");
      $check->appendChild($version);
      $dnsext->appendChild($check);
      $ext->appendChild($dnsext);
      $this->getCommand()->appendChild($ext);
      $this->addSessionId();
   }

}