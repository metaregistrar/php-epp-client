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
}