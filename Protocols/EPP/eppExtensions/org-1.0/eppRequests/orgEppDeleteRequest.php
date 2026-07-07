<?php
namespace Metaregistrar\EPP;

/**
 * <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
 *   <command>
 *     <delete>
 *       <org:delete
 *        xmlns:org="urn:ietf:params:xml:ns:epp:org-1.0">
 *         <org:id>res1523</org:id>
 *       </org:delete>
 *     </delete>
 *     <clTRID>ABC-12345</clTRID>
 *   </command>
 * </epp>
 */

class orgEppDeleteRequest extends eppRequest {
	/**
	 * @param string $orgid
	 * @throws \DOMException
	 *
	 * Delete a reseller profile
	 */
	function __construct(string $orgid) {
		parent::__construct();
		$epp = $this->getEpp();
		$command = $this->createElement('command');
		$delete = $this->createElement('delete');
		$orgdelete = $this->createElement('org:delete');
		$id = $this->createElement('org:id',$orgid);
		$orgdelete->appendChild($id);
		$delete->appendChild($orgdelete);
		$command->appendChild($delete);
		$epp->appendChild($command);
	}
}
