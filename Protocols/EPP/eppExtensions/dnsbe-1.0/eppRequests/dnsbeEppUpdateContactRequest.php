<?php
namespace Metaregistrar\EPP;
/*
<extension>
	<dnsbe:ext>
		<dnsbe:update>
			<dnsbe:contact>
				<dnsbe:chg>
					<dnsbe:vat>GB12345678</dnsbe:vat>
					<dnsbe:lang>en</dnsbe:lang>
				</dnsbe:chg>
			</dnsbe:contact>
		</dnsbe:update>
	</dnsbe:ext>
</extension>
*/


class dnsbeEppUpdateContactRequest extends eppUpdateContactRequest {

    /**
     * dnsbeEppUpdateContactRequest constructor.
     * @param $objectname
     * @param null|eppContact $addinfo
     * @param null|eppContact $removeinfo
     * @param null|eppContact $updateinfo
     * @param string $language
     * @throws eppException
     */
    function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null, $language = 'en') {
        if ($updateinfo instanceof eppContact) {
            parent::__construct($objectname, $addinfo, $removeinfo, $updateinfo);
            $this->addDnsbeExtension($language);
        } else {
            throw new eppException('DNSBE needs $updateinfo to be an eppContact for this update request');
        }
        $this->addSessionId();
    }

    /**
     * @param string $language
     */
    public function addDnsbeExtension($language) {
        $this->addExtension('xmlns:dnsbe', 'http://www.dns.be/xml/epp/dnsbe-1.0');
        $ext = $this->createElement('extension');
        $dnsbeext = $this->createElement('dnsbe:ext');
        $update = $this->createElement('dnsbe:update');
        $contact = $this->createElement('dnsbe:contact');
		$change = $this->createElement('dnsbe:chg');
        $change->appendChild($this->createElement('dnsbe:lang', $language));
        $contact->appendChild($change);
        $update->appendChild($contact);
        $dnsbeext->appendChild($update);
        $ext->appendChild($dnsbeext);
        $this->getCommand()->appendChild($ext);
    }

}