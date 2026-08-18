<?php
namespace Metaregistrar\EPP;

class furyPropertiesRequest extends eppRequest {
	function __construct(array $properties = [], ?string $language = null) {
		parent::__construct();
		if (!$this->extension) {
			$this->extension = $this->createElement('extension');
			$this->getEpp()->appendChild($this->extension);
		}
		$furycommand = $this->createElement('fury:command');
		$furycommand->setAttribute('xmlns:fury', 'urn:ietf:params:xml:ns:fury-2.1');
		$furyinfo = $this->createElement('fury:info');
		if ($language) {
			$furylanguage = $this->createElement('fury:language',$language);
			$furyinfo->appendChild($furylanguage);
		}
		$furyproperties = $this->createElement('fury:properties');
		if (count($properties) > 0) {
			foreach ($properties as $key=>$value) {
				$property = $this->createElement('fury:property');
				$furykey = $this->createElement('fury:key',$key);
				$property->appendChild($furykey);
				if ($value) {
					$furyvalue = $this->createElement('fury:value',$value);
					$property->appendChild($furyvalue);
				}
				$furyproperties->appendChild($property);
			}
		}
		$furyinfo->appendChild($furyproperties);
		$furycommand->appendChild($furyinfo);
		$this->extension->appendChild($furycommand);
	}
}