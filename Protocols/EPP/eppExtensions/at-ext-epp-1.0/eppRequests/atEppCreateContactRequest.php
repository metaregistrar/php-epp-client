<?php
namespace Metaregistrar\EPP;

class atEppCreateContactRequest extends eppCreateContactRequest {

    use atEppCommandTrait;

    protected $atEppExtensionChain = null;

    function __construct($createinfo,atEppExtensionChain $atEppExtensionChain=null) {
        $this->atEppExtensionChain = $atEppExtensionChain;
       parent::__construct($createinfo);
       $this->addSessionId();


    }




    private function checkEncoding($str) {
        return mb_check_encoding($str, 'ASCII');
    }

    public function setContact(atEppContact $contact) {
        #
        # Object create structure
        #

        $create = $this->createElement('create');


        $this->contactobject = $this->createElement('contact:create');
        $this->contactobject->setAttribute('xmlns:contact',atEppConstants::namespaceContact);
        $this->contactobject->setAttribute('xsi:schemaLocation', atEppConstants::schemaLocationContact);
        $this->contactobject->appendChild($this->createElement('contact:id', atEppConstants::autoHandle));
        $postalinfo = $this->createElement('contact:postalInfo');
        $postal = $contact->getPostalInfo(0);
        if (!$postal instanceof eppContactPostalInfo) {
            throw new eppException('PostalInfo must be filled on eppCreateContact request');
        }
        if ($postal->getType()==eppContact::TYPE_AUTO) {
            // If all fields are ascii, type = int (international) else type = loc (localization)
            if (($this->checkEncoding($postal->getName())) && ($this->checkEncoding($postal->getOrganisationName())) && ($this->checkEncoding($postal->getStreet(0)))) {
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
        $this->setAtContactDisclosure($contact);
        $create->appendChild($this->contactobject);
        $this->getCommand()->appendChild($create);
        $this->setAtExtensions();
    }

    protected function setAtContactDisclosure(atEppContact $contact)
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
            $this->contactobject->appendChild($disclose);
        }


    }




}
