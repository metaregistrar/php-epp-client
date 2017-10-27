<?php
namespace Metaregistrar\EPP;

/*
<?xml version='1.0' encoding='UTF-8'?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="request">
      <domain:transfer xmlns:domain='urn:ietf:params:xml:ns:domain-1.0'>
        <domain:name>some-domain-name</domain:name>
        <domain:authInfo>
          <domain:pw>XXXX-2N5J-L7AU-MV2L</domain:pw>
        </domain:authInfo>
      </domain:transfer>
    </transfer>
    <extension>
      <domain-ext:transfer xmlns:domain='urn:ietf:params:xml:ns:domain-1.0' xmlns:domain-ext='http://www.eurid.eu/xml/epp/domain-ext-2.1'>
        <domain-ext:request>
          <domain-ext:contact type='billing'>c10</domain-ext:contact>
          <domain-ext:contact type='tech'>c159</domain-ext:contact>
        </domain-ext:request>
      </domain-ext:transfer>
    </extension>
  </command>
</epp>
 */


class euridEppTransferDomainRequest extends eppTransferRequest {
    function __construct($operation, eppDomain $domain) {
        parent::__construct($operation,$domain);
        $this->addContacts($domain);
        parent::addSessionId();

    }

    private function addContacts(eppDomain $domain) {
        $transfer = $this->createElement('domain-ext:transfer');
        $this->setNamespace('xmlns:domain','urn:ietf:params:xml:ns:domain-1.0',$transfer);
        $this->setNamespace('xmlns:domain-ext','http://www.eurid.eu/xml/epp/domain-ext-2.1',$transfer);
        $request = $this->createElement('domain-ext:request');
        foreach ($domain->getContacts() as $contact) {
            /* @var $contact \Metaregistrar\EPP\eppContactHandle */
            $c = $this->createElement('domain-ext:contact',$contact->getContactHandle());
            $c->setAttribute('type',$contact->getContactType());
            $request->appendChild($c);
        }
        $transfer->appendChild($request);
        $this->getExtension()->appendChild($transfer);
    }


}