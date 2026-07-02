<?php
namespace Metaregistrar\EPP;

class furyPropertiesResponse extends eppResponse {
	function getFuryProperties():array {
		$result = [];
		$xpath = $this->xPath();
		$properties = $xpath->query('/epp:epp/epp:response/epp:extension/fury:response/fury:infData/fury:properties');
		var_dump($properties);
		return $result;
	}
}