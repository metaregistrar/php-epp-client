<?php
require(dirname(__FILE__).'/../autoloader.php');

class eppTestCase extends PHPUnit_Framework_TestCase {
    /**
     * @var Metaregistrar\EPP\eppConnection
     *
     */
    protected $conn;

    protected function setUp() {
        $this->conn = self::setupConnection();
    }

    protected function tearDown() {
        self::teardownConncection($this->conn);
    }

    private static function setupConnection() {
        try {
            $conn = new Metaregistrar\EPP\metaregEppConnection();
            if ($conn->setConnectionDetails(dirname(__FILE__).'/testsetup.ini')){
                if ($conn->connect()) {
                    if ($conn->login()) {
                        return $conn;
                    }
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
     * Create a contact to be used in create contact or create domain testing
     * @return string
     * @throws \Metaregistrar\EPP\eppException
     */
    protected function createHost() {
        $hostname = 'ns1.test.frl';
        $host = new Metaregistrar\EPP\eppHost($hostname);
        $create = new Metaregistrar\EPP\eppCreateHostRequest($host);
        if ((($response = $this->conn->writeandread($create)) instanceof Metaregistrar\EPP\eppCreateHostResponse) && ($response->Success())) {
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
    protected function createContact() {
        $name = 'Test name';
        $city = 'Test city';
        $country = 'NL';
        $organization = 'Test company';
        $address = 'Teststreet 1';
        $province = 'CA';
        $postcode = '00000';
        $email = 'test@test.com';
        $telephone = '+1.55500000';
        $password = self::randomstring(8);
        $postalinfo = new Metaregistrar\EPP\eppContactPostalInfo($name, $city, $country, $organization, $address, $province, $postcode, Metaregistrar\EPP\eppContact::TYPE_LOC);
        $contactinfo = new Metaregistrar\EPP\eppContact($postalinfo, $email, $telephone);
        $contactinfo->setPassword($password);
        $create = new Metaregistrar\EPP\eppCreateContactRequest($contactinfo);
        if ((($response = $this->conn->writeandread($create)) instanceof Metaregistrar\EPP\eppCreateContactResponse) && ($response->Success())) {
            /* @var $response Metaregistrar\EPP\eppCreateContactResponse */
            return $response->getContactId();
        }
        return null;
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