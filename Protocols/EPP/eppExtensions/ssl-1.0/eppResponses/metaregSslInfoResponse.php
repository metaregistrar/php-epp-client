<?php
namespace Metaregistrar\EPP;
/*
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:domain="urn:ietf:params:xml:ns:domain-1.0" xmlns:contact="urn:ietf:params:xml:ns:contact-1.0" xmlns:host="urn:ietf:params:xml:ns:host-1.0" xmlns:dns-ext="http://www.metaregistrar.com/epp/dns-ext-1.0" xmlns:ssl="http://www.metaregistrar.com/epp/ssl-1.0" xmlns:ext="http://www.metaregistrar.com/epp/ext-1.0" xmlns:command-ext="http://www.metaregistrar.com/epp/command-ext-1.0" xmlns:command-ext-domain="http://www.metaregistrar.com/epp/command-ext-domain-1.0" xmlns:command-ext-contact="http://www.metaregistrar.com/epp/command-ext-contact-1.0" xmlns:secDNS="urn:ietf:params:xml:ns:secDNS-1.1" xmlns:rgp="urn:ietf:params:xml:ns:rgp-1.0">
  <response>
    <result code="1000">
      <msg>Command completed successfully</msg>
    </result>
    <resData>
      <ssl:infData>
        <ssl:certificateId>2</ssl:certificateId>
        <ssl:provisioningId><![CDATA[108_f41c3cb5ebaf33b96843ee807f2644397648f41e]]></ssl:provisioningId>
        <ssl:commonName><![CDATA[example.com]]></ssl:commonName>
        <ssl:status><![CDATA[new]]></ssl:status>
        <ssl:created><![CDATA[2018-04-11 10:28:36 UTC]]></ssl:created>
        <ssl:modified><![CDATA[2018-04-11 10:28:36 UTC]]></ssl:modified>
      </ssl:infData>
    </resData>
    <trID>
      <clTRID>5acde3549c727</clTRID>
      <svTRID>MTR_808c0df0e210fa7f90dd348626560902258377cec3df</svTRID>
    </trID>
  </response>
</epp>


*/

class metaregSslInfoResponse extends eppResponse {

    function __construct() {
        parent::__construct();
    }

    /**
     * @return null|string
     */
    function getCertificateId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/ssl:infData/ssl:certificateId');
    }

    /**
     * @return null|string
     */
    function getProvisioningId() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/ssl:infData/ssl:provisioningId');
    }

    /**
     * @return null|string
     */
    function getCommonName() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/ssl:infData/ssl:commonName');
    }

    /**
     * @return null|string
     */
    function getStatus() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/ssl:infData/ssl:status');
    }

    /**
     * @return null|string
     */
    function getCreateDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/ssl:infData/ssl:created');
    }

    /**
     * @return null|string
     */
    function getModifiedDate() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/ssl:infData/ssl:modified');
    }

    /**
     * @return null|string
     */
    function getDownloadLink() {
        return $this->queryPath('/epp:epp/epp:response/epp:resData/ssl:infData/ssl:downloadLink');
    }


    /**
     * @return array|null
     */
    function getValidations() {
        $xpath = $this->xPath();
        $validations = $xpath->query('/epp:epp/epp:response/epp:resData/ssl:infData/ssl:validation');
        if ($validations->length == 0) {
            $result = null;
        } else {
            $result = [];
            foreach ($validations as $validation) {
                /* @var $validation \DOMElement */
                $valresult = new metaregSslValidation();
                $valresult->setType($this->queryPath('type',$validation));
                $valresult->setStatus($this->queryPath('status',$validation));
                $valresult->setStatusMessage($this->queryPath('statusMessage',$validation));
                $host = $validation->getElementsByTagName('host');
                if ($host->length>0) {
                    $host = $host->item(0);
                    if ($valresult->getType()=='dcv') {
                        $valresult->setHostName($this->queryPath('name',$host));
                        $valresult->setValidationType($this->queryPath('dcvType',$host));
                        $valresult->setHostStatus($this->queryPath('status',$host));
                        $valresult->setHostStatusMessage($this->queryPath('statusMessage',$host));
                        $valresult->setDnsRecord($this->queryPath('dnsRecord',$host));
                        $valresult->setCnameValue($this->queryPath('dnsCnameValue',$host));
                        $valresult->setFileLocation($this->queryPath('fileLocation',$host));
                        $valresult->setFileContents($this->queryPath('fileContents',$host));
                    }
                }
                $result[] = $valresult;
            }
        }
        return $result;
    }
}