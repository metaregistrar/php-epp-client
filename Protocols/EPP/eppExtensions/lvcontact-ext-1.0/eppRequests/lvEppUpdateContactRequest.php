<?php
namespace Metaregistrar\EPP;

class lvEppUpdateContactRequest extends eppUpdateContactRequest {

    /**
     * lvEppUpdateContactRequest constructor.
     * @param eppContact|null $updateinfo
     * @param string $regNr
     * @param string $vatNr
     * @throws eppException
     */
    function __construct($objectname, $addInfo = null, $removeInfo = null, $updateInfo = null, $namespacesinroot = true) {
        parent::__construct($objectname, $addInfo, $removeInfo, $updateInfo, $namespacesinroot);
        $this->updateContactExtension($updateInfo);
        $this->addSessionId();
    }

    /**
     * @param object eppContact
     */
    public function updateContactExtension(lvEppContact $updateinfo) {
        $this->addExtension('xmlns:lvcontact', 'http://www.nic.lv/epp/schema/lvcontact-ext-1.0');
        
        $create = $this->createElement('lvcontact:update');

        if(!empty($updateinfo->getContactExtReg())) {
            $create->appendChild($this->createElement('lvcontact:regNr', $updateinfo->getContactExtReg()));
            $this->getExtension()->appendChild($create);
        }

        if(!empty($updateinfo->getContactExtVat())) {
            $create->appendChild($this->createElement('lvcontact:vatNr', $updateinfo->getContactExtVat()));
            $this->getExtension()->appendChild($create);
        }
    }

}