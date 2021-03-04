<?php
namespace Metaregistrar\EPP;

/**
 * Class dnsbeEppInfoDomainResponse
 * @package Metaregistrar\EPP
 */
class dnsbeEppInfoContactResponse extends eppInfoContactResponse {
    function __construct() {
        parent::__construct();
    }


    /**
     * Retrieve the vat registered number of the contact
     * @return string|null
     */
    public function getVat() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/dnsbe:ext/dnsbe:infData/dnsbe:contact/dnsbe:vat');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }


    /**
     * Retrieve the the type of contact. Specific to DNS.be.
     * @return string|null
     */
    public function getDnsBeContactType() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/dnsbe:ext/dnsbe:infData/dnsbe:contact/dnsbe:type');
        if ($result->length > 0) {
           return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }
   /**
    * Retrieve the the type of contact. Specific to DNS.be.
    * @return string|null
    */
   public function getLang() {
      $xpath = $this->xPath();
      $result = $xpath->query('/epp:epp/epp:response/epp:extension/dnsbe:ext/dnsbe:infData/dnsbe:contact/dnsbe:lang');
      if ($result->length > 0) {
         return $result->item(0)->nodeValue;
      } else {
         return null;
      }
   }

}

