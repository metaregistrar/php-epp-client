<?php
/**
 * Created by PhpStorm.
 * User: thomasm
 * Date: 17.09.2015
 * Time: 14:15
 */

namespace Metaregistrar\EPP;


class atEppUpdateContactRequest extends eppUpdateContactRequest
{
    use atEppCommandTrait;
    protected $atEppExtensionChain = null;

    function __construct($objectname, atEppContact $addinfo = null, atEppContact $removeinfo = null,atEppContact $updateinfo = null,atEppExtensionChain $atEppExtensionChain=null) {
        $this->atEppExtensionChain = $atEppExtensionChain;
        parent::__construct($objectname, $addinfo , $removeinfo , $updateinfo);
        $this->addSessionId();
    }




    /**
     *
     * @param string $contactid
     * @param eppContact $addInfo
     * @param eppContact $removeInfo
     * @param eppContact $updateInfo
     * @return \domElement
     */
    public function updateContact($contactid,atEppContact $addInfo,atEppContact $removeInfo,atEppContact $updateInfo) {
        #
        # Object create structure
        #
        $update = $this->createElement('update');
        $this->contactobject = $this->createElement('contact:update');
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
        $update->appendChild($this->contactobject);
        $this->getCommand()->appendChild($update);
        $this->setAtExtensions();
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
                if (($this->isAscii($postal->getName())) && ($this->isAscii($postal->getOrganisationName())) && ($this->isAscii($postal->getStreet(0)))) {
                    $postal->setType(eppContact::TYPE_INT);
                } else {
                    $postal->setType(eppContact::TYPE_LOC);
                }
            }
            $postalinfo->setAttribute('type', $postal->getType());
            if (strlen($postal->getName())) {
                $postalinfo->appendChild($this->createElement('contact:name', $postal->getName()));
            }
            if (strlen($postal->getOrganisationName())) {
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
        $this->setAtContactDisclosure($element,$contact);

    }


    private static function isAscii($str) {
        return mb_check_encoding($str, 'ASCII');
    }



    protected function setAtContactDisclosure(\domElement $element,atEppContact $contact)
    {

        if (!is_null($contact->getDisclose())) {
            $disclose = $this->createElement('contact:disclose');
            $disclose->setAttribute('flag',$contact->getDisclose());

            $disclPhone = $this->createElement('contact:voice');
            if ($contact->getDisclose()==1) {
                $disclPhone->setAttribute('type',eppContact::TYPE_LOC);
            }
            if($contact->getDisclose() != $contact->getWhoisHidePhone()) {
                $disclose->appendChild($disclPhone);
            }
            $disclFax = $this->createElement('contact:fax');
            if ($contact->getDisclose()==1) {
                $disclFax->setAttribute('type',eppContact::TYPE_LOC);
            }
            if($contact->getWhoisHideFax() != $contact->getDisclose()) {
                $disclose->appendChild($disclFax);
            }
            $disclEmail = $this->createElement('contact:email');
            if ($contact->getDisclose()==1) {
                $disclEmail->setAttribute('type',eppContact::TYPE_LOC);
            }
            if($contact->getWhoisHideEmail() != $contact->getDisclose()) {
                $disclose->appendChild($disclEmail);
            }
            $element->appendChild($disclose);
        }


    }

}