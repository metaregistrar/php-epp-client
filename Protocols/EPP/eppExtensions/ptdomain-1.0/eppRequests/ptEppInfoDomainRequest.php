<?php
namespace Metaregistrar\EPP;

class ptEppInfoDomainRequest extends eppInfoDomainRequest {
    /**
     * ptEppInfoDomainRequest constructor.
     * @param  eppDomain $infodomain
     * @param string $roid
     */
    function __construct($infodomain, $roid) {
        parent::__construct($infodomain);
        $this->addRoid($roid);
        $this->addSessionId();
        echo $this->saveXML();
    }

    private function addRoid($roid) {
        $ptdomain = $this->createElement('ptdomain:info');
        $ptdomain->appendChild($this->createElement('ptdomain:roid',$roid));
        $this->getExtension()->appendChild($ptdomain);
    }
}

