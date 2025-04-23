<?php
namespace Metaregistrar\EPP;

/*
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <delete>
      <domain:delete xmlns:domain='urn:ietf:params:xml:ns:domain-1.0'>
        <domain:name>testdelete-1349683848.eu</domain:name>
      </domain:delete>
    </delete>
    <extension>
      <domain-ext:delete xmlns:domain-ext='http://www.eurid.eu/xml/epp/domain-ext-2.5'>
        <domain-ext:schedule>
          <domain-ext:delDate>2018-01-01T00:00:00.0Z</domain-ext:delDate>
        </domain-ext:schedule>
      </domain-ext:delete>
    </extension>
  </command>
</epp>
*/

class euridEppDeleteDomainRequest extends eppDeleteDomainRequest {
    function __construct(eppDomain $deleteinfo, $namespacesinroot = true, ?\DateTime $deleteDate = null) {
        parent::__construct($deleteinfo, $namespacesinroot);
        if($deleteDate !== null) {
            $this->addEURIDExtension($deleteDate->format('Y-m-d\TH:i:s\.\0\Z'));
        }
        $this->addSessionId();
    }

    public function addEURIDExtension($delDate) {
        $deleteext = $this->createElement('domain-ext:delete');
        $deleteext->setAttribute('xmlns:domain-ext', 'http://www.eurid.eu/xml/epp/domain-ext-2.5');
        $scheduleext = $this->createElement('domain-ext:schedule');
        $scheduleext->appendChild($this->createElement('domain-ext:delDate', $delDate));
        $deleteext->appendChild($scheduleext);
        $this->getExtension()->appendChild($deleteext);
    }

}
