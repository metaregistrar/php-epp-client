<?php
namespace Metaregistrar\EPP;
/*
 * Extension example: Corporation
<extension>
      <skContactIdent:create
        xmlns:skContactIdent="http://www.sk-nic.sk/xml/epp/sk-contact-ident-0.2">
      <skContactIdent:legalForm>CORP</skContactIdent:legalForm>
      <skContactIdent:identValue>
        <skContactIdent:corpIdent>1234567890</skContactIdent:corpIdent>
      </skContactIdent:identValue>
      </skContactIdent:create>
</extension>
 * Extension example: Natural person
<extension>
      <skContactIdent:create
        xmlns:skContactIdent="http://www.sk-nic.sk/xml/epp/sk-contact-ident-0.2">
      <skContactIdent:legalForm>PERS</skContactIdent:legalForm>
      </skContactIdent:create>
</extension>
*/

class sknicEppCreateContactRequest extends eppCreateContactRequest {
    function __construct(eppContact $createinfo, $namespacesinroot = true, $usecdata = true) {
        parent::__construct($createinfo, $namespacesinroot, $usecdata);

        $this->addContactExtension($createinfo);

        // Add session ID
        $this->addSessionId();
    }

    /**
     * @param eppContact $createinfo
     * @throws eppException
     */
    public function addContactExtension(eppContact $createinfo) {
        // Get the postal info to extract the SK-NIC specific data
        $postalInfo = $createinfo->getPostalInfo(0);

        // Check if it's the sknicEppContactPostalInfo class
        if ($postalInfo instanceof sknicEppContactPostalInfo) {
            // Use the generateXML method from the postal info class
            $existingElement = $this->getElementsByTagName('command')->item(0);
            $doc = $existingElement->ownerDocument;
            $extensionElement = $postalInfo->generateXML($doc);
            $this->getExtension()->appendChild($extensionElement->firstChild);
        } else {
            throw new eppException("Postal info must be of type sknicEppContactPostalInfo");
        }
    }
}
