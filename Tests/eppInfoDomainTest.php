<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class eppInfoDomainTest extends eppTestCase {

    private $extension = '.nl';

    /**
     * Test succesful domain info
     * @throws \Metaregistrar\EPP\eppException
     */
    public function testInfoDomainSuccess() {
        $domainname = $this->createDomain(null,$this->extension);
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $info = new Metaregistrar\EPP\eppInfoDomainRequest($domain);
        $response = $this->conn->writeandread($info);
        $this->assertInstanceOf('Metaregistrar\EPP\eppInfoDomainResponse',$response);
        /* @var $response Metaregistrar\EPP\eppInfoDomainResponse */
        $this->assertTrue($response->Success());
        $this->assertEquals('Command completed successfully',$response->getResultMessage());
        $this->assertEquals(1000,$response->getResultCode());
    }

    /**
     * Test succesful contact info giving an authcode
     * @throws \Metaregistrar\EPP\eppException
     */
    public function testInfoDomainWithAuthcode() {
        $domainname = $this->createDomain(null,$this->extension);
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $domain->setAuthorisationCode('foorbar');
        $info = new Metaregistrar\EPP\eppInfoDomainRequest($domain);
        //$info->dumpContents();
        $response = $this->conn->writeandread($info);
        $this->assertInstanceOf('Metaregistrar\EPP\eppInfoDomainResponse',$response);
        /* @var $response Metaregistrar\EPP\eppInfoDomainResponse */
        $this->assertTrue($response->Success());
        $this->assertEquals('Command completed successfully',$response->getResultMessage());
        $this->assertEquals(1000,$response->getResultCode());
    }


    /**
     * Test succesful contact info giving an authcode
     * @throws \Metaregistrar\EPP\eppException
     */
    public function testInfoDomainWithoutAuthcode() {
        $domainname = $this->createDomain($this->randomstring(20).$this->extension);
        $info = new Metaregistrar\EPP\metaregEppAuthcodeRequest(new Metaregistrar\EPP\eppDomain($domainname));
        $response = $this->conn->writeandread($info);
        $this->assertInstanceOf('Metaregistrar\EPP\eppInfoDomainResponse',$response);
        /* @var $response Metaregistrar\EPP\eppInfoDomainResponse */
        $this->assertTrue($response->Success());
        $this->assertEquals('Command completed successfully',$response->getResultMessage());
        $this->assertEquals(1000,$response->getResultCode());
    }


    function testInfoDomainDnssec() {
        $domainname = $this->createDomain(null,$this->extension);
        $add = new \Metaregistrar\EPP\eppDomain($domainname);
        $sec = new \Metaregistrar\EPP\eppSecdns();
        $sec->setKey('256', '8', 'AwEAAbWM8nWQZbDZgJjyq+tLZwPLEXfZZjfvlRcmoAVZHgZJCPn/Ytu/iOsgci+yWgDT28ENzREAoAbKMflFFdhc5DNV27TZxhv8nMo9n2f+cyyRKbQ6oIAvMl7siT6WxrLxEBIMyoyFgDMbqGScn9k19Ppa8fwnpJgv0VUemfxGqHH9');
        $add->addSecdns($sec);
        $update = new \Metaregistrar\EPP\eppDnssecUpdateDomainRequest($domainname, $add);
        $this->conn->request($update);
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $info = new Metaregistrar\EPP\eppInfoDomainRequest($domain);
        $response = $this->conn->writeandread($info);
        $this->assertInstanceOf('Metaregistrar\EPP\eppInfoDomainResponse',$response);
        /* @var $response Metaregistrar\EPP\eppDnssecInfoDomainResponse */
        //$keys = $response->getKeys();
        //var_dump($keys);
        //$data = $response->getKeydata();
        //var_dump($data);
        $this->assertTrue($response->Success());
        $this->assertEquals('Command completed successfully',$response->getResultMessage());
        $this->assertEquals(1000,$response->getResultCode());
    }

    /**
     * Test that cannot be performed using the EPP client, because the client will not allow this
     * This test should fail
     */
    public function testInfoDomainEmptyContact() {
        $xml = '<?xml version="1.0" encoding="utf-8"?>
<epp xmlns:host="urn:ietf:params:xml:ns:host-1.0" xmlns:domain="urn:ietf:params:xml:ns:domain-1.0" xmlns:contact="urn:ietf:params:xml:ns:contact-1.0" xmlns:obj="urn:ietf:params:xml:ns:obj-1.0" xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <response>
    <result code="1000">
      <msg>Command completed successfully</msg>
    </result>
    <resData>
      <domain:infData>
        <domain:name>DOMAIN_NAME</domain:name>
        <domain:registrylock>0</domain:registrylock>
        <domain:autorenew>0</domain:autorenew>
        <domain:status s="Granted"></domain:status>
        <domain:registrant>CXXXXX</domain:registrant>
        <domain:contact type="admin"></domain:contact>
        <domain:contact type="billing"></domain:contact>
        <domain:contact type="tech">CXXXXX</domain:contact>
        <domain:ns>
          <domain:hostObj>NAMESERVER1</domain:hostObj>
          <domain:hostObj>NAMESERVER2</domain:hostObj>
        </domain:ns>
        <domain:clID>CXXXX</domain:clID>
        <domain:crID>CXXXX</domain:crID>
        <domain:crDate>2016-07-13T20:59:52.097</domain:crDate>
        <domain:exDate>2017-07-13T20:59:52.02</domain:exDate>
        <domain:authInfo></domain:authInfo>
      </domain:infData>
    </resData>
    <trID>
      <svTRID>1234567</svTRID>
    </trID>
  </response>
</epp>';
        $infodomain = new \Metaregistrar\EPP\eppInfoDomainResponse;
        $infodomain->loadXML($xml);
        $contacts = $infodomain->getDomainContacts();
        $this->assertEquals(count($contacts),1);
        $this->assertEquals($contacts[0]->getContactHandle(),'CXXXXX');
        $this->assertEquals($contacts[0]->getContactType(),'tech');
    }

}