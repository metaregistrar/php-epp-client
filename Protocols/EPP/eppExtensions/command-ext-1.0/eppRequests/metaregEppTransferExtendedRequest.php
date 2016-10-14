<?php
namespace Metaregistrar\EPP;

/**
 * Class metaregEppTransferExtendedRequest
 */
class metaregEppTransferExtendedRequest extends eppTransferRequest
{
    /**
     * metaregEppTransferExtendedRequest constructor.
     * @param string $operation
     * @param eppDomain $object
     * @throws eppException
     */
    public function __construct($operation, eppDomain $object)
    {
        parent::__construct($operation, $object);
        $this->addExtension('xmlns:command-ext-domain', 'http://www.metaregistrar.com/epp/command-ext-domain-1.0');
        $domainChild = $this->createElement('command-ext-domain:domain');
        $transfer = $this->createElement('command-ext-domain:transfer');
        $ns = $this->createElement('command-ext-domain:ns');
        $nameservers = $object->getHosts();
        foreach ($nameservers as $nsRecord) {
            /**
             * @var eppHost $nsRecord
             */
            if ($nsRecord->getHostname()) {
                $hostObj = $this->createElement('command-ext-domain:hostObj', $nsRecord->getHostname());
                $ns->appendChild($hostObj);
            } else {
                throw new eppException("nsRecord has no hostname on metaregEppTransferExtendedRequest");
            }
        }
        $transfer->appendChild($ns);
        if ($object->getRegistrant()) {
            $registrant = $this->createElement('command-ext-domain:registrant', $object->getRegistrant());
        } else {
            throw new eppException("eppDomain object has no registrant on metaregEppTransferExtendedRequest");
        }
        $transfer->appendChild($registrant);
        $types = ['admin', 'tech', 'billing'];
        foreach ($types as $type) {
            if ($object->getContact($type)) {
                $contact = $this->createElement('command-ext-domain:contact', $object->getContact($type)->getContactHandle());
                $contact->setAttribute('type', $type);
                $transfer->appendChild($contact);
            } else {
                throw new eppException("eppDomain object has no contact of type $type on metaregEppTransferExtendedRequest");
            }
        }
        $domainChild->appendChild($transfer);
        $commandExt = $this->createElement('command-ext:command-ext');
        $commandExt->appendChild($domainChild);
        $this->getExtension()->appendChild($commandExt);
        $this->addSessionId();
    }
}
