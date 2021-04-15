<?php
namespace Metaregistrar\EPP;
/**
 * Class eppBalanceInfoRequest
 * @package Metaregistrar\EPP
 *
 *
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
    <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
    <command>
        <info>
            <balance:info xmlns:balance="http://www.verisign.com/epp/balance-1.0"/>
        </info>
        <clTRID>ABC-12345</clTRID>
    </command>
</epp>
 **/
class eppBalanceInfoRequest extends eppRequest {
    /**
     * eppBalanceInfoRequest constructor
     */
    function __construct() {
        parent::__construct();
        $this->setNamespacesinroot(false);
        $info = $this->createElement('info');
        $balanceinfo = $this->createElement('balance:info');
        if (!$this->rootNamespaces()) {
            $balanceinfo->setAttribute('xmlns:balance','http://www.verisign.com/epp/balance-1.0');
        }
        $info->appendChild($balanceinfo);
        $this->getCommand()->appendChild($info);
        $this->addSessionId();
    }
}
