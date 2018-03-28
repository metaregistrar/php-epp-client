<?php
namespace Metaregistrar\EPP;
/*
<extension>
    <dnsbe:ext>
      <dnsbe:transfer>
        <dnsbe:domain>
         <dnsbe:registrant>c25</dnsbe:registrant>
         <dnsbe:billing>c20</dnsbe:billing>
         <dnsbe:tech>c21</dnsbe:tech>
        <dnsbe:ns>
            <domain:hostAttr>
                <domain:hostName>ns1.superdomain.be</domain:hostName>
            </domain:hostAttr>
            <domain:hostAttr>
                <domain:hostName>ns.test.be</domain:hostName> </domain:hostAttr>
          </dnsbe:ns>
        </dnsbe:domain>
      </dnsbe:transfer>
    </dnsbe:ext>
</extension>
*/
class dnsbeEppTransferRequest extends eppTransferRequest {

    private $transfer = null;
    private $domain = null;
    
    function __construct($operation, $object, $tech = null, $billing = null, $onsite = null, $registrant = null) {
        parent::__construct($operation, $object);
        $this->addExtension('xmlns:dnsbe', 'http://www.dns.be/xml/epp/dnsbe-1.0');
        $ext = $this->createElement('extension');
        $dnsbeext = $this->createElement('dnsbe:ext');
        $this->transfer = $this->createElement('dnsbe:transfer');
        $this->domain = $this->createElement('dnsbe:domain');
        $this->addDnsbeExtension($tech, $billing, $onsite, $registrant);
        $this->addDnsbeNameservers($object);
        $this->transfer->appendChild($this->domain);
        $dnsbeext->appendChild($this->transfer);
        $ext->appendChild($dnsbeext);
        $this->getCommand()->appendChild($ext);
        $this->addSessionId();
    }
    
    public function addDnsbeNameservers(eppDomain $domain) {
        if ($this->domain) {
            // Set Nameservers at Transfer if needed
            $nsobjects = $domain->getHosts();
            if ($domain->getHostLength() > 0) {
                $nameservers = $this->createElement('dnsbe:ns');
                foreach ($nsobjects as $nsobject) {
                    /* @var $nsobject \Metaregistrar\EPP\eppHost */
                    $attr = $this->createElement('domain:hostAttr');
                    $c = $this->createElement('domain:hostName', $nsobject->getHostname());
                    $attr->appendChild($c);

                    $nameservers->appendChild($attr);
                }
                $this->domain->appendChild($nameservers);
            }
        }

    }

    public function addDnsbeExtension($tech = null, $billing = null, $onsite=null, $registrant=null) {
        if ($registrant) {
            $this->domain->appendChild($this->createElement('dnsbe:registrant', $registrant));
        } else {
            $this->domain->appendChild($this->createElement('dnsbe:registrant', '#AUTO#'));
        }
        if ($billing) {
            $this->domain->appendChild($this->createElement('dnsbe:billing', $billing));
        }
        if ($tech) {
            $this->domain->appendChild($this->createElement('dnsbe:tech', $tech));
        }
        if ($onsite) {
            $this->domain->appendChild($this->createElement('dnsbe:onsite', $onsite));
        }
;
    }
}