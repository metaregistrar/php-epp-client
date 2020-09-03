<?php
namespace Metaregistrar\EPP;

/*
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd">
    <extension>
        <command xmlns="http://www.nic.at/xsd/at-ext-epp-1.0" xsi:schemaLocation="http://www.nic.at/xsd/at-ext-epp-1.0 at-ext-epp-1.0.xsd">
            <withdraw>
                <domain:withdraw xmlns:domain="http://www.nic.at/xsd/at-ext-domain-1.0" xsi:schemaLocation="http://www.nic.at/xsd/at-ext-domain-1.0 at-ext-domain-1.0.xsd">
                    <domain:name>example.at</domain:name>
                    <domain:zd value="0"/>
                </domain:withdraw>
            </withdraw>
        <clTRID>ABC-12345</clTRID>
        </command>
    </extension>
</epp>
*/

class atEppWithdrawRequest extends eppRequest {
    const WITHDRAW_ZONEDELETE_YES = "1";
    const WITHDRAW_ZONEDELETE_NO = "0";

    function __construct(string $domainname, $zonedelete = self::WITHDRAW_ZONEDELETE_YES) {
        parent::__construct();
        $this->addATWithdrawExtension($domainname, $zonedelete);
        //$this->addSessionId();
    }

    public function addATWithdrawExtension($domainname, $zonedelete) {
        //$commandext = $this->createElement('command');
        $this->command = $this->createElement('command');
        $command = $this->createElement('command');
        $command->setAttribute('xmlns', 'http://www.nic.at/xsd/at-ext-epp-1.0');
        $command->setAttribute('xsi:schemaLocation', 'http://www.nic.at/xsd/at-ext-epp-1.0 at-ext-epp-1.0.xsd');
        $withdrawext = $this->createElement('withdraw');
        $domain_withdrawext = $this->createElement('domain:withdraw');
        $domain_withdrawext->setAttribute('xmlns:domain', 'http://www.nic.at/xsd/at-ext-domain-1.0');
        $domain_withdrawext->setAttribute('xsi:schemaLocation', 'http://www.nic.at/xsd/at-ext-domain-1.0 at-ext-domain-1.0.xsd');
        $domain_name = $this->createElement('domain:name', $domainname);
        $domain_zd = $this->createElement('domain:zd');
        $domain_zd->setAttribute('value', $zonedelete);
        $domain_withdrawext->appendChild($domain_name);
        $domain_withdrawext->appendChild($domain_zd);
        $withdrawext->appendChild($domain_withdrawext);
        //$commandext->appendChild($withdrawext);
        $command->appendChild($withdrawext);
        $command->appendChild($this->createElement('clTRID', $this->sessionid));
        $this->getExtension()->appendChild($command);
        $this->getEpp()->appendChild($this->getExtension());
        $this->getEpp()->setAttribute('xmlns', 'urn:ietf:params:xml:ns:epp-1.0');
        $this->getEpp()->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $this->getEpp()->setAttribute('xsi:schemaLocation', 'urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd');
    }

}