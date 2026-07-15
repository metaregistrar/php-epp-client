<?php
namespace Metaregistrar\EPP;

class fee011EppCheckdomainResponse extends eppCheckDomainResponse {

	public function getFees() {
		$result = [];
		$xpath = $this->xPath();
		$result['available'] = $xpath->query('/epp:epp/epp:response/epp:extension/fee:chkData/*')->item(0)->getAttribute('avail');
		$list = $xpath->query('/epp:epp/epp:response/epp:extension/fee:chkData/fee:cd/*');
		foreach ($list as $node) {
			/* @var \DOMElement $node */
			switch ($node->tagName) {
				case 'fee:command':
					$result['command'] = $node->nodeValue;
					break;
				case 'fee:fee':
					$result['price'] = $node->nodeValue;
					break;
				case 'fee:class':
					$result['class'] = $node->nodeValue;
					break;
				case 'fee:period':
					$result['period'] = $node->nodeValue;
					$periodunit = $node->getAttribute('unit');
					$result['periodunit'] = $periodunit;
					break;
				case 'fee:currency':
					$result['currency'] = $node->nodeValue;
					break;
				case 'fee:object':
					$result['domainname'] = $node->getElementsByTagName('name')->item(0)->nodeValue;
					break;
			};
		}
		return $result;
	}


}