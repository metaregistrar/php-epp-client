<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class eppInfoContactTest extends eppTestCase {
    /**
     * Test succesful contact info
     * @throws \Metaregistrar\EPP\eppException
     */
    public function testInfoContactSuccess() {
        $contactid = 100;
        $contact = new Metaregistrar\EPP\eppContactHandle($contactid);
        $info = new Metaregistrar\EPP\eppInfoContactRequest($contact);
        $response = $this->conn->writeandread($info);
        $this->assertInstanceOf('Metaregistrar\EPP\eppInfoContactResponse',$response);
        /* @var $response Metaregistrar\EPP\eppInfoContactResponse */
        $this->assertTrue($response->Success());
        $this->assertEquals('Command completed successfully',$response->getResultMessage());
        $this->assertEquals(1000,$response->getResultCode());
    }

    /**
     * Test succesful contact info giving an authcode
     * @throws \Metaregistrar\EPP\eppException
     */
    public function testInfoContactWithAuthcode() {
        $contactid = 100;
        $contact = new Metaregistrar\EPP\eppContactHandle($contactid);
        $contact->setPassword('foobar');
        $info = new Metaregistrar\EPP\eppInfoContactRequest($contact);
        $response = $this->conn->writeandread($info);
        $this->assertInstanceOf('Metaregistrar\EPP\eppInfoContactResponse',$response);
        /* @var $response Metaregistrar\EPP\eppInfoContactResponse */
        $this->assertTrue($response->Success());
        $this->assertEquals('Command completed successfully',$response->getResultMessage());
        $this->assertEquals(1000,$response->getResultCode());
    }


}