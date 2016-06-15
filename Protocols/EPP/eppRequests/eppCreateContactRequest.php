<?php
namespace Metaregistrar\EPP;

class eppCreateContactRequest extends eppContactRequest {

    /**
     * eppCreateContactRequest constructor.
     * @param eppContact|null $createinfo
     * @throws eppException
     */
    function __construct($createinfo, $namespacesinroot = true) {
        $this->setNamespacesinroot($namespacesinroot);
        parent::__construct(eppRequest::TYPE_CREATE);
        
        if ($createinfo){
            if ($createinfo instanceof eppContact) {
                $this->setContact($createinfo);
            } else {
                throw new eppException('createinfo must be of type eppContact on eppCreateContactRequest');
            }
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
        $this->setContactId( $contact->generateContactId());
        $this->setPostalInfo($contact->getPostalInfo(0));
        $this->setVoice($contact->getVoice());
        $this->setFax($contact->getFax());
        $this->setEmail($contact->getEmail());
        $this->setPassword($contact->getPassword());
        $this->setDisclose($contact->getDisclose());
    }

    /**
     * Create the contact:id field
     * @param $contactid
     */
    public function setContactId($contactid) {
        $this->contactobject->appendChild($this->createElement('contact:id', $contactid));
    }

    /**
     * Set the postalinfo information in the contact
     * @param eppContactPostalInfo $postal
     * @throws eppException
     */
    public function setPostalInfo(eppContactPostalInfo $postal) {
        $postalinfo = $this->createElement('contact:postalInfo');
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
    }

    /**
     * @param $voice
     */
    public function setVoice($voice) {
        if ($voice) {
            $this->contactobject->appendChild($this->createElement('contact:voice', $voice));
        }
    }

    public function setFax($fax) {
        if ($fax) {
            $this->contactobject->appendChild($this->createElement('contact:fax', $fax));
        }
    }

    public function setEmail($email) {
        if ($email) {
            $this->contactobject->appendChild($this->createElement('contact:email', $email));
        }
    }

    public function setPassword($password) {
        if (!is_null($password))
        {
            $authinfo = $this->createElement('contact:authInfo');
            $authinfo->appendChild($this->createElement('contact:pw', $password));
            $this->contactobject->appendChild($authinfo);
        }
    }

    public function setDisclose($contactdisclose) {
        if (!is_null($contactdisclose)) {
            $disclose = $this->createElement('contact:disclose');
            $disclose->setAttribute('flag',$contactdisclose);
            $name = $this->createElement('contact:name');
            if ($contactdisclose==1) {
                $name->setAttribute('type',eppContact::TYPE_LOC);
            }
            $disclose->appendChild($name);
            $org = $this->createElement('contact:org');
            if ($contactdisclose==1) {
                $org->setAttribute('type',eppContact::TYPE_LOC);
            }
            $disclose->appendChild($org);
            $addr = $this->createElement('contact:addr');
            if ($contactdisclose==1) {
                $addr->setAttribute('type',eppContact::TYPE_LOC);
            }
            $disclose->appendChild($addr);
            $disclose->appendChild($this->createElement('contact:voice'));
            $disclose->appendChild($this->createElement('contact:email'));
            $this->contactobject->appendChild($disclose);
        }
    }
}

