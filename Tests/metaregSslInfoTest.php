<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class metaregSslInfoTest extends eppTestCase {
    /**
     * Test info of ssl certificate
     */

    public function testInfoSslCert() {
        $info = new \Metaregistrar\EPP\metaregSslInfoRequest(1);
        //echo $info->saveXML();
        if ($response = $this->conn->request($info)) {
            /* @var $response \Metaregistrar\EPP\metaregSslInfoResponse */
            $this->assertEquals('1000',$response->getResultCode());
            $this->assertGreaterThan(0,$response->getCertificateId());
            $this->assertStringMatchesFormat('%d_%s',$response->getProvisioningId());
            $this->assertEquals('example.com',$response->getCommonName());
            $this->assertEquals('new',$response->getStatus());
            $this->assertEquals(date('Y'),date('Y',strtotime($response->getCreateDate())));
            $this->assertEquals(date('m'),date('m',strtotime($response->getCreateDate())));
            $this->assertEquals(date('d'),date('d',strtotime($response->getCreateDate())));
        }
    }

    public function testInfoSslCertNotExists() {
        $this->setExpectedException('\Metaregistrar\EPP\eppException','Error 2303: Object does not exist');
        $info = new \Metaregistrar\EPP\metaregSslInfoRequest(439838283);
        $this->conn->request($info);
    }

}