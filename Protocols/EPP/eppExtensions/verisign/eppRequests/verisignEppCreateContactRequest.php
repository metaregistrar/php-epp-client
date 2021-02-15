<?php
namespace Metaregistrar\EPP;

class verisignEppCreateContactRequest extends eppCreateContactRequest {
    use verisignEppExtension;
    /**
     * verisignEppCreateContactRequest constructor.
     *
     * @param eppContact $contact
     * @throws eppException
     */
    public function __construct(eppContact $contact) {
        parent::__construct($contact);
        //add namestore extension
        $this->addNamestore();
        $this->addSessionId();
    }

    /**
     * Set the postalinfo information in the contact
     * 
     * Overrides the setPostalInfo function in the parent.
     * Verisign doesn't expect empty values to be sent as part of the XML request
     * and will actually respond with an XML syntax error. This function will
     * prevent empty eppContactPostalInfo fields from being added to the XML DOM request.
     * 
     * @param eppContactPostalInfo $eppContactPostalInfo
     * @throws eppException
     */

    public function setPostalInfo(eppContactPostalInfo $eppContactPostalInfo) {
        $postalInfoElement = $this->createElement('contact:postalInfo');
        if (!$eppContactPostalInfo instanceof eppContactPostalInfo) {
            throw new eppException('PostalInfo must be filled on eppCreateContact request');
        }
        if ($eppContactPostalInfo->getType() == eppContact::TYPE_AUTO) {
            // If all fields are ascii, type = int (international) else type = loc (localization)
            if ((self::isAscii($eppContactPostalInfo->getName())) && (self::isAscii($eppContactPostalInfo->getOrganisationName())) && (self::isAscii($eppContactPostalInfo->getStreet(0)))) {
                $eppContactPostalInfo->setType(eppContact::TYPE_INT);
            } else {
                $eppContactPostalInfo->setType(eppContact::TYPE_LOC);
            }
        }
        $postalInfoElement->setAttribute('type', $eppContactPostalInfo->getType());
        if ($eppContactPostalInfo->getName()) {
            $postalInfoElement->appendChild($this->createElement('contact:name', $eppContactPostalInfo->getName()));
        }
        if ($eppContactPostalInfo->getOrganisationName()) {
            $postalInfoElement->appendChild($this->createElement('contact:org', $eppContactPostalInfo->getOrganisationName()));
        }
        $addressElement = $this->createElement('contact:addr');
        $count = $eppContactPostalInfo->getStreetCount();
        for ($i = 0; $i < $count; $i++) {
            $addressElement->appendChild($this->createElement('contact:street', $eppContactPostalInfo->getStreet($i)));
        }
        if ($eppContactPostalInfo->getCity()) {
            $addressElement->appendChild($this->createElement('contact:city', $eppContactPostalInfo->getCity()));
        }
        if ($eppContactPostalInfo->getProvince()) {
            $addressElement->appendChild($this->createElement('contact:sp', $eppContactPostalInfo->getProvince()));
        }
        if ($eppContactPostalInfo->getZipcode()) {
            $addressElement->appendChild($this->createElement('contact:pc', $eppContactPostalInfo->getZipcode()));
        }
        if ($eppContactPostalInfo->getCountrycode()) {
            $addressElement->appendChild($this->createElement('contact:cc', $eppContactPostalInfo->getCountrycode()));
        }
        if ($addressElement->hasChildNodes()) {
            $postalInfoElement->appendChild($addressElement);
        }
        $this->contactobject->appendChild($postalInfoElement);
    }
}
