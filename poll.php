<?php
// Base EPP objects
include_once('Protocols/EPP/eppConnection.php');
include_once('Protocols/EPP/eppRequests/eppIncludes.php');
include_once('Protocols/EPP/eppResponses/eppIncludes.php');
include_once('Protocols/EPP/eppData/eppIncludes.php');
// Connection object to Metaregistrar EPP server - this contains your userid and passwords!
include_once('Registries/Metaregistrar/metaregEppConnection.php');
include_once('Registries/IIS/iisEppConnection.php');
// Base EPP commands: hello, login and logout
include_once('base.php');


/*
 * This script polls for new messages in the EPP system
 * The messages tell you if a domain name was transferred away to another provider
 * Or it tells you that your credit balance is low
 * Please use the pollack function to acknowledge a message and remove it from the queue
 */



echo "Polling for messages\n";
$conn = new iisEppConnection();
// Connect to the EPP server
if ($conn->connect())
{
	if (greet($conn))
	{
		if (login($conn))
		{
            $messageid = poll($conn);
            if ($messageid)
            {
                //pollack($conn,$messageid);
            }
            logout($conn);
        }
    }
}


function poll($conn)
{
	try
	{
        $poll = new eppPollRequest(eppPollRequest::POLL_REQ, $id);
		if ((($response = $conn->writeandread($poll)) instanceof eppPollResponse) && ($response->Success()))
		{
            /* @var $response eppPollResponse */
     		if ($response->getResultCode() == eppResponse::RESULT_MESSAGE_ACK)
			{
                echo $response->saveXML();
				echo $response->getMessageCount()." messages waiting in the queue\n";
				$messageid = $response->getMessageId();
				echo "Picked up message ".$response->getMessageId().': '.$response->getMessage()."\n";
                return $response->getMessageId();
			}
			else
			{
				echo $response->getResultMessage()."\n";
			}
		}
	}
	catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
    return null;
}

function pollack($conn, $messageid)
{
	try
	{
        $poll = new eppPollRequest(eppPollRequest::POLL_ACK, $messageid);
		if ((($response = $conn->writeandread($poll)) instanceof eppPollResponse) && ($response->Success()))
		{
			echo "Message $messageid is acknowledged\n";
		}
	}
	catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
}