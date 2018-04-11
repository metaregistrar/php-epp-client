<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class metaregSslDeleteTest extends eppTestCase {

    /**
     * Test delete of ssl certificate
     */
    public function testDeleteSslCert() {
        $delete = new \Metaregistrar\EPP\metaregSslDeleteRequest(5,"No reason at all");
        echo $delete->saveXML();
        if ($response = $this->conn->request($delete)) {
            /* @var $response \Metaregistrar\EPP\metaregSslDeleteResponse */
            echo $response->saveXML();
        }
    }

    public function testDeleteSslCertNotExists() {
        $this->setExpectedException('\Metaregistrar\EPP\eppException','Error 2303: Object does not exist');
        $delete = new \Metaregistrar\EPP\metaregSslInfoRequest(439838283);
        $this->conn->request($delete);
    }

}