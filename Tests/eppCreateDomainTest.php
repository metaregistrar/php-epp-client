<?php
include_once(dirname(__FILE__).'/eppTestCase.php');

class eppCreateDomainTest extends eppTestCase {
    /**
     * empties Poll queue
     * @throws \Metaregistrar\EPP\eppException
     */
    public function testCreateDomain() {
        $poll = new Metaregistrar\EPP\eppPollRequest(Metaregistrar\EPP\eppPollRequest::POLL_REQ);
        $this->assertInstanceOf('Metaregistrar\EPP\eppPollRequest',$poll);
        $response = $this->conn->writeandread($poll);
        $this->assertInstanceOf('Metaregistrar\EPP\eppPollResponse',$response);
        if ($response instanceof Metaregistrar\EPP\eppPollResponse) {
            /* @var $response Metaregistrar\EPP\eppPollResponse */
            $this->assertTrue($response->Success());
            while ($response->getMessageCount()>0) {
                echo "message id:".$response->getMessageId()."\n";
                $ack = new Metaregistrar\EPP\eppPollRequest(Metaregistrar\EPP\eppPollRequest::POLL_ACK, $response->getMessageId());
                $response = $this->conn->writeandread($ack);
                $this->assertInstanceOf('Metaregistrar\EPP\eppPollResponse',$response);
                $poll = new Metaregistrar\EPP\eppPollRequest(Metaregistrar\EPP\eppPollRequest::POLL_REQ);
                $this->assertInstanceOf('Metaregistrar\EPP\eppPollRequest',$poll);
                $response = $this->conn->writeandread($poll);
            }

        }
    }

}