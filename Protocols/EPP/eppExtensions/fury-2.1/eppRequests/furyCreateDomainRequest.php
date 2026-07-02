<?php
namespace Metaregistrar\EPP;


class furyCreateDomainRequest extends \Metaregistrar\EPP\eppCreateDomainRequest {

	/**
	 * Create domain with extra properties for FURY
	 * @param array $properties
	 * @return void
	 * @throws \DOMException
	 */
	public function addFuryProperties(array $properties) {
		if (count($properties) >0) {
			if (!$this->extension) {
				$this->extension = $this->createElement('extension');
				$this->getCommand()->appendChild($this->extension);
			}
			$furycreate = $this->createElement('fury:create');
			$furycreate->setAttribute('xmlns:fury', 'urn:ietf:params:xml:ns:fury-2.1');
			$furyproperties = $this->createElement('fury:properties');
			$furycreate->appendChild($furyproperties);
			foreach ($properties as $key=>$value) {
				$property = $this->createElement('fury:property');
				$furykey = $this->createElement('fury:key',$key);
				$furyvalue = $this->createElement('fury:value',$value);
				$property->appendChild($furykey);
				$property->appendChild($furyvalue);
				$furyproperties->appendChild($property);
			}
			$this->extension->appendChild($furycreate);
			$this->addSessionId();
		}
	}
}