<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class eppPollTest extends eppTestCase {

    /**
     * empties Poll queue
     * @throws \Metaregistrar\EPP\eppException
     */
    public function testEmptyPollQueue() {
        $poll = new Metaregistrar\EPP\eppPollRequest(Metaregistrar\EPP\eppPollRequest::POLL_REQ);
        $this->assertInstanceOf('Metaregistrar\EPP\eppPollRequest',$poll);
        $response = $this->conn->writeandread($poll);
        $this->assertInstanceOf('Metaregistrar\EPP\eppPollResponse',$response);
        if ($response instanceof Metaregistrar\EPP\eppPollResponse) {
            /* @var $response Metaregistrar\EPP\eppPollResponse */
            $this->assertTrue($response->Success());
            while ($response->getMessageCount()>0) {
                //echo "message id:".$response->getMessageId()."\n";
                $ack = new Metaregistrar\EPP\eppPollRequest(Metaregistrar\EPP\eppPollRequest::POLL_ACK, $response->getMessageId());
                $response = $this->conn->writeandread($ack);
                $this->assertInstanceOf('Metaregistrar\EPP\eppPollResponse',$response);
                $poll = new Metaregistrar\EPP\eppPollRequest(Metaregistrar\EPP\eppPollRequest::POLL_REQ);
                $this->assertInstanceOf('Metaregistrar\EPP\eppPollRequest',$poll);
                $response = $this->conn->writeandread($poll);
            }

        }
    }


    /**
     * Test if poll queue is empty
     * Expects a standard result for an empty poll queue
     */
    public function testPollEmpty() {
        $poll = new Metaregistrar\EPP\eppPollRequest(Metaregistrar\EPP\eppPollRequest::POLL_REQ, 0);
        $this->assertInstanceOf('Metaregistrar\EPP\eppPollRequest',$poll);
        $response = $this->conn->writeandread($poll);
        $this->assertInstanceOf('Metaregistrar\EPP\eppPollResponse',$response);
        if ($response instanceof Metaregistrar\EPP\eppPollResponse) {
            /* @var $response Metaregistrar\EPP\eppPollResponse */
            $this->assertTrue($response->Success());
            $this->assertSame(Metaregistrar\EPP\eppResponse::RESULT_NO_MESSAGES,$response->getResultCode());
            $this->assertSame(0,$response->getMessageCount());
        }
    }

    public function testPollResponse() {
        $response = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
   <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
     <response>
       <result code="1301">
         <msg>Command completed successfully; ack to dequeue</msg>
       </result>
       <msgQ count="5" id="12345">
         <qDate>2000-06-08T22:00:00.0Z</qDate>
         <msg>Transfer requested.</msg>
       </msgQ>
       <resData>
         <domain:trnData xmlns:domain="urn:ietf:params:xml:ns:domain-1.0" >
           <domain:name>example.com</domain:name>
           <domain:trStatus>pending</domain:trStatus>
           <domain:reID>ClientX</domain:reID>
           <domain:reDate>2000-06-08T22:00:00.0Z</domain:reDate>
           <domain:acID>ClientY</domain:acID>
           <domain:acDate>2000-06-13T22:00:00.0Z</domain:acDate>
           <domain:exDate>2002-09-08T22:00:00.0Z</domain:exDate>
         </domain:trnData>
       </resData>
       <trID>
         <clTRID>ABC-12345</clTRID>
         <svTRID>54321-XYZ</svTRID>
       </trID>
     </response>
   </epp>';
        $pollResponse = new Metaregistrar\EPP\eppPollResponse();
        $pollResponse->loadXML($response);
        $pollResponse->xpathuri = ['urn:ietf:params:xml:ns:domain-1.0'=>'domain'];
        $this->assertSame($pollResponse->getMessageCount(),'5');
        $this->assertSame($pollResponse->getMessage(),'Transfer requested.');
        $this->assertSame($pollResponse->getResultCode(),'1301');
        $this->assertSame($pollResponse->getResultMessage(),'Command completed successfully; ack to dequeue');
        $this->assertSame($pollResponse->getMessageId(),'12345');
        $this->assertSame($pollResponse->getMessageDate(),'2000-06-08T22:00:00.0Z');
        $this->assertSame($pollResponse->getMessageType(),'trn');
        $this->assertSame($pollResponse->getDomainName(),'example.com');
        $this->assertSame($pollResponse->getDomainTrStatus(),'pending');
        $this->assertSame($pollResponse->getDomainRequestClientId(),'ClientX');
        $this->assertSame($pollResponse->getDomainRequestDate(),'2000-06-08T22:00:00.0Z');
        $this->assertSame($pollResponse->getDomainActionClientId(),'ClientY');
        $this->assertSame($pollResponse->getDomainActionDate(),'2000-06-13T22:00:00.0Z');
        $this->assertSame($pollResponse->getDomainExpirationDate(),'2002-09-08T22:00:00.0Z');
    }

    public function testPollRenewResponse() {
        $response = '<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
 <response>
   <result code="1301">
     <msg>Command completed successfully; ack to dequeue</msg>
   </result>
   <resData>
     <domain:renData>
       <domain:name>transfertest.frl</domain:name>
       <domain:exDate>2019-09-20T07:55:35.000000+0000</domain:exDate>
     </domain:renData>
   </resData>
   <msgQ count="1" id="100">
     <qDate>2016-09-20T14:49:27.000000+0200</qDate>
     <msg lang="en">Domain transfertest.frl renewed.</msg>
   </msgQ>
   <trID>
     <svTRID>MTR_15d45b90826bcc2c90b2d8b362f6c2c0dfab4f5f</svTRID>
     <clTRID>57e14c7fcd236</clTRID>
   </trID>
 </response>
</epp>';
        $pollResponse = new Metaregistrar\EPP\eppPollResponse();
        $pollResponse->loadXML($response);
        $pollResponse->xpathuri = ['urn:ietf:params:xml:ns:domain-1.0'=>'domain'];
        $this->assertSame($pollResponse->getMessageCount(),'1');
        $this->assertSame($pollResponse->getMessage(),'Domain transfertest.frl renewed.');
        $this->assertSame($pollResponse->getResultCode(),'1301');
        $this->assertSame($pollResponse->getResultMessage(),'Command completed successfully; ack to dequeue');
        $this->assertSame($pollResponse->getMessageId(),'100');
        $this->assertSame($pollResponse->getMessageDate(),'2016-09-20T14:49:27.000000+0200');
        $this->assertSame($pollResponse->getMessageType(),'ren');
        $this->assertSame($pollResponse->getDomainName(),'transfertest.frl');
        $this->assertSame($pollResponse->getDomainExpirationDate(),'2019-09-20T07:55:35.000000+0000');
        $this->assertNull($pollResponse->getDomainRequestClientId());
        $this->assertNull($pollResponse->getDomainActionClientId());
        $this->assertNull($pollResponse->getDomainRequestDate());
        $this->assertNull($pollResponse->getDomainTrStatus());

    }


public function testPollFailedResponse() {
    $response = '<?xml version="1.0" encoding="UTF-8"?> <epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:domain="urn:ietf:params:xml:ns:domain-1.0" xmlns:contact="urn:ietf:params:xml:ns:contact-1.0" xmlns:host="urn:ietf:params:xml:ns:host-1.0" xmlns:ext="http://www.metaregistrar.com/epp/ext-1.0" xmlns:command-ext="http://www.metaregistrar.com/epp/command-ext-1.0" xmlns:command-ext-domain="http://www.metaregistrar.com/epp/command-ext-domain-1.0" xmlns:secDNS="urn:ietf:params:xml:ns:secDNS-1.1" xmlns:rgp="urn:ietf:params:xml:ns:rgp-1.0">   <response>     <msgQ count="522" id="8034">       <qDate>2016-12-07T13:13:51.000000+0100</qDate>       <msg lang="en"><![CDATA[Validating contact \'RP018RWE275DHuxEQhbswZ2q\' completed]]></msg>     </msgQ>     <trID>       <svTRID>MTR_250a12a4a7accb2dff22837edd74290daf6be43a</svTRID>       <clTRID>584ec780dfd19</clTRID>     </trID>   </response> </epp>';
    $pollResponse = new Metaregistrar\EPP\eppPollResponse();
    $pollResponse->loadXML($response);
    $pollResponse->xpathuri = ['urn:ietf:params:xml:ns:domain-1.0'=>'domain'];
    $this->assertSame($pollResponse->getMessageCount(),'522');
    $this->assertSame($pollResponse->getMessage(),'Validating contact \'RP018RWE275DHuxEQhbswZ2q\' completed');
    $this->assertSame($pollResponse->getResultCode(),'1000');
    $this->assertNull($pollResponse->getResultMessage());
    $this->assertSame($pollResponse->getMessageId(),'8034');
    $this->assertSame($pollResponse->getMessageDate(),'2016-12-07T13:13:51.000000+0100');
    $this->setExpectedException('Metaregistrar\EPP\eppException','Type of message cannot be determined on EPP poll message');
    $this->assertSame($pollResponse->getMessageType(),'ren');

    }

}