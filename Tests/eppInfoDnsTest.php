<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class eppInfoDnsTest extends eppTestCase {
    /**
     * Test successful dns info
     * @throws \Metaregistrar\EPP\eppException
     */
    public function testInfoDnsSuccess() {
        $domainname = $this->createDns();
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $info = new Metaregistrar\EPP\metaregInfoDnsRequest($domain);
        #echo $info->saveXML();
        $response = $this->conn->writeandread($info);
        #echo $response->saveXML();
        $this->assertInstanceOf('Metaregistrar\EPP\metaregInfoDnsResponse', $response);
        /* @var $response Metaregistrar\EPP\metaregInfoDnsResponse */
        $this->assertTrue($response->Success());
        $this->assertEquals('Command completed successfully', $response->getResultMessage());
        $this->assertEquals(1000, $response->getResultCode());
        $content = $response->getContent();
        foreach ($content as $record) {
            $this->assertEquals($record['name'], $domainname);
            $this->assertEquals($record['priority'], '');
            $this->assertEquals($record['ttl'], '3600');
            if ($record['type'] == 'A') {
                $this->assertEquals($record['content'], '127.0.0.1');

            }
        }
    }

    /**
     * Test dns info when domain has no zone
     * @throws \Metaregistrar\EPP\eppException
     */
    public function testInfoDnsNoZone() {
        $domainname = $this->createDomain();
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $info = new Metaregistrar\EPP\metaregInfoDnsRequest($domain);
        $response = $this->conn->writeandread($info);
        $this->assertInstanceOf('Metaregistrar\EPP\metaregInfoDnsResponse', $response);
        /* @var $response Metaregistrar\EPP\metaregInfoDnsResponse */
        $this->setExpectedException('Metaregistrar\EPP\eppException','The domain '.$domainname.' does not have a zone.');
        $this->assertTrue($response->Success());
    }
}