<?php
namespace Metaregistrar\EPP;

class verisignEppDeleteHostRequest extends eppDeleteHostRequest {
    use verisignEppExtension;
    /**
     * verisignEppDeleteHostRequest constructor.
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
