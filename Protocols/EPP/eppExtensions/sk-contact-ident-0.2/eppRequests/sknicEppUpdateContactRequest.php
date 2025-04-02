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

class sknicEppUpdateContactRequest extends eppUpdateContactRequest {
    function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null, $namespacesinroot = true, $usecdata = true) {
        parent::__construct($objectname, $addinfo, $removeinfo, $updateinfo, $namespacesinroot, $usecdata);

        $this->addContactExtension($updateinfo);

        // Add session ID
        $this->addSessionId();
    }

    /**
     * @param eppContact $updateInfo
     * @throws eppException
     */
    public function addContactExtension(eppContact $updateInfo) {
        // Get the postal info to extract the SK-NIC specific data
        $postalInfo = $updateInfo->getPostalInfo(0);

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
