<?php
namespace Metaregistrar\EPP;

class verisignEppUpdateDomainRequest extends eppUpdateDomainRequest {
    use verisignEppExtension;
    /**
     * verisignEppUpdateDomainRequest constructor.
     *
     * @param eppDomain $domain
     * @param null      $add
     * @param null      $remove
     * @param null      $update
     * @throws eppException
     */
    public function __construct(eppDomain $domain, $add=null, $remove=null, $update=null) {
        parent::__construct($domain, $add, $remove, $update);
        //add namestore extension
        $this->addNamestore($domain);
        $this->addSessionId();

    }
}
