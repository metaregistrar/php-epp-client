<?php
namespace Metaregistrar\EPP;
/**
 * The Contact Info Object
 *
 * This will hold the complete contact info a registry can receive and give you
 *
 */

class atEppContact extends eppContact {


    #
    # These values can be set into the personType field
    # Only NAT and JUR are allowed, ROLE default value NAT
    # NAT (natural person) JUR (companies ect.) ROLE (e.g. administrators ect.)
    #

    const PERS_TYPE_UNSPECIFIED = 'unspecified';
    const PERS_TYPE_PRIVATPERSON = 'privateperson';
    const PERS_TYPE_ORGANISATION = 'organisation';
    const PERS_TYPE_ROLE = 'role';



    private $personType = self::PERS_TYPE_PRIVATPERSON;

    private $whoisHidePhone=0;
    private $whoisHideFax=0;
    private $whoisHideEmail=0;




    /**
     * @param null $postalInfo
     * @param string $personType |$personType=self::PERS_TYPE_PRIVATPERSON
     * @param null $email
     * @param null $voice
     * @param null $fax
     * @param bool|false $whoisHideEmail
     * @param bool|false $whoisHidePhone
     * @param bool|false $whoisHideFax
     * @param null $password
     * @param null $status
     * @throws eppException
     */
    public function __construct($postalInfo = null,$personType=self::PERS_TYPE_UNSPECIFIED, $email = null, $voice = null, $fax = null,$whoisHideEmail=false,$whoisHidePhone=false,$whoisHideFax=false, $password = null, $status = null) {
       parent::__construct($postalInfo , $email , $voice , $fax , $password , $status );
       $this->setPersonType($personType);
        $this->setWhoisHideEmail($whoisHideEmail);
        $this->setWhoisHideFax($whoisHideFax);
        $this->setWhoisHidePhone($whoisHidePhone);
    }




    private function setWhoisHidePhone($whoisHidePhone=false)
    {
        $this->whoisHidePhone = $whoisHidePhone ? 1 : 0;
    }

    private function setWhoisHideFax($whoisHideFax=false)
    {
        $this->whoisHideFax = $whoisHideFax? 1 : 0;
    }

    private function setWhoisHideEmail($whoisHideEmail=false)
    {
        $this->whoisHideEmail = $whoisHideEmail? 1 : 0;
    }


    public function getWhoisHidePhone()
    {
        return $this->whoisHidePhone;
    }

    public function getWhoisHideFax()
    {
        return $this->whoisHideFax;
    }

    public function getWhoisHideEmail()
    {
        return  $this->whoisHideEmail;
    }


    private function setPersonType($personType)
    {
        if($personType !== self::PERS_TYPE_ORGANISATION && $personType !== self::PERS_TYPE_PRIVATPERSON && $personType !== self::PERS_TYPE_ROLE)
        {
            throw new eppException('Invalid personType ' . htmlspecialchars ($personType) . ' assigned! One of the following personTypes are allowd: privateperson (natural person) organisation (companies ect.) role (e.g. administrators ect.)');
        }
        $this->personType=$personType;
    }

    public function getPersonType()
    {
        return $this->personType;
    }



    /**
     *
     * @return string ContactId
     */
    public function generateContactId() {
        return "AUTO";
    }
}