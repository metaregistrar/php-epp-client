<?php
namespace Metaregistrar\EPP;

/*
 * This object contains all the logic to create an EPP host:info command
 */

class eppInfoHostRequest extends eppHostRequest {
    function __construct($inforequest, $namespacesinroot = true) {
        $this->setNamespacesinroot($namespacesinroot);
        parent::__construct(eppRequest::TYPE_INFO);

        if ($inforequest instanceof eppHost) {
            $this->setHost($inforequest);
        } else {
            throw new eppException('parameter of infohostrequest needs to be eppHost object');
        }
        $this->addSessionId();
    }

    function __destruct() {
        parent::__destruct();
    }


    public function setHost(eppHost $host) {
        #
        # Domain check structure
        #
        $this->hostobject->appendChild($this->createElement('host:name', $host->getHostname()));
    }
}
