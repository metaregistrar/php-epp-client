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
            echo "ERROR: " . $e->getMessage() . "\n\n";
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

}