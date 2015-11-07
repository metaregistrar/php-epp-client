<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class eppDeleteContactTest extends eppTestCase {

    /**
     * Test succesful contact deletion
     * @throws \Metaregistrar\EPP\eppException
     */
    public function testDeleteContact() {
        $contacthandle = $this->createContact();
        $contact = new Metaregistrar\EPP\eppContactHandle($contacthandle);
        $delete = new Metaregistrar\EPP\eppDeleteContactRequest($contact);
        $response = $this->conn->writeandread($delete);
        $this->assertInstanceOf('Metaregistrar\EPP\eppDeleteResponse',$response);
        /* @var $response Metaregistrar\EPP\eppDeleteResponse */
        $this->assertTrue($response->Success());
        $this->assertEquals('Command completed successfully',$response->getResultMessage());
        $this->assertEquals(1000,$response->getResultCode());
    }

    /**
     * Test unsuccesful deletion because contact does not exist
     * @throws \Metaregistrar\EPP\eppException
     */
    public function testDeleteNonexistentContact() {
        $message = null;
        $contacthandle = self::randomnumber(8);
        $contact = new Metaregistrar\EPP\eppContactHandle($contacthandle);
        $delete = new Metaregistrar\EPP\eppDeleteContactRequest($contact);
        $response = $this->conn->writeandread($delete);
        $this->assertInstanceOf('Metaregistrar\EPP\eppDeleteResponse',$response);
        /* @var $response Metaregistrar\EPP\eppDeleteResponse */
         try {
            $this->assertFalse($response->Success());
        } catch (Metaregistrar\EPP\eppException $e) {
            $message = $e->getMessage();
        }
        $this->assertEquals('Error 2303: Object does not exist; contactid:'.$contacthandle.' (Contact could not be found)',$message);
    }

    /**
     * Test unsuccesful deletion because contact is not an integer
     * @throws \Metaregistrar\EPP\eppException
     */
    public function testDeleteWrongContact() {
        $message = null;
        $contacthandle = self::randomstring(8);
        $contact = new Metaregistrar\EPP\eppContactHandle($contacthandle);
        $delete = new Metaregistrar\EPP\eppDeleteContactRequest($contact);
        $response = $this->conn->writeandread($delete);
        $this->assertInstanceOf('Metaregistrar\EPP\eppDeleteResponse',$response);
        /* @var $response Metaregistrar\EPP\eppDeleteResponse */
        try {
            $this->assertFalse($response->Success());
        } catch (Metaregistrar\EPP\eppException $e) {
            $message = $e->getMessage();
        }
        $this->assertEquals('Error 2005: Parameter value syntax error; contactid:'.$contacthandle.' (Contact id should be an integer)',$message);
    }
}