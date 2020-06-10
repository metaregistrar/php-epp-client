<?php
namespace Metaregistrar\EPP;

class verisignEppRenewDomainRequest extends eppRenewRequest {
    use verisignEppExtension;
    /**
     * verisignEppRenewDomainRequest constructor.
     *
     * @param eppDomain $domain
     */
    public function __construct(eppDomain $domain, string $expDate) {
        //转换传入的日期为UTC时区日期
        $expireDate = gmdate('Y-m-d', strtotime($expDate));
        parent::__construct($domain, $expireDate);
        //add namestore extension
        $this->addNamestore($domain);
        $this->addSessionId();

    }
}
