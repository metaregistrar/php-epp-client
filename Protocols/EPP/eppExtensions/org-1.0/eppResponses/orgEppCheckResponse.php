<?php
namespace Metaregistrar\EPP;

/**
 * <epp xmlns="urn:ietf:paramxml:nepp-1.0">
 *   <response>
 *     <resData>
 *       <org:chkData
 *         xmlnorg="urn:ietf:paramxml:nepp:org-1.0">
 *         <org:cd>
 *           <org:id avail="1">res1523</org:id>
 *         </org:cd>
 *         <org:cd>
 *           <org:id avail="0">re1523</org:id>
 *           <org:reason lang="en">In use</org:reason>
 *         </org:cd>
 *         <org:cd>
 *           <org:id avail="1">1523res</org:id>
 *         </org:cd>
 *       </org:chkData>
 *     </resData>
 *   </response>
 * </epp>
 */

class orgEppCheckResponse extends eppResponse {
	public function getCheckedOrgs() {
		$result = null;
		if ($this->getResultCode() == self::RESULT_SUCCESS) {
			$result = array();
			$xpath = $this->xPath();
			$orgs = $xpath->query('/epp:epp/epp:response/epp:resData/org:chkData/org:cd');
			foreach ($orgs as $org) {
				$childs = $org->childNodes;
				$checkedorg = array('id' => null, 'available' => false, 'reason' => null);
				foreach ($childs as $child) {
					if ($child instanceof \DOMElement) {
						if (strpos($child->tagName, ':id')) {
							$available = $child->getAttribute('avail');
							switch ($available) {
								case '0':
								case 'false':
									$checkedorg['available'] = false;
									break;
								case '1':
								case 'true':
									$checkedorg['available'] = true;
									break;
							}
							$checkedorg['id'] = $child->nodeValue;
						}
						if (strpos($child->tagName, ':reason')) {
							$checkedorg['reason'] = $child->nodeValue;
						}
					}
				}
				$result[] = $checkedorg;
			}
		}
		return ($result);
	}
}