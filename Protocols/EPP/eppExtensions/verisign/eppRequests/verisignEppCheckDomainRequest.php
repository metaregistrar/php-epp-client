<?php
namespace Metaregistrar\EPP;

class verisignEppCheckDomainRequest extends eppCheckDomainRequest {
    use verisignEppExtension;
    /**
     * verisignEppCheckDomainRequest constructor.
     *
     * @param eppDomain $domain
     */
    public function __construct(eppDomain $domain) {
        parent::__construct($domain);
        //add namestore extension
        $this->addNamestore($domain);
        $this->addSessionId();

    }
}
