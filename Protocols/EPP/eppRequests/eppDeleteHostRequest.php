<?php
namespace Metaregistrar\EPP;

class eppDeleteHostRequest extends eppHostRequest {

    function __construct(eppHost $deleteinfo, $namespacesinroot = true) {
        $this->setNamespacesinroot($namespacesinroot);
        parent::__construct(eppRequest::TYPE_DELETE);

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
        $this->hostobject->appendChild($this->createElement('host:name', $host->getHostname()));
    }

}