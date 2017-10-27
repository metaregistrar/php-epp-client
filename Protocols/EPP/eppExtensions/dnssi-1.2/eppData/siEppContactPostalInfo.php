<?php
namespace Metaregistrar\EPP;


class siEppContactPostalInfo extends eppContactPostalInfo
{
    const ARNES_CONTACT_TYPE_PERSON = 'person';
    const ARNES_CONTACT_TYPE_ORG = 'org';

    private $contactType;
    private $contactID;
    /**
     *
     * @param string $name
     * @param string $city
     * @param string $countrycode
     * @param string $organisationName
     * @param string $street
     * @param string $province
     * @param string $zipcode
     * @param string $type POSTAL_TYPE_LOC or POSTAL_TYPE_INT
     */
    public function __construct(
        $name = null,
        $city = null,
        $countrycode = null,
        $organisationName = null,
        $street = null,
        $province = null,
        $zipcode = null,
        $type = self::POSTAL_TYPE_INT
    ) {
        parent::__construct($name, $city, $countrycode, $organisationName, $street, $province, $zipcode, $type);
        $this->contactType = self::ARNES_CONTACT_TYPE_PERSON;
    }
    /**
     *
     * @param string $type person or org
     */
    public function setContactType($type)
    {
        $type = strtolower($type);
        if (($type != self::ARNES_CONTACT_TYPE_PERSON) && ($type!=self::ARNES_CONTACT_TYPE_ORG)) {
            throw new eppException('Person type can only be '.self::ARNES_CONTACT_TYPE_PERSON.' or '.self::ARNES_CONTACT_TYPE_ORG);
        }
        $this->contactType = $type;
    }
    public function getContactType()
    {
        return $this->contactType;
    }
    public function setContactID($id)
    {
        $this->contactID = $id;
    }
    public function getContactID()
    {
        return $this->contactID;
    }
    public function getIDType()
    {
        switch ($this->getContactType()) {
            case self::ARNES_CONTACT_TYPE_ORG:
                return "maticna";
            case self::ARNES_CONTACT_TYPE_PERSON:
            default:
                return "EMSO";
        }
    }
}