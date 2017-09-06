<?php


namespace Metaregistrar\EPP;


class ficoraEppInfoContactRequest extends eppInfoContactRequest
{
    public function __construct($inforequest, $namespacesinroot = true)
    {
        parent::__construct($inforequest, $namespacesinroot);
        $this->addFicoraExtension();
        $this->addSessionId();
    }

    private function addFicoraExtension()
    {
        $this->contactobject->setAttribute('xmlns:contact', 'urn:ietf:params:xml:ns:contact-1.0');
    }
}