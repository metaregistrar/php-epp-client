<?php
namespace Metaregistrar\EPP;

/*
<extension>
  <iis:create xmlns:iis="urn:se:iis:xml:epp:iis-1.2" xsi:schemaLocation="urn:se:iis:xml:epp:iis-1.2 iis-1.2.xsd">
    <iis:orgno>[SE]802405-0190</iis:orgno>
    <iis:vatno>SE802405019001</iis:vatno>
  </iis:create>
</extension>


*/
class iisEppCreateContactRequest extends eppCreateContactRequest {

    private $create=null;

    function __construct($createinfo, $orgno = null, $vatno = null) {
        parent::__construct($createinfo);
        $contactname = $createinfo->getPostalInfo(0)->getOrganisationName();
        if ((!$contactname) || (strlen($contactname)==0)) {
            $contactname = $createinfo->getPostalInfo(0)->getName();
        }
        // Set the contact ID, as it is specified by IIS.SE
        $this->contactobject->getElementsByTagName('contact:id')->item(0)->nodeValue=$this->createContactId($contactname);

        // Add organisation number, if applicable
        if ($orgno) {
            $this->addIISOrganization($orgno);
        }
        // Add vat no if applicable
        if ($vatno) {
            $this->addIISVat($vatno);
        }
        $this->addSessionId();
    }


    public function addIISOrganization($organizationnumber) {
        if (!$this->create) {
            $this->create = $this->createElement('iis:create');
            $this->setNamespace('xmlns:iis', 'urn:se:iis:xml:epp:iis-1.2',$this->create);
            $this->getExtension()->appendChild($this->create);
        }
        $this->create->appendChild($this->createElement('iis:orgno', $organizationnumber));

    }

    public function addIISVat($vatnumber) {
        if (!$this->create) {
            $this->create = $this->createElement('iis:create');
            $this->setNamespace('xmlns:iis', 'urn:se:iis:xml:epp:iis-1.2',$this->create);
            $this->getExtension()->appendChild($this->create);
        }
        $this->create->appendChild($this->createElement('iis:vatno', $vatnumber));
    }

    private function createContactId($name = null) {
        if ((!$name) || (strlen($name)==0)) {
            $charset = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz";
            $contact_id = substr(str_shuffle($charset), 0, 6);
        } else {
            $contact_id = str_pad(substr(str_replace(' ','',strtolower(iconv('utf-8', 'us-ascii//IGNORE', $name))),0,6),6,'zzzzz');
        }
        $contact_id .= date("ym") . "-" . str_pad((time() - strtotime("today")), 5, '0', STR_PAD_LEFT);
        return $contact_id;
    }

}