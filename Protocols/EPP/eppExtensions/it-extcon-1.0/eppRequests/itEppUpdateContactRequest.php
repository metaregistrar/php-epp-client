<?php

namespace Metaregistrar\EPP;

class itEppUpdateContactRequest extends eppUpdateContactRequest
{

  function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null, $namespacesinroot = true, $usecdata = true)
  {
    parent::__construct($objectname, $addinfo, $removeinfo, $updateinfo, $namespacesinroot, $usecdata);
    $this->addContactExtension($updateinfo);
    $this->addSessionId();
  }

  /**
   * @param object itEppContact
   */
  public function addContactExtension(itEppContact $updateInfo)
  {
    $this->addExtension('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
    $this->addExtension('xsi:schemaLocation', 'urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd');

    $this->contactobject->setAttribute('xmlns:contact', 'urn:ietf:params:xml:ns:contact-1.0');
    $this->contactobject->setAttribute('xsi:schemaLocation', 'urn:ietf:params:xml:ns:contact-1.0 contact-1.0.xsd');

    $update = $this->createElement('extcon:update');
    $update->setAttribute('xmlns:extcon', 'http://www.nic.it/ITNIC-EPP/extcon-1.0');
    $update->setAttribute('xsi:schemaLocation', 'http://www.nic.it/ITNIC-EPP/extcon-1.0 extcon-1.0.xsd');

    $update->appendChild($this->createElement('extcon:consentForPublishing', $updateInfo->getConsentForPublishing()));
    $registrant = $updateInfo->getRegistrant();
    if (! is_null($registrant)) {
      $registrantElement = $this->createElement('extcon:registrant');
      $registrantElement->appendChild($this->createElement('extcon:nationalityCode', $registrant['nationalityCode']));
      $registrantElement->appendChild($this->createElement('extcon:entityType', $registrant['entityType']));
      $registrantElement->appendChild($this->createElement('extcon:regCode', $registrant['regCode']));
      $update->appendChild($registrantElement);
    }


    $this->getExtension()->appendChild($update);
  }
}
