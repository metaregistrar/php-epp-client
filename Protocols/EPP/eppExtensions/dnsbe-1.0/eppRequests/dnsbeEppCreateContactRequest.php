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
class dnsbeEppCreateContactRequest extends eppCreateContactRequest {
    function __construct($createinfo, $contacttype='licensee') {

        if ($createinfo instanceof eppContact) {
            parent::__construct($createinfo);
            $this->addDnsbeExtension($createinfo,$contacttype);
        } else {
            throw new eppException('DNSBE does not support Host objects');
        }
        $this->addSessionId();
    }


    public function addDnsbeExtension(eppContact $contact, $contacttype) {
        $this->addExtension('xmlns:dnsbe', 'http://www.dns.be/xml/epp/dnsbe-1.0');
        $ext = $this->createElement('extension');
        $dnsbeext = $this->createElement('dnsbe:ext');
        $create = $this->createElement('dnsbe:create');
        $contact = $this->createElement('dnsbe:contact');
        $contact->appendChild($this->createElement('dnsbe:type', $contacttype));
        $contact->appendChild($this->createElement('dnsbe:lang', 'nl'));
        $create->appendChild($contact);
        $dnsbeext->appendChild($create);
        $ext->appendChild($dnsbeext);
        $this->command->appendChild($ext);

    }

}