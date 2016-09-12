<?php
namespace Metaregistrar\EPP;

/*
 <extension>
   <iis:infData xmlns:iis=”urn:se:iis:xml:epp:iis-1.2” xsi:schemaLocation=”urn:se:iis:xml:epp:iis-1.2 iis-1.2.xsd”>
     <iis:deactDate>2000-11-01</iis:deactDate>
     <iis:delDate>2000-11-11</iis:delDate>
     <iis:state>expired</iis:state>
     <iis:clientDelete>0</iis:clientDelete>
   </iis:infData>
 </extension>
 */


class iisEppUpdateDomainClientDeleteRequest extends eppUpdateDomainRequest {
    function __construct(eppDomain $domain, $clientdelete) {
        parent::__construct($domain, null, null, $domain);
        $this->setClientDelete($clientdelete);
        parent::addSessionId();

    }

    private function setClientDelete($clientdelete) {
        $infdata = $this->createElement('iis:update');
        $this->setNamespace('xmlns:iis', 'urn:se:iis:xml:epp:iis-1.2',$infdata);
        $cd = $this->createElement('iis:clientDelete', $clientdelete);
        $infdata->appendChild($cd);
        $this->getExtension()->appendChild($infdata);
    }


}