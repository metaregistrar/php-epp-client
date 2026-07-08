<?php

namespace Metaregistrar\EPP;

/**
 *
 * <epp xmlns="urn:ietf:paramxml:epp-1.0">
 *   <response>
 *     <resData>
 *       <org:infData
 *         xmlnorg="urn:ietf:paramxml:nepp:org-1.0">
 *         <org:id>registrar1362</org:id>
 *         <org:roid>registrar1362-REP</org:roid>
 *         <org:role>
 *           <org:type>registrar</org:type>
 *           <org:status>ok</org:status>
 *           <org:status>linked</org:status>
 *           <org:roleID>1362</org:roleID>
 *         </org:role>
 *         <org:status>ok</org:status>
 *         <org:postalInfo type="int">
 *           <org:name>Example Registrar Inc.</org:name>
 *           <org:addr>
 *             <org:street>123 Example Dr.</org:street>
 *             <org:street>Suite 100</org:street>
 *             <org:city>Dulles</org:city>
 *             <org:sp>VA</org:sp>
 *             <org:pc>20166-6503</org:pc>
 *             <org:cc>US</org:cc>
 *           </org:addr>
 *         </org:postalInfo>
 *         <org:voice x="1234">+1.7035555555</org:voice>
 *         <org:fax>+1.7035555556</org:fax>
 *         <org:email>contact@organization.example</org:email>
 *         <org:url>http//organization.example</org:url>
 *         <org:contact type="admin">sh8013</org:contact>
 *         <org:contact type="billing">sh8013</org:contact>
 *         <org:contact type="custom"
 *            typeName="legal">sh8013</org:contact>
 *         <org:crID>ClientX</org:crID>
 *         <org:crDate>2018-04-03T22:00:00.0Z</org:crDate>
 *         <org:upID>ClientX</org:upID>
 *         <org:upDate>2018-12-03T09:00:00.0Z</org:upDate>
 *       </org:infData>
 *     </resData>
 *   </response>
 * </epp>
 */
class orgEppInfoResponse extends eppResponse {
	function getOrgId(): string {
		return $this->queryPath('/epp:epp/epp:response/epp:resData/org:infData/org:id');
	}

	function getOrgRoid(): string {
		return $this->queryPath('/epp:epp/epp:response/epp:resData/org:infData/org:roid');
	}

	function getOrgRole(): string {
		return $this->queryPath('/epp:epp/epp:response/epp:resData/org:infData/org:role/org:type');
	}

	function getOrgStatus(): string {
		return $this->queryPath('/epp:epp/epp:response/epp:resData/org:infData/org:status');
	}

	function getOrgName(): string {
		return $this->queryPath('/epp:epp/epp:response/epp:resData/org:infData/org:postalInfo/org:name');
	}

	function getOrgStreet(): string {
		return $this->queryPath('/epp:epp/epp:response/epp:resData/org:infData/org:postalInfo/org:addr/org:street');
	}

	function getOrgCity(): string {
		return $this->queryPath('/epp:epp/epp:response/epp:resData/org:infData/org:postalInfo/org:addr/org:city');
	}

	function getOrgPostcode(): string {
		return $this->queryPath('/epp:epp/epp:response/epp:resData/org:infData/org:postalInfo/org:addr/org:pc');
	}

	function getOrgState(): ?string {
		return $this->queryPath('/epp:epp/epp:response/epp:resData/org:infData/org:postalInfo/org:addr/org:sp');
	}

	function getOrgCountry(): string {
		return $this->queryPath('/epp:epp/epp:response/epp:resData/org:infData/org:postalInfo/org:addr/org:cc');
	}

	function getOrgPhone(): string {
		return $this->queryPath('/epp:epp/epp:response/epp:resData/org:infData/org:voice');
	}

	function getOrgFax(): ?string {
		return $this->queryPath('/epp:epp/epp:response/epp:resData/org:infData/org:fax');
	}

	function getOrgEmail(): string {
		return $this->queryPath('/epp:epp/epp:response/epp:resData/org:infData/org:email');
	}

	function getOrgUrl(): ?string {
		return $this->queryPath('/epp:epp/epp:response/epp:resData/org:infData/org:url');
	}

	function getOrgCreateDate(): string {
		return $this->queryPath('/epp:epp/epp:response/epp:resData/org:infData/org:crDate');
	}
	function getOrgUpdateDate(): string {
		return $this->queryPath('/epp:epp/epp:response/epp:resData/org:infData/org:upDate');
	}

	function getOrgContacts(): array {
		$result = [];
		$xpath = $this->xPath();
		$data = $xpath->query('/epp:epp/epp:response/epp:resData/org:infData/org:contact');
		if ($data->length >0) {
			var_dump($data);
		}
		return $result;
	}

	public function getOrgPostalInfo() {
		$xpath = $this->xPath();
		$result = $xpath->query('/epp:epp/epp:response/epp:resData/org:infData/org:postalInfo');
		$postalinfo = [];
		foreach ($result as $postalresult) {
			/* @var $postalresult \DOMElement */
			$testtype = $postalresult->getAttributeNode('type');
			$type = eppContact::TYPE_LOC;
			if ($testtype) {
				$type = $testtype->value;
			}
			$testname = $postalresult->getElementsByTagName('name');
			$name = null;
			if ($testname->length > 0) {
				$name = $testname->item(0)->nodeValue;
			}
			$testorg = $postalresult->getElementsByTagName('org');
			$org = null;
			if ($testorg->length > 0) {
				$org = $testorg->item(0)->nodeValue;
			}
			$city = null;
			$country = null;
			$zipcode = null;
			$province = null;
			$streets = null;
			$testaddr = $postalresult->getElementsByTagName('addr');
			if ($testaddr->length > 0) {
				$addr = $testaddr->item(0);
				/* @var $addr \DOMElement */
				$testcity = $addr->getElementsByTagName('city');
				/* @var $postalresult \DOMElement */

				if ($testcity->length > 0) {
					$city = $testcity->item(0)->nodeValue;
				}
				$testcc = $addr->getElementsByTagName('cc');

				if ($testcc->length > 0) {
					$country = $testcc->item(0)->nodeValue;
				}
				$testpc = $addr->getElementsByTagName('pc');

				if ($testpc->length > 0) {
					$zipcode = $testpc->item(0)->nodeValue;
				}
				$testsp = $addr->getElementsByTagName('sp');

				if ($testsp->length > 0) {
					$province = $testsp->item(0)->nodeValue;
				}
				$teststreet = $addr->getElementsByTagName('street');
				if ($teststreet->length > 0) {
					foreach ($teststreet as $street) {
						$streets[] = $street->nodeValue;
					}
				}
			}
			$postalinfo[] = new eppContactPostalInfo($name, $city, $country, $org, $streets, $province, $zipcode, $type);
		}
		return $postalinfo;
	}
}