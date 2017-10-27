<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=dtra for example request/response

class noridEppTransferRequest extends eppTransferRequest {

    const OPERATION_EXECUTE = 'execute';

    use noridEppDomainRequestTrait;
    
    protected $domainobject = null;

    function __construct($operation, $object) {
        parent::__construct($operation, $object);
        $remove = $this->getElementsByTagName('command');
        foreach ($remove as $node) {
            $node->parentNode->removeChild($node);
        }
        if ($operation == self::OPERATION_EXECUTE) {
            if ($object instanceof noridEppDomain) {
                $this->setExtDomainExecute($object);
            } else {
                throw new eppException('Object parameter should be an instance of noridEppDomain when operation is EXECUTE');
            }
        }
        $this->addExtSessionId();
    }

    public function setExtDomainExecute(noridEppDomain $domain) {
        $transfer = $this->createElement('transfer');
        $transfer->setAttribute('op', self::OPERATION_EXECUTE);
        $this->domainobject = $this->createElement('domain:transfer');
        $this->domainobject->setAttribute('xmlns:domain', 'urn:ietf:params:xml:ns:domain-1.0');
        $this->domainobject->appendChild($this->createElement('domain:name', $domain->getDomainname()));
        if (strlen($domain->getAuthorisationCode())) {
            $authinfo = $this->createElement('domain:authInfo');
            $authinfo->appendChild($this->createElement('domain:pw', $domain->getAuthorisationCode()));
            $this->domainobject->appendChild($authinfo);
        }
        if ($domain->getPeriod()) {
            $domainperiod = $this->createElement('domain:period', $domain->getPeriod());
            $domainperiod->setAttribute('unit', eppDomain::DOMAIN_PERIOD_UNIT_Y);
            $this->domainobject->appendChild($domainperiod);
        }
        if (strlen($domain->getExtToken())) {
            $this->getExtCommandDomainExtension('transfer')->appendChild($this->createElement('no-ext-domain:token', $domain->getExtToken()));
        }
        if (strlen($domain->getExtNotifyMobilePhone()) || strlen($domain->getExtNotifyEmail())) {
            $notify = $this->createElement('no-ext-domain:notify');
            if (strlen($domain->getExtNotifyMobilePhone())) {
                $notify->appendChild($this->createElement('no-ext-domain:mobilePhone', $domain->getExtNotifyMobilePhone()));
            }
            if (strlen($domain->getExtNotifyEmail())) {
                $notify->appendChild($this->createElement('no-ext-domain:email', $domain->getExtNotifyEmail()));
            }
            $this->getExtCommandDomainExtension('transfer')->appendChild($notify);
        }
        $transfer->appendChild($this->domainobject);
        $this->getExtCommand()->appendChild($transfer);
    }

}