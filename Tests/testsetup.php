<?php
require(dirname(__FILE__).'/../autoloader.php');

class testSetup {

    public static function setupConnection() {
        try {
            $conn = new Metaregistrar\EPP\metaregEppConnection();
            if ($conn->setConnectionDetails('/Users/Ewout/Documents/GitProjects/php-epp-client/Tests/testsetup.ini')){
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
    public static function teardownConncection($conn) {
        if ($conn) {
            $conn->logout();
        }
    }

    public static function randomstring($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
