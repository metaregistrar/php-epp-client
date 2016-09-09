<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class eppTransferDomainTest extends eppTestCase {
    /**
     * Test succesful contact info
     * @throws \Metaregistrar\EPP\eppException
     */
    public function testTransferQueryDomainSuccess() {
        $domainname = $this->randomstring(20).'.frl';
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $domain->setAuthorisationCode('dkejdkejdekjkd');
        $transfer = new Metaregistrar\EPP\eppTransferRequest(Metaregistrar\EPP\eppTransferRequest::OPERATION_QUERY,$domain);
        echo $transfer->saveXML();
        $response = $this->conn->writeandread($transfer);
        $this->assertInstanceOf('Metaregistrar\EPP\eppTransferResponse',$response);
        /* @var $response Metaregistrar\EPP\eppInfoDomainResponse */
        $this->assertTrue($response->Success());
        $this->assertEquals('Command completed successfully',$response->getResultMessage());
        $this->assertEquals(1000,$response->getResultCode());
    }
}