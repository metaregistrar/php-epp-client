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
        //$this->assertSame($pollResponse->getMessageType(),'trn');
        $this->assertSame($pollResponse->getMessage(),'Transfer requested.');
        $this->assertSame($pollResponse->getResultCode(),'1301');
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

}