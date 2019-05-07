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
/*
     <extension>
      <keysys:update xmlns:keysys="http://www.key-systems.net/epp/keysys-1.0">
        <keysys:domain>
          <keysys:whois-privacy>1</keysys:whois-privacy>
        </keysys:domain>
      </keysys:update>
    </extension>
*/


class rrpproxyEppUpdateDomainRequest extends eppUpdateDomainRequest {
    function __construct(eppDomain $domain, $addinfo = null, $removeinfo = null, $updateinfo = null, $forcehostattr=false, $trustee = true) {
        $upd = new eppDomain($domain->getDomainname());
        parent::__construct($domain, $addinfo, $removeinfo, $upd);
        $this->addTrustee($trustee);
        parent::addSessionId();
    }

    private function addTrustee($trustee) {
        $ext = $this->createElement('extension');
        $infdata = $this->createElement('keysys:update');
        $domdata = $this->createElement('keysys:domain');
        $cd = $this->createElement('keysys:de-accept-trustee-tac', $trustee);
        $domdata->appendChild($cd);
        $infdata->appendChild($domdata);
        $ext->appendChild($infdata);
        $this->getCommand()->appendChild($ext);
    }


}
