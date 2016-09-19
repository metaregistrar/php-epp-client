<?php
namespace Metaregistrar\EPP;

class ficoraEppUpdateDomainRequest extends eppUpdateDomainRequest {

    // Note: default value for $namespacesinroot differs from parent
    public function __construct($objectname, ficoraEppDomain $addinfo = null, ficoraEppDomain $removeinfo = null, ficoraEppDomain $updateinfo = null, $forcehostattr=false, $namespacesinroot=false)
    {
        parent::__construct($objectname, $addinfo, $removeinfo, $updateinfo, $forcehostattr, $namespacesinroot);
    }

    /**
     *
     * @param \domElement $element
     * @param eppDomain $domain ficoraEppDomain element containing changes
     */
    protected function addDomainChanges($element, eppDomain $domain) {
        // can't change function argument class due to strict standards warning
        if (!$domain instanceof ficoraEppDomain) {
            throw new eppException('Domains passed to ficoraEppUpdateDomainRequest must be instances of ficoraEppDomain');
        }

        if ($domain->getRegistrant()) {
            $element->appendChild($this->createElement('domain:registrant', $domain->getRegistrant()));
        }
        $hosts = $domain->getHosts();
        if (is_array($hosts) && (count($hosts))) {
            $nameservers = $this->createElement('domain:ns');
            foreach ($hosts as $host) {
                /* @var eppHost $host */
                if (($this->getForcehostattr()) ||  (is_array($host->getIpAddresses()))) {
                    $nameservers->appendChild($this->addDomainHostAttr($host));
                } else {
                    $nameservers->appendChild($this->addDomainHostObj($host));
                }
            }
            $element->appendChild($nameservers);
        }
        $contacts = $domain->getContacts();
        if (is_array($contacts)) {
            foreach ($contacts as $contact) {
                /* @var eppContactHandle $contact */
                $this->addDomainContact($element, $contact->getContactHandle(), $contact->getContactType());
            }
        }

        // Changing status is not supported for *.fi domains, verified from registry
        $statuses = $domain->getStatuses();
        if (is_array($statuses) && count($statuses)) {
            throw new eppException('Changing statuses is not supported for *.fi domains.');
        }

        // authinfo might contain domain:pw (provider transfer key) and/or domain:pwregistranttransfer (registrant transfer key)
        // registrant transfer key must be present on registrant change, empty one is valid if registry number doesn't change
        if (strlen($domain->getAuthorisationCode()) || $domain->getRegistrant() || $domain->getRegistrantTransferCode()) {
            $authinfo = $this->createElement('domain:authInfo');
            if (strlen($domain->getAuthorisationCode())) {
                $pw = $this->createElement('domain:pw');
                $pw->appendChild($this->createCDATASection($domain->getAuthorisationCode()));
                $authinfo->appendChild($pw);
            }

            if ($domain->getRegistrant() || $domain->getRegistrantTransferCode()) {
                $registrantPassword = $this->createElement('domain:pwregistranttransfer', $domain->getRegistrantTransferCode());
                $authinfo->appendChild($registrantPassword);
            }

            $element->appendChild($authinfo);
        }
    }
}