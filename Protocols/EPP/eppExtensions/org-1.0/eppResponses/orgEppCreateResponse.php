<?php
namespace Metaregistrar\EPP;

/**
 * <epp xmlns="urn:ietf:paramxml:nepp-1.0">
 *   <response>
 *     <result code="1000">
 *       <msg lang="en">Command completed successfully</msg>
 *     </result>
 *     <resData>
 *       <org:creData
 *         xmlnorg="urn:ietf:paramxml:nepp:org-1.0">
 *         <org:id>res1523</org:id>
 *         <org:crDate>2018-04-03T22:00:00.0Z</org:crDate>
 *       </org:creData>
 *     </resData>
 *     <trID>
 *       <clTRID>ABC-12345</clTRID>
 *       <svTRID>54321-XYZ</svTRID>
 *     </trID>
 *   </response>
 * </epp>
 */

class orgEppCreateResponse extends eppResponse {
	public function getOrgId() {
		return $this->queryPath('/epp:epp/epp:response/epp:resData/org:creData/org:id');
	}

	public function getCreateDate() {
		return $this->queryPath('/epp:epp/epp:response/epp:resData/org:creData/org:crDate');
	}
}