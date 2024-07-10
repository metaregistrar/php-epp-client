<?php
namespace Metaregistrar\EPP;

class verisignEppRealNameDomainRequest extends eppDomainRequest {
    use verisignEppExtension;
    /**
     * verisignEppRealNameDomainRequest constructor.
     *
     * @param eppDomain   $domain
     * @param string      $rnvc
     * @param string|null $dnvc
     */
    public function __construct(eppDomain $domain, string $rnvc, ?string $dnvc=null) {
        $this->setNamespacesinroot(true);
        $this->setForcehostattr(false);
        parent::__construct(eppRequest::TYPE_UPDATE);
        $this->domainobject->appendChild($this->createElement('domain:name', $domain->getDomainname()));
        $this->domainobject->appendChild($this->createElement('domain:chg'));
        //add namestore extension
        $this->addNamestore($domain);
        //add verificationCode extension
        if (!empty($rnvc)){
            $this->addVerificationCode($rnvc, $dnvc);
        }
        $this->addSessionId();

    }
}
