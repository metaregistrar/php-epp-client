<?php
namespace Metaregistrar\EPP;

class eppUpdateContactRequest extends eppContactRequest {
    function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null, $namespacesinroot = null) {
        if ($namespacesinroot!==null) {
            $this->setNamespacesinroot($namespacesinroot);
        } else {
            if (defined("NAMESPACESINROOT")) {
                $this->setNamespacesinroot(NAMESPACESINROOT);
            }
        }
        parent::__construct(eppRequest::TYPE_UPDATE);

        if ($objectname instanceof eppContactHandle) {
            $contacthandle = $objectname->getContactHandle();
        } else {
            if (strlen($objectname)) {
                $contacthandle = $objectname;
            } else {
                throw new eppException("Object name must be valid string on eppUpdateContactRequest");
            }
        }
        if (($addinfo instanceof eppContact) || ($removeinfo instanceof eppContact) || ($updateinfo instanceof eppContact)) {
            $this->updateContact($contacthandle, $addinfo, $removeinfo, $updateinfo);
        } else {
            throw new eppException('addinfo, removeinfo and updateinfo needs to be eppContact object on eppUpdateContactRequest');
        }
        $this->addSessionId();
    }

    function __destruct() {
        parent::__destruct();
    }


    /**
     *
     * @param string $contactid
     * @param eppContact $addInfo
     * @param eppContact $removeInfo
     * @param eppContact $updateInfo
     */
    public function updateContact($contactid, $addInfo, $removeInfo, $updateInfo) {
        #
        # Object create structure
        #
        $this->contactobject->appendChild($this->createElement('contact:id', $contactid));
        if ($updateInfo instanceof eppContact) {
            $chgcmd = $this->createElement('contact:chg');
            $this->addContactChanges($chgcmd, $updateInfo);
            $this->contactobject->appendChild($chgcmd);
        }
        if ($removeInfo instanceof eppContact) {
            $remcmd = $this->createElement('contact:rem');
            $this->addContactStatus($remcmd, $removeInfo);
            $this->contactobject->appendChild($remcmd);
        }
        if ($addInfo instanceof eppContact) {
            $addcmd = $this->createElement('contact:add');
            $this->addContactStatus($addcmd, $addInfo);
            $this->contactobject->appendChild($addcmd);
        }
        return;
    }

    /**
     *
     * @param \DOMElement $element
     * @param eppContact $contact
     */
    private function addContactStatus(\DOMElement $element, eppContact $contact) {
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


    /**
     *
     * @param \DOMElement $element
     * @param eppContact $contact
     */
    private function addContactChanges($element, eppContact $contact) {

        if ($contact->getPostalInfoLength() > 0) {
            $postal = $contact->getPostalInfo(0);
            $postalinfo = $this->createElement('contact:postalInfo');
            if ($postal->getType()==eppContact::TYPE_AUTO) {
                // If all fields are ascii, type = int (international) else type = loc (localization)
                if ((self::isAscii($postal->getName())) && (self::isAscii($postal->getOrganisationName())) && (self::isAscii($postal->getStreet(0)))) {
                    $postal->setType(eppContact::TYPE_INT);
                } else {
                    $postal->setType(eppContact::TYPE_LOC);
                }
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
            $element->appendChild($disclose);
        }
    }


}