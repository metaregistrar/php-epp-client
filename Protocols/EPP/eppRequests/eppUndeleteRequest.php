<?php
namespace Metaregistrar\EPP;

class eppUndeleteRequest extends eppRequest {

    function __construct($restoreinfo) {
        if (defined("NAMESPACESINROOT")) {
            $this->setNamespacesinroot(NAMESPACESINROOT);
        }
        parent::__construct();
        if ($restoreinfo instanceof eppDomain) {
            $this->setDomain($restoreinfo);
        } else {
            throw new eppException('parameter of eppUndeleteRequest must be valid eppDomain object');
        }
        //$this->addSessionId();

    }

    function __destruct() {
        parent::__destruct();
    }


    public function setDomain(eppDomain $domain) {
        if (!strlen($domain->getDomainname())) {
            throw new eppException('eppUndeleteRequest domain object does not contain a valid domain name');
        }
        #
        # Object delete structure
        #
        $commandext = $this->createElement('ext:command');
        $undelete = $this->createElement('ext:undelete');
        $undelete->appendChild($this->createElement('domain:name', $domain->getDomainname()));
        $commandext->appendChild($undelete);
        $this->getExtension()->appendChild($commandext);
    }

}