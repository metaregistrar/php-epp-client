<?php
namespace Metaregistrar\EPP;
/**
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
   <response>
      <result code="1000">
         <msg>Command completed successfully</msg>
      </result>
      <trID>
         <clTRID>clientref-00013</clTRID>
         <svTRID>dnsbe-110</svTRID>
      </trID>
   </response>
</epp>
 */
/**
 * Class dnsbeEppInfoNsgroupResponse
 * @package Metaregistrar\EPP
 */
class dnsbeEppDeleteNsgroupResponse extends eppResponse {
    function __construct() {
        parent::__construct();
    }
   function __destruct() {
      parent::__destruct();
   }
}
