<?php
namespace Metaregistrar\EPP;
/*
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0"
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xmlns:contact="urn:ietf:params:xml:ns:contact-1.0"
     xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd
                urn:ietf:params:xml:ns:contact-1.0 contact-1.0.xsd">
<command>
  <info>
    <contact:info>
      <contact:id>c16</contact:id>
    </contact:info>
  </info>
  <clTRID>clientref-00010</clTRID>
</command>
</epp>
*/
class dnsbeEppInfoContactRequest extends eppInfoContactRequest {

   /* Requests is standard, Response contains an extension element */

   function __construct($inforequest, $namespacesinroot = true) {
      parent::__construct($inforequest, $namespacesinroot);
   }
}