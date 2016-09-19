<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class ficoraEppInfoContactTest extends eppTestCase {

    /**
     * @param null $configfile
     */
    protected function setUp($configfile=null)
    {
        // Initialize ficora to load ficora extension classes
        $this->connection = new \Metaregistrar\EPP\ficoraEppConnection;
    }

    protected function getFicoraContactResponse()
    {
        // The following XML would never be returned in production due to multiple "identity" fields (birth date, identity, registernumber) being specified
        $xml = '<?xml version="1.0" encoding="utf-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
  <response>
    <result code="1000">
      <msg>Command completed successfully</msg>
    </result>
    <resData>
      <contact:infData>
        <contact:id>C12345</contact:id>
        <contact:role>5</contact:role>
        <contact:type>0</contact:type>
        <contact:postalInfo type="loc">
          <contact:isFinnish>1</contact:isFinnish>
          <contact:firstname>Bob</contact:firstname>
          <contact:lastname>Bobson</contact:lastname>
          <contact:identity>123456-123X</contact:identity>
          <contact:registernumber>1234567-1</contact:registernumber>
          <contact:birthDate>1.2.1980</contact:birthDate>
          <contact:addr>
            <contact:street>Streetstreet 12</contact:street>
            <contact:city>Helsinki</contact:city>
            <contact:pc>00200</contact:pc>
            <contact:cc>FI</contact:cc>
          </contact:addr>
        </contact:postalInfo>
        <contact:voice>+35840123456</contact:voice>
        <contact:email>example@mail.mail</contact:email>
        <contact:legalemail>legal@mail.mail</contact:legalemail>
        <contact:clID>C1234</contact:clID>
        <contact:crID>C4321</contact:crID>
        <contact:crDate>2016-08-08T13:19:37</contact:crDate>
      </contact:infData>
    </resData>
    <trID>
      <clTRID></clTRID>
      <svTRID></svTRID>
    </trID>
  </response>
</epp>';

        $response = new \Metaregistrar\EPP\ficoraEppInfoContactResponse;
        $response->loadXML($xml);
        return $response;
    }

    public function testParentContactMethods()
    {
        $contactResponse = $this->getFicoraContactResponse();
        $this->assertEquals($contactResponse->getContactId(), 'C12345');
        $this->assertEquals($contactResponse->getContactVoice(), '+35840123456');
        $this->assertEquals($contactResponse->getContactEmail(), 'example@mail.mail');
        $this->assertEquals($contactResponse->getContactClientId(), 'C1234');
        $this->assertEquals($contactResponse->getContactCreateClientId(), 'C4321');
        $this->assertEquals($contactResponse->getContactCreateDate(), '2016-08-08T13:19:37');
        $this->assertEquals($contactResponse->getContactStatus(), null);
    }

    public function testAdditionalContactMethods() {
        $contactResponse = $this->getFicoraContactResponse();
        $this->assertEquals($contactResponse->getContactRole(), 5);
        $this->assertEquals($contactResponse->getContactType(), 0);
        $this->assertEquals($contactResponse->getContactLegalEmail(), 'legal@mail.mail');

        $contacts = $contactResponse->getContactPostalInfo();
        $this->assertCount(1, $contacts);
    }

    public function testPostalInfo() {
        $contactResponse = $this->getFicoraContactResponse();
        $postalInfo = $contactResponse->getContactPostalInfo();
        // parent method tests
        $this->assertCount(1, $postalInfo);
        $this->assertEquals($postalInfo[0]->getType(), \Metaregistrar\EPP\eppContact::TYPE_LOC);
        $this->assertEquals($postalInfo[0]->getStreet(0), 'Streetstreet 12');
        $this->assertContains('Streetstreet 12', $postalInfo[0]->getStreets());
        $this->assertEquals($postalInfo[0]->getStreetCount(), 1);
        $this->assertEquals($postalInfo[0]->getName(), '');
        $this->assertEquals($postalInfo[0]->getCity(), 'Helsinki');
        $this->assertEquals($postalInfo[0]->getZipcode(), '00200');
        $this->assertEquals($postalInfo[0]->getProvince(), '');
        $this->assertEquals($postalInfo[0]->getCountrycode(), 'FI');
        $this->assertEquals($postalInfo[0]->getOrganisationName(), '');
        // extended method tests
        $this->assertEquals($postalInfo[0]->getFirstName(), 'Bob');
        $this->assertEquals($postalInfo[0]->getLastName(), 'Bobson');
        $this->assertEquals($postalInfo[0]->getPersonName(), 'Bob Bobson');
        $this->assertEquals($postalInfo[0]->getIsFinnish(), '1');
        $this->assertEquals($postalInfo[0]->getIdentity(), '123456-123X');
        $this->assertEquals($postalInfo[0]->getBirthDate(), '1.2.1980');
        $this->assertEquals($postalInfo[0]->getRegisterNumber(), '1234567-1');
    }
}