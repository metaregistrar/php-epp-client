<?php
namespace Metaregistrar\EPP;

class verisignEppCreateDomainRequest extends eppCreateDomainRequest {
    use verisignEppExtension;
    /**
     * verisignEppCreateDomainRequest constructor.
     *
     * @param eppDomain $domain
     */
    public function __construct(eppDomain $domain, ?string $rnvc=null, ?string $dnvc=null, ?string $lang=null) {
        parent::__construct($domain);
        //add namestore extension
        $this->addNamestore($domain);
        //add idnlang extension
        if (!empty($lang) && $lang!='ENG') {
            $this->addIdnLang($lang);
        }
        //add verificationCode extension
        if (!empty($rnvc) || !empty($dnvc)){
            $this->addVerificationCode($rnvc, $dnvc);
        }
        $this->addSessionId();
    }
}
