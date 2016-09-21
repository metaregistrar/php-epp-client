<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class eppCreateDomainTest extends eppTestCase {

    public function testCreateDomainWithRegistrant() {
        $contactid = $this->createContact();
        $domain = new \Metaregistrar\EPP\eppDomain($this->randomstring(20).'.frl');
        $domain->setPeriod(1);
        $domain->setRegistrant($contactid);
        $domain->setAuthorisationCode('fubar');
        $create = new \Metaregistrar\EPP\eppCreateDomainRequest($domain);
        $response = $this->conn->writeandread($create);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCreateDomainResponse',$response);
        /* @var $response Metaregistrar\EPP\eppCreateDomainResponse */
        $this->assertTrue($response->Success());
        $this->assertEquals('Command completed successfully',$response->getResultMessage());
        $this->assertEquals(1000,$response->getResultCode());
    }

    public function testCreateDomainWithoutRegistrant() {
        $domain = new \Metaregistrar\EPP\eppDomain($this->randomstring(20).'.frl');
        $domain->setPeriod(1);
        $domain->setAuthorisationCode('fubar');
        $this->setExpectedException('Metaregistrar\EPP\eppException','No valid registrant in create domain request');
        new \Metaregistrar\EPP\eppCreateDomainRequest($domain);
    }


    public function testCreateDomainWithoutAuthcode() {
        $contactid = $this->createContact();
        $domain = new \Metaregistrar\EPP\eppDomain($this->randomstring(20).'.frl');
        $domain->setPeriod(1);
        $domain->setRegistrant($contactid);
        $create = new \Metaregistrar\EPP\eppCreateDomainRequest($domain);
        $response = $this->conn->writeandread($create);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCreateDomainResponse',$response);
        /* @var $response Metaregistrar\EPP\eppCreateDomainResponse */
        $this->setExpectedException('Metaregistrar\EPP\eppException',"Error 2001: Command syntax error; value:line: 2 column: 689 cvc-complex-type.2.4.b: The content of element 'domain:create' is not complete. One of '{\"urn:ietf:params:xml:ns:domain-1.0\":contact, \"urn:ietf:params:xml:ns:domain-1.0\":authInfo}' is expected.");
        $this->assertFalse($response->Success());
    }


}