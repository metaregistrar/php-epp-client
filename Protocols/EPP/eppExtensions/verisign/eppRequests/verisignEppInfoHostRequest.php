<?php
namespace Metaregistrar\EPP;

class verisignEppInfoHostRequest extends eppInfoHostRequest {
    use verisignEppExtension;
    /**
     * verisignEppInfoHostRequest constructor.
     *
     * @param eppHost $host
     * @throws eppException
     */
    public function __construct(eppHost $host) {
        parent::__construct($host);
        //add namestore extension
        $this->addNamestore();
        $this->addSessionId();

    }
}
