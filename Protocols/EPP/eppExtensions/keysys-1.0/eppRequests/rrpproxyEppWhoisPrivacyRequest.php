<?php
namespace Metaregistrar\EPP;

/*
     <extension>
      <keysys:update xmlns:keysys="http://www.key-systems.net/epp/keysys-1.0">
        <keysys:domain>
          <keysys:whois-privacy>1</keysys:whois-privacy>
        </keysys:domain>
      </keysys:update>
    </extension>
*/



class rrpproxyEppWhoisPrivacyRequest extends eppUpdateDomainRequest {


    function __construct(eppDomain $domain, $enableprivacy) {
        $upd = new eppDomain($domain->getDomainname());
        parent::__construct($domain, null, null, $upd);
        $this->addPrivacy($enableprivacy);
        parent::addSessionId();

    }

    private function addPrivacy($enableprivacy) {
        $ext = $this->createElement('extension');
        $infdata = $this->createElement('keysys:update');
        $domdata = $this->createElement('keysys:domain');
        $cd = $this->createElement('keysys:whois-privacy', $enableprivacy);
        $domdata->appendChild($cd);
        $infdata->appendChild($domdata);
        $ext->appendChild($infdata);
        $this->getCommand()->appendChild($ext);
    }


}