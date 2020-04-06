<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

/*
This tests lvcontact-ext-1.0
*/

class lvEppContactExtTest extends eppTestCase {

    /**
     * @param null $configfile
     */
    protected function setUp($configfile=null) {
        // Initialize LV to load LV extension classes
        $this->connection = new \Metaregistrar\EPP\lvEppConnection;
    }


    /**
     * Example response for lvEppInfoContactResponse extension
     */

    protected function getLvContactInfoResponse() {
        // The following XML would never be returned in production due to multiple "identity" fields (birth date, identity, registernumber) being specified
        $xml = '<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd">
          <response>
            <result code="1000">
              <msg lang="en">Command completed successfully</msg>
            </result>
            <resData>
              <contact:infData xmlns:contact="urn:ietf:params:xml:ns:contact-1.0" xsi:schemaLocation="urn:ietf:params:xml:ns:contact-1.0 contact-1.0.xsd">
                <contact:id>testholder-12345</contact:id>
                <contact:roid>CNT-873-LVNIC</contact:roid>
                <contact:status s="ok" lang="en">No reason supplied</contact:status>
                <contact:postalInfo type="loc">
                  <contact:name>Person name</contact:name>
                  <contact:org>Organisation Inc.</contact:org>
                  <contact:addr>
                    <contact:street>Address 1</contact:street>
                    <contact:city>City</contact:city>
                    <contact:pc>lv-1001</contact:pc>
                    <contact:cc>LV</contact:cc>
                  </contact:addr>
                </contact:postalInfo>
                <contact:voice>+371.12345678</contact:voice>
                <contact:email>test.holder@enterprise.lv</contact:email>
                <contact:clID>niceppuser</contact:clID>
                <contact:crID>niceppuser</contact:crID>
                <contact:crDate>2019-08-01T15:37:13+03:00</contact:crDate>
                <contact:upDate>2019-08-01T15:37:13+03:00</contact:upDate>
                <contact:authInfo>
                  <contact:pw>
                    <contact:null/>
                  </contact:pw>
                </contact:authInfo>
              </contact:infData>
            </resData>
            <extension>
              <lvContact:infData xmlns:lvContact="http://www.nic.lv/epp/schema/lvcontact-ext-1.0" xsi:schemaLocation="http://www.nic.lv/epp/schema/lvcontact-ext-1.0 lvcontact-ext-1.0.xsd">
                <lvContact:regNr>40003014197</lvContact:regNr>
                <lvContact:vatNr>LV40003014197</lvContact:vatNr>
              </lvContact:infData>
            </extension>
            <trID>
              <clTRID></clTRID>
              <svTRID></svTRID>
            </trID>
          </response>
        </epp>';

        $response = new \Metaregistrar\EPP\lvEppInfoContactResponse;
        $response->loadXML($xml);
        $response->setXpath($this->connection->getServices());
        $response->setXpath($this->connection->getExtensions());
        $response->setXpath($this->connection->getXpathExtensions());
        return $response;
    }


    /**
     * Tests lvEppInfoContactResponse extension
     */

    public function testLvEppInfoContactResponse() {
        $contactResponse = $this->getLvContactInfoResponse();

        // contact:infData schema validation
        $this->assertEquals($contactResponse->getContactId(), 'testholder-12345');
        $this->assertEquals($contactResponse->getContactVoice(), '+371.12345678');
        $this->assertEquals($contactResponse->getContactEmail(), 'test.holder@enterprise.lv');
        $this->assertEquals($contactResponse->getContactClientId(), 'niceppuser');
        $this->assertEquals($contactResponse->getContactCreateClientId(), 'niceppuser');
        $this->assertEquals($contactResponse->getContactCreateDate(), '2019-08-01T15:37:13+03:00');
        $this->assertEquals($contactResponse->getContactStatus()[0], "ok");

        // lvContact:infData schema validation
        $this->assertEquals($contactResponse->getRegNr(), '40003014197');
        $this->assertEquals($contactResponse->getVatNr(), 'LV40003014197');
    }


    /**
     * Tests lvEppCreateContactRequest extension
     */

