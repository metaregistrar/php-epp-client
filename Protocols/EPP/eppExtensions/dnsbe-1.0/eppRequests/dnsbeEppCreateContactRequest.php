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

    /**
     * dnsbeEppCreateContactRequest constructor.
     * @param eppContact|null $createinfo
     * @param string $contacttype
     * $param string $language
     * @throws eppException
     */
    function __construct($createinfo, $contacttype='licensee', $language = 'en') {
        parent::__construct($createinfo);
        $this->addDnsbeExtension($contacttype, $language);
        $this->addSessionId();
    }

    /**
     * @param string $contacttype
     * @param string $language
     */
    public function addDnsbeExtension($contacttype, $language) {
        $dnsbeext = $this->createElement('dnsbe:ext');
        $this->setNamespace('xmlns:dnsbe', 'http://www.dns.be/xml/epp/dnsbe-1.0',$dnsbeext);
        $create = $this->createElement('dnsbe:create');
        $contact = $this->createElement('dnsbe:contact');
        $contact->appendChild($this->createElement('dnsbe:type', $contacttype));
        $contact->appendChild($this->createElement('dnsbe:lang', $language));
        $create->appendChild($contact);
        $dnsbeext->appendChild($create);
        $this->getExtension()->appendChild($dnsbeext);
    }

}