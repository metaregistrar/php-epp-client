<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class eppDeleteDnsTest extends eppTestCase
{
    /**
     * Test successful dns delete
     */
    public function testDeleteDnsSuccess()
    {
        $domainname = $this->createDns();
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $delete = new Metaregistrar\EPP\metaregDeleteDnsRequest($domain);
        $response = $this->conn->writeandread($delete);
        $this->assertInstanceOf('Metaregistrar\EPP\metaregDeleteDnsResponse', $response);
        /* @var $response Metaregistrar\EPP\metaregDeleteDnsResponse */
        $this->assertTrue($response->Success());
        $this->assertEquals('Command completed successfully', $response->getResultMessage());
        $this->assertEquals(1000, $response->getResultCode());

    }


    /**
     * Test failed dns delete
     * @throws \Metaregistrar\EPP\eppException;
     */
    public function testDeleteDnsWrongDomain()
    {
        $domainname = $this->randomstring(20).'.frl';
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $delete = new Metaregistrar\EPP\metaregDeleteDnsRequest($domain);
        $response = $this->conn->writeandread($delete);
        $this->assertInstanceOf('Metaregistrar\EPP\metaregDeleteDnsResponse', $response);
        /* @var $response Metaregistrar\EPP\metaregDeleteDnsResponse */
        $this->setExpectedException('Metaregistrar\EPP\eppException', 'Error 2201: Authorization error; Domain is not yours');
        $this->assertFalse($response->Success());

    }


    /**
     * Test failed dns delete
     * @throws \Metaregistrar\EPP\eppException;
     */
    public function testDeleteDnsDomainEmpty()
    {
        $domainname = $this->createDomain();
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $delete = new Metaregistrar\EPP\metaregDeleteDnsRequest($domain);
        $response = $this->conn->writeandread($delete);
        $this->assertInstanceOf('Metaregistrar\EPP\metaregDeleteDnsResponse', $response);
        /* @var $response Metaregistrar\EPP\metaregDeleteDnsResponse */
        $this->setExpectedException('Metaregistrar\EPP\eppException', 'Error 2303: Object does not exist; The domain '.$domainname.' does not have a zone.');
        $this->assertFalse($response->Success());

    }
}