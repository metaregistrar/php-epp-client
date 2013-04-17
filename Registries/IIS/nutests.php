<?php
// Base EPP objects
include_once('../Protocols/EPP/eppConnection.php');
include_once('../Protocols/EPP/eppRequests/eppIncludes.php');
include_once('../Protocols/EPP/eppResponses/eppIncludes.php');
include_once('../Protocols/EPP/eppData/eppIncludes.php');
include_once('../Registries/IIS/iisEppConnection.php');
// Base EPP commands: hello, login and logout
include_once('../base.php');



$conn = new iisEppConnection();
// Connect to the EPP server
if ($conn->connect())
{
	if (greet($conn))
	{
		if (login($conn))
		{
            #echo "Test 1: Create domain name with subordinate hosts\n";
            #test1($conn,'');
            #echo "Test 2: Update contact with telephone and postal code\n";
            #test2($conn,'');
            #echo "Test 3: Add ip 217.108.99.249 , 2001:698:a:e:208:2ff:fe15:b2e8 to host\n";
            #test3($conn, '');
            #echo "Test 4: Add host primary.nu to domain\n";
            #test4($conn, '');
            #echo "Test 5: Remove host testhost from domain\n";
            #test5($conn, '');
            #echo "Test 6: Change the owner of domain \n";
            #test6($conn, '');
            #echo "Test 7: Renew domain\n";
            #test7($conn,'');
            #echo "Test 8: Set client delete for domain\n";
            #test8($conn, '');
            #echo "Test 9: Clear client delete for domain\n";
            #test9($conn, '');
            #echo "Test 10: Request transfer of domain with authInfo\n";
            #test10($conn, '.nu', '');
            #echo "Test 11: Remove DS records from domain \n";
            #test11($conn,'.nu');
            #echo "Test 12: Set authInfo for domain\n";
            #test12($conn, '.nu', '');
            #echo "Test 13: Empty message queue\n";
            #test13($conn);
            logout($conn);
        }
    }
}

function test1($conn, $domainname)
{
    $domain = new eppDomain($domainname);
    if (checkdomain($conn,$domain))
    {
        $registrant = createcontact($conn,'noreply@iis.nu','+46.123412340','Hostmistress','Not a Real Company','longwayhome','76543','Kiruna', 'NL');
        $adminc = createcontact($conn,'noreply@iis.nu','+46.123412340','Hostmistress','Not a Real Company','longwayhome','76543','Kiruna', 'NL');
        createdomain($conn,$domainname,$registrant,$adminc,$adminc,null,null);
        /* Check and/or create host objects */
        echo $domainname." is registered, update domain with host objects\n";
        $hostnames[] = 'ns1.'.$domainname;
        $hostnames[] = 'ns2.'.$domainname;
        checkandcreatehosts($conn, $hostnames);
        
        updatedomainaddhost($conn,$domainname,$hostnames);
    }
    else
    {
        echo "Test 1 completed, info domain:\n";
        infodomain($conn,$domainname);
    }
}

function test2($conn, $contactid)
{
    updatecontact($conn, $contactid);
}

function test3($conn, $hostname)
{
    $ips[] = '217.108.99.249';
    $ips[] = '2001:698:a:e:208:2ff:fe15:b2e8';
    updatehostaddip($conn, $hostname, $ips);
}

function test4($conn, $domainname)
{
    updatedomainaddhost($conn, $domainname, array('primary.nu'));
}

function test5($conn, $domainname)
{
    updatedomainremovehost($conn,$domainname,array('testhost.'.$domainname));
}

function test6($conn, $domainname)
{
    //infodomain($conn,$domainname);
    // Current registrant = CONT1002-236937
    $registrant = createcontact($conn,'noreply@iis.nu','+46.123412340','Hostmistress','Not a Real Company','longwayhome','76543','Kiruna', 'NL');
    updatedomainchangeregistrant($conn, $domainname, $registrant);
}


function test7($conn, $domainname)
{
    // Current exp date: 2013-06-16T00:00:00.0Z
    $info = infodomain($conn, $domainname);
    /* @var $info iisEppInfoDomainResponse */
    echo "Current exp date: ".$info->getDomainExpirationDate();
    renewdomain($conn, $domainname, $info->getDomainExpirationDate());
}

