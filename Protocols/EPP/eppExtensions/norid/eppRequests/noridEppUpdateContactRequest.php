<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=cupd for example request/response

class noridEppUpdateContactRequest extends eppUpdateContactRequest {

    use noridEppContactRequestTrait;
    
    function __construct($objectname, $addInfo = null, $removeInfo = null, $updateInfo = null, $namespacesinroot = true) {
        parent::__construct($objectname, $addInfo, $removeInfo, $updateInfo, $namespacesinroot);

        if (($addInfo instanceof noridEppContact) || ($removeInfo instanceof noridEppContact) || ($updateInfo instanceof noridEppContact)) {
            $this->updateExtContact($addInfo, $removeInfo, null);
        }

        $this->addSessionId();
    }

    public function updateExtContact($addInfo, $removeInfo, $updateInfo) {
        if ($updateInfo instanceof noridEppContact) {            // Add Norid EPP extensions
            if (!is_null($updateInfo->getExtOrganizations()) || (!is_null($updateInfo->getExtIdentityType()) && !is_null($updateInfo->getExtIdentity())) || !is_null($updateInfo->getExtMobilePhone()) || !is_null($updateInfo->getExtEmails()) || !is_null($updateInfo->getExtRoleContacts())) {
                $extchgcmd = $this->createElement('no-ext-contact:chg');
                $this->addContactExtChanges($extchgcmd, $updateInfo);
                $this->getContactExtension('update')->appendChild($extchgcmd);
            }
        }
        if ($removeInfo instanceof noridEppContact) {
            if (!is_null($removeInfo->getExtOrganizations()) || (!is_null($removeInfo->getExtIdentityType()) && !is_null($removeInfo->getExtIdentity())) || !is_null($removeInfo->getExtMobilePhone()) || !is_null($removeInfo->getExtEmails()) || !is_null($removeInfo->getExtRoleContacts())) {
                $extremcmd = $this->createElement('no-ext-contact:rem');
                $this->addContactExtChanges($extremcmd, $removeInfo);
                $this->getContactExtension('update')->appendChild($extremcmd);
            }
        }
        if ($addInfo instanceof noridEppContact) {
            if (!is_null($addInfo->getExtOrganizations()) || (!is_null($addInfo->getExtIdentityType()) && !is_null($addInfo->getExtIdentity())) || !is_null($addInfo->getExtMobilePhone()) || !is_null($addInfo->getExtEmails()) || !is_null($addInfo->getExtRoleContacts())) {
                $extaddcmd = $this->createElement('no-ext-contact:add');
                $this->addContactExtChanges($extaddcmd, $addInfo);
                $this->getContactExtension('update')->appendChild($extaddcmd);
            }
        }
    }

    private function addContactExtChanges(\DOMElement $element, noridEppContact $contact) {
        // Norid EPP extensions
        if (!is_null($contact->getExtOrganizations())) {
            foreach ($contact->getExtOrganizations() as $organization) {
                $element->appendChild($this->createElement('no-ext-contact:organization', $organization));
            }
        }
        if (!is_null($contact->getExtIdentityType()) && !is_null($contact->getExtIdentity())) {
            $identityElement = $this->createElement('no-ext-contact:identity', $contact->getExtIdentity());
            $identityElement->setAttribute('type', $contact->getExtIdentityType());
            $element->appendChild($identityElement);
        }
        if (!is_null($contact->getExtMobilePhone())) {
            $element->appendChild($this->createElement('no-ext-contact:mobilePhone', $contact->getExtMobilePhone()));
        }
        if (!is_null($contact->getExtEmails())) {
            foreach ($contact->getExtEmails() as $email) {
                $element->appendChild($this->createElement('no-ext-contact:email', $email));
            }
        }
        if (!is_null($contact->getExtRoleContacts())) {
            foreach ($contact->getExtRoleContacts() as $roleContact) {
                $element->appendChild($this->createElement('no-ext-contact:roleContact', $roleContact));
            }
        }
    }

}