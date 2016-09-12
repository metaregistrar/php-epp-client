<?php
namespace Metaregistrar\EPP;
/**
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <transfer op="query">
      <domain:transfer xmlns:obj="urn:ietf:params:xml:ns:domain">
        <!-- Domain-specific elements. -->
      </domain:transfer>
    </transfer>
    <clTRID>ABC-12346</clTRID>
  </command>
C:</epp>
 */

class eppTransferDomainRequest extends eppRequest {

    private $domainobject;

    const OPERATION_QUERY = 'query';
    const OPERATION_REQUEST = 'request';
    const OPERATION_APPROVE = 'approve';
    const OPERATION_REJECT = 'reject';
    const OPERATION_CANCEL = 'cancel';

    function __construct($operation, eppDomain $domain) {
        if (defined("NAMESPACESINROOT")) {
            $this->setNamespacesinroot(NAMESPACESINROOT);
        }
        parent::__construct();

        if ($domain instanceof eppDomain) {
            if (!strlen($domain->getDomainname())) {
                throw new eppException('Domain object does not contain a valid domain name on eppDomainTransferRequest');
            }
        } else {
            throw new eppException('Parameter $domain must be of type eppDomain on eppDomainTransferRequest');
        }
        #
        # Sanity checks
        #
        switch ($operation) {
            case self::OPERATION_REQUEST:
                $this->setDomainRequest($domain);
                break;
            case self::OPERATION_QUERY:
            case self::OPERATION_CANCEL:
            case self::OPERATION_APPROVE:
            case self::OPERATION_REJECT:
                $this->setDomain($domain,$operation);
                break;
            default:
                throw new eppException('Operation parameter needs to be QUERY, REQUEST, CANCEL, APPROVE or REJECT on eppDomainTransferRequest');
                break;

        }
        $this->addSessionId();
    }

    function __destruct() {
        parent::__destruct();
    }


    /**
     * Create domain:transfer structure
     * @param eppDomain $domain
     * @param string $operation
     */
    public function setDomain(eppDomain $domain, $operation) {
        #
        # Object create structure
        #
        $transfer = $this->createElement('transfer');
        $transfer->setAttribute('op', $operation);
        $this->domainobject = $this->createElement('domain:transfer');
        $this->domainobject->appendChild($this->createElement('domain:name', $domain->getDomainname()));
        if (strlen($domain->getAuthorisationCode())) {
            $authinfo = $this->createElement('domain:authInfo');
            $authinfo->appendChild($this->createElement('domain:pw', $domain->getAuthorisationCode()));
            $this->domainobject->appendChild($authinfo);
        }
        $transfer->appendChild($this->domainobject);
        $this->getCommand()->appendChild($transfer);
    }


    public function setDomainRequest(eppDomain $domain) {
        #
        # Object create structure
        #
        $transfer = $this->createElement('transfer');
        $transfer->setAttribute('op', self::OPERATION_REQUEST);
        $this->domainobject = $this->createElement('domain:transfer');
        $this->domainobject->appendChild($this->createElement('domain:name', $domain->getDomainname()));
        if ($domain->getPeriod()) {
            $domainperiod = $this->createElement('domain:period', $domain->getPeriod());
            $domainperiod->setAttribute('unit', eppDomain::DOMAIN_PERIOD_UNIT_Y);
            $this->domainobject->appendChild($domainperiod);
        }
        if (strlen($domain->getAuthorisationCode())) {
            $authinfo = $this->createElement('domain:authInfo');
            $authinfo->appendChild($this->createElement('domain:pw', $domain->getAuthorisationCode()));
            $this->domainobject->appendChild($authinfo);
        }
        $transfer->appendChild($this->domainobject);
        $this->getCommand()->appendChild($transfer);
    }

}