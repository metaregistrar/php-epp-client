<?php
/*
 * Example taken from Official Documentation
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xmlns:domain="urn:ietf:params:xml:ns:domain-1.0"
xmlns:dnsbe="http://www.dns.be/xml/epp/dnsbe-1.0"
xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd
urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd
http://www.dns.be/xml/epp/dnsbe-1.0 dnsbe-1.0.xsd">
<command>
<update>
  <domain:update>
    <domain:name>greatdomain.be</domain:name>
      <domain:add>
      <domain:ns>
         <domain:hostAttr>
            <domain:hostName>ns2.greatdomain.be</domain:hostName>
            <domain:hostAddr>193.168.0.2</domain:hostAddr>
         </domain:hostAttr>
      </domain:ns>
     <domain:contact type="onsite">c18</domain:contact>
     </domain:add>
     <domain:rem>
       <domain:ns>
         <domain:hostAttr>
            <domain:hostName>ns.hostingcompany.be</domain:hostName>
         </domain:hostAttr>
    </domain:ns>
    </domain:rem>
 </domain:update>
</update>
<extension>
  <dnsbe:ext>
    <dnsbe:update>
      <dnsbe:domain>
         <dnsbe:add>
            <dnsbe:nsgroup>newnsgroup1</dnsbe:nsgroup>
         </dnsbe:add>
         <dnsbe:rem>
           <dnsbe:nsgroup>mynsgroup1</dnsbe:nsgroup>
         </dnsbe:rem>
     </dnsbe:domain>
  </dnsbe:update>
</dnsbe:ext>
</extension>
<clTRID>clientref-00020</clTRID>
</command>
</epp>
 */

namespace Metaregistrar\EPP;

class dnsbeEppUpdateDomainRequest extends eppUpdateDomainRequest {

   function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null, $forcehostattr = true, $namespacesinroot = true) {
      parent::__construct($objectname, $addinfo, $removeinfo, $updateinfo, $forcehostattr, $namespacesinroot);
      $this->setForcehostattr($forcehostattr);
      $this->addSessionId();
   }

   public function updatensgroup($addnsgroup = null, $removensgroup = null) {
      $this->addExtension('xmlns:dnsbe', 'http://www.dns.be/xml/epp/dnsbe-1.0');
      $ext = $this->createElement('extension');
      $dnsext = $this->createElement('dnsbe:ext');
      $update = $this->createElement('dnsbe:update');
      $domain = $this->createElement('dnsbe:domain');
      if ($addnsgroup !== null) {
         if (is_array($addnsgroup) && !empty($addnsgroup)) {
            $add = $this->createElement('dnsbe:add');
            foreach ($addnsgroup as $nsgroupname) {
               $add->appendChild($this->createElement('dnsbe:nsgroup', $nsgroupname));
            }
         } elseif (is_string($addnsgroup)) {
            $add = $this->createElement('dnsbe:add');
            $add->appendChild($this->createElement('dnsbe:nsgroup', $addnsgroup));
         } else {
            throw new eppException("addnsgroup must either be an array or a string in updatensgroup");
         }
         $domain->appendChild($add);
      }
      if ($removensgroup !== null) {
         if (is_array($removensgroup) && !empty($removensgroup)) {
            $rem = $this->createElement('dnsbe:rem');
            foreach ($removensgroup as $nsgroupname) {
               $rem->appendChild($this->createElement('dnsbe:nsgroup', $nsgroupname));
            }
         } elseif (is_string($removensgroup)) {
            $rem = $this->createElement('dnsbe:rem');
            $rem->appendChild($this->createElement('dnsbe:nsgroup', $removensgroup));
         } else {
            throw new eppException("removensgroup must either be an array or a string in updatensgroup");
         }
         $domain->appendChild($rem);
      }
      $update->appendChild($domain);
      $dnsext->appendChild($update);
      $ext->appendChild($dnsext);
      $this->getCommand()->appendChild($ext);
      $this->addSessionId();
   }

}