<?php
namespace Metaregistrar\EPP;
/**
 *
 * <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
 *   <command>
 *     <create>
 *       <org:create xmlns:org="urn:ietf:params:xml:ns:epp:org-1.0">
 *         <org:id>res1523</org:id>
 *         <org:role>
 *           <org:type>reseller</org:type>
 *         </org:role>
 *         <org:parentId>1523res</org:parentId>
 *         <org:postalInfo type="int">
 *           <org:name>Example Organization Inc.</org:name>
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
 *         <org:url>https://organization.example</org:url>
 *         <org:contact type="admin">sh8013</org:contact>
 *         <org:contact type="billing">sh8013</org:contact>
 *       </org:create>
 *     </create>
 *   </command>
 * </epp>
 *
 */

class orgEppCreateRequest extends eppCreateRequest {
	private \DOMElement|false $createobject;

	/**
	 * @param eppContact $createinfo
	 * @param bool $namespacesinroot
	 * @param bool $usecdata
	 * @throws \DOMException
	 */
	function __construct(eppContact $createinfo, string $type, bool $namespacesinroot = true, bool $usecdata = true) {
		$this->setNamespacesinroot($namespacesinroot);
		parent::__construct();
		$this->setUseCdata($usecdata);
		$create = $this->createElement('create');
		$this->createobject = $this->createElement('org:create');
		$this->setContact($createinfo, $type);
		$create->appendChild($this->createobject);
		$this->getCommand()->appendChild($create);
		$this->addSessionId();
	}

	public function setContact(eppContact $contact, string $type): void {
		$this->setContactId($contact->getId());
		$this->setContactType($type);
		$this->setPostalInfo($contact->getPostalInfo(0));
		$this->setVoice($contact->getVoice());
		$this->setFax($contact->getFax());
		$this->setEmail($contact->getEmail());
	}

	/**
	 * @param $contactid
	 * @return void
	 * @throws \DOMException
	 */
	public function setContactId($contactid): void {
		$this->createobject->appendChild($this->createElement('org:id', $contactid));
	}

	public function setContactType(string $type): void {
		$role = $this->createobject->appendChild($this->createElement('org:role'));
		$role->appendChild($this->createElement('org:type',$type));
	}

	public function setContactUrl(string $url) {
		$this->createobject->appendChild($this->createElement('org:url',$url));
	}

	/**
	 * Set the postalinfo information in the contact
	 * @param eppContactPostalInfo $postal
	 * @throws eppException
	 */
	public function setPostalInfo(eppContactPostalInfo $postal) {
		$postalinfo = $this->createElement('org:postalInfo');
		if (!$postal instanceof eppContactPostalInfo) {
			throw new eppException('PostalInfo must be filled on eppCreateContact request');
		}
		if ($postal->getType()==eppContact::TYPE_AUTO) {
			// If all fields are ascii, type = int (international) else type = loc (localization)
			if ((self::isAscii($postal->getName())) && (self::isAscii($postal->getOrganisationName())) && (self::isAscii($postal->getStreet(0)))) {
				$postal->setType(eppContact::TYPE_INT);
			} else {
				$postal->setType(eppContact::TYPE_LOC);
			}
		}
		$postalinfo->setAttribute('type', $postal->getType());
		if ($postal->getOrganisationName()) {
			$postalinfo->appendChild($this->createElement('org:name', $postal->getOrganisationName()));
		} else {
			$postalinfo->appendChild($this->createElement('org:name', $postal->getOrganisationName()));
		}
		$postaladdr = $this->createElement('org:addr');
		$count = $postal->getStreetCount();
		for ($i = 0; $i < $count; $i++) {
			$postaladdr->appendChild($this->createElement('org:street', $postal->getStreet($i)));
		}
		$postaladdr->appendChild($this->createElement('org:city', $postal->getCity()));
		if ($postal->getProvince()) {
			$postaladdr->appendChild($this->createElement('org:sp', $postal->getProvince()));
		}
		$postaladdr->appendChild($this->createElement('org:pc', $postal->getZipcode()));
		$postaladdr->appendChild($this->createElement('org:cc', $postal->getCountrycode()));
		$postalinfo->appendChild($postaladdr);
		$this->createobject->appendChild($postalinfo);
	}

	/**
	 * @param $voice
	 */
	public function setVoice($voice) {
		if ($voice) {
			$this->createobject->appendChild($this->createElement('org:voice', $voice));
		}
	}

	public function setFax($fax) {
		if ($fax) {
			$this->createobject->appendChild($this->createElement('org:fax', $fax));
		}
	}

	public function setEmail($email) {
		if ($email) {
			$this->createobject->appendChild($this->createElement('org:email', $email));
		}
	}


}