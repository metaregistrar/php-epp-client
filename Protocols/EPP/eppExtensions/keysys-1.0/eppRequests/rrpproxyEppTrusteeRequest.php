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

    /**
     * rrpproxyEppTrusteeRequest constructor.
     * @param eppDomain $domain
     * @param boolean $accepttrustee
     */
    function __construct(eppDomain $domain, $accepttrustee) {
        $upd = new eppDomain($domain->getDomainname());
        parent::__construct($domain, null, null, $upd);
        if ($accepttrustee) {
            $this->addTrustee('1');
        } else {
            $this->addTrustee('0');
        }

        parent::addSessionId();

    }

    /**
     * @param string $accepttrustee
     */
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