<?php
namespace Metaregistrar\EPP;

class ficoraEppContactPostalInfo extends eppContactPostalInfo
{
    private $firstName;
    private $lastName;
    private $identity;
    private $registerNumber;
    private $isFinnish;
    private $birthDate;

    public function __construct($name = null, $city = null, $countrycode = null, $organisationName = null, $street = null, $province = null, $zipcode = null, $type = eppContact::TYPE_AUTO, $firstName = null, $lastName = null, $isFinnish = null, $identity = null, $birthDate = null, $registerNumber = null) {

        parent::__construct($name, $city, $countrycode, $organisationName, $street, $province, $zipcode, $type);

        $this->setFirstName($firstName);
        $this->setLastName($lastName);
        $this->setIsFinnish($isFinnish);
        $this->setIdentity($identity);
        $this->setBirthDate($birthDate);
        $this->setRegisterNumber($registerNumber);
    }

    /**
     * Sets the first name of the person
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = htmlspecialchars($firstName, ENT_COMPAT, "UTF-8");
    }

    /**
     * Gets the first name of the person
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Sets the last name of the person
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName =  htmlspecialchars($lastName, ENT_COMPAT, "UTF-8");
    }

    /**
     * Gets the last name of the person
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Gets the name of the person
     * @return string|null
     */
    public function getPersonName()
    {
        if ($this->getFirstName() && $this->getLastName()) {
            return $this->getFirstName() . ' ' . $this->getLastName();
        }

        return null;
    }

    /**
     * Sets the "is finnish" status of the contact
     * @param string $isFinnish
     */
    public function setIsFinnish($isFinnish)
    {
        $this->isFinnish = htmlspecialchars($isFinnish, ENT_COMPAT, "UTF-8");
    }

    /**
     * Gets "is finnish" status of the contact
     * @return string
     */
    public function getIsFinnish()
    {
        return $this->isFinnish;
    }

    /**
     * Sets the identity (social security number) of the person
     * @param string $identity
     */
    public function setIdentity($identity)
    {
        $this->identity = htmlspecialchars($identity, ENT_COMPAT, "UTF-8");
    }

    /**
     * Gets the identity (social security number) of the person
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * Sets the birth of the (foreign) person
     * @param string $birthDate
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = htmlspecialchars($birthDate, ENT_COMPAT, "UTF-8");
    }

    /**
     * Gets the birth date of the person
     * @return string
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Sets the register number of the organisation
     * @param string $registerNumber
     */
    public function setRegisterNumber($registerNumber)
    {
        $this->registerNumber = htmlspecialchars($registerNumber, ENT_COMPAT, "UTF-8");
    }

    /**
     * Gets the register number of the organisation
     * @return string
     */
    public function getRegisterNumber()
    {
        return $this->registerNumber;
    }

}