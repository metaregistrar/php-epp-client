<?php


namespace Metaregistrar\EPP;


class ficoraEppUpdateContactRequest extends eppUpdateContactRequest
{
    /**
     * ficoraEppUpdateContactRequest constructor.
     * @param $objectname
     * @param null $addinfo
     * @param null $removeinfo
     * @param null $updateinfo
     * @param bool $namespacesinroot
     */
    public function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null, $namespacesinroot = true)
    {
        parent::__construct($objectname, $addinfo, $removeinfo, $updateinfo, $namespacesinroot);
        $this->addFicoraExtension();
        $this->addSessionId();
    }

    private function addFicoraExtension()
    {
        $this->contactobject->setAttribute('xmlns:contact', 'urn:ietf:params:xml:ns:contact-1.0');
    }

    /**
     * @param string $contactid
     * @param eppContact $addInfo
     * @param eppContact $removeInfo
     * @param eppContact $updateInfo
     */
    public function updateContact($contactid, $addInfo, $removeInfo, $updateInfo) {
        parent::updateContact($contactid, $addInfo, $removeInfo, $updateInfo);

        /** @var ficoraEppContactPostalInfo $postalInfo */
        $postalInfo = $updateInfo->getPostalInfo(0);

        $contactPostalInfo = $this->getElementsByTagName('contact:postalInfo')->item(0);
        $contactPostalInfo->appendChild($this->createElement('contact:firstname', $postalInfo->getFirstName()));
        $contactPostalInfo->appendChild($this->createElement('contact:lastname', $postalInfo->getLastName()));
    }
}