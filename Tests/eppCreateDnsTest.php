<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class eppCreateDnsTest extends eppTestCase
{
    /**
     * Test successful dns create
     */
    public function testCreateDnsSuccess()
    {
        $domainname = $this->createDomain();
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $records[] = ['type' => 'A', 'name' => $domainname, 'content' => '127.0.0.1', 'ttl' => 3600];
        $create = new Metaregistrar\EPP\metaregCreateDnsRequest($domain, $records);
        $response = $this->conn->writeandread($create);
        $this->assertInstanceOf('Metaregistrar\EPP\metaregCreateDnsResponse', $response);
        /* @var $response Metaregistrar\EPP\metaregCreateDnsResponse */
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
     * Test failed dns create
     * @throws \Metaregistrar\EPP\eppException
     */
    public function testCreateDnsWrongType()
    {
        $domainname = $this->createDomain();
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $records[] = ['type' => 'GARBAGE', 'name' => $domainname, 'content' => '127.0.0.1', 'ttl' => 3600];
        $create = new Metaregistrar\EPP\metaregCreateDnsRequest($domain, $records);
        $response = $this->conn->writeandread($create);
        $this->setExpectedException('Metaregistrar\EPP\eppException', 'Error 2004: Parameter value range error; \'GARBAGE\' records are currently unsupported for writing');
        $this->assertFalse($response->Success());
    }

    /**
     * Test failed dns create
     * @throws \Metaregistrar\EPP\eppException
     */
    public function testCreateDnsWrongIP()
    {
        $domainname = $this->createDomain();
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $records[] = ['type' => 'A', 'name' => $domainname, 'content' => 'GARBAGE', 'ttl' => 3600];
        $create = new Metaregistrar\EPP\metaregCreateDnsRequest($domain, $records);
        $response = $this->conn->writeandread($create);
        $this->setExpectedException('Metaregistrar\EPP\eppException', 'Error 2004: Parameter value range error; Ip \'GARBAGE\' is not a valid IPV4 address for an A record');
        $this->assertFalse($response->Success());
    }

    /**
     * Test failed dns create
     * @throws \Metaregistrar\EPP\eppException
     */
    public function testCreateDnsWrongDomain() {
        $domainname = $this->createDomain();
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $records[] = ['type' => 'A', 'name' => 'wrongdomainname.frl', 'content' => '127.0.0.1', 'ttl' => 3600];
        $create = new Metaregistrar\EPP\metaregCreateDnsRequest($domain, $records);
        $response = $this->conn->writeandread($create);
        $this->setExpectedException('Metaregistrar\EPP\eppException', 'Error 2004: Parameter value range error; Name field wrongdomainname.frl is not a valid name in an A type record');
        $this->assertFalse($response->Success());
    }
}