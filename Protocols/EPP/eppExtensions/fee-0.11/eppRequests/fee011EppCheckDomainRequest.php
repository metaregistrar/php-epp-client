<?php
namespace Metaregistrar\EPP;

class fee011EppCheckDomainRequest extends eppCheckDomainRequest {

	const COMMAND_CREATE = 'create';
	const COMMAND_RENEW = 'renew';
	const COMMAND_TRANSFER = 'transfer';

	function __construct($checkrequest, $command = 'create', $namespacesinroot=true) {
		parent::__construct($checkrequest, $namespacesinroot);
		// $command may be 'create', 'renew', 'transfer'
		// $period is in years and may be 1 - 10
		// $class is determined by the registry and can contain certain tiers of pricing
		$this->addFee($checkrequest,$command);
		$this->addSessionId();
	}

	private function addFee(eppDomain $domain, string $command, ?string $class=null, ?int $period = null, ?string $currency = null) {
		if (!in_array($command,['create','renew','transfer'])) {
			throw new eppException('Invalid command for fee-0.11 extension. Valid commands are create, renew, transfer');
		}
		if ($period)  {
			if (($period == 0) || ($period>10)) {
				throw new eppException('Invalid period for fee-0.11 extension. Valid periods are 0-10');
			}
		}
		$extension = $this->getExtension();
		$check = $this->createElement('fee:check');
		$check->setAttribute('xmlns:fee','urn:ietf:params:xml:ns:fee-0.11');
		$check->appendChild($this->createElement('fee:command',$command));
		if ($currency) {
			$check->appendChild($this->createElement('fee:currency',$currency));
		}
		if ($class) {
			$check->appendChild($this->createElement('fee:class',$class));
		}
		if ($period) {
			$per = $this->createElement('fee:period',$period);
			$per->setAttribute('unit','y');
			$check->appendChild($per);
		}
		$extension->appendChild($check);
		return;
	}
}