<?php
namespace Metaregistrar\EPP;
/*
 *
<?xml version="1.0" encoding="UTF-8"?>
  <epp xmlns="urn:ietf:params:xml:ns:epp-1.0"
       xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
       xmlns:nsgroup="http://www.dns.be/xml/epp/nsgroup-1.0"
       xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd
                           http://www.dns.be/xml/epp/nsgroup-1.0 nsgroup-1.0.xsd">
   <command>
    <delete>
     <nsgroup:delete>
       <nsgroup:name>mynsgroup1</nsgroup:name>
     </nsgroup:delete>
    </delete>
    <clTRID>clientref-00013</clTRID>
   </command>
  </epp>
 */
class dnsbeEppDeleteNsgroupRequest extends eppRequest {
   /**
    * @var \DOMElement
    */
   private $hostobject;

   /**
    * dnsbeEppDeleteNsgroupRequest constructor.
    * @param $nsgroup
    * @throws eppException
    */
   function __construct($nsgroup)
   {
      parent::__construct();
      $this->addExtension('xmlns:nsgroup', 'http://www.dns.be/xml/epp/nsgroup-1.0');
      if (is_string($nsgroup)) {
         if (strlen($nsgroup) > 0) {
            $this->addNsGroup($nsgroup);
         } else {
            throw new eppException("Group name length may not be 0 on eppAuthcodeRequest");
         }
      } else {
         throw new eppException("Group name must be string on eppAuthcodeRequest");
      }
      $this->addSessionId();
   }

   /**
    * @param $groupname
    */
   private function addNsGroup($groupname) {
      $create = $this->createElement('delete');
      $this->hostobject = $this->createElement('nsgroup:delete');
      $this->hostobject->appendChild($this->createElement('nsgroup:name', $groupname));
      $create->appendChild($this->hostobject);
      $this->getCommand()->appendChild($create);
   }
}