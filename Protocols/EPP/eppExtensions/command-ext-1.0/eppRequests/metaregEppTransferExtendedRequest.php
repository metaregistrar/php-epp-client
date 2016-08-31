<?php
namespace Metaregistrar\EPP;

/**
 * Class metaregEppTransferExtendedRequest
 */
class metaregEppTransferExtendedRequest extends eppTransferRequest
{
    /**
     * eppTransferExtendedRequest constructor.
     *
     * @param string    $operation
     * @param eppDomain $object
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
            $hostObj = $this->createElement('command-ext-domain:hostObj', $nsRecord->getHostname());
            $ns->appendChild($hostObj);
        }
        $transfer->appendChild($ns);
        $registrant = $this->createElement('command-ext-domain:registrant', $object->getRegistrant());
        $transfer->appendChild($registrant);
        $types = ['admin', 'tech', 'billing'];
        foreach ($types as $type) {
            $contact = $this->createElement('command-ext-domain:contact', $object->getContact($type)->getContactHandle());
            $contact->setAttribute('type', $type);
            $transfer->appendChild($contact);
        }
        $domainChild->appendChild($transfer);
        $commandExt = $this->createElement('command-ext:command-ext');
        $commandExt->appendChild($domainChild);
        $this->getExtension()->appendChild($commandExt);
        $this->addSessionId();
    }
}
