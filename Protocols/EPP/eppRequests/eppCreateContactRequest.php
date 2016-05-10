<?php
namespace Metaregistrar\EPP;

class eppCreateContactRequest extends eppCreateRequest {

    /**
     * eppCreateContactRequest constructor.
     * @param eppContact $createinfo
     * @throws eppException
     */
    function __construct($createinfo) {
        parent::__construct();

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
     * @throws eppException
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
        if (!$postal instanceof eppContactPostalInfo) {
            throw new eppException('PostalInfo must be filled on eppCreateContact request');
        }
        if ($postal->getType()==eppContact::TYPE_AUTO) {
            // If all fields are ascii, type = int (international) else type = loc (localization)
            if ((self::isAscii($postal->getName())) && (self::isAscii($postal->getOrganisationName())) && (self::isAscii($postal->getStreet(0)))) {
                $postal->setType(eppContact::TYPE_INT);
            } else {
                $postal->setType(eppContact::TYPE_LOC);
            }
        }
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
        if (!is_null($contact->getPassword()))
        {
            $authinfo = $this->createElement('contact:authInfo');
            $authinfo->appendChild($this->createElement('contact:pw', $contact->getPassword()));
            $this->contactobject->appendChild($authinfo);
        }
        if (!is_null($contact->getDisclose())) {
            $disclose = $this->createElement('contact:disclose');
            $disclose->setAttribute('flag',$contact->getDisclose());
            $name = $this->createElement('contact:name');
            if ($contact->getDisclose()==1) {
                $name->setAttribute('type',eppContact::TYPE_LOC);
            }
            $disclose->appendChild($name);
            $org = $this->createElement('contact:org');
            if ($contact->getDisclose()==1) {
                $org->setAttribute('type',eppContact::TYPE_LOC);
            }
            $disclose->appendChild($org);
            $addr = $this->createElement('contact:addr');
            if ($contact->getDisclose()==1) {
                $addr->setAttribute('type',eppContact::TYPE_LOC);
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

