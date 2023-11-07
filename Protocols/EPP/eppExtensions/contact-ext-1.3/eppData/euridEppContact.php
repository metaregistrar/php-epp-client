<?php
namespace Metaregistrar\EPP;
/**
 * The Contact Info Object
 *
 * This will hold the complete contact info a registry can receive and give you
 *
 */

class euridEppContact extends eppContact {

    private $acceptedLangCodes = [
        'bg','cs','da','de','el','en','es','et','fi','fr','ga','hr',
        'hu','it','lt','lv','mt','nl','pl','pt','ro','sk','sl','sv'
    ];

    private $acceptedCitizenshipCodes = [
        'at','be','bg','cy','cz','de','dk','ee','es','fi','fr','gb','gr','hr',
        'hu','ie','it','lt','lu','lv','mt','nl','pl','pt','ro','se','si','sk'
    ];

    #
    # These values can be set into the contactExtType field
    # The type of contact to create. Can be one of: “registrant”, “onsite”, "reseller", “tech”.
    #

    const CONTACT_EXT_TYPES = ['registrant', 'tech', 'onsite', 'reseller', 'billing'];
    const EURID_EXT_CONTACT_REGISTRANT = 'registrant';
    const EURID_EXT_CONTACT_TECH = 'tech';
    const EURID_EXT_CONTACT_ONSITE = 'onsite';
    const EURID_EXT_CONTACT_RESELLER = 'reseller';
    const EURID_EXT_CONTACT_BILLING = 'billing';

    private $contactExtType;
    private $contactExtLang = 'en';
    private $contactExtVat  = null;
    private $countryOfCitizenship = null;
    private $naturalPerson = null;

    public function __construct($postalInfo = null, $email = null, $voice = null, $fax = null, $password = null, $status = null) {
        parent::__construct($postalInfo, $email, $voice, $fax, $password, $status );
    }

    public function setContactExtType($type)
    {
        if(in_array($type, self::CONTACT_EXT_TYPES)) {
            $this->contactExtType =  $type;
        } else {
            throw new \Exception('Contact ext type not supported.');
        }
    }

    public function setContactExtLang($lang)
    {
        if (in_array($lang, $this->acceptedLangCodes)) {
            $this->contactExtLang = $lang;
        } else {
            throw new \Exception('Contact language code not supported.');
        }
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

    public function setContactExtCountryOfCitizenship($country) {
        if (in_array($country, $this->acceptedCitizenshipCodes)) {
            $this->countryOfCitizenship = $country;
        } else {
            throw new \Exception('Contact country of citizenship not supported.');
        }
    }

    public function getContactExtCountryOfCitizenship() {
        return $this->countryOfCitizenship;
    }

    public function setNaturalPerson( $naturalPerson ) {
        if( in_array( $naturalPerson, [true,false,null] ) )
            $this->naturalPerson = $naturalPerson;
        else
            throw new \Exception('Natural person should be set to true, false or null');
    }

    public function getNaturalPerson() {
        return $this->naturalPerson;
    }
}
