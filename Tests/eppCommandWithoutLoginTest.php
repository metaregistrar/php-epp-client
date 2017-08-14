<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class eppCommandWithoutLoginTest extends eppTestCase {

    public function testCommandWithoutLogin()
    {
        $conn = new Metaregistrar\EPP\metaregEppConnection(false);
        $conn->setHostname('eppltest1.metaregistrar.com');
        $conn->setPort(7000);
        if ($conn->connect()) {
            $domain = new Metaregistrar\EPP\eppDomain('fasfasfasfashfgaf.guru');
            $info = new Metaregistrar\EPP\eppInfoDomainRequest($domain);
            $response = $conn->writeandread($info);
            $this->assertInstanceOf('Metaregistrar\EPP\eppInfoResponse',$response);
            if ($response instanceof Metaregistrar\EPP\eppInfoResponse) {
                $this->setExpectedException('Metaregistrar\epp\eppException','Error 2202: Invalid authorization information');
                $this->assertFalse($response->Success());
            }
        }
     }
}