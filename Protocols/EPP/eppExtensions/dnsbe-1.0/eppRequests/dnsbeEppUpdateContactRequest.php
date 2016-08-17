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

    private $language = 'en';

    function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null) {
        if ($updateinfo instanceof eppContact) {
            parent::__construct($objectname, $addinfo, $removeinfo, $updateinfo);
            $this->addDnsbeExtension();
        } else {
            throw new eppException('DNSBE needs $updateinfo to be an eppContact for this update request');
        }
        $this->addSessionId();
    }

    public function addDnsbeExtension() {

        $this->addExtension('xmlns:dnsbe', 'http://www.dns.be/xml/epp/dnsbe-1.0');
        $ext = $this->createElement('extension');
        $dnsbeext = $this->createElement('dnsbe:ext');
        $update = $this->createElement('dnsbe:update');
        $contact = $this->createElement('dnsbe:contact');
		$change = $this->createElement('dnsbe:chg');
       // $change->appendChild($this->createElement('dnsbe:type', 'licensee'));
        $change->appendChild($this->createElement('dnsbe:lang', $this->language));
        $contact->appendChild($change);
        $update->appendChild($contact);
        $dnsbeext->appendChild($update);
        $ext->appendChild($dnsbeext);
        $this->command->appendChild($ext);
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }


}