<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class eppCheckHostTest extends eppTestCase {

    /**
     * Test if random contact handle is available
     * Expects a standard result for a free contact handle
     */
    public function testCheckHostAvailable() {
        $hostname = 'ns1.'.self::randomstring(30).'.frl';
        $host = new Metaregistrar\EPP\eppHost($hostname);
        $this->assertInstanceOf('Metaregistrar\EPP\eppHost',$host);
        $check = new Metaregistrar\EPP\eppCheckRequest($host);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCheckRequest',$check);
        $response = $this->conn->writeandread($check);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCheckResponse',$response);
        if ($response instanceof Metaregistrar\EPP\eppCheckResponse) {
            $this->assertTrue($response->Success());
            if ($response->Success()) {
                $checks = $response->getCheckedHosts();
                $this->assertCount(1,$checks);
                $this->assertArrayHasKey($hostname,$checks);
                $this->assertTrue($checks[$hostname]);
            }
        }
    }


    /**
     * Test if random contact handle is available
     * Expects a standard result for a free contact handle
     */
    public function testCheckHostIllegarChars() {
        $hostname = 'ns1.test%@test.frl';
        $host = new Metaregistrar\EPP\eppHost($hostname);
        $this->assertInstanceOf('Metaregistrar\EPP\eppHost',$host);
        $check = new Metaregistrar\EPP\eppCheckRequest($host);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCheckRequest',$check);
        //echo $check->saveXML();
        $response = $this->conn->writeandread($check);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCheckResponse',$response);
        if ($response instanceof Metaregistrar\EPP\eppCheckResponse) {
            //echo $response->saveXML();
            //$this->setExpectedException('Metaregistrar\EPP\eppException');
            $this->assertTrue($response->Success());

        }
    }
}
