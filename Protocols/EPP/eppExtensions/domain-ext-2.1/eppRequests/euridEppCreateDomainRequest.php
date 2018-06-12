<?php
namespace Metaregistrar\EPP;

/*
<?xml version='1.0' encoding='UTF-8'?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <create>
      <domain:create xmlns:domain='urn:ietf:params:xml:ns:domain-1.0'>
        <domain:name>domainname.eu</domain:name>
        <domain:ns>
          <domain:hostAttr>
            <domain:hostName>a.alpha.al</domain:hostName>
          </domain:hostAttr>
          <domain:hostAttr>
            <domain:hostName>b.bravo.bb</domain:hostName>
          </domain:hostAttr>
        </domain:ns>
        <domain:registrant>xxxxx</domain:registrant>
        <domain:contact type='billing'>xxxxx</domain:contact>
      </domain:create>
    </create>
    <extension>
      <domain-ext:create xmlns:domain-ext='http://www.eurid.eu/xml/epp/domain-ext-2.1'>
        <domain-ext:contact type='onsite'>xxxxx</domain-ext:contact>
        <domain-ext:contact type='reseller'>xxxxx</domain-ext:contact>
      </domain-ext:create>
    </extension>
  </command>
</epp>
 */

/**
 * Class euridEppCreateDomainRequest
 * @package Metaregistrar\EPP
 */
class euridEppCreateDomainRequest extends eppCreateDomainRequest {

    function __construct(eppDomain $domain) {
        parent::__construct($domain, true);
        $this->addContacts($domain);
        parent::addSessionId();

    }

    private function addContacts(eppDomain $domain) {
        $created = false;
        $create = $this->createElement('domain-ext:create');
        $this->setNamespace('xmlns:domain','urn:ietf:params:xml:ns:domain-1.0',$create);
        $this->setNamespace('xmlns:domain-ext','http://www.eurid.eu/xml/epp/domain-ext-2.1',$create);

        foreach ($domain->getContacts() as $contact) {
            /* @var $contact \Metaregistrar\EPP\eppContactHandle */
            if (in_array($contact->getContactType(),['onsite','reseller'])) {
                $c = $this->createElement('domain-ext:contact',$contact->getContactHandle());
                $c->setAttribute('type',$contact->getContactType());
                $create->appendChild($c);
                $created = true;
            }
        }
        if ($created) {
            $this->getExtension()->appendChild($create);
        }
    }


}