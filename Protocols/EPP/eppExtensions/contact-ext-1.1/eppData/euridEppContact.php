<?php
namespace Metaregistrar\EPP;
/**
 * The Contact Info Object
 *
 * This will hold the complete contact info a registry can receive and give you
 *
 */

class euridEppContact extends eppContact {


    #
    # These values can be set into the contactExtType field
    # The type of contact to create. Can be one of: “registrant”, “onsite”, "reseller", “tech”.
    #

    const CONTACT_EXT_TYPES = ['registrant', 'tech', 'onsite', 'reseller'];
    const EURID_EXT_CONTACT_REGISTRANT = 'registrant';
    const EURID_EXT_CONTACT_TECH = 'tech';
    const EURID_EXT_CONTACT_ONSITE = 'onsite';
    const EURID_EXT_CONTACT_RESELLER = 'reseller';

    private $contactExtType;
    private $contactExtLang = 'en';
    private $contactExtVat  = null;


    public function __construct($postalInfo = null, $email = null, $voice = null, $fax = null, $password = null, $status = null) {
       parent::__construct($postalInfo , $email , $voice , $fax , $password , $status );
    }


    public function setContactExtType($type)
    {

        if(in_array($type, self::CONTACT_EXT_TYPES)) {
            $this->contactExtType =  $type;
        }
    }

    public function setContactExtLang($lang)
    {
        $this->contactExtLang =  $lang;
    }

    public function setContactExtVat($vat)
    {
        $this->contactExtVat =  $vat;
    }

    public function getContactExtType()
    {
        return $this->contactExtType;
    }

    public function getContactExtLang()
    {
        return $this->contactExtLang;
    }

    public function getContactExtVat()
    {
        return $this->contactExtVat;
    }

}