<?php
namespace Metaregistrar\EPP;
/**
 *
 *
 */

class orgEppUpdateRequest extends eppRequest {
	private \DOMElement|false $updateobject;

	function __construct(string $handle, $addinfo = null, $removeinfo = null, $updateinfo = null, $namespacesinroot = true, $usecdata = true) {
		$this->setNamespacesinroot($namespacesinroot);
		parent::__construct();
		$this->setUseCdata($usecdata);
		$update = $this->createElement('update');
		$this->updateobject = $this->createElement('org:update');
		//$this->setContact($updateinfo, $type);
		$update->appendChild($this->updateobject);
		$this->getCommand()->appendChild($update);
		$this->updateContact($handle, $addinfo, $removeinfo, $updateinfo);
		$this->addSessionId();
	}

	public function updateContact($handle, $addInfo, $removeInfo, $updateInfo) {
		$this->updateobject->appendChild($this->createElement('org:id', $handle));
		if ($updateInfo instanceof eppContact) {
			$chgcmd = $this->createElement('org:chg');
			$this->addContactChanges($chgcmd, $updateInfo);
			if ($chgcmd->hasChildNodes()) {
				$this->updateobject->appendChild($chgcmd);
			}
		}
		if ($removeInfo instanceof eppContact) {
			$remcmd = $this->createElement('org:rem');
			$this->addContactStatus($remcmd, $removeInfo);
			if ($remcmd->hasChildNodes()) {
				$this->updateobject->appendChild($remcmd);
			}
		}
		if ($addInfo instanceof eppContact) {
			$addcmd = $this->createElement('org:add');
			$this->addContactStatus($addcmd, $addInfo);
			if ($addcmd->hasChildNodes()) {
				$this->updateobject->appendChild($addcmd);
			}
		}
	}

	private function addContactStatus(\DOMElement $element, eppContact $contact) {
		if ((is_array($contact->getStatus())) && (count($contact->getStatus()) > 0)) {
			$statuses = $contact->getStatus();
			if (is_array($statuses)) {
				foreach ($statuses as $status) {
					$element->appendChild($this->createElement('org:status',$status));
				}
			}
		}
	}

	private function addContactChanges($element, eppContact $contact) {
		if ($contact->getPostalInfoLength() > 0) {
			$postal = $contact->getPostalInfo(0);
			$postalinfo = $this->createElement('org:postalInfo');
			if ($postal->getType()==eppContact::TYPE_AUTO) {
				// If all fields are ascii, type = int (international) else type = loc (localization)
				if ((self::isAscii($postal->getName())) && (self::isAscii($postal->getOrganisationName())) && (self::isAscii($postal->getStreet(0)))) {
					$postal->setType(eppContact::TYPE_INT);
				} else {
					$postal->setType(eppContact::TYPE_LOC);
				}
			}
			$postalinfo->setAttribute('type', $postal->getType());
			// Mandatory field
			if (is_string($postal->getName()) && strlen($postal->getName()) > 0) {
				$postalinfo->appendChild($this->createElement('org:name', $postal->getName()));
			}
			// Optional field
			if (!is_null($postal->getOrganisationName())) {
				$postalinfo->appendChild($this->createElement('org:org', $postal->getOrganisationName()));
			}
			if ((($postal->getStreetCount()) > 0) || strlen($postal->getCity()) || strlen($postal->getProvince()) || strlen($postal->getZipcode()) || strlen($postal->getCountrycode())) {
				$postaladdr = $this->createElement('org:addr');
				if (($count = $postal->getStreetCount()) > 0) {
					for ($i = 0; $i < $count; $i++) {
						$postaladdr->appendChild($this->createElement('org:street', $postal->getStreet($i)));
					}
				}
				if (strlen($postal->getCity())) {
					$postaladdr->appendChild($this->createElement('org:city', $postal->getCity()));
				}
				if (is_string($postal->getProvince()) && strlen($postal->getProvince())) {
					$postaladdr->appendChild($this->createElement('org:sp', $postal->getProvince()));
				}
				if (strlen($postal->getZipcode())) {
					$postaladdr->appendChild($this->createElement('org:pc', $postal->getZipcode()));
				}
				if (strlen($postal->getCountrycode())) {
					$postaladdr->appendChild($this->createElement('org:cc', $postal->getCountrycode()));
				}
				$postalinfo->appendChild($postaladdr);
			}
			$element->appendChild($postalinfo);
		}
		// Mandatory field
		if (is_string($contact->getVoice()) && strlen($contact->getVoice())) {
			$element->appendChild($this->createElement('org:voice', $contact->getVoice()));
		}
		// Optional field, may be empty
		if (!is_null($contact->getFax())) {
			$element->appendChild($this->createElement('org:fax', $contact->getFax()));
		}
		// Mandatory field
		if (is_string($contact->getEmail()) && strlen($contact->getEmail())) {
			$element->appendChild($this->createElement('org:email', $contact->getEmail()));
		}
	}
}