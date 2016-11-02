<?php
namespace Metaregistrar\EPP;
/*
<extension>
    <dnsbe:ext>
      <dnsbe:transfer>
        <dnsbe:domain>
         <dnsbe:registrant>c25</dnsbe:registrant>
         <dnsbe:billing>c20</dnsbe:billing>
         <dnsbe:tech>c21</dnsbe:tech>
        <dnsbe:ns>
            <domain:hostAttr>
                <domain:hostName>ns1.superdomain.be</domain:hostName>
            </domain:hostAttr>
            <domain:hostAttr>
                <domain:hostName>ns.test.be</domain:hostName> </domain:hostAttr>
          </dnsbe:ns>
        </dnsbe:domain>
      </dnsbe:transfer>
    </dnsbe:ext>
</extension>
*/
class dnsbeEppTransferRequest extends eppTransferRequest {
    function __construct($operation, $object, $tech = null, $billing = null, $onsite = null, $registrant = null) {
        parent::__construct($operation, $object);
        $this->addDnsbeExtension($tech, $billing, $onsite, $registrant);
        $this->addSessionId();
    }

    public function addDnsbeExtension($tech = null, $billing = null, $onsite=null, $registrant=null) {
        $this->addExtension('xmlns:dnsbe', 'http://www.dns.be/xml/epp/dnsbe-1.0');
        $ext = $this->createElement('extension');
        $sidnext = $this->createElement('dnsbe:ext');
        $create = $this->createElement('dnsbe:transfer');
        $contact = $this->createElement('dnsbe:domain');
        if ($registrant) {
            $contact->appendChild($this->createElement('dnsbe:registrant', $registrant));
        } else {
            $contact->appendChild($this->createElement('dnsbe:registrant', '#AUTO#'));
        }
        if ($billing) {
            $contact->appendChild($this->createElement('dnsbe:billing', $billing));
        }
        if ($tech) {
            $contact->appendChild($this->createElement('dnsbe:tech', $tech));
        }
        if ($onsite) {
            $contact->appendChild($this->createElement('dnsbe:onsite', $onsite));
        }
        $create->appendChild($contact);
        $sidnext->appendChild($create);
        $ext->appendChild($sidnext);
        $this->getCommand()->appendChild($ext);
    }
}