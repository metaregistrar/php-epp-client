<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class eppUpdateDnsTest extends eppTestCase
{
    /**
     * Test successful dns update
     */
    public function testUpdateDnsSuccess() {
        $domainname = $this->createDns();
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $adds[] = ['type' => 'AAAA', 'name' => $domainname, 'content' => '2001:828:12ed:3:3c44:a46a:727:6684', 'ttl' => 3600];
        $dels[] = ['type'=>'A', 'name'=>$domainname,'content'=>'127.0.0.1','ttl'=>3600];
        $update= new Metaregistrar\EPP\metaregUpdateDnsRequest($domain,$adds,$dels,null);
        $response = $this->conn->writeandread($update);
        $this->assertInstanceOf('Metaregistrar\EPP\metaregUpdateDnsResponse', $response);
        /* @var $response Metaregistrar\EPP\metaregDeleteDnsResponse */
        $this->assertTrue($response->Success());
        $this->assertEquals('Command completed successfully', $response->getResultMessage());
        $this->assertEquals(1000, $response->getResultCode());
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $info = new Metaregistrar\EPP\metaregInfoDnsRequest($domain);
        $response = $this->conn->writeandread($info);
        /* @var $response Metaregistrar\EPP\metaregInfoDnsResponse */
        $content = $response->getContent();
        foreach ($content as $record) {
            $this->assertEquals($record['name'], $domainname);
            $this->assertEquals($record['priority'], '');
            $this->assertEquals($record['ttl'], '3600');
            $this->assertNotEquals($record['type'],'A');
            if ($record['type'] == 'AAAA') {
                $this->assertEquals($record['content'], '2001:828:12ed:3:3c44:a46a:727:6684');
            }
        }
    }

    /**
     * Test successful dns update
     */
    public function testSignDnsSuccess() {
        $domainname = $this->createDns();
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $update= new Metaregistrar\EPP\metaregUpdateDnsRequest($domain,null,null,true);
        $response = $this->conn->writeandread($update);
        $this->assertInstanceOf('Metaregistrar\EPP\metaregUpdateDnsResponse', $response);
        /* @var $response Metaregistrar\EPP\metaregDeleteDnsResponse */
        $this->assertTrue($response->Success());
        $this->assertEquals('Command completed successfully;sign pending', $response->getResultMessage());
        $this->assertEquals(1001, $response->getResultCode());
        echo "Test is sleeping 5 minutes until the signing process is complete\n";
        // Sleep until the domain name has been signed
        sleep(300);
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $info = new Metaregistrar\EPP\metaregInfoDnsRequest($domain);
        $response = $this->conn->writeandread($info);
        /* @var $response Metaregistrar\EPP\metaregInfoDnsResponse */
        echo $response->saveXML();
    }
}