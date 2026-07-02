<?php

namespace Metaregistrar\EPP;

/**
 * Class furyInfoDomainResponse
 */
class furyInfoDomainResponse extends eppInfoDomainResponse {
	/**
	 * Retrieve key and value of specialized FURY properties
	 * @return array
	 */
	public function getFuryProperties(): array {
		/**
		 *         <fury:properties>
		 *           <fury:property>
		 *             <fury:key>PRIVACY</fury:key>
		 *             <fury:value>PRIVATE</fury:value>
		 *           </fury:property>
		 *         </fury:properties>
		 */
		$result = [];
		$xpath = $this->xPath();
		$properties = $xpath->query('/epp:epp/epp:response/epp:extension/fury:info/fury:properties');
		if (($properties) && ($properties->length > 0)) {
			foreach ($properties as $property) {
				/* @var $property /DOMelement */
				$result[$property->getElementsByTagname('key')->item(0)->nodeValue]=$property->getElementsByTagname('value')->item(0)->nodeValue;
			}
		}
		return $result;
	}

	public function getFuryBundle(): null {
		/**
		 * This function was not implemented yet because we were not able to test it in the wild
		 *
		 *       <fury:info xmlnfury="urn:ietf:paramxml:nfury-2.1">
		 *         <fury:bundle>
		 *           <fury:name>example.fury</fury:name>
		 *           <fury:domains>
		 *             <fury:name>example.fury</fury:name>
		 *             <fury:name>xn--xmpl-boa3bb.fury</fury:name>
		 *           </fury:domains>
		 *         </fury:bundle>
		 */
		return null;
	}
}