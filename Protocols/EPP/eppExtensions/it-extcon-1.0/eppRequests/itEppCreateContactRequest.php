<?php
namespace Metaregistrar\EPP;
/*
<extension>
  <extcon:create xmlns:extcon="http://www.nic.it/ITNIC-EPP/extcon-1.0" xsi:schemaLocation="http://www.nic.it/ITNIC-EPP/extcon-1.0 extcon-1.0.xsd">
    <extcon:consentForPublishing>true</extcon:consentForPublishing>
    <extcon:registrant>
      <extcon:nationalityCode>IT</extcon:nationalityCode>
      <extcon:entityType>1</extcon:entityType>
      <extcon:regCode>BSSRCR78D03G674P</extcon:regCode>
    </extcon:registrant>
  </extcon:create>
</extension>
*/

class itEppCreateContactRequest extends eppCreateContactRequest {

    /**
     * itEppCreateContactRequest constructor.
     * @param eppContact|null $createInfo
     * @param string $contacttype
     * @param string $language
     * @throws eppException
     */
    function __construct(itEppContact $createInfo) {
        parent::__construct($createInfo);
        $this->addContactExtension($createInfo);
        $this->addSessionId();
    }

    /**
     * @param object eppContact
     */
    public function addContactExtension(itEppContact $createInfo) {
        $this->addExtension('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $this->addExtension('xsi:schemaLocation', 'urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd');

        $this->contactobject->setAttribute('xmlns:contact', 'urn:ietf:params:xml:ns:contact-1.0');
        $this->contactobject->setAttribute('xsi:schemaLocation', 'urn:ietf:params:xml:ns:contact-1.0 contact-1.0.xsd');

        $create = $this->createElement('extcon:create');
        $create->setAttribute('xmlns:extcon', 'http://www.nic.it/ITNIC-EPP/extcon-1.0');
        $create->setAttribute('xsi:schemaLocation', 'http://www.nic.it/ITNIC-EPP/extcon-1.0 extcon-1.0.xsd');

        $create->appendChild($this->createElement('extcon:consentForPublishing', $createInfo->getConsentForPublishing()));

        $registrant = $createInfo->getRegistrant();
        $registrantElement = $this->createElement('extcon:registrant');
        $registrantElement->appendChild($this->createElement('extcon:nationalityCode', $registrant['nationalityCode']));
        $registrantElement->appendChild($this->createElement('extcon:entityType', $registrant['entityType']));
        $registrantElement->appendChild($this->createElement('extcon:regCode', $registrant['regCode']));
        $create->appendChild($registrantElement);

        $this->getExtension()->appendChild($create);
    }
}
