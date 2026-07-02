<?php
namespace Metaregistrar\EPP;

use DOMElement;

class furyPropertiesResponse extends eppResponse {
	function getFuryProperties():array {
		$result = [];
		$xpath = $this->xPath();
		$properties = $xpath->query('/epp:epp/epp:response/epp:extension/fury:response/fury:infData/fury:properties/fury:property/*');
		$pvs = [];
		$pas = [];
		foreach ($properties as $property) {
			/* @var $property \DOMElement */
			if ($property->nodeName == 'fury:key') {
				$key = $property->nodeValue;
			}
			if ($property->nodeName == 'fury:propertyValues') {
				$propertyvalues = $property->getElementsByTagName('propertyValue');
				$as = [];
				foreach ($propertyvalues as $propertyvalue) {
					$name = $propertyvalue->getElementsByTagName('value')->item(0)->nodeValue;
					$attributes = $propertyvalue->getElementsByTagName('propertyValueAttribute');
					foreach ($attributes as $attribute) {
						$attributekey = $attribute->getElementsByTagName('key')->item(0)->nodeValue;
						$attributevalue = $attribute->getElementsByTagName('value')->item(0)->nodeValue;
						$as [$attributekey] = $attributevalue;
					}
					$pvs[$name] = $as;
				}

			}
			if ($property->nodeName == 'fury:propertyAttributes') {
				$propertyattributes = $property->getElementsByTagName('propertyAttribute');
				foreach ($propertyattributes as $propertyattribute) {
					$attributekey = $propertyattribute->getElementsByTagName('key')->item(0)->nodeValue;
					$attributevalue = $propertyattribute->getElementsByTagName('value')->item(0)->nodeValue;
					$pas [$attributekey] = $attributevalue;
				}
			}
			$result[$key] = ['propertyValues'=>$pvs, 'propertyAttributes'=>$pas];
		}
		return $result;
	}
}