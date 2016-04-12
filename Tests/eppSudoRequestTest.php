<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class eppSudoRequestTest extends eppTestCase {

    /**
     * tests as sudo command
     * @throws \Metaregistrar\EPP\eppException
     */
    public function testSudoCheckDomain()
    {
        $domain = new Metaregistrar\EPP\eppDomain('domainname.frl');
        $check = new Metaregistrar\EPP\eppCheckDomainRequest($domain);
        $sudorequest = new Metaregistrar\EPP\MetaregSudoRequest($check,'mtr-enrise');
        //$sudorequest->dumpContents();
        $response = $this->conn->writeandread($sudorequest);
        //$response->dumpContents();
        $this->assertInstanceOf('Metaregistrar\EPP\metaregSudoResponse',$response);
        /* @var $response Metaregistrar\EPP\metaregSudoResponse */
        $response = $response->getOriginalResponse();
        $this->assertInstanceOf('Metaregistrar\EPP\eppCheckDomainResponse',$response);
        //$response->dumpContents();
    }
}