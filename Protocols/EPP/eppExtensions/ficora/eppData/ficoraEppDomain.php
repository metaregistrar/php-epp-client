<?php
namespace Metaregistrar\EPP;

class ficoraEppDomain extends eppDomain {

    /**
     * Domain registrant transfer code
     * @var string
     */
    private $registrantTransferCode = '';

    /**
     * Sets the registrant transfer code. Set to any value (and do not do any other changes to domain) to request a new transfer code to be sent to domain registrant via email.
     * @param string $registrantTransferCode
     */
    public function setRegistrantTransferCode($registrantTransferCode)
    {
        $this->registrantTransferCode = $registrantTransferCode;
    }

    /**
     * @return string
     */
    public function getRegistrantTransferCode()
    {
        return $this->registrantTransferCode;
    }
}