<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=ccre for example request/response

class noridEppCreateContactRequest extends eppCreateContactRequest {

    use noridEppContactRequestTrait;
    
    function __construct(noridEppContact $contact, $namespacesinroot = true) {
        parent::__construct($contact, $namespacesinroot);
        $this->setExtContact($contact);
        $this->addSessionId();
    }

    public function setExtContact(noridEppContact $contact) {
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
            $this->getContactExtension('create')->appendChild($this->createElement('no-ext-contact:type', $type));
        }
    }

    private function setExtOrganizations($organizations) {
        if (!is_null($organizations)) {
            if (!is_array($organizations)) {
                throw new eppException('setExtOrganizations must be called with an array of organization IDs');
            }

            foreach ($organizations as $organization) {
                $this->getContactExtension('create')->appendChild($this->createElement('no-ext-contact:organization', $organization));
            }
        }
    }

    private function setExtIdentity($type, $identity) {
        if (!is_null($type) && !is_null($identity)) {
            $element = $this->createElement('no-ext-contact:identity', $identity);
            $element->setAttribute('type', $type);
            $this->getContactExtension('create')->appendChild($element);
        }
    }

    private function setExtMobilePhone($phone) {
        if (!is_null($phone)) {
            $this->getContactExtension('create')->appendChild($this->createElement('no-ext-contact:mobilePhone', $phone));
        }
    }

    private function setExtEmails($emails) {
        if (!is_null($emails)) {
            if (!is_array($emails)) {
                throw new eppException('setExtEmails must be called with an array of emails');
            }

            foreach ($emails as $email) {
                $this->getContactExtension('create')->appendChild($this->createElement('no-ext-contact:email', $email));
            }
        }
    }

    private function setExtRoleContacts($contacts) {
        if (!is_null($contacts)) {
            if (!is_array($contacts)) {
                throw new eppException('setExtRoleContacts must be called with an array of role contact IDs');
            }

            foreach ($contacts as $contact) {
                $this->getContactExtension('create')->appendChild($this->createElement('no-ext-contact:roleContact', $contact));
            }
        }
    }

}