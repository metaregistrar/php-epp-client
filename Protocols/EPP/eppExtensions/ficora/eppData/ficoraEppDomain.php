<?php
namespace Metaregistrar\EPP;

class ficoraEppDomain extends eppDomain {

    const REGISTRANT_TRANSFER_CODE_NEW = 'new';

    /**
     * Domain registrant transfer code
     * @var string
     */
    private $registrantTransferCode = '';

    /**
     *
     * @param eppContact $registrant
     * @param string $authorisationCode
     */

    public function __construct($domainname, $registrant = null, $contacts = null, $hosts = null, $period = 0, $authorisationCode = null) {
        parent::__construct($domainname, $registrant, $contacts, $hosts, $period, $authorisationCode);
    }

    /**
     * Sets the registrant transfer code. Set to "new" (or any other value) and do not do any other changes to request a new transfer code to be sent to domain registrant via email.
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