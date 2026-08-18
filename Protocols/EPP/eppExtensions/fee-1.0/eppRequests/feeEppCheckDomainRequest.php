<?php
namespace Metaregistrar\EPP;
/**
*
https://datatracker.ietf.org/doc/rfc8748/

<?xml version="1.0" encoding="utf-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <check>
      <domain:check
        xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
        <domain:name>example.net</domain:name>
        <domain:name>example.xyz</domain:name>
      </domain:check>
    </check>
    <extension>
      <fee:check xmlns:fee="urn:ietf:params:xml:ns:fee-1.0">
        <fee:currency>USD</fee:currency>
        <fee:command name="create">
          <fee:period unit="y">2</fee:period>
        </fee:command>
        <fee:command name="renew"/>
        <fee:command name="transfer"/>
        <fee:command name="restore"/>
      </fee:check>
    </extension>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
 */

/**
 * Class feeEppCheckDomainRequest
 * @package Metaregistrar\EPP
 */
class feeEppCheckDomainRequest extends eppCheckDomainRequest {

	private $checkcommand = null;

    function __construct($checkrequest, $namespacesinroot=true) {
        parent::__construct($checkrequest, $namespacesinroot);

    }

    public function addFee($command, $currency = 'USD', $period = null, $phase=null) {
        if (!in_array($command,['renew','transfer','restore','create','delete','update','custom'])) {
            throw new eppException('Command must be create, delete, renew, update, transfer, restore, or custom on addFee command');
        }
	    $cmd = $this->createElement('fee:command');
	    $cmd->setAttribute('name',$command);
	    if ($period) {
		    $per = $this->createElement('fee:period',$period);
		    $per->setAttribute('unit','y');
		    $cmd->appendChild($per);
	    }
	    if ($phase) {
		    $cmd->setAttribute('phase',$phase);
	    }
		if (!$this->checkcommand) {
			$this->checkcommand = $this->createElement('fee:check');
			$this->checkcommand->setAttribute('xmlns:fee', 'urn:ietf:params:xml:ns:epp:fee-1.0');
			$this->checkcommand->appendChild($this->createElement('fee:currency',$currency));
			$this->checkcommand->appendChild($cmd);
			$this->getExtension()->appendChild($this->checkcommand);
		} else {
			$this->checkcommand->appendChild($cmd);
		}
        $this->addSessionId();
    }
}