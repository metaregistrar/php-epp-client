<?php
namespace Metaregistrar\EPP;

class eppDomain {
    const DOMAIN_PERIOD_UNIT_Y = 'y';
    const DOMAIN_PERIOD_UNIT_M = 'm';
    #
    # These status values cannot be set, only viewed
    #
    const STATUS_OK = 'ok';
    const STATUS_SERVER_DELETE_PROHIBITED = 'serverDeleteProhibited';
    const STATUS_SERVER_UPDATE_PROHIBITED = 'serverUpdateProhibited';
    const STATUS_SERVER_RENEW_PROHIBITED = 'serverRenewProhibited';
    const STATUS_SERVER_TRANSFER_PROHIBITED = 'serverTransferProhibited';
    const STATUS_SERVER_HOLD = 'serverHold';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_PENDING_CREATE = 'pendingCreate';
    const STATUS_PENDING_DELETE = 'pendingDelete';
    const STATUS_PENDING_TRANSFER = 'pendingTransfer';
    const STATUS_PENDING_UPDATE = 'pendingUpdate';
    const STATUS_PENDING_RENEW = 'pendingRenew';

    #
    # These status values can be set
    #
    const STATUS_CLIENT_DELETE_PROHIBITED = 'clientDeleteProhibited';
    const STATUS_CLIENT_UPDATE_PROHIBITED = 'clientUpdateProhibited';
    const STATUS_CLIENT_RENEW_PROHIBITED = 'clientRenewProhibited';
    const STATUS_CLIENT_TRANSFER_PROHIBITED = 'clientTransferProhibited';
    const STATUS_CLIENT_HOLD = 'clientHold';

    /** @var string */
    private $domainname = '';
    /** @var string */
    private $registrant = '';
    /** @var array<eppContactHandle> Contact information for this domain name */
    private $contacts = array();
    /** @var array<eppHost> Host information for this domain name */
    private $hosts = array();
    /** @var array<string|eppStatus> Status information for this domain name */
    private $statuses = array();
    /** @var array<eppSecdns> DNSSEC information for this domain name */
    private $secdns = array();
    /** @var string|null */
    private $authorisationCode = null;
    /** @var int */
    private $periodunit = self::DOMAIN_PERIOD_UNIT_Y;

    private $period = 1;

    /**
     * @param string $domainname
     * @param eppContactHandle|string|null $registrant
     * @param array<eppContactHandle>|eppContactHandle|null $contacts
     * @param array<eppHost>|eppHost|null $hosts
     * @param int $period
     * @param string|null $authorisationCode
     */
    public function __construct($domainname, $registrant = null, $contacts = null, $hosts = null, $period = 0, $authorisationCode = null) {

        if (strlen($domainname)) {
            $this->setDomainname($domainname);
        } else {
            throw new eppException('Domain name not set');
        }
        $this->setPeriod($period);
        if ($registrant instanceof eppContact) {
            throw new eppException('Registrant must be Metaregistrar\EPP\eppContactHandle or string on eppDomain creation');
        }
        if ($registrant instanceof eppContactHandle) {
            $this->setRegistrant($registrant->getContactHandle());
        } else {
            if ($registrant) {
                if (strlen($registrant)) {
                    $this->setRegistrant($registrant);
                } else {
                    throw new eppException('Registrant must be Metaregistrar\EPP\eppContactHandle or string on eppDomain creation');
                }
            }
        }
        if ($authorisationCode) {
            $this->setAuthorisationCode($authorisationCode);
        }
        if ($hosts) {
            if (is_array($hosts)) {
                foreach ($hosts as $host) {
                    $this->addHost($host);
                }
            } else {
                $this->addHost($hosts);
            }
        }
        if ($contacts) {
            if (is_array($contacts)) {
                foreach ($contacts as $contact) {
                    $this->addContact($contact);
                }
            } else {
                if (strlen($contacts)) {
                    $this->addContact($contacts);
                }
            }
        }
    }

    /**
     *
     * @param string $domainname
     */
    public function setDomainname($domainname) {
        $this->domainname = $domainname;
    }

    /**
     *
     * @return string
     */
    public function getDomainname() {
        return $this->domainname;
    }

    /**
     *
     * @param int $period
     */
    public function setPeriodUnit($periodunit) {
        if (($periodunit == eppDomain::DOMAIN_PERIOD_UNIT_Y) || ($periodunit == eppDomain::DOMAIN_PERIOD_UNIT_M)) {
            $this->periodunit = $periodunit;
        } else {
            throw new eppException("Domain period unit " . $periodunit . " is invalid, only m or y allowed");
        }
    }

    /**
     *
     * @return int
     */
    public function getPeriodUnit() {
        return $this->periodunit;
    }


    /**
     * @return int
     */
    public function getPeriod() {
        return $this->period;
    }

