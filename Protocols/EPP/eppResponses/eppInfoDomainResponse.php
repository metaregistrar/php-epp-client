<?php
namespace Metaregistrar\EPP;
/*

 */
class eppInfoDomainResponse extends eppInfoResponse {


    /**
     * Get the domain in the response as eppDomain object
     *
     * @return eppDomain
     */
    public function getDomain() {
        $domainname = $this->getDomainName();
        $registrant = $this->getDomainRegistrant();
        $contacts = $this->getDomainContacts();
        $nameservers = $this->getDomainNameservers();
        $authinfo = $this->getDomainAuthInfo();
        $domain = new eppDomain($domainname, $registrant, $contacts, $nameservers, 1, $authinfo);
        foreach ($this->getDomainStatuses() as $status) {
            $domain->addStatus($status);
        }
        return $domain;
    }

    /**
     * Get the domain name in the response
     *
     * @return string domainname
     */
    public function getDomainName() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:infData/domain:name');
    }

    /**
     *
     * @return string domainid
     */
    public function getDomainId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:infData/domain:roid');
    }

    /**
     * Receive an array of statuses
     *
     * @return null|string[]
     */
    public function getDomainStatuses() {
        $statuses = null;
        $xpath = $this->xPath();
    //    $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:infData/domain:status/@s');
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:infData/domain:status');
        foreach ($result as $status) {
            $statuses[] = new eppStatus($status->getAttribute('s'),
                                        $status->getAttribute('lang'),
                                         $status->nodeValue);
        }
        return $statuses;
    }

    /**
     * Receive statuses as CSV string
     *
     * @return string statuses
     */
    public function getDomainStatusCSV() {
        return parent::arrayToCSV($this->getDomainStatuses());
    }

    /**
     * Get the unique domain identifier (roid) for the domainname
     *
     * @return string roid
     */
    public function getDomainRoid() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:infData/domain:roid');
    }

    /**
     * Get the contact handle of the registrant
     *
     * @return string registrant id
     */
    public function getDomainRegistrant() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:infData/domain:registrant');
    }

    /**
     * Get the contact information for a specific contact type (type may be: admin, tech, billing)
     *
     * @param string $contacttype Type of contact
     * @return string
     */
    public function getDomainContact($contacttype) {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:infData/domain:contact[@type=\'' . $contacttype . '\']');
    }

    /**
     * Get the contacts associated with the domain name as eppContactHandle objects
     *
     * @return null|eppContactHandle[]
     */
    public function getDomainContacts() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:infData/domain:contact');
        $cont = null;
        foreach ($result as $contact) {
            /* @var $contact \DOMElement */
            if (($contact->nodeValue) && (strlen($contact->nodeValue) > 0)) {
                $contacttype = $contact->getAttribute('type');
                if ($contacttype) {
                    // DNSBE specific, but too much hassle to create an override for this
                    if ($contacttype == 'onsite') {
                        $contacttype = 'admin';
                    }
                    $cont[] = new eppContactHandle($contact->nodeValue, $contacttype);
                }
            }
        }
        return $cont;
    }

    /**
     * This function returns the SUBORDINATE host objects of a domainname.
     * These must not be confused with the attached host objects.
     * Subordinate host objects are nameservers that end with the same string as the domain name.
     * They do not have to be connected to this domain name
     * @return array of eppHost
     */
    public function getDomainHosts() {
        $ns = null;
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:infData/domain:host');
        foreach ($result as $host) {
            $ns[] = new eppHost($host->nodeValue);
        }
        return $ns;
    }

    /**
     * Get the create date of this domain name
     *
     * @return string create_date
     */
    public function getDomainCreateDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:infData/domain:crDate');
    }

    /**
     * Get the update date of this domain name
     *
     * @return string update_date
     */
    public function getDomainUpdateDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:infData/domain:upDate');
    }

    /**
     * Get the expiration date of this domain name
     *
     * @return string expiration_date
     */
    public function getDomainExpirationDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:infData/domain:exDate');
    }

    /**
     * Get the sponsoring client id (registrar id) of this domain name
     *
     * @return string client id
     */
    public function getDomainClientId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:infData/domain:clID');
    }

    /**
     * Get the creating client id (registrar id) of this domain name
     *
     * @return string client id
     */
    public function getDomainCreateClientId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:infData/domain:crID');
    }

    /**
     * Get the client id (registrar id) that last updated the domain name
     *
     * @return string client id
     */
    public function getDomainUpdateClientId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:infData/domain:upID');
    }

    /**
     * This function returns the associated nameservers from a domain object
     * Please do not confuse this with getDomainHosts(), which is used for subordinate host objects
     *
     * @return null|eppHost[]
     */
    public function getDomainNameservers() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:infData/domain:ns/*');
        if ($result->length > 0) {
            $ns = null;
            foreach ($result as $nameserver) {
                /* @var $nameserver \DOMElement */
                if (strstr($nameserver->tagName, ":hostObj")) {
                    $ns[] = new eppHost(trim($nameserver->nodeValue));
                }
                if (strstr($nameserver->tagName, ":hostAttr")) {
                    $hostname = $nameserver->getElementsByTagName('hostName')->item(0)->nodeValue;
                    $ipaddresses = $nameserver->getElementsByTagName('hostAddr');
                    $ips = null;
                    foreach ($ipaddresses as $ip) {
                        $ips[] = $ip->nodeValue;
                    }
                    $ns[] = new eppHost($hostname, $ips);
                }
            }
            return $ns;
        }
        return null;
    }

    /**
     * Get nameservers associated with this domain name as CSV string
     *
     * @return string nameservers
     */
    public function getDomainNameserversCSV() {
        $nameservers = [];
        $ns = $this->getDomainNameservers();
        if (is_array($ns)) {
            foreach ($ns as $n) {
                $nameservers[] = $n->getHostname();
            }
        } else {
            $nameservers = '';
        }
        return parent::arrayToCSV($nameservers);
    }


    /**
     * Get the authorization code (transfer token) of this domain name
     *
     * @return string authcode
     */
    public function getDomainAuthInfo() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/domain:infData/domain:authInfo/domain:pw');
    }

    /**
     * OBSOLETE, DO NOT USE THIS FUNCTION
     * If you need DNSSEC KeyData or DSData, see the extension SecDNS-1.1
     * ALL DNSSEC FUNCTIONS ARE IN THERE
     * @return null|eppSecdns[]
     */
    public function getKeydata() {
        // Check if dnssec is enabled on this interface
        if ($this->findNamespace('secDNS')) {
            $xpath = $this->xPath();
            $result = $xpath->query('/epp:epp/epp:response/epp:extension/secDNS:infData/*');
            $keys = [];
            if ($result->length > 0) {
                foreach ($result as $keydata) {
                    /* @var $keydata \DOMElement */
                    $secdns = new eppSecdns();
                    $secdns->setFlags($keydata->getElementsByTagName('flags')->item(0)->nodeValue);
                    $secdns->setAlgorithm($keydata->getElementsByTagName('alg')->item(0)->nodeValue);
                    $secdns->setProtocol($keydata->getElementsByTagName('protocol')->item(0)->nodeValue);
                    $secdns->setPubkey($keydata->getElementsByTagName('pubKey')->item(0)->nodeValue);
                    $keys[] = $secdns;
                }
            }
            return $keys;
        }
        return null;
    }

}