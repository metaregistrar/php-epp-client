<?php
namespace Metaregistrar\EPP;
/*
<extension>
    <dnsbe:ext>
        <dnsbe:create>
            <dnsbe:contact>
                <dnsbe:type>licensee</dnsbe:type>
                <dnsbe:vat>BE 123 4576 5645</dnsbe:vat>
                <dnsbe:lang>nl</dnsbe:lang>
            </dnsbe:contact>
        </dnsbe:create>
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
        $dnsbeext = $this->createElement('dnsbe:ext');
        $this->setNamespace('xmlns:dnsbe', 'http://www.dns.be/xml/epp/dnsbe-1.0',$dnsbeext);
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
        $dnsbeext->appendChild($create);
        $this->getExtension()->appendChild($dnsbeext);
    }
}