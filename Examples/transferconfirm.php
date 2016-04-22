<?php
require('../autoloader.php');

use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppPollRequest;
use Metaregistrar\EPP\eppResponse;
use Metaregistrar\EPP\eppDomain;
use Metaregistrar\EPP\eppTransferRequest;

/*
 * This script polls for new messages in the EPP system
 * The messages tell you if a domain name was transferred away to another provider
 * Or it tells you that your credit balance is low
 * Please use the pollack function to acknowledge a message and remove it from the queue
 */

$domainname = 'test.org';
try {
    echo "Polling for messages\n";
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        // Connect to the EPP server
        if ($conn->login()) {
            $messageid = poll($conn);
            if ($messageid) {
                transferconfirm($conn,$domainname);
                pollack($conn, $messageid);
            }
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}

/**
 * @param eppConnection $conn
 * @return null
 */
function poll($conn) {
    try {
        $poll = new eppPollRequest(eppPollRequest::POLL_REQ, 0);
        if ($response = $conn->request($poll)) {
            /* @var $response Metaregistrar\EPP\eppPollResponse */
            if ($response->getResultCode() == eppResponse::RESULT_MESSAGE_ACK) {
                //echo $response->saveXML();
                echo $response->getMessageCount() . " messages waiting in the queue\n";
                echo "Picked up message " . $response->getMessageId() . ': ' . $response->getMessage() . "\n";

                return $response->getMessageId();
            } else {
                echo $response->getResultMessage() . "\n";
            }
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
    return null;
}

/**
 * @param $conn Metaregistrar\EPP\eppConnection
 * @param $messageid
 * @return null
 */
function pollack($conn, $messageid) {
    try {
        $poll = new eppPollRequest(eppPollRequest::POLL_ACK, $messageid);
        if ($response = $conn->request($poll)) {
            echo "Message $messageid is acknowledged\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}


/**
 * @param $conn Metaregistrar\EPP\eppConnection
 * @param $domainname string
 * @return null
 */
function transferconfirm($conn,$domainname) {
    try {
        $transfer = new eppTransferRequest(eppTransferRequest::OPERATION_APPROVE, new eppDomain($domainname));
        if ($response = $conn->request($transfer)) {
            echo "Transfer of $domainname has been approved\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}