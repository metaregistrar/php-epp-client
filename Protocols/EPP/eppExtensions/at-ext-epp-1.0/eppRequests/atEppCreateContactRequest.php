<?php
namespace Metaregistrar\EPP;

class atEppCreateContactRequest extends eppCreateContactRequest {

    use atEppCommandTrait;

    protected $atEppExtensionChain = null;

    function __construct($createinfo,?atEppExtensionChain $atEppExtensionChain=null) {
        $this->atEppExtensionChain = $atEppExtensionChain;
       parent::__construct($createinfo);
       $this->addSessionId();


    }


    /**
     * handle`s at-specifics,
     * please note $contact must be an instance of atEppContact::class
     *
     * @param eppContact $contact
     * @throws eppException
     */
    public function setContact(eppContact $contact) {
        if (!$contact instanceof atEppContact) {
            throw new eppException('contact must be an atEppContact instance');
        }
        $this->setContactId($contact->getId());
        $this->setPostalInfo($contact->getPostalInfo(0));
        $this->setVoice($contact->getVoice());
        $this->setFax($contact->getFax());
        $this->setEmail($contact->getEmail());
        $this->setPassword($contact->getPassword());
        $this->setAtContactDisclosure($contact->getDisclose(), $contact);
        $this->setAtExtensions();
    }

    /**
     * @param $password
     */
    public function setPassword($password)
    {
        if (is_null($password)) {
            $password = "";
        }
        $authinfo = $this->createElement('contact:authInfo');
        $authinfo->appendChild($this->createElement('contact:pw', $password));
        $this->contactobject->appendChild($authinfo);

    }


    /**
     * Set the postalinfo information in the contact, overwritten due to at-perontypes it might be possible
     * that the name field has been left emtpy and organisation has been set instead
     * in this case organisation value will be submitted as contact:name
     *
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

        //.at version requires a $name
        //if only $organisation has been set
        //due to persontype organisation
        //leave organisation field empty and
        //write the organisation value into contact:name
        $organisation = $postal->getOrganisationName();
        $name = $postal->getName();
        if(!empty($organisation) && empty($name)){
            $name =  $organisation;
            $organisation="";
        }


        $postalinfo->appendChild($this->createElement('contact:name', $name));

        if (!empty($organisation)) {
            $postalinfo->appendChild($this->createElement('contact:org', $organisation));
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
     * at  voice-, fax-, email disclosure
     *
     * @param $contactdisclose
     * @param atEppContact $contact
     */
    protected function setAtContactDisclosure($contactdisclose,atEppContact $contact)
    {
        if (!is_null($contactdisclose)) {
            $disclose = $this->createElement('contact:disclose');
            $disclose->setAttribute('flag', $contactdisclose);

            $disclPhone = $this->createElement('contact:voice');
            if ($contactdisclose == 1) {
                $disclPhone->setAttribute('type', eppContact::TYPE_LOC);
            }
            if ($contactdisclose != $contact->getWhoisHidePhone()) {
                $disclose->appendChild($disclPhone);
            }
            $disclFax = $this->createElement('contact:fax');
            if ($contactdisclose == 1) {
                $disclFax->setAttribute('type', eppContact::TYPE_LOC);
            }
            if ($contact->getWhoisHideFax() != $contactdisclose) {
                $disclose->appendChild($disclFax);
            }
            $disclEmail = $this->createElement('contact:email');
            if ($contactdisclose == 1) {
                $disclEmail->setAttribute('type', eppContact::TYPE_LOC);
            }
            if ($contact->getWhoisHideEmail() != $contactdisclose) {
                $disclose->appendChild($disclEmail);
            }
            $this->contactobject->appendChild($disclose);
        }
    }




}
