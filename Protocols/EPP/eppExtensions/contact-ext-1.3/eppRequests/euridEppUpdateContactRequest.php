<?php
namespace Metaregistrar\EPP;

class euridEppUpdateContactRequest extends eppUpdateContactRequest {

    /**
     * euridEppUpdateContactRequest constructor.
     * @throws eppException
     */
    function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null, $namespacesinroot = true, $usecdata = true) {
        parent::__construct($objectname, $addinfo, $removeinfo, $updateinfo, $namespacesinroot, $usecdata);
        $this->addContactExtension($updateinfo);
        $this->addSessionId();
    }

    /**
     * @param object eppContact
     */
    public function addContactExtension(euridEppContact $updateinfo) {
        $this->addExtension('xmlns:contact-ext', 'http://www.eurid.eu/xml/epp/contact-ext-1.3');
        $update = $this->createElement('contact-ext:update');
        $change = $this->createElement('contact-ext:chg');

        if(!empty($updateinfo->getContactExtType())) {
            $change->appendChild($this->createElement('contact-ext:type', $updateinfo->getContactExtType()));
        }
        if(!empty($updateinfo->getContactExtVat())) {
            $change->appendChild($this->createElement('contact-ext:vat', $updateinfo->getContactExtVat()));
        }
        $org = false;
        if (is_string($updateinfo->getPostalInfo(0)->getOrganisationName())) {
            if (strlen($updateinfo->getPostalInfo(0)->getOrganisationName()) > 0) {
                $org = true;
            }
        }

        $change->appendChild($this->createElement('contact-ext:lang', $updateinfo->getContactExtLang()));

        if( $updateinfo->getNaturalPerson() === null ) {
            if ($org) {
                $change->appendChild($this->createElement('contact-ext:naturalPerson', 'false'));
            } else {
                $change->appendChild($this->createElement('contact-ext:naturalPerson', 'true'));
            }
        } else {
            $change->appendChild($this->createElement('contact-ext:naturalPerson', $updateinfo->getNaturalPerson() ? 'true' : 'false' ) );
        }

        if (is_string($updateinfo->getContactExtCountryOfCitizenship())) {
            $change->appendChild($this->createElement('contact-ext:countryOfCitizenship', $updateinfo->getContactExtCountryOfCitizenship()));
        }

        $update->appendChild( $change );

        $this->getExtension()->appendChild($update);
    }
}
