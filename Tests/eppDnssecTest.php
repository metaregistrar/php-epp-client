<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class eppDnssecTest extends eppTestCase {

    public function testCreateWithDnssecSuccess() {
        $contactid = $this->createContact();
        $domain = new \Metaregistrar\EPP\eppDomain($this->randomstring(20).'.frl');
        $domain->setPeriod(1);
        $domain->setRegistrant($contactid);
        $domain->setAuthorisationCode('fubar');
        $domain->addHost(new \Metaregistrar\EPP\eppHost('ns1.metaregistrar.com'));
        $domain->addHost(new \Metaregistrar\EPP\eppHost('ns2.metaregistrar.com'));
        $secdns = new \Metaregistrar\EPP\eppSecdns();
        $secdns->setKey('256', '8', 'AwEAAbWM8nWQZbDZgJjyq+tLZwPLEXfZZjfvlRcmoAVZHgZJCPn/Ytu/iOsgci+yWgDT28ENzREAoAbKMflFFdhc5DNV27TZxhv8nMo9n2f+cyyRKbQ6oIAvMl7siT6WxrLxEBIMyoyFgDMbqGScn9k19Ppa8fwnpJgv0VUemfxGqHH9');
        $domain->addSecdns($secdns);
        $create = new \Metaregistrar\EPP\eppCreateDomainRequest($domain);
        $response = $this->conn->writeandread($create);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCreateDomainResponse',$response);
        /* @var $response Metaregistrar\EPP\eppCreateDomainResponse */
        $this->assertTrue($response->Success());
        $this->assertEquals('Command completed successfully',$response->getResultMessage());
        $this->assertEquals(1000,$response->getResultCode());
    }


}