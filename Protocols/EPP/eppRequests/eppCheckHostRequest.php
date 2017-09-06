<?php
namespace Metaregistrar\EPP;

class eppCheckHostRequest extends eppHostRequest {
    function __construct($checkrequest, $namespacesinroot = true) {
        $this->setNamespacesinroot($namespacesinroot);
        parent::__construct(eppRequest::TYPE_CHECK);

        if ($checkrequest instanceof eppHost) {
            $this->setHosts(array($checkrequest));
        } else {
            if (is_array($checkrequest)) {
                //if ($checkrequest[0] instanceof eppHost) { WHY DID I PUT THIS IN?
                    $this->setHosts($checkrequest);
                //}
            }
        }
        $this->addSessionId();
    }

    function __destruct() {
        parent::__destruct();
    }

    public function setHosts($hosts) {
        #
        # Domain check structure
        #
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
    }


}