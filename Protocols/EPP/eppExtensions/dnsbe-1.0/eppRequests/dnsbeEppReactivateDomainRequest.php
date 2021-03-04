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
  <reactivate>
    <domain:reactivate>
      <domain:name>greatdomain.be</domain:name>
    </domain:reactivate>
  </reactivate>
  <clTRID>clientref-00028</clTRID>
</command>
</epp>
*/

class dnsbeEppReactivateDomainRequest extends eppRequest {

    function __construct($reactivateinfo) {
        parent::__construct($reactivateinfo);
       if ($reactivateinfo instanceof eppDomain) {
          $this->setDomain($reactivateinfo);
       } else {
          throw new eppException('parameter of dnsbeEppReactivateDomainRequest must be valid eppDomain object');
       }
        $this->addSessionId();
    }

   public function setDomain(eppDomain $domain) {
      if (!strlen($domain->getDomainname())) {
         throw new eppException('dnsbeEppReactivateDomainRequest domain object does not contain a valid domain name');
      }
      #
      # Object delete structure
      #

      $reactivate = $this->createElement('reactivate');
      $domainreactivate = $this->createElement('domain:reactivate');
      $domainname = $this->createElement('domain:name', $domain->getDomainname());
      $domainreactivate->appendChild($domainname);
      $reactivate->appendChild($domainreactivate);
      $this->getCommand()->appendChild($reactivate);
   }
}