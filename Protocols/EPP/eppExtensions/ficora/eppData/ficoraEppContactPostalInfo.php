<?php
namespace Metaregistrar\EPP;

class ficoraEppContactPostalInfo extends eppContactPostalInfo
{
    private $firstName;
    private $lastName;

    public function __construct($name = null, $city = null, $countrycode = null, $organisationName = null, $street = null, $province = null, $zipcode = null, $type = eppContact::TYPE_AUTO, $firstName = null, $lastName = null) {

        parent::__construct($name, $city, $countrycode, $organisationName, $street, $province, $zipcode, $type);

        $this->setFirstName($firstName);
        $this->setLastName($lastName);
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
}