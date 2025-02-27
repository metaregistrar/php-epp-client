<?php
namespace Metaregistrar\EPP;

class eppUpdateDomainRequest extends eppDomainRequest {


    function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null, $forcehostattr=false, $namespacesinroot=true, $usecdata = true) {
        $this->setNamespacesinroot($namespacesinroot);
        $this->setForcehostattr($forcehostattr);
        parent::__construct(eppRequest::TYPE_UPDATE);
        $this->setUseCdata($usecdata);
        if ($objectname instanceof eppDomain) {
            $domainname = $objectname->getDomainname();
        } else {
            if (strlen($objectname)) {
                $domainname = $objectname;
            } else {
                throw new eppException("Object name must be valid string on eppUpdateDomainRequest");
            }
        }
        if (($addinfo instanceof eppDomain) || ($removeinfo instanceof eppDomain) || ($updateinfo instanceof eppDomain)) {
            $this->updateDomain($domainname, $addinfo, $removeinfo, $updateinfo);
        } else {
            throw new eppException('addinfo, removeinfo and updateinfo needs to be eppDomain object on eppUpdateDomainRequest');
        }
        $this->addSessionId();
    }

    function __destruct() {
        parent::__destruct();
    }


    /**
     *
     * @param string $domainname
     * @param eppDomain $addInfo
     * @param eppDomain $removeInfo
     * @param eppDomain $updateInfo
     */
    public function updateDomain($domainname, $addInfo, $removeInfo, $updateInfo) {
        #
        # Object create structure
        #
        $this->domainobject->appendChild($this->createElement('domain:name', $domainname));
        if ($addInfo instanceof eppDomain) {
            $addcmd = $this->createElement('domain:add');
            $this->addDomainChanges($addcmd, $addInfo);
            $this->domainobject->appendChild($addcmd);
        }
        if ($removeInfo instanceof eppDomain) {
            $remcmd = $this->createElement('domain:rem');
            $this->addDomainChanges($remcmd, $removeInfo);
            $this->domainobject->appendChild($remcmd);
        }
        if ($updateInfo instanceof eppDomain) {
            $chgcmd = $this->createElement('domain:chg');
            $this->addDomainChanges($chgcmd, $updateInfo);
            $this->domainobject->appendChild($chgcmd);
        }
    }

    /**
     *
     * @param \domElement $element
     * @param eppDomain $domain
     */
    protected function addDomainChanges($element, eppDomain $domain) {
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
        $statuses = $domain->getStatuses(true);
        if (is_array($statuses)) {
            foreach ($statuses as $status) {
                $this->addDomainStatus($element, $status);
            }
        }
        $authcode = $domain->getAuthorisationCode();
        if (is_string($authcode) && strlen($authcode)) {
            $authinfo = $this->createElement('domain:authInfo');
            if ($this->useCdata()) {
                $pw = $this->createElement('domain:pw');
                $pw->appendChild($this->createCDATASection($authcode));
            }
            else {
                $pw = $this->createElement('domain:pw',$authcode);
            }
            $authinfo->appendChild($pw);
            $element->appendChild($authinfo);
        }
    }


    /**
     *
     * @param \domElement $element
     * @param string|eppStatus $status
     */
    protected function addDomainStatus($element, $status) {
        $stat = $this->createElement('domain:status');

        if ($status instanceof eppStatus) {
            $stat = $this->createElement('domain:status',$status->getMessage());
            $stat->setAttribute('s', $status->getStatusname());
            if (!is_null($status->getLanguage())) {
                $stat->setAttribute('lang', $status->getLanguage());
            }

        } else {
            $stat->setAttribute('s', $status);
        }
        $element->appendChild($stat);
    }


    /**
     *
     * @param \domElement $domain
     * @param string $contactid
     * @param string $contacttype
     */
    protected function addDomainContact($domain, $contactid, $contacttype) {
        $domaincontact = $this->createElement('domain:contact', $contactid);
        $domaincontact->setAttribute('type', $contacttype);
        $domain->appendChild($domaincontact);
    }


    /**
     *
     * @param eppHost $host
     * @return \domElement
     */
    protected function addDomainHostAttr(eppHost $host) {

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
        return $ns;
    }

    /**
     *
     * @param eppHost $host
     * @return \domElement
     */
    protected function addDomainHostObj(eppHost $host) {
        $ns = $this->createElement('domain:hostObj', $host->getHostname());
        return $ns;
    }

}