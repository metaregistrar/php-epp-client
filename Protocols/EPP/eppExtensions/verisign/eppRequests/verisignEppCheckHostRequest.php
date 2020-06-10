<?php
namespace Metaregistrar\EPP;

class verisignEppCheckHostRequest extends eppCheckHostRequest {
    use verisignEppExtension;
    /**
     * verisignEppCheckHostRequest constructor.
     *
     * @param eppHost $host
     */
    public function __construct(eppHost $host) {
        parent::__construct($host);
        //add namestore extension
        $this->addNamestore();
        $this->addSessionId();

    }
}
