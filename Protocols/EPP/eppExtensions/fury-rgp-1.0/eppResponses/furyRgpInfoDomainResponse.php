<?php

namespace Metaregistrar\EPP;

/**
 * Class furyRgpInfoDomainResponse
 */
class furyRgpInfoDomainResponse extends eppInfoDomainResponse {
	public function getRgpStatusEnd() {
		return $this->queryPath('/epp:epp/epp:response/epp:extension/fury-rgp:rgpInfo/fury-rgp:rgpStatusEnd');
	}
}
