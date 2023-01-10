<?php
namespace Metaregistrar\EPP;

class bNamedEppUndeleteDomainRequest extends eppUndeleteRequest {

    function __construct($deleteinfo) {
        parent::__construct($deleteinfo);
        $this->addSessionId();
    }

   public function setDomain(eppDomain $domain) {
      if (!strlen($domain->getDomainname())) {
         throw new eppException('bnamedEppUndeleteRequest domain object does not contain a valid domain name');
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