function test8($conn, $domainname)
{
    $info = infodomain($conn, $domainname);
     /* @var $info iisEppInfoDomainResponse */
    echo "Client delete setting: ".$info->getDomainClientDelete();
    if ($info->getDomainClientDelete()=='0')
    {
        updatedomainsetclientdelete($conn, $domainname, '1');
    }
}

function test9($conn, $domainname)
{
    $info = infodomain($conn, $domainname);
     /* @var $info iisEppInfoDomainResponse */
    echo "Client delete setting: ".$info->getDomainClientDelete();
    if ($info->getDomainClientDelete()=='1')
    {
        updatedomainsetclientdelete($conn, $domainname, '0');
    }
}

function test10($conn, $domainname, $authcode)
{
    transferdomain($conn, $domainname, $authcode);
}

function test11($conn, $domainname)
{
    $info = dnssecinfodomain($conn, $domainname);
    /* @var $info eppDnssecInfoDomainResponse */
    $keydata = $info->getKeydata();
    if (count($keydata)>0)
    {
        updatedomainremoveds($conn, $domainname);
    }
    else
    {
        echo "No DS data present any more on this domain name\n";
    }
}

function test12($conn, $domainname, $authcode)
{
    updatedomainsetauthcode($conn, $domainname, $authcode);
}

function test13($conn)
{
    $polldata = poll($conn);
    /* @var $polldata eppPollResponse */
    while ($polldata->getMessageCount() > 0)
    {
        pollack($conn, $polldata->getMessageId());
        $polldata = poll($conn);
    }
}

