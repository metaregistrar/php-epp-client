<?php
namespace Metaregistrar\EPP;

class eppCheckDomainRequest extends eppRequest {

    function __construct($checkrequest) {
        parent::__construct();

        if ($checkrequest instanceof eppDomain) {
            $this->setDomainNames(array($checkrequest));
        } else {
            if (is_array($checkrequest)) {
                if ($checkrequest[0] instanceof eppDomain) {
                    $this->setDomainNames($checkrequest);
                }
            }
        }
    }

    function __destruct() {
        parent::__destruct();
    }


    /**
     *
     * @param array $domains
     */
    public function setDomainNames($domains) {
        #
        # Domain check structure
        #
        $check = $this->createElement('check');
        $this->domainobject = $this->createElement('domain:check');
        foreach ($domains as $domain) {
            if ($domain instanceof eppDomain) {
                $this->domainobject->appendChild($this->createElement('domain:name', $domain->getDomainName()));
            } else {
                $this->domainobject->appendChild($this->createElement('domain:name', $domain));
            }
        }
        $check->appendChild($this->domainobject);
        $this->getCommand()->appendChild($check);
    }


}