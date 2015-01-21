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
class iisEppCreateContactRequest extends eppCreateContactRequest
{
    function __construct($createinfo)
    {
        if ($createinfo instanceof eppContact)
        {
            parent::__construct($createinfo);
            $this->addIISExtension($createinfo);
        }
        else
        {
            parent::__construct($createinfo);
        }
        $this->addSessionId();
    }


    public function addIISExtension(eppContact $contact)
    {
        $this->addExtension('xmlns:iis','urn:se:iis:xml:epp:iis-1.2');
        $ext = $this->createElement('extension');
        $create = $this->createElement('iis:create');
        $orgno = $this->createElement('iis:orgno','[NL]150155');
        $create->appendChild($orgno);
        $ext->appendChild($create);
        $this->command->appendChild($ext);
    }

}