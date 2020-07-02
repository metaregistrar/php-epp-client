<?php
namespace Metaregistrar\EPP;

/*
<extension>
     <keysys:update xmlns:keysys="http://www.key-systems.net/epp/keysys-1.0">
      <keysys:domain>
       <keysys:renewalmode>AUTOEXPIRE</keysys:renewalmode>
      </keysys:domain>
     </keysys:update>
   </extension>
 */


class rrpproxyEppRenewalmodeRequest extends eppUpdateDomainRequest {

    const RRP_RENEWALMODE_DEFAULT = "DEFAULT";
    const RRP_RENEWALMODE_AUTORENEW = "AUTORENEW";
    const RRP_RENEWALMODE_AUTOEXPIRE = "AUTOEXPIRE";
    const RRP_RENEWALMODE_AUTODELETE = "AUTODELETE";
    const RRP_RENEWALMODE_RENEWONCE = "RENEWONCE";
    const RRP_RENEWALMODE_RENEWONCETHENAUTODELETE = "RENEWONCETHENAUTODELETE";
    const RRP_RENEWALMODE_RENEWONCETHENAUTOEXPIRE = "RENEWONCETHENAUTOEXPIRE";

    /**
     * rrpproxyEppRenewalmodeRequest constructor.
     * @param eppDomain $domain
     * @param string $renewalmode
     * @throws eppException
     */
    function __construct(eppDomain $domain, $renewalmode) {
        $upd = new eppDomain($domain->getDomainname());
        parent::__construct($domain, null, null, $upd);
        $this->addRenewalmode($renewalmode);
        parent::addSessionId();
    }

    /**
     * @param string $renewalmode
     */
    private function addRenewalmode($renewalmode){
        $ext = $this->createElement('extension');
        $infdata = $this->createElement('keysys:update');
        $domdata = $this->createElement('keysys:domain');
        $cd = $this->createElement('keysys:renewalmode', $renewalmode);
        $domdata->appendChild($cd);
        $infdata->appendChild($domdata);
        $ext->appendChild($infdata);
        $this->getCommand()->appendChild($ext);
    }
}