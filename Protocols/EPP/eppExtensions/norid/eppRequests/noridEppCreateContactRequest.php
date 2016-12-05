<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=ccre for example request/response

class noridEppCreateContactRequest extends noridEppContactRequest {
    
    function __construct(noridEppContact $contact, $namespacesinroot = true) {
        $this->setNamespacesinroot($namespacesinroot);
        parent::__construct(eppRequest::TYPE_CREATE);
        $this->setContact($contact);
        $this->addSessionId();
    }

    // Setter for values from a noridEppContact
    public function setContact(noridEppContact $contact) {
        // Replicate eppCreateContactRequest structure
        $this->setContactId($contact->generateContactId());
        $this->setPostalInfo($contact->getPostalInfo(0));
        $this->setVoice($contact->getVoice());
        $this->setFax($contact->getFax());
        $this->setEmail($contact->getEmail());
        $this->setPassword($contact->getPassword());
        $this->setDisclose($contact->getDisclose());

        // Add extension structure
        $this->setExtType($contact->getExtType());
        $this->setExtIdentity($contact->getExtIdentityType(), $contact->getExtIdentity());
        $this->setExtMobilePhone($contact->getExtMobilePhone());
        $this->setExtEmails($contact->getExtEmails());
        $this->setExtOrganizations($contact->getExtOrganizations());
        $this->setExtRoleContacts($contact->getExtRoleContacts());
    }

    // Extension setters
    private function setExtType($type) {
        // Can only be null if the contact is not a registrant, let the server figure that out
        if (!is_null($type)) {
            $this->getContactExtension()->appendChild($this->createElement('no-ext-contact:type', $type));
        }
    }

    private function setExtOrganizations($organizations) {
        if (!is_null($organizations)) {
            if (!is_array($organizations)) {
                throw new eppException('setExtOrganizations must be called with an array of organization IDs');
            }

            foreach ($organizations as $organization) {
                $this->getContactExtension()->appendChild($this->createElement('no-ext-contact:organization', $organization));
            }
        }
    }

    private function setExtIdentity($type, $identity) {
        if (!is_null($type) && !is_null($identity)) {
            $element = $this->createElement('no-ext-contact:identity', $identity);
            $element->setAttribute('type', $type);
            $this->getContactExtension()->appendChild($element);
        }
    }

    private function setExtMobilePhone($phone) {
        if (!is_null($phone)) {
            $this->getContactExtension()->appendChild($this->createElement('no-ext-contact:mobilePhone', $phone));
        }
    }

    private function setExtEmails($emails) {
        if (!is_null($emails)) {
            if (!is_array($emails)) {
                throw new eppException('setExtEmails must be called with an array of emails');
            }

            foreach ($emails as $email) {
                $this->getContactExtension()->appendChild($this->createElement('no-ext-contact:email', $email));
            }
        }
    }

    private function setExtRoleContacts($contacts) {
        if (!is_null($contacts)) {
            if (!is_array($contacts)) {
                throw new eppException('setExtRoleContacts must be called with an array of role contact IDs');
            }

            foreach ($contacts as $contact) {
                $this->getContactExtension()->appendChild($this->createElement('no-ext-contact:roleContact', $contact));
            }
        }
    }

    // Standard setters
    private function setContactId($contactid) {
        $this->contactobject->appendChild($this->createElement('contact:id', $contactid));
    }

    private function setPostalInfo(eppContactPostalInfo $postal) {
        $postalinfo = $this->createElement('contact:postalInfo');
        if (!$postal instanceof eppContactPostalInfo) {
            throw new eppException('PostalInfo must be filled on noridEppCreateContact request');
        }
        if ($postal->getType() != eppContact::TYPE_LOC) {
            // Postal info must be of type 'loc' according to Norid
            throw new eppException('PostalInfo must be of type LOC for the Norid registry');
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

    private function setVoice($voice) {
        if (!is_null($voice)) {
            $this->contactobject->appendChild($this->createElement('contact:voice', $voice));
        }
    }

    private function setFax($fax) {
        if (!is_null($fax)) {
            $this->contactobject->appendChild($this->createElement('contact:fax', $fax));
        }
    }

    private function setEmail($email) {
        if (!is_null($email)) {
            $this->contactobject->appendChild($this->createElement('contact:email', $email));
        }
    }

    private function setPassword($password) {
        $authinfo = $this->createElement('contact:authInfo');
        if (!is_null($password)) {
            $authinfo->appendChild($this->createElement('contact:pw', $password));
        } else {
            $authinfo->appendChild($this->createElement('contact:pw'));
        }
        $this->contactobject->appendChild($authinfo);
    }

    private function setDisclose($contactdisclose) {
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