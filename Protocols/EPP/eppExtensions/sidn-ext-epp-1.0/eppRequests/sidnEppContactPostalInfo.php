<?php
namespace Metaregistrar\EPP;
/*
 *
 * The EPP org field is intended for the name of the organisation. In the SIDN EPP implementation the org field
 * is being used as a organisational department and not the organisation itself.
 *
 * The name should contain the name of the organisation and the legalForm SIDN EPP extension shouldn't be a natural person.
 *
 */
class sidnEppContactPostalInfo extends eppContactPostalInfo {
    private $legalForm;

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
        if (!empty($organisationName)) {
            $name = $organisationName;
            $this->legalForm = 'ANDERS';
        } else {
            $this->legalForm = 'PERSOON';
        }

        parent::__construct($name, $city, $countrycode, null, $street, $province, $zipcode, $type);
    }

    public function getLegalForm() {
        return $this->legalForm;
    }
}