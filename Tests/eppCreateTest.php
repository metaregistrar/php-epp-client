<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class eppCreateTest extends eppTestCase {

    /**
     * Tests the class factory
     */
    public function testCreateInterface() {
        $conn = Metaregistrar\EPP\eppConnection::create('./testsetup.ini');
        $this->assertInstanceOf('Metaregistrar\EPP\metaregEppConnection',$conn);
        /* @var $conn Metaregistrar\EPP\metaregEppConnection */
        $this->assertEquals($conn->getHostname(),'ssl://epp.test2.metaregistrar.com');
        $this->assertEquals($conn->getPort(),7443);
    }
}