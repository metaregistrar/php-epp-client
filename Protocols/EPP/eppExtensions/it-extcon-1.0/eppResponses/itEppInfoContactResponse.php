<?php
namespace Metaregistrar\EPP;

class itEppInfoContactResponse extends eppInfoContactResponse {

  /**
   *
   * @return eppContact
   */
  public function getContact()
  {
    $postalinfo = $this->getContactPostalInfo();

    $contact = new itEppContact($postalinfo, $this->getContactEmail(), $this->getContactVoice(), $this->getContactFax(), null, null, $this->getConsentForPublishing(), $this->getRegistrantEntityType(), $this->getRegistrantNationalityCode(), $this->getRegistrantRegCode());
    return $contact;
  }

  public function isRegistrant()
  {
    return $this->queryPath('/epp:epp/epp:response/epp:extension/extcon:infData/extcon:registrant') ? true : false;
  }

  public function getConsentForPublishing()
  {
    return (bool) $this->queryPath('/epp:epp/epp:response/epp:extension/extcon:infData/extcon:consentForPublishing');
  }

  public function getRegistrantEntityType()
  {
    return (int) $this->queryPath('/epp:epp/epp:response/epp:extension/extcon:infData/extcon:registrant/extcon:entityType');
  }

  public function getRegistrantNationalityCode()
  {
    return $this->queryPath('/epp:epp/epp:response/epp:extension/extcon:infData/extcon:registrant/extcon:nationalityCode');
  }

  public function getRegistrantRegCode()
  {
    return $this->queryPath('/epp:epp/epp:response/epp:extension/extcon:infData/extcon:registrant/extcon:regCode');
  }

  public function getRegistrant()
  {
    $registrant['nationalityCode'] = $this->getRegistrantNationalityCode();
    $registrant['entityType'] = $this->getRegistrantEntityType();
    $registrant['regCode'] = $this->getRegistrantRegCode();

    return $registrant;
  }
}