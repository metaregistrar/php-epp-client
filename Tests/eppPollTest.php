<?php
include_once(dirname(__FILE__).'/testsetup.php');

class eppPollTest extends PHPUnit_Framework_TestCase {
    /**
     * @var Metaregistrar\EPP\eppConnection
     */
    protected $conn;

    protected function setUp() {
        $this->conn = testSetup::setupConnection();
    }

    protected function tearDown() {
        testSetup::teardownConncection($this->conn);
    }

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

}