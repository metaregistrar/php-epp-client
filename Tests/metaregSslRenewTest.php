<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class metaregSslRenewTest extends eppTestCase {
    /**
     * Test renew of ssl certificate
     */

    public function testRenewSslCert() {
        $csr = '-----BEGIN CERTIFICATE REQUEST-----
MIIC/DCCAeQCAQAwYjELMAkGA1UEBhMCTkwxFTATBgNVBAgMDFp1aWQgaG9sbGFu
ZDEOMAwGA1UEBwwFR291ZGExFjAUBgNVBAoMDW1ldGFyZWdpc3RyYXIxFDASBgNV
BAMMC2V4YW1wbGUuY29tMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA
w8uKXylWST+dlmbxxOoOBCblOSW0o+Vb67Fd7ekaKX2BXxRE3a9fGU4rLQldfSEp
RPXr2DcUM50tlpXHt3E8JBga4VTqLa/2HkMWcG9A/25IAXkD7MN6TTpQOLFA2s/M
pTI3dxPbJwMek/MAXtm1j3GPBDAOEAKitKDo7v2RBe5T9lxbtE8/sK53/jcGgPPA
5MU1z/rFGcB2U6lML7pAxVS9mWC/Bo5XEUXYhkN78PDnGd5YwOCcb57flofxwGEU
jtp2B29sL9YWTJMvO/saEZ/RIzFsY+Dot/cJQK4B+40i1WOZ11Yif0wLw2Y0aFbu
3UYz9E3uGuhkNUt6+jQq9wIDAQABoFUwUwYJKoZIhvcNAQkOMUYwRDBCBgNVHREE
OzA5ghF0ZXN0MS5leGFtcGxlLmNvbYIRdGVzdDIuZXhhbXBsZS5jb22CEXRlc3Qz
LmV4YW1wbGUuY29tMA0GCSqGSIb3DQEBCwUAA4IBAQCNS6hjRiId/LQTAbCxQD1v
zwJdzjybn07w7XghiixioT6VDfC68PgFeYOCtiB+AHrtRFzOTNZgRU4rdVH3LIvf
wT7ILDrkFM1ewH1qigEGlzqdMvdHsBxwH3eLpegVuYhF0j0T6FBtnakZEIPq9Rkx
BMHnDSTOqRkyojWSq2hnQ8DV4GSbJzfEq0e58U2602RfZcDPif0Ot5Ubs9/auQYa
k1uktENP8UHVqBiovKYKkCB8aqGxsfFd35cG5ydAkWrwm1q/kji9DjWuX3T2IYgB
6RpNqXSR0WPQjt4ifuJPYytIkkT40p0/ysiXwFiltA7IPzTY4bBjoaBTcMomWFfQ
-----END CERTIFICATE REQUEST-----';
        $csr = base64_encode($csr);
        $ssl = new \Metaregistrar\EPP\metaregSslRenewRequest(2,$csr,'COMO.SIN.DV','nl',2);
        $ssl->addHost('metaregistrar.nl',\Metaregistrar\EPP\metaregSslRenewRequest::VALIDATION_DNS);
        $ssl->setApprover('postmaster@example.com','+31.708900654','Ewout','de Graaf','Zuidelijk Halfrond 1','2801DD','Gouda','ewout@metaregistrar.com');
        //echo $ssl->saveXML();
        if ($response = $this->conn->request($ssl)) {
            /* @var $response \Metaregistrar\EPP\metaregSslrenewResponse */
            $this->assertEquals('1000',$response->getResultCode());
            $this->assertGreaterThan(0,$response->getCertificateId());
            $this->assertStringMatchesFormat('%d_%s',$response->getProvisioningId());
            $this->assertEquals('example.com',$response->getCommonName());
            $this->assertEquals('new',$response->getStatus());
            $this->assertEquals(date('Y'),date('Y',strtotime($response->getrenewDate())));
            $this->assertEquals(date('m'),date('m',strtotime($response->getrenewDate())));
            $this->assertEquals(date('d'),date('d',strtotime($response->getrenewDate())));
        }
    }

    public function testRenewSslCertWrongCSR() {
        $csr = '-----BEGIN CERTIFICATE REQUEST-----
MIIC/DCCAeQCAQAwYjELMAkGA1UEBhMCTkwxFTATBgNVBAgMDFp1aWQgaG9sbGFu
ZDEOMAwGA1UEBwwFR291ZGExFjAUBgNVBAoMDW1ldGFyZWdpc3RyYXIxFDASBgNV
BAMMC2V4YW1wbGUuY29tMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA
w8uKXylWST+dlmbxxOoOBCblOSW0o+Vb67Fd7ekaKX2BXxRE3a9fGU4rLQldfSEp
RPXr2DcUM50tlpXHt3E8JBga4VTqLa/2HkMWcG9A/25IAXkD7MN6TTpQOLFA2s/M
pTI3dxPbJwMek/MAXtm1j3GPBDAOEAKitKDo7v2RBe5T9lxbtE8/sK53/jcGgPPA
5MU1z/rFGcB2U6lML7pAxVS9mWC/Bo5XEUXYhkN78PDnGd5YwOCcb57flofxwGEU
jtp2B29sL9YWTJMvO/saEZ/RIzFsY+Dot/cJQK4B+40i1WOZ11Yif0wLw2Y0aFbu
3UYz9E3uGuhkNUt6+jQq9wIDAQABoFUwUwYJKoZIhvcNAQkOMUYwRDBCBgNVHREE
OzA5ghF0ZXN0MS5leGFtcGxlLmNvbYIRdGVzdDIuZXhhbXBsZS5jb22CEXRlc3Qz
LmV4YW1wbGUuY29tMA0GCSqGSIb3DQEBCwUAA4IBAQCNS6hjRiId/LQTAbCxQD1v
zwJdzjybn07w7XghiixioT6VDfC68PgFeYOCtiB+AHrtRFzOTNZgRU4rdVH3LIvf
wT7ILDrkFM1ewH1qigEGlzqdMvdHsBxwH3eLpegVuYhF0j0T6FBtnakZEIPq9Rkx
BMHnDSTOqRkyojWSq2hnQ8DV4GSbJzfEq0e58U2602RfZcDPif0Ot5Ubs9/auQYa
k1uktENP8UHVqBiovKYKkCB8aqGxsfFd35cG5ydAkWrwm1q/kji9DjWuX3T2IYgB
6RpNqXSR0WPQjt4ifuJPYytIkkT40p0/ysiXwFiltA7IPzTY4bBjoaBTcMomWFfQ
-----END CERTIFICATE REQUEST-----';
        $this->setExpectedException('\Metaregistrar\EPP\eppException','CSR data must be base64-encoded upon metaregSslRenewRequest call');
        new \Metaregistrar\EPP\metaregSslRenewRequest(1,$csr,'Comodo Positive SSL','nl',2);

    }
}