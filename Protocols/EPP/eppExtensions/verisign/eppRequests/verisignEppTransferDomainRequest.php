<?php
namespace Metaregistrar\EPP;

class verisignEppTransferDomainRequest extends eppTransferRequest {
    use verisignEppExtension;
    /**
     * verisignEppTransferDomainRequest constructor.
     *
     * @param string    $op
     * @param eppDomain $domain
     * @throws eppException
     */
    public function __construct(string $op, eppDomain $domain) {
        parent::__construct($op, $domain);
        //add namestore extension
        $this->addNamestore($domain);
        $this->addSessionId();

    }
}
