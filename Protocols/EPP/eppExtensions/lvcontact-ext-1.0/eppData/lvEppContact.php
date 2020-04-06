<?php
namespace Metaregistrar\EPP;
/**
 * The Contact Info Object
 *
 * This will hold the complete contact info a registry can receive and give you
 *
 */

class lvEppContact extends eppContact {


    #
    # These values can be set into the contactExtType field
    # The type of contact to create. Can be one of: “registrant”, “onsite”, "reseller", “tech”.
    #

    private $contactExtReg  = null;
    private $contactExtVat  = null;


    public function __construct($postalInfo = 'loc', $email = null, $voice = null, $fax = null, $password = null, $status = null) {
       parent::__construct($postalInfo , $email , $voice , $fax , $password , $status );
       parent::setId($this->generateContactId());
    }


    public function setContactExtReg($reg) {
        $this->contactExtReg =  $reg;
    }

    public function setContactExtVat($vat) {
        $this->contactExtVat =  $vat;
    }

    public function getContactExtReg() {
        return $this->contactExtReg;
    }

    public function getContactExtVat() {
        return $this->contactExtVat;
    }

    public function generateContactId() {
        return uniqid('LV');
    }
}
