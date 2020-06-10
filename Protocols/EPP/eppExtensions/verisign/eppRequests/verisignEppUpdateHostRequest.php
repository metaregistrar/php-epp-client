<?php
namespace Metaregistrar\EPP;

class verisignEppUpdateHostRequest extends eppUpdateHostRequest {
    use verisignEppExtension;
    /**
     * verisignEppUpdateHostRequest constructor.
     *
     * @param eppHost $domain
     * @param null    $add
     * @param null    $remove
     * @param null    $update
     * @throws eppException
     */
    public function __construct(eppHost $host, $add=null, $remove=null, $update=null) {
        parent::__construct($host, $add, $remove, $update);
        //add namestore extension
        $this->addNamestore();
        $this->addSessionId();

    }
}
