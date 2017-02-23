<?php
namespace Metaregistrar\EPP;

class metaregDnsRequest extends eppRequest
{
    public $dnsObject = null;

    const NS = 'http://www.metaregistrar.com/epp/dns-ext-1.0';

    /**
     * metaregDnsRequest constructor.
     *
     * @param string $type
     */
    public function __construct($type)
    {
        parent::__construct();
        $check = $this->createElement($type);
        $this->dnsObject = $this->createElement('dns-ext:' . $type);
        $this->dnsObject->setAttribute('xmlns:dns-ext', 'http://www.metaregistrar.com/epp/dns-ext-1.0');
        $check->appendChild($this->dnsObject);
        $this->getCommand()->appendChild($check);
    }
}
