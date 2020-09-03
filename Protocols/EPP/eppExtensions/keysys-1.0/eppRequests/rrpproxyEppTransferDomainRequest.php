<?php
namespace Metaregistrar\EPP;

/*
<extension>
      <keysys:transfer xmlns:keysys="http://www.key-systems.net/epp/keysys-1.0">
        <keysys:domain>
          <keysys:admincontact0>P-ABC123</keysys:admincontact0>
          <keysys:billingcontact0>P-ABC123</keysys:billingcontact0>
          <keysys:nameserver0>ns1.nameserver.com</keysys:nameserver0>
          <keysys:nameserver1>ns2.nameserver.com</keysys:nameserver1>
          <keysys:ownercontact0>P-ABC123</keysys:ownercontact0>
          <keysys:techcontact0>P-ABC123</keysys:techcontact0>
        </keysys:domain>
      </keysys:transfer>
    </extension>
 */

class rrpproxyEppTransferDomainRequest extends eppTransferRequest
{
    function __construct($operation, eppDomain $domain)
    {
        parent::__construct($operation, $domain);
        $this->addContactsAndNameserver($domain);
        parent::addSessionId();
    }

    private function addContactsAndNameserver(eppDomain $domain)
    {
        $transfer = $this->createElement('keysys:transfer');
        $transfer->setAttribute('xmlns:keysys', 'http://www.key-systems.net/epp/keysys-1.0');
        $request = $this->createElement('keysys:domain');

        foreach ($domain->getContacts() as $contact) {
            /* @var $contact \Metaregistrar\EPP\eppContactHandle */
            if($contact->getContactType() === eppContactHandle::CONTACT_TYPE_ADMIN) {
                $c = $this->createElement('keysys:admincontact0', $contact->getContactHandle());
                $request->appendChild($c);
            }
        }

        foreach ($domain->getContacts() as $contact) {
            /* @var $contact \Metaregistrar\EPP\eppContactHandle */
            if($contact->getContactType() === eppContactHandle::CONTACT_TYPE_BILLING) {
                $c = $this->createElement('keysys:billingcontact0', $contact->getContactHandle());
                $request->appendChild($c);
            }
        }

        // Set Nameservers at Transfer if needed
        $nsobjects = $domain->getHosts();
        if ($domain->getHostLength() > 0) {
            $count = 0;
            foreach ($nsobjects as $nsobject) {
                /* @var $nsobject \Metaregistrar\EPP\eppHost */
                $c = $this->createElement('keysys:nameserver'.$count, $nsobject->getHostname());
                $request->appendChild($c);
                $count++;
            }
        }

        if($domain->getRegistrant() != "") {
            $c = $this->createElement('keysys:ownercontact0', $domain->getRegistrant());
            $request->appendChild($c);
        }

        foreach ($domain->getContacts() as $contact) {
            /* @var $contact \Metaregistrar\EPP\eppContactHandle */
            if($contact->getContactType() === eppContactHandle::CONTACT_TYPE_TECH) {
                $c = $this->createElement('keysys:techcontact0', $contact->getContactHandle());
                $request->appendChild($c);
            }
        }

        $transfer->appendChild($request);
        $this->getExtension()->appendChild($transfer);
    }

}