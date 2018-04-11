<?php
require(dirname(__FILE__).'/../autoloader.php');

class eppTestCase extends PHPUnit_Framework_TestCase {
    /**
     * @var Metaregistrar\EPP\eppConnection
     *
     */
    protected $conn;

    protected function setUp($configfile = null) {
        if (!$configfile) {
            $configfile = dirname(__FILE__).'/testsetup.ini';
        }
        $this->conn = self::setupConnection($configfile);
    }

    protected function tearDown() {
        self::teardownConncection($this->conn);
    }

    private static function setupConnection($configfile) {
        try {
            if ($conn = Metaregistrar\EPP\metaregEppConnection::create($configfile)) {
                /* @var $conn Metaregistrar\EPP\eppConnection */
                if ($conn->login()) {
                    return $conn;
                }
            }
        } catch (Metaregistrar\EPP\eppException $e) {
            echo "Test setup error in ".$e->getClass().": " . $e->getMessage() . "\n\n";
            die();
        }
        return null;
    }

    /**
     * @param Metaregistrar\EPP\eppConnection $conn
     */
    private static function teardownConncection($conn) {
        if ($conn) {
            $conn->logout();
        }
    }

    protected static function randomstring($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    protected static function randomnumber($length) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Create a hostname to be used in create host or create domain testing
     * @var string $hostname
     * @return string
     * @throws \Metaregistrar\EPP\eppException
     */
    protected function createHost($hostname) {
        $host = new Metaregistrar\EPP\eppHost($hostname);
        $host->setIpAddress('81.4.97.247');
        $create = new Metaregistrar\EPP\eppCreateHostRequest($host);
        if ($response = $this->conn->request($create)) {
            /* @var $response Metaregistrar\EPP\eppCreateHostResponse */
            return $hostname;
        }
        return null;
    }

    /**
     * Create a contact to be used in create contact or create domain testing
     * @return string
     * @throws \Metaregistrar\EPP\eppException
     */
    protected function createNLContact() {
        $name = 'Test name';
        $city = 'Test city';
        $country = 'NL';
        $organization = 'Test company';
        $address = 'Teststreet 1';
        $province = 'CA';
        //$postcode = '00000';
        $postcode = '3825AB';
        $email = 'ewout@mdmail.nl';
        //$telephone = '+1.55500000';
        $telephone = '+31.628901768';
        $password = self::randomstring(8);
        $postalinfo = new Metaregistrar\EPP\eppContactPostalInfo($name, $city, $country, $organization, $address, $province, $postcode, Metaregistrar\EPP\eppContact::TYPE_LOC);
        $contactinfo = new Metaregistrar\EPP\eppContact($postalinfo, $email, $telephone);
        $contactinfo->setPassword($password);
        $create = new Metaregistrar\EPP\sidnEppCreateContactRequest($contactinfo);
        if ($response = $this->conn->request($create)) {
            /* @var $response Metaregistrar\EPP\eppCreateContactResponse */
            return $response->getContactId();
        }
        return null;
    }

    /**
     * Create a contact to be used in create contact or create domain testing
     * @return string
     * @throws \Metaregistrar\EPP\eppException
     */
    protected function createContact() {
        $name = 'Test name';
        $city = 'Test city';
        $country = 'NL';
        $organization = 'Test company';
        $address = 'Teststreet 1';
        $province = 'CA';
        //$postcode = '00000';
        $postcode = '3825 AB';
        $email = 'ewout@mdmail.nl';
        //$telephone = '+1.55500000';
        $telephone = '+31.628901768';
        $password = self::randomstring(8);
        $postalinfo = new Metaregistrar\EPP\eppContactPostalInfo($name, $city, $country, $organization, $address, $province, $postcode, Metaregistrar\EPP\eppContact::TYPE_LOC);
        $contactinfo = new Metaregistrar\EPP\eppContact($postalinfo, $email, $telephone);
        $contactinfo->setPassword($password);
        $create = new Metaregistrar\EPP\eppCreateContactRequest($contactinfo);
        if ($response = $this->conn->request($create)) {
            /* @var $response Metaregistrar\EPP\eppCreateContactResponse */
            return $response->getContactId();
        }
        return null;
    }

    protected function createDns($domainname = null) {
        $domainname = $this->createDomain($domainname);
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $records[] = ['name' => $domainname, 'type' => 'A', 'content' => '127.0.0.1', 'ttl' => 3600];
        $create = new Metaregistrar\EPP\metaregCreateDnsRequest($domain, $records);
        //echo $create->saveXML();
        $response = $this->conn->writeandread($create);
        //echo $response->saveXML();
        $this->assertInstanceOf('Metaregistrar\EPP\metaregCreateDnsResponse', $response);
        /* @var $response Metaregistrar\EPP\metaregCreateDnsResponse */
        return $domainname;
    }

    protected function createNLDomain($domainname = null, $extension='.frl') {
        // If no domain name was given, test with a random .FRL domain name
        if (!$domainname) {
            $domainname = $this->randomstring(20).$extension;
        }
        $contactid = $this->createNLContact();
        $domain = new \Metaregistrar\EPP\eppDomain($domainname);
        $domain->setPeriod(1);
        $domain->setRegistrant($contactid);
        $domain->setAuthorisationCode('fubar01');
        $domain->addContact(new \Metaregistrar\EPP\eppContactHandle($contactid, \Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_ADMIN));
        $domain->addContact(new \Metaregistrar\EPP\eppContactHandle($contactid, \Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_TECH));
        $domain->addContact(new \Metaregistrar\EPP\eppContactHandle($contactid, \Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_BILLING));
        $create = new \Metaregistrar\EPP\eppCreateDomainRequest($domain);
        if ($response = $this->conn->request($create)) {
            /* @var $response \Metaregistrar\EPP\eppCreateDomainResponse */
            return $response->getDomainName();
        }
        return null;
    }

    protected function createDomain($domainname = null, $extension='.frl') {
        // If no domain name was given, test with a random .FRL domain name
        if (!$domainname) {
            $domainname = $this->randomstring(20).$extension;
        }
        $contactid = $this->createContact();
        $domain = new \Metaregistrar\EPP\eppDomain($domainname);
        $domain->setPeriod(1);
        $domain->setRegistrant($contactid);
        $domain->setAuthorisationCode('fubar01');
        $domain->addContact(new \Metaregistrar\EPP\eppContactHandle($contactid, \Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_ADMIN));
        $domain->addContact(new \Metaregistrar\EPP\eppContactHandle($contactid, \Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_TECH));
        $domain->addContact(new \Metaregistrar\EPP\eppContactHandle($contactid, \Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_BILLING));
        $create = new \Metaregistrar\EPP\eppCreateDomainRequest($domain);
        if ($response = $this->conn->request($create)) {
            /* @var $response \Metaregistrar\EPP\eppCreateDomainResponse */
            return $response->getDomainName();
        }
        return null;
    }

    protected function deleteDomain($domainname) {
        $domain = new \Metaregistrar\EPP\eppDomain($domainname);
        $delete = new \Metaregistrar\EPP\eppDeleteDomainRequest($domain);
        if ($response = $this->conn->request($delete)) {
            if ($response->getResultCode()==1000) {
                return true;
            }
        }
        return false;
    }

    /**
     * Gets information on a contact handle
     * @param $contacthandle
     * @return \Metaregistrar\EPP\eppInfoContactResponse|\Metaregistrar\EPP\eppResponse
     * @throws \Metaregistrar\EPP\eppException
     */
    protected function getContactInfo($contacthandle) {
        $epp = new Metaregistrar\EPP\eppContactHandle($contacthandle);
        $info = new Metaregistrar\EPP\eppInfoContactRequest($epp);
        if ((($response = $this->conn->writeandread($info)) instanceof Metaregistrar\EPP\eppInfoContactResponse) && ($response->Success())) {
            /* @var $response Metaregistrar\EPP\eppInfoContactResponse */
            return $response;
        }
        return null;
    }

}