    public function testLvEppCreateContactRequest() {
        $postalinfo = new \Metaregistrar\EPP\eppContactPostalInfo("Test Name", "Test City", "LV", "Test Org", "Test Street", null, "LV-1001", \Metaregistrar\EPP\lvEppContact::TYPE_LOC);
        $contactinfo = new \Metaregistrar\EPP\lvEppContact($postalinfo, "test@test.lv", "+371.61626364");
        $contactinfo->setContactExtReg("40003014197");
        $contactinfo->setContactExtVat("LV40003014197");

        $contact = new \Metaregistrar\EPP\lvEppCreateContactRequest($contactinfo);
        $text = $contact->saveXML(null, LIBXML_NOEMPTYTAG);


        // contact:create schema validation with assertTrue
        $this->assertTrue(strpos($text, '<epp xmlns:lvcontact="http://www.nic.lv/epp/schema/lvcontact-ext-1.0">') !== false);
        $this->assertTrue(strpos($text, '<contact:create>') !== false);
        $this->assertTrue(strpos($text, '</contact:create>') !== false);
        $this->assertTrue(strpos($text, '<extension>') !== false);
        $this->assertTrue(strpos($text, '</extension>') !== false);

        // contact:create schema validation with assertEquals
        $this->assertEquals("Test Name", $this->getTextBetween($text, '<contact:name>', '</contact:name>' ));
        $this->assertEquals("Test Org", $this->getTextBetween($text, '<contact:org>', '</contact:org>' ));
        $this->assertEquals("Test Street", $this->getTextBetween($text, '<contact:street>', '</contact:street>' ));
        $this->assertEquals("Test City", $this->getTextBetween($text, '<contact:city>', '</contact:city>' ));
        $this->assertEquals("LV-1001", $this->getTextBetween($text, '<contact:pc>', '</contact:pc>' ));
        $this->assertEquals("LV", $this->getTextBetween($text, '<contact:cc>', '</contact:cc>' ));
        $this->assertEquals("+371.61626364", $this->getTextBetween($text, '<contact:voice>', '</contact:voice>' ));
        $this->assertEquals("test@test.lv", $this->getTextBetween($text, '<contact:email>', '</contact:email>' ));

        // lvcontact:create schema validation with assertTrue
        $this->assertTrue(strpos($text, '<lvcontact:create>') !== false);
        $this->assertTrue(strpos($text, '</lvcontact:create>') !== false);

        // lvcontact:create schema validation
        $this->assertEquals("40003014197", $this->getTextBetween($text, '<lvcontact:regNr>', '</lvcontact:regNr>' ));
        $this->assertEquals("LV40003014197", $this->getTextBetween($text, '<lvcontact:vatNr>', '</lvcontact:vatNr>' ));
    }


    /**
     * Tests lvEppCreateContactRequest extension
     */

    public function testLvEppUpdateContactRequest() {
        $postalinfo = new \Metaregistrar\EPP\eppContactPostalInfo("Test Name", "Test City", "LV", "Test Org", "Test Street", null, "LV-1001", \Metaregistrar\EPP\lvEppContact::TYPE_LOC);
        $contact = new \Metaregistrar\EPP\eppContactHandle("LV5db2d2ba5baea");
        $update = new \Metaregistrar\EPP\lvEppContact($postalinfo, "test@test.lv", "+371.61626364");

        $update->setContactExtReg("40003014197");
        $update->setContactExtVat("LV40003014197");

        $up = new \Metaregistrar\EPP\lvEppUpdateContactRequest($contact, null, null, $update);
        $text = $up->saveXML(null, LIBXML_NOEMPTYTAG);

        // contact:update schema validation with assertTrue
        $this->assertTrue(strpos($text, '<epp xmlns:lvcontact="http://www.nic.lv/epp/schema/lvcontact-ext-1.0">') !== false);
        $this->assertTrue(strpos($text, '<contact:update>') !== false);
        $this->assertTrue(strpos($text, '</contact:update>') !== false);
        $this->assertTrue(strpos($text, '<extension>') !== false);
        $this->assertTrue(strpos($text, '</extension>') !== false);

        // contact:update schema validation with assertEquals
        $this->assertEquals("Test Name", $this->getTextBetween($text, '<contact:name>', '</contact:name>' ));
        $this->assertEquals("Test Org", $this->getTextBetween($text, '<contact:org>', '</contact:org>' ));
        $this->assertEquals("Test Street", $this->getTextBetween($text, '<contact:street>', '</contact:street>' ));
        $this->assertEquals("Test City", $this->getTextBetween($text, '<contact:city>', '</contact:city>' ));
        $this->assertEquals("LV-1001", $this->getTextBetween($text, '<contact:pc>', '</contact:pc>' ));
        $this->assertEquals("LV", $this->getTextBetween($text, '<contact:cc>', '</contact:cc>' ));
        $this->assertEquals("+371.61626364", $this->getTextBetween($text, '<contact:voice>', '</contact:voice>' ));
        $this->assertEquals("test@test.lv", $this->getTextBetween($text, '<contact:email>', '</contact:email>' ));

        // lvcontact:update schema validation with assertTrue
        $this->assertTrue(strpos($text, '<lvcontact:update>') !== false);
        $this->assertTrue(strpos($text, '</lvcontact:update>') !== false);

        // lvcontact:update schema validation
        $this->assertEquals("40003014197", $this->getTextBetween($text, '<lvcontact:regNr>', '</lvcontact:regNr>' ));
        $this->assertEquals("LV40003014197", $this->getTextBetween($text, '<lvcontact:vatNr>', '</lvcontact:vatNr>' ));
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
