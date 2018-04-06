<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class metaregPropertiesTest extends eppTestCase {
    /**
     * Test updates of contact properties
     */

    public function testCreateContactProperties() {
        $contactid = $this->createContact();
        $contact = new \Metaregistrar\EPP\eppContactHandle($contactid);
        $update = new Metaregistrar\EPP\metaregEppUpdateContactRequest($contact);
        $update->addContactProperty('Dnsbe','vat','1219884');
        $update->addContactProperty('Dnsbe','lang','nl');
        //echo $update->saveXML();
        $response = $this->conn->writeandread($update);
        //echo $response->saveXML();
        $this->assertEquals('1000',$response->getResultCode());
        $this->assertEquals('Command completed successfully',$response->getResultMessage());
    }

    public function testInfoContactProperty() {
        $contactid = $this->createContact();
        $contact = new \Metaregistrar\EPP\eppContactHandle($contactid);
        $update = new Metaregistrar\EPP\metaregEppUpdateContactRequest($contact);
        $update->addContactProperty('Dnsbe','vat','1219884');
        $update->addContactProperty('Dnsbe','lang','nl');
        $response = $this->conn->writeandread($update);
        $this->assertEquals('1000',$response->getResultCode());
        $contacthandle = new \Metaregistrar\EPP\eppContactHandle($contactid);
        $info = new \Metaregistrar\EPP\eppInfoContactRequest($contacthandle);
        $response = $this->conn->writeandread($info);
        /* @var $response \Metaregistrar\EPP\metaregEppInfoContactResponse */
        $this->assertEquals('1219884',$response->getContactProperty('Dnsbe','vat'));
        $this->assertEquals('nl',$response->getContactProperty('Dnsbe','lang'));
    }

    public function testInfoContactProperties() {
        $contactid = $this->createContact();
        $contact = new \Metaregistrar\EPP\eppContactHandle($contactid);
        $update = new Metaregistrar\EPP\metaregEppUpdateContactRequest($contact);
        $update->addContactProperty('Dnsbe','vat','1219884');
        $update->addContactProperty('Dnsbe','lang','nl');
        $response = $this->conn->writeandread($update);
        $this->assertEquals('1000',$response->getResultCode());
        $contacthandle = new \Metaregistrar\EPP\eppContactHandle($contactid);
        $info = new \Metaregistrar\EPP\eppInfoContactRequest($contacthandle);
        $response = $this->conn->writeandread($info);
        /* @var $response \Metaregistrar\EPP\metaregEppInfoContactResponse */
        $properties = $response->getContactProperties();
        $this->assertTrue(isset($properties['Dnsbe']));
        $this->assertEquals('1219884',$properties['Dnsbe']['vat']);
        $this->assertEquals('nl',$properties['Dnsbe']['lang']);
    }
}