function poll($conn)
{
	try
	{
        $poll = new eppPollRequest(eppPollRequest::POLL_REQ);
		if ((($response = $conn->writeandread($poll)) instanceof eppPollResponse) && ($response->Success()))
		{
            /* @var $response eppPollResponse */
     		if ($response->getResultCode() == eppResponse::RESULT_MESSAGE_ACK)
			{
                echo $response->saveXML();
				echo $response->getMessageCount()." messages waiting in the queue\n";
				$messageid = $response->getMessageId();
				echo "Picked up message ".$response->getMessageId().': '.$response->getMessage()."\n";
                return $response;
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


function checkandcreatehosts($conn, $hosts)
{
	try
	{
        foreach ($hosts as $host)
        {
            $checkhost[] = new eppHost($host);
        }
		$check = new eppCheckRequest($checkhost);
		if ((($response = $conn->writeandread($check)) instanceof eppCheckResponse) && ($response->Success()))
		{
			$checks = $response->getCheckedHosts();
            $allchecksok = true;
			foreach ($checks as $hostname => $check)
			{
				echo "$hostname ".($check ? 'does not exist' : 'exists')."\n";
                if ($check)
                {
                    if ($hostname == '')
                    {
                        $ipaddress[] = '81.4.97.131';
                        //$ipaddress[] = 'fe80:0:0:0:200:f8ff:fe21:67cf';
                    }
                    else
                    {
                        $ipaddress[] = '188.93.148.99';
                        //$ipaddress[] = '2a00:4e40:1:1:0:5:3';
                    }
                    echo "Creating host $hostname \n";
                    createhost($conn,$hostname,$ipaddress);
                }
			}
		}
	}
	catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
}


function createhost($conn, $hostname, $ipaddress)
{
	try
	{
        $create = new eppHost($hostname, $ipaddress);
		$host = new eppCreateRequest($create);
		if ((($response = $conn->writeandread($host)) instanceof eppCreateResponse) && ($response->Success()))
		{
			echo "Host created on ".$response->getHostCreateDate()." with name ".$response->getHostName()."\n";
		}
	}
	catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
}

function createcontact($conn,$email,$telephone,$name,$organization,$address,$postcode,$city, $country)
{
    try
	{
        $postalinfo = new eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode, eppContactPostalInfo::POSTAL_TYPE_LOCAL);
        $contactinfo = new eppContact($postalinfo, $email, $telephone);
        $contact = new iisEppCreateRequest($contactinfo);
        if ((($response = $conn->writeandread($contact)) instanceof eppCreateResponse) && ($response->Success()))
        {
            echo "Contact created on ".$response->getContactCreateDate()." with id ".$response->getContactId()."\n";
            return $response->getContactId();
        }
	}
	catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
    return null;
}


function createdomain($conn,$domainname,$registrant,$admincontact,$techcontact,$billingcontact,$nameservers)
{
    try
    {

        $reg = new eppContactHandle($registrant);
        $domain->setRegistrant($reg);
        if ($admincontact)
        {
            $admin = new eppContactHandle($admincontact,eppContactHandle::CONTACT_TYPE_ADMIN);
            $domain->addContact($admin);
        }
        if ($techcontact)
        {
            $tech = new eppContactHandle($techcontact,eppContactHandle::CONTACT_TYPE_TECH);
            $domain->addContact($tech);
        }
        if ($billingcontact)
        {
            $billing = new eppContactHandle($billingcontact,eppContactHandle::CONTACT_TYPE_BILLING);
            $domain->addContact($billing);
        }
        if (is_array($nameservers))
        {
            foreach ($nameservers as $nameserver)
            {
                $host = new eppHost($nameserver);
                $domain->addHost($host);
            }
        }
        $create = new eppCreateRequest($domain);
        if ((($response = $conn->writeandread($create)) instanceof eppCreateResponse) && ($response->Success()))
        {
            echo "Domain ".$response->getDomainName()." created on ".$response->getDomainCreateDate().", expiration date is ".$response->getDomainExpirationDate()."\n";
        }
    }
    catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
}

function renewdomain($conn, $domainname, $expdate)
{
try
    {
        $domain = new eppDomain($domainname);
        $domain->setPeriodUnit('y');
        $domain->setPeriod('3');
        $expdate = substr($expdate,0,10);
        $renew = new eppRenewRequest($domain, $expdate);
        if ((($response = $conn->writeandread($renew)) instanceof eppRenewResponse) && ($response->Success()))
        {
            echo "Domain $domainname renewed, infoing\n";
            infodomain($conn,$domainname);
        }

    }
    catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
}

function updatedomainaddhost($conn, $domainname, $hosts)
{
    try
    {
        $domain = new eppDomain($domainname);
        $add = new eppDomain($domainname);
        foreach ($hosts as $host)
        {
            $h = new eppHost($host);
            $add->addHost($h);
        }
        $up = new eppUpdateRequest($domain, $add, null, null);
        if ((($response = $conn->writeandread($up)) instanceof eppUpdateResponse) && ($response->Success()))
        {
            echo "Domain $domainname updated, infoing\n";
            infodomain($conn,$domainname);
        }
        
    }
    catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
}

function updatedomainremovehost($conn, $domainname, $hosts)
{
    try
    {
        $domain = new eppDomain($domainname);
        $remove = new eppDomain($domainname);
        foreach ($hosts as $host)
        {
            $h = new eppHost($host);
            $remove->addHost($h);
        }
        $up = new eppUpdateRequest($domain, null, $remove, null);
        if ((($response = $conn->writeandread($up)) instanceof eppUpdateResponse) && ($response->Success()))
        {
            echo "Domain $domainname updated, infoing\n";
            infodomain($conn,$domainname);
        }

    }
    catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
}

function updatedomainchangeregistrant($conn, $domainname, $registrant)
{
    try
    {
        $domain = new eppDomain($domainname);
        $chg = new eppDomain($domainname);
        $chg->setRegistrant($registrant);
        $up = new eppUpdateRequest($domain, null, null, $chg);
        if ((($response = $conn->writeandread($up)) instanceof eppUpdateResponse) && ($response->Success()))
        {
            echo "Domain $domainname updated, infoing\n";
            infodomain($conn,$domainname);
        }

    }
    catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
}


function updatedomainsetauthcode($conn, $domainname, $autcode)
{
    try
    {
        $domain = new eppDomain($domainname);
        $chg = new eppDomain($domainname);
        $chg->setAuthorisationCode($authcode);
        $up = new eppUpdateRequest($domain, null, null, $chg);
        if ((($response = $conn->writeandread($up)) instanceof eppUpdateResponse) && ($response->Success()))
        {
            echo "Domain $domainname updated, infoing\n";
            infodomain($conn,$domainname);
        }

    }
    catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
}

function updatedomainsetclientdelete($conn, $domainname, $clientdelete)
{
    try
    {
        $domain = new eppDomain($domainname);
        $up = new iisEppUpdateDomainClientDeleteRequest($domain, $clientdelete);
        if ((($response = $conn->writeandread($up)) instanceof eppUpdateResponse) && ($response->Success()))
        {
            echo "Domain $domainname updated, infoing\n";
            infodomain($conn,$domainname);
        }
    }
    catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
}

function updatedomainremoveds($conn, $domainname)
{
    try
    {
        $secdns = new eppSecdns();
        $secdns->setData(45678, 2,'B5C422428DEA4137FBF15E1049A48D27FA5EADE64D2EC9F3B58A994A6ABDE543');
        $secdns->setAlgorithm(5);
        $domain = new eppDnssecUpdateRequest($domainname,null,$secdns);
        echo $domain->saveXML();
        if ((($response = $conn->writeandread($domain)) instanceof eppUpdateResponse) && ($response->Success()))
        {
            echo $response->saveXML();
        }
    }
    catch (eppException $e)
    {
        echo $e->getMessage()."\n";
    }
 
}

function updatecontact($conn, $contactid)
{
    try
    {
        $contact = new eppContactHandle($contactid);
        $update = new eppContact();
        $update->setVoice('+46.799999999');
        $pi = new eppContactPostalInfo(null,'Kiruna','SE', null, null, null, '18752', eppContactPostalInfo::POSTAL_TYPE_LOCAL);
        $update->addPostalInfo($pi);
        $up = new eppUpdateRequest($contact, null, null, $update);
        if ((($response = $conn->writeandread($up)) instanceof eppUpdateResponse) && ($response->Success()))
        {
            echo "Contact $contactid updated, infoing\n";
            infocontact($conn,$contactid);
        }
    }
    catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
}

function updatehostaddip($conn, $hostname, $ips)
{
    try
    {
        $domain = new eppHost($hostname);
        $add = new eppHost($hostname);
        foreach ($ips as $ip)
        {
            $add->setIpAddress($ip);
        }
        $up = new eppUpdateRequest($domain, $add, null, null);
        echo $up->saveXML();
        if ((($response = $conn->writeandread($up)) instanceof eppUpdateResponse) && ($response->Success()))
        {
            echo "Host $hostname updated, infoing\n";
            infohost($conn,$hostname);
        }

    }
    catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
}


function infodomain($conn, $domainname)
{
    try
    {
        $domain = new eppDomain($domainname);
        $info = new eppInfoDomainRequest($domain);
        if ((($response = $conn->writeandread($info)) instanceof iisEppInfoDomainResponse) && ($response->Success()))
        {
            /* @var $response iisEppInfoDomainResponse */
            echo $response->saveXML();
            return $response;
        }

    }
    catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}

}

function dnssecinfodomain($conn, $domainname)
{
    try
    {
        $domain = new eppDomain($domainname);
        $info = new eppDnssecInfoDomainRequest($domain);
        if ((($response = $conn->writeandread($info)) instanceof eppDnssecInfoDomainResponse) && ($response->Success()))
        {
            /* @var $response eppDnssecInfoResponse */
            return $response;
        }

    }
    catch (eppException $e)
	{
        echo $response->saveXML();
		echo $e->getMessage()."\n";
	}

}

function infocontact($conn, $contactid)
{
    try
    {
        $contact = new eppContactHandle($contactid);
        $info = new eppInfoContactRequest($contact);
        if ((($response = $conn->writeandread($info)) instanceof eppInfoContactResponse) && ($response->Success()))
        {
            echo $response->saveXML();
        }

    }
    catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}

}


function infohost($conn, $hostname)
{
    try
    {
        $host = new eppHost($hostname);
        $info = new eppInfoHostRequest($host);
        if ((($response = $conn->writeandread($info)) instanceof eppInfoHostResponse) && ($response->Success()))
        {
            echo $response->saveXML();
        }

    }
    catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}

}

function checkdomain($conn, $domain)
{
    $check = new eppCheckRequest($domain);
    if ((($response = $conn->writeandread($check)) instanceof eppCheckResponse) && ($response->Success()))
    {
        /* @var $response eppCheckResponse */
        $result = $response->getCheckedDomains();
        foreach ($result as $checkeddomain)
        {
            if ($checkeddomain['domainname']==$domain->getDomainName())
            {                
                return $checkeddomain['available'];
            }
        }
    }

}

function transferdomain ($conn, $domainname, $authcode)
{
    try
    {
        $domain = new eppDomain($domainname);
        $domain->setAuthorisationCode($authcode);
        $transfer = new eppTransferRequest(eppTransferRequest::OPERATION_REQUEST,$domain);
        echo $transfer->saveXML();
        if ((($response = $conn->writeandread($transfer)) instanceof eppTransferResponse) && ($response->Success()))
        {
            echo $response->saveXML();
        }

    }
    catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
}