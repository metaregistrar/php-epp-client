<?php
namespace Metaregistrar\EPP;

class verisignEppInfoDomainRequest extends eppInfoDomainRequest {
    use verisignEppExtension;
    /**
     * verisignEppInfoDomainRequest constructor.
     *
     * @param eppDomain $domain
     * @param string    $hosts
     */
    public function __construct(eppDomain $domain, string $hosts=self::HOSTS_ALL) {
        parent::__construct($domain, $hosts);
        //add namestore extension
        $this->addNamestore($domain);
        $this->addVerificationCodeInfo();
        $this->addSessionId();

    }
}
