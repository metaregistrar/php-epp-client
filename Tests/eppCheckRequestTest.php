<?php
include_once(dirname(__FILE__).'/testsetup.php');

class eppCheckRequestTest extends PHPUnit_Framework_TestCase {
    /**
     * @var Metaregistrar\EPP\eppConnection
     */
    protected $conn;

    protected function setUp() {
        $this->conn = testSetup::setupConnection();
    }

    protected function tearDown() {
        testSetup::teardownConncection($this->conn);
    }

    /**
     * Test if random domain name is available
     * Expects a standard result for a free domainname
     */
    public function testCheckDomainAvailable() {
        $domainname = testSetup::randomString(30).'.frl';
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $this->assertInstanceOf('Metaregistrar\EPP\eppDomain',$domain);
        $check = new Metaregistrar\EPP\eppCheckDomainRequest($domain);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCheckDomainRequest',$check);
        $response = $this->conn->writeandread($check);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCheckDomainResponse',$response);
        if ($response instanceof Metaregistrar\EPP\eppCheckDomainResponse) {
            $this->assertTrue($response->Success());
            if ($response->Success()) {
                $checks = $response->getCheckedDomains();
                $this->assertCount(1,$checks);
                $check = $checks[0];
                $this->assertArrayHasKey('domainname',$check);
                $this->assertSame($domainname,$check['domainname']);
                $this->assertArrayHasKey('available',$check);
                $this->assertTrue($check['available']);
                $this->assertArrayHasKey('reason',$check);
                $this->assertNull($check['reason']);
            }
        }
    }

    /**
     * Test if nic.frl domain name is taken
     * Expects a standard result for a taken domain name
     */
    public function testCheckDomainTaken() {
        $domainname = 'nic.frl';
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $this->assertInstanceOf('Metaregistrar\EPP\eppDomain',$domain);
        $check = new Metaregistrar\EPP\eppCheckRequest($domain);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCheckRequest',$check);
        $response = $this->conn->writeandread($check);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCheckResponse',$response);
        if ($response instanceof Metaregistrar\EPP\eppCheckResponse) {
            $this->assertTrue($response->Success());
            if ($response->Success()) {
                $checks = $response->getCheckedDomains();
                $this->assertCount(1,$checks);
                $check = $checks[0];
                $this->assertArrayHasKey('domainname',$check);
                $this->assertSame($domainname,$check['domainname']);
                $this->assertArrayHasKey('available',$check);
                $this->assertFalse($check['available']);
                $this->assertArrayHasKey('reason',$check);
                $this->assertSame('Domain is in use.',$check['reason']);
            }
        }
    }

    /**
     * Test if test.frl domain name is reserved
     * Expects a standard result for a taken domain name
     */
    public function testCheckDomainReserved() {
        $domainname = 'test.frl';
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $this->assertInstanceOf('Metaregistrar\EPP\eppDomain',$domain);
        $check = new Metaregistrar\EPP\eppCheckRequest($domain);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCheckRequest',$check);
        $response = $this->conn->writeandread($check);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCheckResponse',$response);
        if ($response instanceof Metaregistrar\EPP\eppCheckResponse) {
            $this->assertTrue($response->Success());
            if ($response->Success()) {
                $checks = $response->getCheckedDomains();
                $this->assertCount(1,$checks);
                $check = $checks[0];
                $this->assertArrayHasKey('domainname',$check);
                $this->assertSame($domainname,$check['domainname']);
                $this->assertArrayHasKey('available',$check);
                $this->assertFalse($check['available']);
                $this->assertArrayHasKey('reason',$check);
                $this->assertSame('Domain is in use.',$check['reason']);
            }
        }
    }

    /**
     * Test if test.frl domain name with illegal characters
     * Expects an error result domainname is invalid
     */
    public function testCheckDomainIllegalChars() {
        $domainname = 'test%test.frl';
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $this->assertInstanceOf('Metaregistrar\EPP\eppDomain',$domain);
        $check = new Metaregistrar\EPP\eppCheckRequest($domain);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCheckRequest',$check);
        $response = $this->conn->writeandread($check);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCheckResponse',$response);
        if ($response instanceof Metaregistrar\EPP\eppCheckResponse) {
            $this->assertTrue($response->Success());
            if ($response->Success()) {
                $checks = $response->getCheckedDomains();
                $this->assertCount(1,$checks);
                $check = $checks[0];
                $this->assertArrayHasKey('domainname',$check);
                $this->assertSame($domainname,$check['domainname']);
                $this->assertArrayHasKey('available',$check);
                $this->assertFalse($check['available']);
                $this->assertArrayHasKey('reason',$check);
                $this->assertSame('Domainname is invalid.',$check['reason']);
            }
        }
    }

    /**
     * Test if test.frl domain name with illegal characters
     * Expects an error result domainname is invalid
     */
    public function testCheckDomainUnknownExtension() {
        $domainname = testSetup::randomString(30).'.abracadabra';
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $this->assertInstanceOf('Metaregistrar\EPP\eppDomain',$domain);
        $check = new Metaregistrar\EPP\eppCheckRequest($domain);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCheckRequest',$check);
        $response = $this->conn->writeandread($check);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCheckResponse',$response);
        if ($response instanceof Metaregistrar\EPP\eppCheckResponse) {
            $this->assertTrue($response->Success());
            if ($response->Success()) {
                $checks = $response->getCheckedDomains();
                $this->assertCount(1,$checks);
                $check = $checks[0];
                $this->assertArrayHasKey('domainname',$check);
                $this->assertSame($domainname,$check['domainname']);
                $this->assertArrayHasKey('available',$check);
                $this->assertFalse($check['available']);
                $this->assertArrayHasKey('reason',$check);
                $this->assertSame('Domainname is invalid.',$check['reason']);
            }
        }
    }


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


    /**
     * Test if random contact handle is available
     * Expects a standard result for a free contact handle
     */
    public function testCheckHostAvailable() {
        $hostname = 'ns1.'.testSetup::randomString(30).'.frl';
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
        $hostname = 'ns1.test%test.frl';
        $host = new Metaregistrar\EPP\eppHost($hostname);
        $this->assertInstanceOf('Metaregistrar\EPP\eppHost',$host);
        $check = new Metaregistrar\EPP\eppCheckRequest($host);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCheckRequest',$check);
        $response = $this->conn->writeandread($check);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCheckResponse',$response);
        if ($response instanceof Metaregistrar\EPP\eppCheckResponse) {
            $this->setExpectedException('Metaregistrar\EPP\eppException');
            $this->assertTrue($response->Success());

        }
    }
}