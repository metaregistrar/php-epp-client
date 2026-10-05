<?php
namespace Metaregistrar\EPP;


class atEppUpdateContactRequest extends eppUpdateContactRequest
{
    use atEppCommandTrait;
    protected $atEppExtensionChain = null;

    function __construct($objectname, ?atEppContact $addinfo = null, ?atEppContact $removeinfo = null,?atEppContact $updateinfo = null,?atEppExtensionChain $atEppExtensionChain=null) {
        $this->atEppExtensionChain = $atEppExtensionChain;
        parent::__construct($objectname, $addinfo , $removeinfo , $updateinfo);
        $this->addSessionId();
    }




    /**
     *
     * @param string $contactid
     * @param atEppContact $addInfo
     * @param atEppContact $removeInfo
     * @param atEppContact $updateInfo
     * @return \domElement
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

        // Empty update shall produce a <contact:chg /> to allow verification extension
        if (is_null($addInfo) && is_null($updateInfo) && is_null($removeInfo)) {
            $this->contactobject->appendChild($this->createElement('contact:chg'));
        }

        $this->setAtExtensions();
        $this->epp->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
    }

    /**
     *
     * @param \domElement $element
     * @param eppContact $contact
     */
    private function addContactStatus(\domElement $element, atEppContact $contact) {
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
     * @param \domElement $element
     * @param eppContact $contact
     */
    private function addContactChanges(\domElement $element, atEppContact $contact) {

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
            $organisation = $postal->getOrganisationName();
            $name = $postal->getName();
            if(!empty($organisation) && empty($name)){
                $name =  $organisation;
                $organisation="";
            }
            if(!empty($name)) {
                $postalinfo->appendChild($this->createElement('contact:name', $name));
            }
            if(!empty($organisation)) {
                $postalinfo->appendChild($this->createElement('contact:org', $organisation));
            }
            if (($postal->getStreetCount() > 0) || $postal->getCity() || $postal->getProvince() || $postal->getZipcode() || $postal->getCountrycode()) {
                $postaladdr = $this->createElement('contact:addr');
                if (($count = $postal->getStreetCount()) > 0) {
                    for ($i = 0; $i < $count; $i++) {
                        $postaladdr->appendChild($this->createElement('contact:street', $postal->getStreet($i)));
                    }
                }
                if ($city = $postal->getCity()) {
                    $postaladdr->appendChild($this->createElement('contact:city', $city));
                }
                if ($province = $postal->getProvince()) {
                    $postaladdr->appendChild($this->createElement('contact:sp', $province));
                }
                if ($zip = $postal->getZipcode()) {
                    $postaladdr->appendChild($this->createElement('contact:pc', $zip));
                }
                if ($country = $postal->getCountrycode()) {
                    $postaladdr->appendChild($this->createElement('contact:cc', $country));
                }
                $postalinfo->appendChild($postaladdr);
            }
            $element->appendChild($postalinfo);
        }

        $element->appendChild($this->createElement('contact:voice', $contact->getVoice()));

        if (strlen($contact->getEmail())) {
            $element->appendChild($this->createElement('contact:email', $contact->getEmail()));
        }
        if ($contact->getPassword()) {
            $authinfo = $this->createElement('contact:authInfo');
            $authinfo->appendChild($this->createElement('contact:pw', $contact->getPassword()));
            $element->appendChild($authinfo);
        }

    }

}