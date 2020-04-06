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

class lvEppCreateContactRequest extends eppCreateContactRequest {

    /**
     * euridEppCreateContactRequest constructor.
     * @param eppContact|null $createinfo
     * @param string $contacttype
     * @param string $language
     * @throws eppException
     */
    function __construct(lvEppContact $createinfo) {
        parent::__construct($createinfo);
        $this->addContactExtension($createinfo);
        $this->addSessionId();
    }

    /**
     * @param object eppContact
     */
    public function addContactExtension(lvEppContact $createinfo) {
        $this->addExtension('xmlns:lvcontact', 'http://www.nic.lv/epp/schema/lvcontact-ext-1.0');
        $create = $this->createElement('lvcontact:create');

        if(!empty($createinfo->getContactExtReg())) {
            $create->appendChild($this->createElement('lvcontact:regNr', $createinfo->getContactExtReg()));
            $this->getExtension()->appendChild($create);
        }

        if(!empty($createinfo->getContactExtVat())) {
            $create->appendChild($this->createElement('lvcontact:vatNr', $createinfo->getContactExtVat()));
            $this->getExtension()->appendChild($create);
        }
    }

}
