<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class eppCreateTest extends eppTestCase {

    /**
     * Tests the class factory
     */
    public function testCreateInterface() {
        $conn = Metaregistrar\EPP\eppConnection::create('Tests/testsetup.ini');
        $this->assertInstanceOf('Metaregistrar\EPP\metaregEppConnection',$conn);
        /* @var $conn Metaregistrar\EPP\metaregEppConnection */
        $this->assertEquals($conn->getHostname(),'ssl://epp.test2.metaregistrar.com');
        $this->assertEquals($conn->getPort(),7443);
    }

    public function testCreateInterfaceFileNotFound() {
        $this->setExpectedException('Metaregistrar\EPP\eppException','File not found: dejdkjedkjejd.ini');
        Metaregistrar\EPP\eppConnection::create('dejdkjedkjejd.ini');
    }

    public function testCreateInterfaceNoParam() {
        $this->setExpectedException('Metaregistrar\EPP\eppException','Configuration file not specified on eppConnection:create');
        Metaregistrar\EPP\eppConnection::create(null);
    }

}