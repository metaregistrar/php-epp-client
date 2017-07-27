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



class rrpproxyEppTrusteeRequest extends eppUpdateDomainRequest {


    function __construct(eppDomain $domain, $accepttrustee) {
        $upd = new eppDomain($domain->getDomainname());
        parent::__construct($domain, null, null, $upd);
        $this->addTrustee($accepttrustee);
        parent::addSessionId();

    }

    private function addTrustee($accepttrustee) {
        $ext = $this->createElement('extension');
        $infdata = $this->createElement('keysys:update');
        $domdata = $this->createElement('keysys:domain');
        $cd = $this->createElement('keysys:de-accept-trustee-tac', $accepttrustee);
        $domdata->appendChild($cd);
        $infdata->appendChild($domdata);
        $ext->appendChild($infdata);
        $this->getCommand()->appendChild($ext);
    }


}