<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class eppCreateContactTest extends eppTestCase {

    public function testCreateContact() {
        $name = 'Test name';
        $city = 'Test city';
        $country = 'US';
        $organization = 'Test company';
        $address = 'Teststreet 1';
        $province = 'CA';
        $postcode = '00000';
        $email = 'test@test.com';
        $telephone = '+1.55500000';
        $password = self::randomstring(8);
        $postalinfo = new Metaregistrar\EPP\eppContactPostalInfo($name, $city, $country, $organization, $address, $province, $postcode, Metaregistrar\EPP\eppContact::TYPE_LOC);
        $this->assertInstanceOf('Metaregistrar\EPP\eppContactPostalInfo',$postalinfo);
        $contactinfo = new Metaregistrar\EPP\eppContact($postalinfo, $email, $telephone);
        $this->assertInstanceOf('Metaregistrar\EPP\eppContact',$contactinfo);
        $contactinfo->setPassword($password);
        $contact = new Metaregistrar\EPP\eppCreateContactRequest($contactinfo);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCreateContactRequest',$contact);
        $response = $this->conn->writeandread($contact);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCreateContactResponse',$response);
        /* @var $response Metaregistrar\EPP\eppCreateContactResponse */
        $this->assertTrue($response->Success());
        $createdate = new DateTime($response->getContactCreateDate());
        $now = new DateTime();
        $this->assertEquals($createdate->format('Y-m-d'),$now->format('Y-m-d'));
        //$this->assertEquals($createdate->format('H:i:s'),$now->format('H:i:s'));
        $this->assertEquals('1000',$response->getResultCode());
        $this->assertNotEmpty($response->getContactId());
        $contactinfo=$this->getContactInfo($response->getContactId());
        //var_dump($contactinfo);
        $this->assertInstanceOf('Metaregistrar\EPP\eppInfoContactResponse',$contactinfo);
        /* @var $contactinfo Metaregistrar\EPP\eppInfoContactResponse */
        $this->assertEquals($email,$contactinfo->getContactEmail());
        $this->assertEquals($telephone,$contactinfo->getContactVoice());
        $this->assertEquals($name,$contactinfo->getContactName());
        $this->assertEquals($city,$contactinfo->getContactCity());
        $this->assertEquals($country,$contactinfo->getContactCountrycode());
        $this->assertEquals($organization,$contactinfo->getContactCompanyname());
        $postalinfo = $contactinfo->getContactPostalInfo()[0];
        $this->assertInstanceOf('Metaregistrar\EPP\eppContactPostalInfo',$postalinfo);
        /* @var $postalinfo Metaregistrar\EPP\eppContactPostalInfo */
        $this->assertEquals($address,$postalinfo->getStreet(0));
        $this->assertEquals($postcode,$postalinfo->getZipcode());
        $this->assertEquals($province,$postalinfo->getProvince());
        $this->assertEquals($city,$postalinfo->getCity());
        $this->assertEquals($country,$postalinfo->getCountrycode());
        $this->assertEquals(1,$postalinfo->getStreetCount());
    }


    public function testCreateContactWithId() {
        $name = 'Test name';
        $city = 'Test city';
        $country = 'US';
        $organization = 'Test company';
        $address = 'Teststreet 1';
        $province = 'CA';
        $postcode = '00000';
        $email = 'test@test.com';
        $telephone = '+1.55500000';
        $password = self::randomstring(8);
        $contactid = self::randomstring(40);
        $postalinfo = new Metaregistrar\EPP\eppContactPostalInfo($name, $city, $country, $organization, $address, $province, $postcode, Metaregistrar\EPP\eppContact::TYPE_LOC);
        $this->assertInstanceOf('Metaregistrar\EPP\eppContactPostalInfo',$postalinfo);
        $contactinfo = new Metaregistrar\EPP\eppContact($postalinfo, $email, $telephone);
        $this->assertInstanceOf('Metaregistrar\EPP\eppContact',$contactinfo);
        $contactinfo->setPassword($password);
        $contactinfo->setId($contactid);
        $contact = new Metaregistrar\EPP\eppCreateContactRequest($contactinfo);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCreateContactRequest',$contact);
        $response = $this->conn->writeandread($contact);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCreateContactResponse',$response);
        /* @var $response Metaregistrar\EPP\eppCreateContactResponse */
        $this->assertTrue($response->Success());
        $createdate = new DateTime($response->getContactCreateDate());
        $now = new DateTime();
        $this->assertEquals($createdate->format('Y-m-d'),$now->format('Y-m-d'));
        //$this->assertEquals($createdate->format('H:i:s'),$now->format('H:i:s'));
        $this->assertEquals('1000',$response->getResultCode());
        $this->assertNotEmpty($response->getContactId());
        $this->assertEquals($contactid,$response->getContactId());
        $contactinfo=$this->getContactInfo($response->getContactId());
        //var_dump($contactinfo);
        $this->assertInstanceOf('Metaregistrar\EPP\eppInfoContactResponse',$contactinfo);
        /* @var $contactinfo Metaregistrar\EPP\eppInfoContactResponse */
        $this->assertEquals($email,$contactinfo->getContactEmail());
        $this->assertEquals($telephone,$contactinfo->getContactVoice());
        $this->assertEquals($name,$contactinfo->getContactName());
        $this->assertEquals($city,$contactinfo->getContactCity());
        $this->assertEquals($country,$contactinfo->getContactCountrycode());
        $this->assertEquals($organization,$contactinfo->getContactCompanyname());
        $postalinfo = $contactinfo->getContactPostalInfo()[0];
        $this->assertInstanceOf('Metaregistrar\EPP\eppContactPostalInfo',$postalinfo);
        /* @var $postalinfo Metaregistrar\EPP\eppContactPostalInfo */
        $this->assertEquals($address,$postalinfo->getStreet(0));
        $this->assertEquals($postcode,$postalinfo->getZipcode());
        $this->assertEquals($province,$postalinfo->getProvince());
        $this->assertEquals($city,$postalinfo->getCity());
        $this->assertEquals($country,$postalinfo->getCountrycode());
        $this->assertEquals(1,$postalinfo->getStreetCount());
    }

    public function testCreateContactUtf8Chars() {
        $name = 'Tëst name';
        $city = 'Test city';
        $country = 'US';
        $organization = 'Test company';
        $address = 'Teststreet 1';
        $province = 'CA';
        $postcode = '00000';
        $email = 'test@test.com';
        $telephone = '+1.55500000';
        $password = self::randomstring(8);
        $postalinfo = new Metaregistrar\EPP\eppContactPostalInfo($name, $city, $country, $organization, $address, $province, $postcode, Metaregistrar\EPP\eppContact::TYPE_LOC);
        $this->assertInstanceOf('Metaregistrar\EPP\eppContactPostalInfo',$postalinfo);
        $contactinfo = new Metaregistrar\EPP\eppContact($postalinfo, $email, $telephone);
        $this->assertInstanceOf('Metaregistrar\EPP\eppContact',$contactinfo);
        $contactinfo->setPassword($password);
        $contact = new Metaregistrar\EPP\eppCreateContactRequest($contactinfo);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCreateContactRequest',$contact);
        //echo $contact->saveXML();
        //die();
        $response = $this->conn->writeandread($contact);
        $this->assertInstanceOf('Metaregistrar\EPP\eppCreateContactResponse',$response);
        /* @var $response Metaregistrar\EPP\eppCreateContactResponse */
        $this->assertTrue($response->Success());
        $createdate = new DateTime($response->getContactCreateDate());
        $now = new DateTime();
        $this->assertEquals($createdate->format('Y-m-d'),$now->format('Y-m-d'));
        //$this->assertEquals($createdate->format('H:i:s'),$now->format('H:i:s'));
        $this->assertEquals('1000',$response->getResultCode());
        $this->assertNotEmpty($response->getContactId());
        $contactinfo=$this->getContactInfo($response->getContactId());
        //var_dump($contactinfo);
        $this->assertInstanceOf('Metaregistrar\EPP\eppInfoContactResponse',$contactinfo);
        /* @var $contactinfo Metaregistrar\EPP\eppInfoContactResponse */
        $this->assertEquals($email,$contactinfo->getContactEmail());
        $this->assertEquals($telephone,$contactinfo->getContactVoice());
        $this->assertEquals($name,$contactinfo->getContactName());
        $this->assertEquals($city,$contactinfo->getContactCity());
        $this->assertEquals($country,$contactinfo->getContactCountrycode());
        $this->assertEquals($organization,$contactinfo->getContactCompanyname());
        $postalinfo = $contactinfo->getContactPostalInfo()[0];
        $this->assertInstanceOf('Metaregistrar\EPP\eppContactPostalInfo',$postalinfo);
        /* @var $postalinfo Metaregistrar\EPP\eppContactPostalInfo */
        $this->assertEquals($address,$postalinfo->getStreet(0));
        $this->assertEquals($postcode,$postalinfo->getZipcode());
        $this->assertEquals($province,$postalinfo->getProvince());
        $this->assertEquals($city,$postalinfo->getCity());
        $this->assertEquals($country,$postalinfo->getCountrycode());
        $this->assertEquals(1,$postalinfo->getStreetCount());
    }

}