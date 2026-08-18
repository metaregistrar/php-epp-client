<?php
namespace Metaregistrar\EPP;

class furyUpdateContactRequest extends eppUpdateContactRequest {
	function addFuryProperties(array $properties): void {
		if (count($properties) >0) {
			if (!$this->extension) {
				$this->extension = $this->createElement('extension');
				$this->getCommand()->appendChild($this->extension);
			}
			$furyupdate = $this->createElement('fury:update');
			$furyupdate->setAttribute('xmlns:fury', 'urn:ietf:params:xml:ns:fury-2.1');
			$furyadd = $this->createElement('fury:add');
			$furyupdate->appendChild($furyadd);
			$furyproperties = $this->createElement('fury:properties');
			foreach ($properties as $key=>$value) {
				$property = $this->createElement('fury:property');
				$furykey = $this->createElement('fury:key',$key);
				$furyvalue = $this->createElement('fury:value',$value);
				$property->appendChild($furykey);
				$property->appendChild($furyvalue);
				$furyproperties->appendChild($property);
			}
			$furyadd->appendChild($furyproperties);
		}
	}
	function removeFuryProperties(array $properties): void {
		if (count($properties) >0) {
			if (!$this->extension) {
				$this->extension = $this->createElement('extension');
				$this->getCommand()->appendChild($this->extension);
			}
			$furyupdate = $this->createElement('fury:update');
			$furyupdate->setAttribute('xmlns:fury', 'urn:ietf:params:xml:ns:fury-2.1');
			$furyrem = $this->createElement('fury:rem');
			$furyupdate->appendChild($furyrem);
			$furyproperties = $this->createElement('fury:properties');
			foreach ($properties as $key=>$value) {
				$property = $this->createElement('fury:property');
				$furykey = $this->createElement('fury:key',$key);
				$furyvalue = $this->createElement('fury:value',$value);
				$property->appendChild($furykey);
				$property->appendChild($furyvalue);
				$furyproperties->appendChild($property);
			}
			$furyrem->appendChild($furyproperties);
		}
	}
}