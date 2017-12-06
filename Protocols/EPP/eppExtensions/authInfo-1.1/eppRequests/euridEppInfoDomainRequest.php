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
class euridEppInfoDomainRequest extends eppInfoDomainRequest {
    function __construct($infodomain, $hosts = null, $withAuthcode = false) {
        parent::__construct($infodomain, $hosts);
        if($withAuthcode == true) {
            $this->addEURIDExtension();
        }
        $this->addSessionId();
    }


    public function addEURIDExtension() {
        $authext = $this->createElement('authInfo:info');
        $authext->setAttribute('xmlns:authInfo', 'http://www.eurid.eu/xml/epp/authInfo-1.1');
        $authext->appendChild($this->createElement('authInfo:request'));
        $this->getExtension()->appendChild($authext);
    }

}