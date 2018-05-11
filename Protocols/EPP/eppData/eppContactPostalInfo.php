<?php
namespace Metaregistrar\EPP;
/**
 * The EPP Result object
 *
 *
 *
 */

class eppContactPostalInfo {
    private $name;
    private $organisationName;
    private $street = array();
    private $city;
    private $province;
    private $zipcode;
    private $countrycode;
    private $type;

    const POSTAL_TYPE_LOC = 'loc';
    const POSTAL_TYPE_INT = 'int';


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
    public function __construct($name = null, $city = null, $countrycode = null, $organisationName = null, $street = null, $province = null, $zipcode = null, $type = eppContact::TYPE_AUTO) {
        $this->setName($name);
        #
        # Street can be an array of max 3 streets, or a string with an address
        #
        if (is_array($street)) {
            foreach ($street as $str) {
                $this->addStreet($str);
            }
        } else {
            if ($street) {
                $this->addStreet($street);
            }
        }
        $this->setOrganisationName($organisationName);
        $this->setCity($city);
        $this->setProvince($province);
        $this->setZipcode($zipcode);
        $this->setCountrycode($countrycode);
        $this->setType($type);
    }

    /**
     * Add a street line
     * @param string $street
     * @return void
     */
    public function addStreet($street) {
        if ((is_string($street)) && (strlen($street) > 0)) {
            if ((is_array($this->street)) && (count($this->street) < 3)) {
                $this->street[count($this->street)] = htmlspecialchars($street, ENT_COMPAT, "UTF-8");
            } else {
                throw new eppException('Cannot add more then 3 street names to postal info');
            }
        }
    }

    /**
     * Gets a street by given line number
     * @param int $line
     * @return string
     */
    public function getStreet($line) {
        if ($this->street[$line]) {
            return $this->street[$line];
        }
        return null;
    }

    public function getStreetCount() {
        return count($this->street);
    }

    public function getStreets() {
        return $this->street;
    }

    /**
     * Sets the organisation name
     * @param string $organisationName
     * @return void
     */
    public function setOrganisationName($organisationName) {
        $this->organisationName = htmlspecialchars($organisationName, ENT_COMPAT, "UTF-8");
    }

    /**
     * Gets the organisation name
     * @return string
     */
    public function getOrganisationName() {
        return $this->organisationName;
    }

    /**
     * Sets the name of the person
     * @param string $name
     * @return void
     */
    public function setName($name) {
        $this->name = htmlspecialchars($name, ENT_COMPAT, "UTF-8");
    }

    /**
     * Gets the name
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Sets the city of residence
     * @param string $city
     * @return void
     */
    public function setCity($city) {
        $this->city = htmlspecialchars($city, ENT_COMPAT, "UTF-8");
    }

    /**
     * Gets the city
     * @return string
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Sets the zipcode
     * @param string $zipcode
     * @return void
     */
    public function setZipcode($zipcode) {
        //DONT Remove garbage from the zipcode, never modify customer input!
        //$zipcode = preg_replace('/[^a-z\d]/i', '', $zipcode);
        $this->zipcode = $zipcode;
    }

    /**
     * Gets the zipcode
     * @return string
     */
    public function getZipcode() {
        return $this->zipcode;
    }

    /**
     * Sets the province
     * @param string $province
     * @return void
     */
    public function setProvince($province) {
        $this->province = htmlspecialchars($province, ENT_COMPAT, "UTF-8");
    }

    /**
     * Gets the province
     * @return string
     */
    public function getProvince() {
        return $this->province;
    }

    /**
     * Sets the countrycode
     * @param string $countrycode
     * @link http://xml.coverpages.org/country3166.html
     * @return void
     */
    public function setCountrycode($countrycode) {
        $this->countrycode = $countrycode;
    }

    /**
     * Gets the countrycode
     * @link http://xml.coverpages.org/country3166.html
     * @return string
     */
    public function getCountrycode() {
        return $this->countrycode;
    }

    /**
     *
     * @return string int or loc
     */
    public function getType() {
        return $this->type;
    }

    /**
     *
     * @param string $type int or loc
     */
    public function setType($type) {
        $type = strtolower($type);
        if (($type != eppContact::TYPE_AUTO) && ($type != eppContact::TYPE_LOC)&& ($type != eppContact::TYPE_INT)) {
            throw new eppException('PostalInfo type can only be INT or LOC');
        }
        $this->type = $type;
    }
}