<?php
namespace Metaregistrar\EPP;

class eppCreateHostRequest extends eppHostRequest {
    function __construct($createinfo, $namespacesinroot = true) {
        $this->setNamespacesinroot($namespacesinroot);
        parent::__construct(eppRequest::TYPE_CREATE);

        if ($createinfo instanceof eppHost) {
            $this->setHost($createinfo);
        } else {
            throw new eppException('createinfo must be of type eppContact on eppCreateHostRequest');
        }
        $this->addSessionId();
    }

    function __destruct() {
        parent::__destruct();
    }

    /**
     *
     * @param eppHost $host
     * @return \DOMElement
     * @throws eppException
     */
    public function setHost(eppHost $host) {
        if (!strlen($host->getHostname())) {
            throw new eppException('No valid hostname in create host request');
        }
        #
        # Object create structure
        #
        $this->hostobject->appendChild($this->createElement('host:name', $host->getHostname()));
        $addresses = $host->getIpAddresses();
        if (is_array($addresses)) {
            foreach ($addresses as $address => $type) {
                $ipaddress = $this->createElement('host:addr', $address);
                $ipaddress->setAttribute('ip', $type);
                $this->hostobject->appendChild($ipaddress);
            }
        }
        return;
    }

}

