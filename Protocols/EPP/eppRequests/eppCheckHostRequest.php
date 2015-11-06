<?php
namespace Metaregistrar\EPP;

class eppCheckHostRequest extends eppRequest {
    function __construct($checkrequest) {
        parent::__construct();

        if ($checkrequest instanceof eppHost) {
            $this->setHosts(array($checkrequest));
        } else {
            if (is_array($checkrequest)) {
                if ($checkrequest[0] instanceof eppHost) {
                    $this->setHosts($checkrequest);
                }
            }
        }
    }

    function __destruct() {
        parent::__destruct();
    }

    public function setHosts($hosts) {
        #
        # Domain check structure
        #
        $check = $this->createElement('check');
        $this->hostobject = $this->createElement('host:check');
        foreach ($hosts as $host) {
            if ($host instanceof eppHost) {
                if (strlen($host->getHostname()) > 0) {
                    $this->hostobject->appendChild($this->createElement('host:name', $host->getHostname()));
                } else {
                    throw new eppException("Empty hostobject on checkRequest creation");
                }
            } else {
                if (strlen($host) > 0) {
                    $this->hostobject->appendChild($this->createElement('host:name', $host));
                } else {
                    throw new eppException("Empty hostname on checkRequest creation");
                }
            }
        }
        $check->appendChild($this->hostobject);
        $this->getCommand()->appendChild($check);
    }


}