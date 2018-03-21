<?php
namespace Metaregistrar\EPP;

/*
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:dnsbe="http://www.dns.be/xml/epp/dnsbe-1.0">
  <response>
    <result code="2306">
      <msg>Parameter value policy error</msg>
    </result>
    <extension>
      <dnsbe:ext>
        <dnsbe:result>
          <dnsbe:msg>authorisation code is invalid</dnsbe:msg>
        </dnsbe:result>
      </dnsbe:ext>
    </extension>
    <trID>
      <clTRID>5a01a56857e45</clTRID>
      <svTRID>dnsbe-78621920</svTRID>
    </trID>
  </response>
</epp>
 */



class dnsbeEppTransferResponse extends eppTransferResponse {

    function __construct() {
        parent::__construct();
    }

    /**
     *
     * @return string
     */
    public function getMsg() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/dnsbe:ext/dnsbe:result/dnsbe:msg');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

}

