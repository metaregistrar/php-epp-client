<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=cupd for example request/response

class noridEppUpdateContactRequest extends noridEppContactRequest {
    
    function __construct($objectname, $addInfo = null, $removeInfo = null, $updateInfo = null, $namespacesinroot = true) {
        $this->setNamespacesinroot($namespacesinroot);
        parent::__construct(eppRequest::TYPE_UPDATE);

        if ($objectname instanceof eppContactHandle) {
            $contactHandle = $objectname->getContactHandle();
        } else {
            if (strlen($objectname)) {
                $contactHandle = $objectname;
            } else {
                throw new eppException("Object name must be valid string on eppUpdateContactRequest");
            }
        }

        if (($addInfo instanceof noridEppContact) || ($removeInfo instanceof noridEppContact) || ($updateInfo instanceof noridEppContact)) {
            $this->updateContact($contactHandle, $addInfo, $removeInfo, $updateInfo);
        } else {
            throw new eppException('addInfo, removeInfo and updateInfo needs to be noridEppContact object on noridEppUpdateContactRequest');
        }

        $this->addSessionId();
    }

    public function updateContact($contactid, $addInfo, $removeInfo, $updateInfo) {
        // Create contact object structure
        $this->contactobject->appendChild($this->createElement('contact:id', $contactid));
        if ($updateInfo instanceof noridEppContact) {
            $chgcmd = $this->createElement('contact:chg');
            $this->addContactChanges($chgcmd, $updateInfo);
            $this->contactobject->appendChild($chgcmd);

            // Add Norid EPP extensions
            if (!is_null($updateInfo->getExtOrganizations()) || (!is_null($updateInfo->getExtIdentityType()) && !is_null($updateInfo->getExtIdentity())) || !is_null($updateInfo->getExtMobilePhone()) || !is_null($updateInfo->getExtEmails()) || !is_null($updateInfo->getExtRoleContacts())) {
                $extchgcmd = $this->createElement('no-ext-contact:chg');
                $this->addContactExtChanges($extchgcmd, $updateInfo);
                $this->getContactExtension()->appendChild($extchgcmd);
            }
        }
        if ($removeInfo instanceof noridEppContact) {
            $remcmd = $this->createElement('contact:rem');
            $this->addContactStatus($remcmd, $removeInfo);
            $this->contactobject->appendChild($remcmd);

            // Add Norid EPP extensions
            if (!is_null($removeInfo->getExtOrganizations()) || (!is_null($removeInfo->getExtIdentityType()) && !is_null($removeInfo->getExtIdentity())) || !is_null($removeInfo->getExtMobilePhone()) || !is_null($removeInfo->getExtEmails()) || !is_null($removeInfo->getExtRoleContacts())) {
                $extremcmd = $this->createElement('no-ext-contact:rem');
                $this->addContactExtChanges($extremcmd, $removeInfo);
                $this->getContactExtension()->appendChild($extremcmd);
            }
        }
        if ($addInfo instanceof noridEppContact) {
            $addcmd = $this->createElement('contact:add');
            $this->addContactStatus($addcmd, $addInfo);
            $this->contactobject->appendChild($addcmd);

            // Add Norid EPP extensions
            if (!is_null($addInfo->getExtOrganizations()) || (!is_null($addInfo->getExtIdentityType()) && !is_null($addInfo->getExtIdentity())) || !is_null($addInfo->getExtMobilePhone()) || !is_null($addInfo->getExtEmails()) || !is_null($addInfo->getExtRoleContacts())) {
                $extaddcmd = $this->createElement('no-ext-contact:add');
                $this->addContactExtChanges($extaddcmd, $addInfo);
                $this->getContactExtension()->appendChild($extaddcmd);
            }
        }
    }

