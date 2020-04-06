<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

/*
This tests lvdomain-ext-1.0
*/

class lvEppDomainExtTest extends eppTestCase {

    /**
     * @param null $configfile
     */
    protected function setUp($configfile=null)
    {
        // Initialize LV to load LV extension classes
        $this->connection = new \Metaregistrar\EPP\lvEppConnection;
    }


    /**
     * Example response for lvEppInfoDomainResponse extension
     */

    protected function getLvDomainInfoResponse()
    {
        // The following XML would never be returned in production due to
        // multiple "identity" fields (birth date, identity, registernumber)
        // being specified
        $xml = '<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd">
                  <response>
                    <result code="1000">
                      <msg lang="en">Command completed successfully</msg>
                    </result>
                    <resData>
                      <domain:infData xmlns:domain="urn:ietf:params:xml:ns:domain-1.0" xsi:schemaLocation="urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd">
                        <domain:name>123.lv</domain:name>
                        <domain:roid>DOM-123_lv-LV</domain:roid>
                        <domain:status s="serverTransferProhibited" lang="en">Domain name should be renewed at least once before it may be transfered</domain:status>
                        <domain:registrant>LV5db2f7875587f</domain:registrant>
                        <domain:contact type="admin">LV5db2f787558f</domain:contact>
                        <domain:contact type="billing">LV-bill</domain:contact>
                        <domain:contact type="tech">LV-adm0</domain:contact>
                        <domain:ns>
                          <domain:hostAttr>
                            <domain:hostName>ns.nic.lv</domain:hostName>
                          </domain:hostAttr>
                          <domain:hostAttr>
                            <domain:hostName>ns2.nic.lv</domain:hostName>
                          </domain:hostAttr>
                        </domain:ns>
                        <domain:clID>LV</domain:clID>
                        <domain:crDate>2019-10-25T16:24:23+03:00</domain:crDate>
                        <domain:exDate>2019-10-25T16:24:23+03:00</domain:exDate>
                        <domain:upID>LV</domain:upID>
                        <domain:upDate>2019-10-25T17:50:15+03:00</domain:upDate>
                        <domain:authInfo>
                          <domain:pw>XXXXXXXXXXXXXXXX</domain:pw>
                        </domain:authInfo>
                      </domain:infData>
                    </resData>
                    <extension>
                      <lvDomain:infData xmlns:lvDomain="http://www.nic.lv/epp/schema/lvdomain-ext-1.0" xsi:schemaLocation="http://www.nic.lv/epp/schema/lvdomain-ext-1.0 lvdomain-ext-1.0.xsd">
                        <lvDomain:status s="clientAutoRenewProhibited" lang="en">Didn\'t like this domain name</lvDomain:status>
                      </lvDomain:infData>
                    </extension>
                    <trID>
                      <clTRID></clTRID>
                      <svTRID></svTRID>
                    </trID>
                  </response>
                </epp>';

        $response = new \Metaregistrar\EPP\lvEppInfoDomainResponse;
        $response->loadXML($xml);
        $response->setXpath($this->connection->getServices());
        $response->setXpath($this->connection->getExtensions());
        $response->setXpath($this->connection->getXpathExtensions());
        return $response;
    }


    /**
     * Tests lvEppInfoDomainResponse extension
     */

    public function testLvEppInfoDomainResponse()
    {
        $domainResponse = $this->getLvDomainInfoResponse();

        // domain:infData schema validation
        $this->assertEquals($domainResponse->getDomainname(), '123.lv');
        $this->assertEquals($domainResponse->getDomainId(), 'DOM-123_lv-LV');
        $this->assertEquals($domainResponse->getDomainRegistrant(), 'LV5db2f7875587f');
        $this->assertEquals($domainResponse->getDomainContact('admin'), 'LV5db2f787558f');
        $this->assertEquals($domainResponse->getDomainContact("billing"), 'LV-bill');
        $this->assertEquals($domainResponse->getDomainContact("tech"), 'LV-adm0');
        $this->assertEquals($domainResponse->getDomainNameservers()[0]->getHostname(), 'ns.nic.lv');
        $this->assertEquals($domainResponse->getDomainNameservers()[1]->getHostname(), 'ns2.nic.lv');
        $this->assertEquals($domainResponse->getDomainCreateDate(), '2019-10-25T16:24:23+03:00');
        $this->assertEquals($domainResponse->getDomainUpdateDate(), '2019-10-25T17:50:15+03:00');
        $this->assertEquals($domainResponse->getDomainExpirationDate(), '2019-10-25T16:24:23+03:00');
        $this->assertEquals($domainResponse->getDomainClientId(), 'LV');
        $this->assertEquals($domainResponse->getDomainUpdateClientId(), 'LV');
        $this->assertEquals($domainResponse->getDomainAuthInfo(), 'XXXXXXXXXXXXXXXX');

        // lvDomain:infData schema validation
        $this->assertEquals($domainResponse->getLvDomainStatus(), 'Didn\'t like this domain name');
    }

