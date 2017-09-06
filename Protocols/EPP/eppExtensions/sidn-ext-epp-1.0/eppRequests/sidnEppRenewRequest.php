<?php
namespace Metaregistrar\EPP;
/*
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
 <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
     <command>
         <renew>
             <domain:renew xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
                 <domain:name>domain100-renew.nl</domain:name>
                 <domain:curExpDate>2012-01-01</domain:curExpDate>
                 <domain:period unit="m">1</domain:period>
             </domain:renew>
         </renew>
         <clTRID>TestVWDNC10T20</clTRID>
     </command>
 </epp>
 */
class sidnEppRenewRequest extends eppRenewRequest {


    function __construct($domainname, $expdate, $period) {
        parent::__construct($domainname, $expdate);

        $this->addSidnExtension($period);
        $this->addSessionId();
    }

    private function addSidnExtension($period) {
        $renew = $this->getCommand()->getElementsByTagName('domain:renew')->item(0);
        $period = $this->createElement('domain:period', $period);
        $period->setAttribute("unit", "m");
        $renew->appendChild($period);
    }
}