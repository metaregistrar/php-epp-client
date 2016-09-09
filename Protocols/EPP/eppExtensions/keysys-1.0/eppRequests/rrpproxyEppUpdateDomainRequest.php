<?php
namespace Metaregistrar\EPP;

/*
 <extension>
     <keysys:update xmlns:keysys="http://www.key-systems.net/epp/keysys-1.0">
       <keysys:domain>
         <keysys:de-accept-trustee-tac>1</keysys:de-accept-trustee-tac>
       </keysys:domain>
     </keysys:update>
   </extension>
 */


class rrpproxyEppUpdateDomainRequest extends eppUpdateDomainRequest {
    function __construct(eppDomain $domain, $addinfo = null, $removeinfo = null, $updateinfo = null, $forcehostattr=false) {
        $upd = new eppDomain($domain->getDomainname());
        parent::__construct($domain, null, null, $upd);
        $this->addTrustee();
        parent::addSessionId();

    }

    private function addTrustee() {
        $ext = $this->createElement('extension');
        $infdata = $this->createElement('keysys:update');
        $domdata = $this->createElement('keysys:domain');
        $cd = $this->createElement('keysys:de-accept-trustee-tac', '1');
        $domdata->appendChild($cd);
        $infdata->appendChild($domdata);
        $ext->appendChild($infdata);
        $this->getCommand()->appendChild($ext);
    }


}