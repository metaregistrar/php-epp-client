<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class eppCheckContactTest extends eppTestCase {

    /**
     * Test if random contact handle is available
     * Expects a standard result for a free contact handle
     */
    public function testCheckContactAvailable() {
        $handleid = 999999999;
        $contact = new Metaregistrar\EPP\eppContactHandle($handleid);
        $this->assertInstanceOf('Metaregistrar\EPP\eppContactHandle',$contact);
        $check = new Metaregistrar\EPP\eppCheckRequest($contact);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCheckRequest',$check);
        $response = $this->conn->writeandread($check);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCheckResponse',$response);
        if ($response instanceof Metaregistrar\EPP\eppCheckResponse) {
            $this->assertTrue($response->Success());
            if ($response->Success()) {
                $checks = $response->getCheckedContacts();
                $this->assertCount(1,$checks);
                $this->assertArrayHasKey($handleid,$checks);
                $this->assertTrue($checks[$handleid]);
            }
        }
    }
}