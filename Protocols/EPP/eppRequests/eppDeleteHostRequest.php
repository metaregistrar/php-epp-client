<?php
namespace Metaregistrar\EPP;

class eppDeleteHostRequest extends eppRequest {

    function __construct(eppHost $deleteinfo) {
        parent::__construct();

        if ($deleteinfo instanceof eppHost) {
            $this->setHost($deleteinfo);
        } else {
            throw new eppException('parameter of eppDeleteHostRequest must be eppHost object');
        }
        $this->addSessionId();
    }

    function __destruct() {
        parent::__destruct();
    }

    public function setHost(eppHost $host) {
        if (!strlen($host->getHostname())) {
            throw new eppException('eppDeleteRequest host object does not contain a valid hostname');
        }
        #
        # Object delete structure
        #
        $this->hostobject = $this->createElement('delete');

        $hostdelete = $this->createElement('host:delete');
        $hostdelete->appendChild($this->createElement('host:name', $host->getHostname()));
        $this->hostobject->appendChild($hostdelete);
        $this->getCommand()->appendChild($this->hostobject);
    }

}