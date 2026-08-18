<?php
namespace Metaregistrar\EPP;

class verisignEppTransferDomainRequest extends eppTransferRequest {
    use verisignEppExtension;

    /**
     * verisignEppTransferDomainRequest constructor.
     *
     * @param string $op
     * @param eppDomain $domain
     * @param string|null $rnvc
     * @param string|null $dnvc
     * @param string|null $lang
     * @throws eppException
     */
    public function __construct(string $op, eppDomain $domain, ?string $rnvc=null, ?string $dnvc=null, ?string $lang=null) {
        parent::__construct($op, $domain);
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