    /**
     * Tests lvEppCreateDomainRequest extension
     */

    public function testLvEppAddDomainRenewStatusRequest()
    {
        $domain = new \Metaregistrar\EPP\eppDomain("123.lv");
        $update = new \Metaregistrar\EPP\lvEppUpdateDomainRenewStatusRequest($domain, true, "Client wants to keep domain");

        $text = $update->saveXML(null, LIBXML_NOEMPTYTAG);

        // Checks if status message is correct
        $this->assertEquals("Client wants to keep domain", $this->getTextBetween($text, '<lvdomain:status s="clientAutoRenewProhibited" lang="en">', '</lvdomain:status>' ));

        // Checks if elements exist for parent
        $this->assertTrue(strpos($text, '<epp xmlns:lvdomain="http://www.nic.lv/epp/schema/lvdomain-ext-1.0">') !== false);
        $this->assertTrue(strpos($text, '<update>') !== false);
        $this->assertTrue(strpos($text, '<domain:update>') !== false);
        $this->assertTrue(strpos($text, '<domain:name>123.lv</domain:name>') !== false);
        $this->assertTrue(strpos($text, '<domain:chg></domain:chg>') !== false);
        $this->assertTrue(strpos($text, '</domain:update>') !== false);
        $this->assertTrue(strpos($text, '</update>') !== false);

        // Checks if elements exist for extension
        $this->assertTrue(strpos($text, '</update>') !== false);
        $this->assertTrue(strpos($text, '<lvdomain:update>') !== false);
        $this->assertTrue(strpos($text, '<lvdomain:rem>') !== false);
        $this->assertTrue(strpos($text, '<lvdomain:status s="clientAutoRenewProhibited" lang="en">') !== false);
        $this->assertTrue(strpos($text, '</lvdomain:status>') !== false);
        $this->assertTrue(strpos($text, '</lvdomain:rem>') !== false);
        $this->assertTrue(strpos($text, '</lvdomain:update>') !== false);
        $this->assertTrue(strpos($text, '</extension>') !== false);
    }


    /**
     * Tests lvEppCreateDomainRequest extension
     */

    public function testLvEppRemoveDomainRenewStatusRequest()
    {
        $domain = new \Metaregistrar\EPP\eppDomain("123.lv");
        $update = new \Metaregistrar\EPP\lvEppUpdateDomainRenewStatusRequest(
            $domain, false, "Client doesn't want to keep domain");

        $text = $update->saveXML(null, LIBXML_NOEMPTYTAG);

        // Checks if status message is correct
        $this->assertEquals("Client doesn't want to keep domain",
            $this->getTextBetween($text, '<lvdomain:status s="clientAutoRenewProhibited" lang="en">', '</lvdomain:status>' )
        );

        // Checks if elements exist for parent
        $this->assertTrue(strpos($text, '<epp xmlns:lvdomain="http://www.nic.lv/epp/schema/lvdomain-ext-1.0">') !== false);
        $this->assertTrue(strpos($text, '<update>') !== false);
        $this->assertTrue(strpos($text, '<domain:update>') !== false);
        $this->assertTrue(strpos($text, '<domain:name>123.lv</domain:name>') !== false);
        $this->assertTrue(strpos($text, '<domain:chg></domain:chg>') !== false);
        $this->assertTrue(strpos($text, '</domain:update>') !== false);
        $this->assertTrue(strpos($text, '</update>') !== false);

        // Checks if elements exist for extension
        $this->assertTrue(strpos($text, '</update>') !== false);
        $this->assertTrue(strpos($text, '<lvdomain:update>') !== false);
        $this->assertTrue(strpos($text, '<lvdomain:add>') !== false);
        $this->assertTrue(strpos($text, '<lvdomain:status s="clientAutoRenewProhibited" lang="en">') !== false);
        $this->assertTrue(strpos($text, '</lvdomain:status>') !== false);
        $this->assertTrue(strpos($text, '</lvdomain:add>') !== false);
        $this->assertTrue(strpos($text, '</lvdomain:update>') !== false);
        $this->assertTrue(strpos($text, '</extension>') !== false);
    }


    /*
     * Helper function to get value from string
     */
    protected function getTextBetween($text, $start, $end) {

        // check for matching start
        if (($startpos = strpos(strtolower($text), strtolower($start))) === false) {
            return $text;
        }

        // check for matching end
        if (($endpos = strpos(strtolower($text), strtolower($end))) === false) {
            return $text;
        }

        // success
        $startpos = $startpos + strlen($start);
        $length = $endpos - $startpos;
        $text = substr($text, $startpos, $length);

        return $text;
    }

}
