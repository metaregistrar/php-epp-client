<?php
namespace Metaregistrar\EPP;
/*
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <info>
      <domain:info xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>domain-0007.eu</domain:name>
      </domain:info>
    </info>
    <extension>
       <authInfo:info xmlns:authInfo="http://www.eurid.eu/xml/epp/authInfo-1.0">
         <authInfo:request/>
       </authInfo:info>
    </extension>
  </command>
</epp>


*/
class authEppInfoDomainRequest extends eppInfoDomainRequest {
    function __construct($infodomain, $hosts = null, $withAuthcode = false, $cancelAuthCode = false) {
        parent::__construct($infodomain, $hosts);

        if ($withAuthcode && $cancelAuthCode) {
            throw new eppException('Cannot request and cancel authcode at the same time');
        }

        if ($withAuthcode) {
            $this->addAuthExtension('authInfo:request');
        } elseif ($cancelAuthCode) {
            $this->addAuthExtension('authInfo:cancel');
        }
      
        $this->addSessionId();
    }


    public function addAuthExtension(string $method) {
        $authext = $this->createElement('authInfo:info');
        $authext->setAttribute('xmlns:authInfo', 'http://www.eurid.eu/xml/epp/authInfo-1.1');
        $authext->appendChild($this->createElement($method));
        $this->getExtension()->appendChild($authext);
    }

}
