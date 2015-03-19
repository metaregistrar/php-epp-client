<?php
namespace Metaregistrar\EPP;

class eppCreateContactRequest extends eppCreateRequest {

    function __construct($createinfo) {
        parent::__construct($createinfo);

        if ($createinfo instanceof eppContact) {
            $this->setContact($createinfo);
        } else {
            throw new eppException('createinfo must be of type eppContact on eppCreateContactRequest');
        }
        $this->addSessionId();
    }

    function __destruct() {
        parent::__destruct();
    }


    /**
     *
     * @param eppContact $contact
     * @return \domElement
     */
    public function setContact(eppContact $contact) {
        #
        # Object create structure
        #
        $create = $this->createElement('create');

        $this->contactobject = $this->createElement('contact:create');
        $this->contactobject->appendChild($this->createElement('contact:id', $contact->generateContactId()));
        $postalinfo = $this->createElement('contact:postalInfo');
        $postal = $contact->getPostalInfo(0);
        $postalinfo->setAttribute('type', $postal->getType());
        $postalinfo->appendChild($this->createElement('contact:name', $postal->getName()));
        if ($postal->getOrganisationName()) {
            $postalinfo->appendChild($this->createElement('contact:org', $postal->getOrganisationName()));
        }
        $postaladdr = $this->createElement('contact:addr');
        $count = $postal->getStreetCount();
        for ($i = 0; $i < $count; $i++) {
            $postaladdr->appendChild($this->createElement('contact:street', $postal->getStreet($i)));
        }
        $postaladdr->appendChild($this->createElement('contact:city', $postal->getCity()));
        if ($postal->getProvince()) {
            $postaladdr->appendChild($this->createElement('contact:sp', $postal->getProvince()));
        }
        $postaladdr->appendChild($this->createElement('contact:pc', $postal->getZipcode()));
        $postaladdr->appendChild($this->createElement('contact:cc', $postal->getCountrycode()));
        $postalinfo->appendChild($postaladdr);
        $this->contactobject->appendChild($postalinfo);
        $this->contactobject->appendChild($this->createElement('contact:voice', $contact->getVoice()));
        if ($contact->getFax()) {
            $this->contactobject->appendChild($this->createElement('contact:fax', $contact->getFax()));
        }
        $this->contactobject->appendChild($this->createElement('contact:email', $contact->getEmail()));
        $authinfo = $this->createElement('contact:authInfo');
        $authinfo->appendChild($this->createElement('contact:pw', 'foo2bar'));
        $this->contactobject->appendChild($authinfo);
        if (!is_null($contact->getDisclose())) {
            $disclose = $this->createElement('contact:disclose');
            $disclose->setAttribute('flag',$contact->getDisclose());
            $name = $this->createElement('contact:name');
            if ($contact->getDisclose()==1) {
                $name->setAttribute('type','loc');
            }
            $disclose->appendChild($name);
            $org = $this->createElement('contact:org');
            if ($contact->getDisclose()==1) {
                $org->setAttribute('type','loc');
            }
            $disclose->appendChild($org);
            $addr = $this->createElement('contact:addr');
            if ($contact->getDisclose()==1) {
                $addr->setAttribute('type','loc');
            }
            $disclose->appendChild($addr);
            $disclose->appendChild($this->createElement('contact:voice'));
            $disclose->appendChild($this->createElement('contact:email'));
            $this->contactobject->appendChild($disclose);
        }
        $create->appendChild($this->contactobject);
        $this->getCommand()->appendChild($create);
    }
}

