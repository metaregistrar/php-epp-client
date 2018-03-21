<?php
namespace Metaregistrar\EPP;

class siEppCreateContactRequest extends eppCreateContactRequest
{
    public function __construct($createinfo)
    {
        parent::__construct($createinfo);
        if ($createinfo instanceof eppContact) {
            $this->addExtension('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
            $this->addExtension('xmlns:dnssi', 'http://www.arnes.si/xml/epp/dnssi-1.2');
            $this->addDnssiExtension($createinfo);
        }
        $this->addSessionId();
    }
    private function addDnssiExtension(eppContact $contact)
    {
        $postalInfo = $contact->getPostalInfo(0);
        /* @var $postalInfo \Metaregistrar\EPP\siEppContactPostalInfo */
        if ($postalInfo) {
            $dnssiext = $this->createElement('dnssi:ext');
            $create = $this->createElement('dnssi:create');
            $contact = $this->createElement('dnssi:contact');
            $typeAttribute = $this->createAttribute('type');
            $typeAttribute->value = $postalInfo->getContactType();
            $contact->appendChild($typeAttribute);
            $create->appendChild($contact);
            /* Tega ni vec v novem*/
            /*if($postalInfo->getContactID()) {
                $id = $this->createElement('dnssi:'.$postalInfo->getIDType(), $postalInfo->getContactID());
                $create->appendChild($id);
            }*/
            $dnssiext->appendChild($create);
            $this->getExtension()->appendChild($dnssiext);
        }
    }
}