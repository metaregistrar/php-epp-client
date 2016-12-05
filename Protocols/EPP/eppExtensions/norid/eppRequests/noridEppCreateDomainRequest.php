<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=dcre-ad for example request/response

class noridEppCreateDomainRequest extends eppCreateDomainRequest {

    function __construct(noridEppDomain $domain, $forcehostattr = false, $namespacesinroot = true) {
        $this->setNamespacesinroot($namespacesinroot);
        $this->setForcehostattr($forcehostattr);
        parent::__construct(eppRequest::TYPE_CREATE);
        $this->setDomain($domain);
        $this->addSessionId();
    }

    public function addSecdns($secdns) {
        if (!$this->extension) {
            $this->extension = $this->createElement('extension');
            $this->getCommand()->appendChild($this->extension);
        }
        $seccreate = $this->createElement('secDNS:create');
        $seccreate->setAttribute('xmlns:secDNS', 'urn:ietf:params:xml:ns:secDNS-1.1');
        if ($secdns->getKeytag()) {
            $secdsdata = $this->createElement('secDNS:dsData');
            $secdsdata->appendChild($this->createElement('secDNS:keyTag', $secdns->getKeytag()));
            $secdsdata->appendChild($this->createElement('secDNS:alg', $secdns->getAlgorithm()));
            $secdsdata->appendChild($this->createElement('secDNS:digestType', $secdns->getDigestType()));
            $secdsdata->appendChild($this->createElement('secDNS:digest', $secdns->getDigest()));
            if ($secdns->getPubkey()) {
                $seckeydata = $this->createElement('secDNS:keyData');
                $seckeydata->appendChild($this->createElement('secDNS:flags', $secdns->getFlags()));
                $seckeydata->appendChild($this->createElement('secDNS:protocol', $secdns->getProtocol()));
                $seckeydata->appendChild($this->createElement('secDNS:alg', $secdns->getAlgorithm()));
                $seckeydata->appendChild($this->createElement('secDNS:pubKey', $secdns->getPubkey()));
                $secdsdata->appendChild($seckeydata);
            }
            $seccreate->appendChild($secdsdata);
        } else {
            $seckeydata = $this->createElement('secDNS:keyData');
            $seckeydata->appendChild($this->createElement('secDNS:flags', $secdns->getFlags()));
            $seckeydata->appendChild($this->createElement('secDNS:protocol', $secdns->getProtocol()));
            $seckeydata->appendChild($this->createElement('secDNS:alg', $secdns->getAlgorithm()));
            $seckeydata->appendChild($this->createElement('secDNS:pubKey', $secdns->getPubkey()));
            $seccreate->appendChild($seckeydata);
        }
        $this->extension->appendChild($seccreate);
        $this->addSessionId();
    }

    public function setDomain(noridEppDomain $domain) {
        if (!strlen($domain->getDomainname())) {
            throw new eppException('No valid domain name in create domain request');
        }
        if (!strlen($domain->getRegistrant())) {
            throw new eppException('No valid registrant in create domain request');
        }

        // Create domain object structure
        $this->domainobject->appendChild($this->createElement('domain:name', $domain->getDomainname()));
        if ($domain->getPeriod() > 0) {
            $domainperiod = $this->createElement('domain:period', $domain->getPeriod());
            $domainperiod->setAttribute('unit', $domain->getPeriodUnit());
            $this->domainobject->appendChild($domainperiod);
        }
        $nsobjects = $domain->getHosts();
        if ($domain->getHostLength() > 0) {
            $nameservers = $this->createElement('domain:ns');
            foreach ($nsobjects as $nsobject) {
                if (($this->getForcehostattr()) || ($nsobject->getIpAddressCount() > 0)) {
                    $this->addDomainHostAttr($nameservers, $nsobject);
                } else {
                    $this->addDomainHostObj($nameservers, $nsobject);
                }
            }
            $this->domainobject->appendChild($nameservers);
        }
        $this->domainobject->appendChild($this->createElement('domain:registrant', $domain->getRegistrant()));
        $contacts = $domain->getContacts();
        if ($domain->getContactLength() > 0) {
            foreach ($contacts as $contact) {
                $this->addDomainContact($this->domainobject, $contact->getContactHandle(), $contact->getContactType());
            }
        }

        // Add authInfo
        if (strlen($domain->getAuthorisationCode())) {
            $authinfo = $this->createElement('domain:authInfo');
            $authinfo->appendChild($this->createElement('domain:pw', $domain->getAuthorisationCode()));
            $this->domainobject->appendChild($authinfo);
        }

        // Add DNSSEC
        if ($domain->getSecdnsLength() > 0) {
            for ($i = 0; $i < $domain->getSecdnsLength(); $i++) {
                $sd = $domain->getSecdns($i);
                if ($sd) {
                    $ext = new eppSecdns();
                    $ext->copy($sd);
                    $this->addSecdns($ext);
                }
            }
        }

        // Add Norid applicant dataset
        $this->addDomainExtApplicantDataset($this->getDomainExtension(), $domain);
    }

    private function addDomainContact(\DOMElement $element, $contactid, $contacttype) {
        $domaincontact = $this->createElement('domain:contact', $contactid);
        $domaincontact->setAttribute('type', $contacttype);
        $element->appendChild($domaincontact);
    }

    private function addDomainHostAttr(\DOMElement $element, noridEppHost $host) {
        $ns = $this->createElement('domain:hostAttr');
        $ns->appendChild($this->createElement('domain:hostName', $host->getHostname()));
        if ($host->getIpAddressCount() > 0) {
            $addresses = $host->getIpAddresses();
            foreach ($addresses as $address => $type) {
                $ip = $this->createElement('domain:hostAddr', $address);
                $ip->setAttribute('ip', $type);
                $ns->appendChild($ip);
            }
        }
        $element->appendChild($ns);
    }

    private function addDomainHostObj(\DOMElement $element, noridEppHost $host) {
        $element->appendChild($this->createElement('domain:hostObj', $host->getHostname()));
    }

    private function addDomainExtApplicantDataset(\DOMElement $element, noridEppDomain $domain) {
        $dataset = $domain->getExtApplicantDataset();
        if (is_null($dataset['versionNumber']) || is_null($dataset['acceptName']) || is_null($dataset['acceptDate'])) {
            throw new eppException('A valid applicant dataset is required to create a domain in the Norid registry');
        }
        $datasetElement = $this->createElement('no-ext-domain:applicantDataset');
        $datasetElement->appendChild($this->createElement('no-ext-domain:versionNumber', $dataset['versionNumber']));
        $datasetElement->appendChild($this->createElement('no-ext-domain:acceptName', $dataset['acceptName']));
        $datasetElement->appendChild($this->createElement('no-ext-domain:acceptDate', $dataset['acceptDate']));
        $element->appendChild($datasetElement);
    }

}