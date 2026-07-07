<?php
namespace Metaregistrar\EPP;

/**
 * <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
 *   <command>
 *     <check>
 *       <org:check
 *         xmlns:org="urn:ietf:params:xml:ns:epp:org-1.0">
 *         <org:id>res1523</org:id>
 *         <org:id>re1523</org:id>
 *         <org:id>1523res</org:id>
 *       </org:check>
 *     </check>
 *     <clTRID>ABC-12345</clTRID>
 *   </command>
 * </epp>
 */

class orgEppCheckRequest extends eppRequest {
	/**
	 * @param string|array $orgid
	 * @throws \DOMException
	 *
	 * Check on or more reseller ID's to see if they are taken or free
	 */
	function __construct(string|array $orgid) {
		parent::__construct();
		$epp = $this->getEpp();
		$command = $this->createElement('command');
		$check = $this->createElement('check');
		$orgcheck = $this->createElement('org:check');
		if (is_string($orgid)) {
			$id = $this->createElement('org:id',$orgid);
			$orgcheck->appendChild($id);
		}
		if ((is_array($orgid)) && (count($orgid)>0)) {
			foreach ($orgid as $id) {
				$id = $this->createElement('org:id',$id);
				$orgcheck->appendChild($id);
			}
		}
		$check->appendChild($orgcheck);
		$command->appendChild($check);
		$epp->appendChild($command);
	}
}