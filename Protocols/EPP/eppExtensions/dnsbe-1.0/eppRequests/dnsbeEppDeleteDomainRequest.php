<?php
namespace Metaregistrar\EPP;
/*
<extension>
    <dnsbe:ext xmlns:dnsbe="http://www.dns.be/xml/epp/dnsbe-1.0">
        <dnsbe:delete>
            <dnsbe:domain>
                <dnsbe:deleteDate>2010-10-10T10:10:10.0Z</dnsbe:deleteDate>
                <dnsbe:overwriteDeleteDate>true</dnsbe:overwriteDeleteDate>
            </dnsbe:domain>
        </dnsbe:delete>
    </dnsbe:ext>
</extension>
*/

class dnsbeEppDeleteDomainRequest extends eppDeleteDomainRequest {

    private $overwriteDeleteDate = true;

    function __construct($deleteinfo, $deletedate=null, $overwritedeletedate=true) {
        parent::__construct($deleteinfo);
        $this->setOverwriteDeleteDate($overwritedeletedate);
        $this->addDnsbeExtension($deletedate);
        $this->addSessionId();
    }

    public function getOverwriteDeleteDate() {
        return $this->overwriteDeleteDate;
    }

    public function setOverwriteDeleteDate($overwriteDeleteDate) {
        $this->overwriteDeleteDate = $overwriteDeleteDate;
    }

    public function addDnsbeExtension($deletedate) {
        $this->addExtension('xmlns:dnsbe', 'http://www.dns.be/xml/epp/dnsbe-1.0');
        $ext = $this->createElement('extension');
        $dnsbeext = $this->createElement('dnsbe:ext');
        $delete = $this->createElement('dnsbe:delete');
        $deletedomain = $this->createElement('dnsbe:domain');
        if($deletedate)
            $deletedomain->appendChild($this->createElement('dnsbe:deleteDate', $deletedate));
        if($this->overwriteDeleteDate)
            $deletedomain->appendChild($this->createElement('dnsbe:overwriteDeleteDate', 'true'));
        $delete->appendChild($deletedomain);
        $dnsbeext->appendChild($delete);
        $ext->appendChild($dnsbeext);
        $this->getCommand()->appendChild($ext);
    }

}