    /**
     * @param int $period
     * @return void
     */
    public function setPeriod($period) {
        if ($this->periodunit == self::DOMAIN_PERIOD_UNIT_Y) {
            if (($period > 10) || ($period < 0)) {
                throw new eppException("If period unit = y, period can only be 1 - 10");
            }
        }
        if ($this->periodunit == self::DOMAIN_PERIOD_UNIT_M) {
            if (($period > 120) || ($period < 0)) {
                throw new eppException("If period unit = m, period can only be 1 - 120");
            }
        }
        $this->period = $period;
    }


    /**
     *
     * @param string|eppContactHandle $registrant
     */
    public function setRegistrant($registrant) {
        if ($registrant instanceof eppContactHandle) {
            $this->registrant = $registrant->getContactHandle();
        } else {
            $this->registrant = $registrant;
        }
    }


    /**
     *
     * @return string registrant
     */
    public function getRegistrant() {
        return $this->registrant;
    }


    /**
     *
     * @param eppContactHandle $contact
     * @return void
     */
    public function addContact(eppContactHandle $contact) {
        if (!strlen($contact->getContactType())) {
            throw new eppException('No contact type set for: ' . $contact->getContactHandle() . ', please set one!');
        }
        $this->contacts[] = $contact;
    }

    /**
     *
     * @param string $type
     * @return eppContactHandle|null
     */
    public function getContact($type) {
        if (is_array($this->contacts)) {
            foreach ($this->contacts as $contact) {
                /* @var $contact eppContactHandle */
                if ($contact->getContactType() == $type) {
                    return $contact;
                }
            }
        }
        return null;
    }

    /**
     *
     * @return array<eppContactHandle> contactHandles
     */
    public function getContacts() {
        return $this->contacts;
    }

    /**
     *
     * @return int
     */
    public function getContactLength() {
        if (is_array($this->contacts)) {
            return count($this->contacts);
        }
        return 0;
    }

    /**
     *
     * @param eppHost $host
     * @return void
     */
    public function addHost(eppHost $host) {
        if (is_array($this->hosts)) {
            if (count($this->hosts) < 13) {
                $this->hosts[] = $host;
            } else {
                throw new eppException('Cannot set more then 13 hosts on object');
            }
        }
    }

    /**
     *
     * @return array<eppHost> of eppHosts
     */
    public function getHosts() {
        return $this->hosts;
    }

    /**
     *
     * @return int
     */
    public function getHostLength() {
        return count($this->hosts);
    }

    /**
     *
     * @param int|null $line
     * @return eppHost|null
     */
    public function getHost($line=null) {
        if (!is_null($line)) {
            if (isset($this->hosts[$line])) {
                return $this->hosts[$line];
            }
        }
        return null;
    }

    /**
     * @return void
     */
    public function addSecdns(eppSecdns $secdns) {
        $this->secdns[] = $secdns;
    }

    /**
     * @return int
     */
    public function getSecdnsLength() {
        return count($this->secdns);
    }

    /**
     * @param int|null $row
     * @return eppSecdns|array<eppSecdns>
     * 
     * @phpstan-return ($row is null ? array<eppSecdns> : eppSecdns)
     */
    public function getSecdns($row = null) {
        if (!is_null($row)) {
           if (isset($this->secdns[$row])) {
                return $this->secdns[$row];
            } else {
               throw new eppException("DNSSEC info number $row could not be retrieved");
           }
        } else {
            // return the whole array
            return $this->secdns;
        }
    }

    /**
     *
     * @param string $authorisationCode
     * @return void
     */
    public function setAuthorisationCode($authorisationCode) {
        $this->authorisationCode = $authorisationCode;
    }

    /**
     *
     * @return string|null
     */
    public function getAuthorisationCode() {
        return $this->authorisationCode;
    }

    /**
     *
     * @param string $password
     * @return void
     */
    public function setPassword($password) {
        $this->setAuthorisationCode($password);
    }

    /**
     *
     * @return string|null
     */
    public function getPassword() {
        return $this->authorisationCode;
    }    
    
    /**
     *
     * @param string|eppStatus $status
     */
    public function addStatus($status) {
        $this->statuses[] = $status;
    }

    /**
     *
     * @param bool $fullobjects
     * @return array<string|eppStatus>
     *
     * @phpstan-return ($fullobjects is true ? array<eppStatus> : array<string>)
     * 
     */
    public function getStatuses($fullobjects=false) {
        $return_statuses=[];

        if ($fullobjects) { // return full eppStatus Objects
            foreach ($this->statuses as $status) {
                if  ($status instanceof eppStatus) {
                    $return_statuses[]=$status;
                } else {
                    $return_statuses[]=new eppStatus($status);
                }
            }
        }  else {  // return just a list of statuses
            foreach ($this->statuses as $status) {
                if  ($status instanceof eppStatus) {
                    $return_statuses[]=$status->getStatusname();
                } else {
                    $return_statuses[]=$status;
                }
            }
        } 

        return $return_statuses;
    }


    /**
     * @param int $length
     * @return string
     */
    public static function generateRandomString($length = 10) {
        $characters = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
