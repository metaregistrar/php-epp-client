<?php
namespace Metaregistrar\EPP;
/*
<extension>
    <contact-ext:create>
        <contact-ext:type>registrant</contact-ext:type>
        <contact-ext:lang>en</contact-ext:lang>
    </contact-ext:create>
</extension>

*/

class euridEppCreateContactRequest extends eppCreateContactRequest {

    /**
     * euridEppCreateContactRequest constructor.
     * @param eppContact|null $createinfo
     * @param string $contacttype
     * @param string $language
     * @throws eppException
     */
    function __construct(euridEppContact $createinfo) {
        parent::__construct($createinfo);
        $this->addContactExtension($createinfo);
        $this->addSessionId();
    }

    /**
     * @param object eppContact
     */
    public function addContactExtension(euridEppContact $createinfo) {
        $this->addExtension('xmlns:contact-ext', 'http://www.eurid.eu/xml/epp/contact-ext-1.1');
        $create = $this->createElement('contact-ext:create');
        if(!empty($createinfo->getContactExtType())) {
            $create->appendChild($this->createElement('contact-ext:type', $createinfo->getContactExtType()));
        }
        if(!empty($createinfo->getContactExtVat())) {
            $create->appendChild($this->createElement('contact-ext:vat', $createinfo->getContactExtVat()));
        }
        $create->appendChild($this->createElement('contact-ext:lang', $createinfo->getContactExtLang()));

        $this->getExtension()->appendChild($create);
    }

}