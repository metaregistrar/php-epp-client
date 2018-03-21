<?php
namespace Metaregistrar\EPP;
#
# rfc5910
#
/**
 *    <extension>
<secDNS:infData>
<secDNS:keyData>
<secDNS:flags>257</secDNS:flags>
<secDNS:protocol>3</secDNS:protocol>
<secDNS:alg>8</secDNS:alg>
<secDNS:pubKey>AwEAAdHSD0dsAPliSTZAhP4wesBbotNO4TtQSre5ZCiliWXe3h6VAoe+Msd3UQV4/DXgYF1kuy6RdNncbElqnQNdoF8/65cHJJI8hKaDOgqWRDCKp7/2/j/etLDhuyv+ybVo8gIRLnUS55J44PyhiuHeTIsgI5oroKdRhHw1lxpZRdjiue/cZ6E6rVWx2x9p3BUZO3ygZy6pnSQxO5oj7zklTKjyKu4/Bx7sRoZ5FCVKYIx+ENVg52ly4hLLQyFCuOEaI8+hXap0ooNEeCWP7NMH4nIZGKRMSZi485dqLyvIZLMqBxuVOMiUuCTyRggAk7It6X1APDV6dUEoaoEIqWXiCJ8=</secDNS:pubKey>
</secDNS:keyData>
</secDNS:infData>


<extension>
<secDNS:infData xmlns:secDNS="urn:ietf:params:xml:ns:secDNS-1.1">
<secDNS:keyData>
<secDNS:flags>257</secDNS:flags>
<secDNS:protocol>3</secDNS:protocol>
<secDNS:alg>8</secDNS:alg>
<secDNS:pubKey>AwEAAaQOLIYKhaBDAFDCJk8+ubGyRU0bRxXrB6TQ/MaD6N5ut5pVzbv4YE9AKHBJ36q2lyuLvFObz/xSLd+E0cNDeEYOoNHY53T0LINX6iFs2euyiMbqVPDXksa0C/ZYEx7EVnVTJfLBFI56VxV2Sj8WjrpeKv9Bl+kDg7TLlX7VQunBtPxQrKQyXpfYKHMeGP7+V6wJdDBh6M9EElu7Wi7OgS/FDfO8z7dGSAmSY6xIq/d+DyCQPd5eBkNWRyL3zjyOqa0r3pg2PBmL2+j5KPAsJqN2d0a/g4Ikvv2PnZ/Xvrhrq7NkoWzFxvWEdYxKvNEjoo4rLAjohsL0HBM8tLUiayU=</secDNS:pubKey>
</secDNS:keyData>
<secDNS:keyData>
<secDNS:flags>257</secDNS:flags>
<secDNS:protocol>3</secDNS:protocol>
<secDNS:alg>8</secDNS:alg>
<secDNS:pubKey>AwEAAbqDt56Ez75fGvlA6QRyJaZ8kblN13jW19smEly1N8Wt+plL/ELU5j6cLxhTV6FBHecrVbvkpQY/v848hAXzG5vEUO5rmAib1aZvr9EpFQWW9TPhvusPf5kuM5cv0ypehXP7R1skF5ez8Lroub3RkwoJl0sultyalrMI84DN4eZZr/MtXAYy7X2yaBK9sSXTY7I1Ou2msmXtYXljjOOJ3Pcig7tmrdDPe2Sd2gvuFiwoPj86Ko/L0iCjNIZT6hmvCgCuc6s7sUz4jRJxH/EKmL70c+eoaaCzFouNIw6hz860/ZFBDhMLKhlNTkDpP+sqbc8bhEPlLiYgzVDXmq4uCik=</secDNS:pubKey>
</secDNS:keyData>
</secDNS:infData>
</extension>

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
     * Receive an array of statuses
     *
     * @return string status
     */
    public function getDomainStatuses() {
        $statuses = null;
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/domain:infData/domain:status/@s');
        foreach ($result as $status) {
            $statuses[] = $status->nodeValue;
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
     * @return array eppContactHandles
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
        date_default_timezone_set("UTC");
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
     * @return array of strings
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
        } else {
            return null;
        }
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
     * ALL DNSSEC FUNCTION ARE IN THERE
     * @return array|null
     */
    public function getKeydata() {
        // Check if dnssec is enabled on this interface
        if ($this->findNamespace('secDNS')) {
            $xpath = $this->xPath();
            $result = $xpath->query('/epp:epp/epp:response/epp:extension/secDNS:infData/*');
            $keys = array();
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