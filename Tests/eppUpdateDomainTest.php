<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class eppUpdateDomainTest extends eppTestCase
{
    /**
     * Test update of hosts on a domain name
     * Expects a standard result for a free domainname
     */
    public function testUpdateDomainHostAttr()
    {
        $domainname = self::randomstring(30) . '.frl';
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $add = null;
        $del = new Metaregistrar\EPP\eppDomain($domainname);
        $d1 = new Metaregistrar\EPP\eppHost('ns1.metaregistrar.nl');
        $d2 = new Metaregistrar\EPP\eppHost('ns2.metaregistrar.nl');
        $del->addHost($d1);
        $del->addHost($d2);
        $mod = new Metaregistrar\EPP\eppDomain($domainname);
        $h1 = new Metaregistrar\EPP\eppHost('ns1.metaregistrar.nl');
        $h2 = new Metaregistrar\EPP\eppHost('ns2.metaregistrar.nl');
        $mod->addHost($h1);
        $mod->addHost($h2);
        $update = new Metaregistrar\EPP\eppUpdateDomainRequest($domain, $add, $del, $mod, true);
        //echo $update->saveXML();
        $this->setExpectedException('Metaregistrar\EPP\eppException',"Error 2001: Command syntax error; value:line: 2 column: 851 cvc-complex-type.2.4.a: Invalid content was found starting with element 'domain:ns'. One of '{\"urn:ietf:params:xml:ns:domain-1.0\":registrant, \"urn:ietf:params:xml:ns:domain-1.0\":authInfo}' is expected.");
        $response = $this->conn->writeandread($update);
        $this->assertFalse($response->Success());
    }
}