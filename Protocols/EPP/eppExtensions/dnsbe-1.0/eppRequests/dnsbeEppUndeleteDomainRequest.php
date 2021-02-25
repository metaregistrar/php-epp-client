<?php
namespace Metaregistrar\EPP;
/*
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0"
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xmlns:domain="urn:ietf:params:xml:ns:domain-1.0"
     xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd
                         urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd">
<command>
  <undelete>
    <domain:undelete>
      <domain:name>test-domain.be</domain:name>
    </domain:undelete>
  </undelete>
  <clTRID>clientref-00024</clTRID>
</command>
</epp>
*/

class dnsbeEppUndeleteDomainRequest extends eppUndeleteRequest {

    function __construct($deleteinfo) {
        parent::__construct($deleteinfo);
        $this->addSessionId();
    }

   public function setDomain(eppDomain $domain) {
      if (!strlen($domain->getDomainname())) {
         throw new eppException('dnsbeEppUndeleteRequest domain object does not contain a valid domain name');
      }
      #
      # Object delete structure
      #

      $undelete = $this->createElement('undelete');
      $domainundelete = $this->createElement('domain:undelete');
      $domainname = $this->createElement('domain:name', $domain->getDomainname());
      $domainundelete->appendChild($domainname);
      $undelete->appendChild($domainundelete);
      $this->getCommand()->appendChild($undelete);
   }
}