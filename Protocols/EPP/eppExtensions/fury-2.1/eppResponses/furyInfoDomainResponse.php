<?php

namespace Metaregistrar\EPP;

/**
 * Class furyInfoDomainResponse
 */
class furyInfoDomainResponse extends eppInfoDomainResponse {
	public function getFuryProperties(): ?array {
		$properties = $this->queryPath('/epp:epp/epp:response/epp:extension/fury:info/fury:properties');
		if ($properties) {

		}
	}
}