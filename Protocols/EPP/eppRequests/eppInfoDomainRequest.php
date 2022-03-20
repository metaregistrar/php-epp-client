<?php
namespace Metaregistrar\EPP;

/*
 * This object contains all the logic to create an EPP domain:info command
 */

class eppInfoDomainRequest extends eppDomainRequest {
    const HOSTS_ALL = 'all';
    const HOSTS_DELEGATED = 'del';
    const HOSTS_SUBORDINATE = 'sub';
    const HOSTS_NONE = 'none';


    /**
     * eppInfoDomainRequest constructor.
     * @param $infodomain
     * @param null $hosts
     * @throws eppException
     */
    public function __construct($infodomain, $hosts = null, $namespacesinroot = true, $usecdata = true) {
        $this->setNamespacesinroot($namespacesinroot);
        parent::__construct(eppRequest::TYPE_INFO);
        $this->setUseCdata($usecdata);
        if ($infodomain instanceof eppDomain) {
            $this->setDomain($infodomain, $hosts);
        } else {
            throw new eppException('parameter of infodomainrequest needs to be eppDomain object');
        }
        $this->addSessionId();
    }

    function __destruct() {
        parent::__destruct();
    }

    public function setDomain(eppDomain $domain, $hosts = null) {
        if (!strlen($domain->getDomainname())) {
            throw new eppException('Domain object does not contain a valid domain name');
        }
        #
        # Domain structure
        #
        $dname = $this->createElement('domain:name', $domain->getDomainname());
        if ($hosts) {
            if (($hosts == self::HOSTS_ALL) || ($hosts == self::HOSTS_DELEGATED) || ($hosts == self::HOSTS_NONE) || ($hosts == self::HOSTS_SUBORDINATE)) {
                $dname->setAttribute('hosts', $hosts);
            } else {
                throw new eppException('Hosts parameter of inforequest can only be to be all, none, del or sub');
            }
        }
        $this->domainobject->appendChild($dname);
        if (!is_null($domain->getAuthorisationCode())) {
            $authinfo = $this->createElement('domain:authInfo');
            if ($this->useCdata()) {
                $pw = $authinfo->appendChild($this->createElement('domain:pw'));
                $pw->appendChild($this->createCDATASection($domain->getAuthorisationCode()));
            } else {
                $authinfo->appendChild($this->createElement('domain:pw', $domain->getAuthorisationCode()));
            }
            $this->domainobject->appendChild($authinfo);
        }
    }
}