    private function addContactStatus(\DOMElement $element, noridEppContact $contact) {
        if ((is_array($contact->getStatus())) && (count($contact->getStatus()) > 0)) {
            $statuses = $contact->getStatus();
            if (is_array($statuses)) {
                foreach ($statuses as $status) {
                    $stat = $this->createElement('contact:status');
                    $stat->setAttribute('s', $status);
                    $element->appendChild($stat);
                }
            }
        }
    }

    private function addContactChanges(\DOMElement $element, noridEppContact $contact) {
        // Standard EPP fields
        if ($contact->getPostalInfoLength() > 0) {
            $postal = $contact->getPostalInfo(0);
            $postalinfo = $this->createElement('contact:postalInfo');
            if ($postal->getType() != eppContact::TYPE_LOC) {
                // Postal info must be of type 'loc' according to Norid
                throw new eppException('PostalInfo must be of type LOC for the Norid registry');
            }
            $postalinfo->setAttribute('type', $postal->getType());
            if (!$postal->getName()=='') {
                $postalinfo->appendChild($this->createElement('contact:name', $postal->getName()));
            }
            if (!$postal->getOrganisationName()=='') {
                $postalinfo->appendChild($this->createElement('contact:org', $postal->getOrganisationName()));
            }
            if ((($postal->getStreetCount()) > 0) || strlen($postal->getCity()) || strlen($postal->getProvince()) || strlen($postal->getZipcode()) || strlen($postal->getCountrycode())) {
                $postaladdr = $this->createElement('contact:addr');
                if (($count = $postal->getStreetCount()) > 0) {
                    for ($i = 0; $i < $count; $i++) {
                        $postaladdr->appendChild($this->createElement('contact:street', $postal->getStreet($i)));
                    }
                }
                if (strlen($postal->getCity())) {
                    $postaladdr->appendChild($this->createElement('contact:city', $postal->getCity()));
                }
                if (strlen($postal->getProvince())) {
                    $postaladdr->appendChild($this->createElement('contact:sp', $postal->getProvince()));
                }
                if (strlen($postal->getZipcode())) {
                    $postaladdr->appendChild($this->createElement('contact:pc', $postal->getZipcode()));
                }
                if (strlen($postal->getCountrycode())) {
                    $postaladdr->appendChild($this->createElement('contact:cc', $postal->getCountrycode()));
                }
                $postalinfo->appendChild($postaladdr);
            }
            $element->appendChild($postalinfo);
        }
        if (strlen($contact->getVoice())) {
            $element->appendChild($this->createElement('contact:voice', $contact->getVoice()));
        }
        if (strlen($contact->getFax())) {
            $element->appendChild($this->createElement('contact:fax', $contact->getFax()));
        }
        if (strlen($contact->getEmail())) {
            $element->appendChild($this->createElement('contact:email', $contact->getEmail()));
        }
        if ($contact->getPassword()) {
            $authinfo = $this->createElement('contact:authInfo');
            $authinfo->appendChild($this->createElement('contact:pw', $contact->getPassword()));
            $element->appendChild($authinfo);
        }
        if (!is_null($contact->getDisclose())) {
            $type = $contact->getType();
            if ($type == $contact::TYPE_AUTO) {
                $type = $contact::TYPE_LOC;
            }
            $disclose = $this->createElement('contact:disclose');
            $disclose->setAttribute('flag',$contact->getDisclose());
            $name = $this->createElement('contact:name');
            if ($contact->getDisclose()==1) {

                $name->setAttribute('type',$type);
            }
            $disclose->appendChild($name);
            $org = $this->createElement('contact:org');
            if ($contact->getDisclose()==1) {
                $org->setAttribute('type',$type);
            }
            $disclose->appendChild($org);
            $addr = $this->createElement('contact:addr');
            if ($contact->getDisclose()==1) {
                $addr->setAttribute('type',$type);
            }
            $disclose->appendChild($addr);
            $disclose->appendChild($this->createElement('contact:voice'));
            $disclose->appendChild($this->createElement('contact:email'));
            $element->appendChild($disclose);
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