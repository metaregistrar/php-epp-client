<?php
namespace Metaregistrar\EPP;
/*
    DNSBE supports hostattrs, but no hostobjs. This object forces the epp connection to use hostattrs
*/
class dnsbeEppCreateDomainRequest extends eppCreateDomainRequest {
    function __construct($createinfo) {

        if ($createinfo instanceof eppDomain) {
            $this->setForcehostattr(true);
            parent::__construct($createinfo, $this->getForcehostattr());
        } else {
            throw new eppException('DNSBE does not support Host objects');
        }
        $this->addSessionId();
    }

   public function addnsgroup($nsgroup) {
      $this->addExtension('xmlns:dnsbe', 'http://www.dns.be/xml/epp/dnsbe-1.0');
      $ext = $this->createElement('extension');
      $dnsext = $this->createElement('dnsbe:ext');
      $create = $this->createElement('dnsbe:create');
      $domain = $this->createElement('dnsbe:domain');
      if(is_array($nsgroup)){
         foreach ($nsgroup as $nsgroupname){
            $domain->appendChild($this->createElement('dnsbe:nsgroup', $nsgroupname));
         }
      }
      else {
         $domain->appendChild($this->createElement('dnsbe:nsgroup', $nsgroup));
      }
      $create->appendChild($domain);
      $dnsext->appendChild($create);
      $ext->appendChild($dnsext);
      $this->getCommand()->appendChild($ext);
      $this->addSessionId();
   }
}