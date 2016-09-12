<?php
namespace Metaregistrar\EPP;

class eppUpdateDomainRequest extends eppDomainRequest {


    function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null, $forcehostattr=false, $namespacesinroot=null) {
        if ($namespacesinroot!==null) {
            $this->setNamespacesinroot($namespacesinroot);
        } else {
            if (defined("NAMESPACESINROOT")) {
                $this->setNamespacesinroot(NAMESPACESINROOT);
            }
        }
        $this->setForcehostattr($forcehostattr);
        parent::__construct(eppRequest::TYPE_UPDATE);
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
        return;
    }

    /**
     *
     * @param \domElement $element
     * @param eppDomain $domain
     */
    private function addDomainChanges($element, eppDomain $domain) {
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
        $statuses = $domain->getStatuses();
        if (is_array($statuses)) {
            foreach ($statuses as $status) {
                $this->addDomainStatus($element, $status);
            }
        }
        if (strlen($domain->getAuthorisationCode())) {
            $authinfo = $this->createElement('domain:authInfo');
            $pw = $this->createElement('domain:pw');
            $pw->appendChild($this->createCDATASection($domain->getAuthorisationCode()));
            $authinfo->appendChild($pw);
            $element->appendChild($authinfo);
        }
    }


    /**
     *
     * @param \domElement $element
     * @param string $status
     */
    private function addDomainStatus($element, $status) {
        $stat = $this->createElement('domain:status');
        $stat->setAttribute('s', $status);
        $element->appendChild($stat);
    }


    /**
     *
     * @param \domElement $domain
     * @param string $contactid
     * @param string $contacttype
     */
    private function addDomainContact($domain, $contactid, $contacttype) {
        $domaincontact = $this->createElement('domain:contact', $contactid);
        $domaincontact->setAttribute('type', $contacttype);
        $domain->appendChild($domaincontact);
    }


    /**
     *
     * @param eppHost $host
     * @return \domElement
     */
    private function addDomainHostAttr(eppHost $host) {

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
    private function addDomainHostObj(eppHost $host) {
        $ns = $this->createElement('domain:hostObj', $host->getHostname());
        return $ns;
    }

}