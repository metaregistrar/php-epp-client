<?php
namespace Metaregistrar\EPP;

class lvEppUpdateDomainRenewStatusRequest extends eppUpdateDomainRequest {

    private $reason = null;

    function __construct($objectname, $renew = true, $reason = null) {
        parent::__construct($objectname, null, null, $objectname);
        
        $this->reason = $reason; 

        if($renew == true){
            $this->setToAutoRenew();
        } 
        else if ($renew == false){
            $this->prohibitAutoRenew();
        }
        $this->addSessionId();
    }

    private function setRenewStatus($create) {
        $this->addExtension('xmlns:lvdomain', 'http://www.nic.lv/epp/schema/lvdomain-ext-1.0');
        $element = $this->createElement('lvdomain:update');

        $create2 = $this->createElement('lvdomain:status', $this->reason);
        $create2->setAttribute('s', 'clientAutoRenewProhibited');
        $create2->setAttribute('lang', 'en');

        $create->appendChild($create2);

        $element->appendChild($create);
        
        $this->getExtension()->appendChild($element);
    }   

    private function prohibitAutoRenew() {

        $create  = $this->createElement('lvdomain:add');

        $this->setRenewStatus($create);
    }

    private function setToAutoRenew() {

        $create  = $this->createElement('lvdomain:rem');

        $this->setRenewStatus($create);
    }   
}