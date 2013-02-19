<?php
include_once('Protocols/EPP/eppConnection.php');
include_once('Protocols/EPP/eppRequests/eppIncludes.php');
include_once('Protocols/EPP/eppResponses/eppIncludes.php');
include_once('Protocols/EPP/eppData/eppIncludes.php');


error_reporting(E_ALL ^ E_NOTICE);

if (!stristr(PHP_OS, 'WIN'))
{
    exec("stty -icanon");

}


#$sidnextensions = array('http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
$host = null;
$port = null;
$interfaces = array(
    '1'=>array('name'=>'Metaregistrar test','host'=>'epp1.metaregistrar.com','port'=>31337,'user'=>'','pass'=>'','class'=>'metaregEppConnection', 'classpath'=>'Registries/Metareg'),
);

if ($argc <= 1)
{
	echo "No interface specified, select one of the following:\n\n";
	foreach ($interfaces as $key => $interface)
	{
		echo $key.': '.$interface[name]."\n";
	}
	die();
}
foreach ($interfaces as $interface)
{
	if ($interface['classpath'])
	{
		include_once($interface['classpath'].'/'.$interface['class'].'.php');
	}
}
$interface = $interfaces[$argv[1]];
echo "Using interface $argv[1] ($interface[name]) to open the connection\n";
    
if ($interface['class'])
{
    if ($interface['classparm'])
    {
        echo $interface['classparm'];
        $conn = new $interface['class']($interface['classparm']);
    }
    else
    {
        $conn = new $interface['class']();
    }
}
else
{
    $conn = new eppConnection();
}
if ($conn->connect($interface['host'], $interface['port']))
{
	if ($response = greet($conn))
	{
		if (login($conn, $interface))
		{
			$run = true;
			while ($run)
			{
				$in = mainmenu();
				//$in = '5a';

				switch ($in)
				{
					case '0':
					case 'q':
						echo "Logging out. Program ends.\n";
						$run = false;
						break;
					case '1a':
						echo "Polling server for new messages...\n";
						poll($conn, $interface);
						break;
					case '1b':
						$msgid = readstring("Enter the id message you want to retrieve:");
						if ($msgid)
						{
							echo "Polling server for new message: $msgid...\n";
							poll($conn, $interface, $msgid);
						}
						break;
					case '1c':
						$msgid = readstring("Enter the id message you want to acknowledge:");
						if ($msgid)
						{
							echo "Polling server for acknowledging message: $msgid...\n";
							pollack($conn, $msgid);
						}
						break;
					case '2a':
						$contactid = readstring("Enter the id of the contact you want to check");
						if ($contactid)
						{
							try
							{
								$contact = new eppContactHandle($contactid);
								checkcontact($conn, $contact);
							}
							catch (eppException $e)
							{
								echo $e->getMessage()."\n";
							}
						}
						break;
					case '2b':
						echo "Creating contact Ewout de Graaf\n";
						try
						{
							$contactpostal = new eppContactPostalInfo('Ewout de Graaf', 'Leusden', 'NL', 'Graafadvies', array('Goudplevier', '24'), '', '3831 GS');
							$contact = new eppContact($contactpostal, 'ewout@mijndomein.nl', '+31.332880151');
							$contacthandle = createcontact($conn, $contact, $interface);
						}
						catch (eppException $e)
						{
							echo $e->getMessage()."\n";
						}
						break;
					case '2c':
						$contactid = readstring("Enter the id of the contact you want info of");
						if ($contactid)
						{
							try
							{
								$contact = new eppContactHandle($contactid);
								contactinfo($conn, $contact);
							}
							catch (eppException $e)
							{
								echo $e->getMessage()."\n";
							}
						}
						break;
					case '2d':
						$contactid = readstring("Enter the id of the contact you want to delete");
						if ($contactid)
						{
							try
							{
								$contact = new eppContactHandle($contactid);
								deletecontact($conn, $contact);
							}
							catch (eppException $e)
							{
								echo $e->getMessage()."\n";
							}
						}
						break;
					case '2e':
						$contactid = readstring("Enter the id of the contact you want to update");
						if ($contactid)
						{
							try
							{
								$contactpostal = new eppContactPostalInfo('Ralph de Boom', 'Mars City', 'NL', 'Mars & Co', array('Pluto', '24'), '', '1333 ET');
								$contact = new eppContact($contactpostal, 'ralph@mijndomein.nl', '+31.33333337');
								$contacthandle = updatecontact($conn, $contactid, null, null, $contact);
							}
							catch (eppException $e)
							{
								echo $e->getMessage()."\n";
							}
						}
						break;
					case '2f':
						$contactid = readstring("Enter the id of the contact you want to add status to");
						if ($contactid)
						{
							try
							{
								$addinfo = new eppContact(null, null, null, null, null, eppContact::STATUS_CLIENT_UPDATE_PROHIBITED);
								$contacthandle = updatecontact($conn, $contactid, $addinfo, null, null);
							}
							catch (eppException $e)
							{
								echo $e->getMessage()."\n";
							}
						}
						break;
					case '2g':
						$contactid = readstring("Enter the id of the contact you want to remove status from");
						if ($contactid)
						{
							try
							{
								$reminfo = new eppContact(null, null, null, null, null, eppContact::STATUS_CLIENT_UPDATE_PROHIBITED);
								$contacthandle = updatecontact($conn, $contactid, null, $reminfo, null);
							}
							catch (eppException $e)
							{
								echo $e->getMessage()."\n";
							}
						}
						break;
					case '2h':
						$contactid = readstring("Enter the id of the contact you want to add status to");
						if ($contactid)
						{
							try
							{
								$addinfo = new eppContact(null, null, null, null, null, eppContact::STATUS_SERVER_UPDATE_PROHIBITED);
								$contacthandle = updatecontact($conn, $contactid, $addinfo, null, null);
							}
							catch (eppException $e)
							{
								echo $e->getMessage()."\n";
							}
						}
						break;
					case '2i':
						$contactid = readstring("Enter the id of the contact you want to remove status from");
						if ($contactid)
						{
							try
							{
								$reminfo = new eppContact(null, null, null, null, null, eppContact::STATUS_SERVER_UPDATE_PROHIBITED);
								$contacthandle = updatecontact($conn, $contactid, null, $reminfo, null);
							}
							catch (eppException $e)
							{
								echo $e->getMessage()."\n";
							}
						}
						break;
					case '3a':
						$hostname = readstring("Enter the name of the host you want to check");
						if ($hostname)
						{
							try
							{
								$host = new eppHost($hostname);
								checkhosts($conn, array($host));
							}
							catch (eppException $e)
							{
								echo $e->getMessage()."\n";
							}
						}
						break;
					case '3b':
						$hostname = readstring("Enter the name of the host you want to create");
						if ($hostname)
						{
							echo "Creating host $hostname \n";
							try
							{
								$host = new eppHost($hostname);
								createhost($conn, $host);
							}
							catch (eppException $e)
							{
								echo $e->getMessage()."\n";
							}
						}
						break;
					case '3c':
						$hostname = readstring("Enter the name of the host you want info of");
						if ($hostname)
						{
							try
							{
								$host = new eppHost($hostname);
								hostinfo($conn, $host);
							}
							catch (eppException $e)
							{
								echo $e->getMessage()."\n";
							}
						}
						break;
					case '3d':
						$hostname = readstring("Enter the name of the host you want to delete");
						if ($hostname)
						{
							try
							{
								$host = new eppHost($hostname);
								deletehost($conn, $host);
							}
							catch (eppException $e)
							{
								echo $e->getMessage()."\n";
							}
						}
						break;
					case '4':
						try
						{
							$dom = new eppDomain('endan.nu');
							#$info = new eppDomain('endan.nu');
							#$info->setRegistrant('MRG4f1aa66d1cde8');
							#$domain = new eppUpdateRequest($dom,null,null,$info);
							#if ((($response = $conn->writeandread($domain)) instanceof eppUpdateResponse) && ($response->Success()))
							#{
							#    echo "Domain owner succesfully updated\n";
							#}                                                
							$contact = new eppContactHandle('MRG4f1aa66d1cde8', eppContactHandle::CONTACT_TYPE_ADMIN);
							$info = new eppDomain('endan.nu');
							$info->addContact($contact);
							$domain = new eppUpdateRequest($dom, $info, null, null);
							if ((($response = $conn->writeandread($domain)) instanceof eppUpdateResponse) && ($response->Success()))
							{
								echo "Domain admin contact succesfully added\n";
							}
							$contact = new eppContactHandle('MRG4f06ffc802240', eppContactHandle::CONTACT_TYPE_TECH);
							$info = new eppDomain('endan.nu');
							$info->addContact($contact);
							$domain = new eppUpdateRequest($dom, $info, null, null);
							if ((($response = $conn->writeandread($domain)) instanceof eppUpdateResponse) && ($response->Success()))
							{
								echo "Domain tech contact succesfully added\n";
							}
							$contact = new eppContactHandle('MRG4f06ff9585ff6', eppContactHandle::CONTACT_TYPE_BILLING);
							$info = new eppDomain('endan.nu');
							$info->addContact($contact);
							$domain = new eppUpdateRequest($dom, $info, null, null);
							if ((($response = $conn->writeandread($domain)) instanceof eppUpdateResponse) && ($response->Success()))
							{
								echo "Domain billing contact succesfully added\n";
							}
						}
						catch (eppException $e)
						{
							echo $e->getMessage();
						}
						break;
					case '4a':
						$domain = readstring("Enter the name of the domain you want to check");
						if ($domain)
						{
							try
							{
								checkdomains($conn, array($domain));
							}
							catch (eppException $e)
							{
								echo $e->getMessage()."\n";
							}
						}
						break;
					case '4b':
						$domain = readstring("Enter the name of the domain you want to create");
						if ($domain)
						{
							try
							{
								$d1 = new eppDomain($domain);
								$d1->setRegistrant('169');
								$d1->setAuthorisationCode('foo2bar');
								$contact = new eppContactHandle('169', eppContactHandle::CONTACT_TYPE_ADMIN);
                                $contactt = new eppContactHandle('170', eppContactHandle::CONTACT_TYPE_TECH);
                                $contactb = new eppContactHandle('171', eppContactHandle::CONTACT_TYPE_BILLING);
                                // check if contact exists
                                checkcontact($conn, $contact);
								#$host1 = new eppHost('ns1.metaregistrar.nl');
								#$host2 = new eppHost('ns2.metaregistrar.nl');
                                #checkhosts($conn, array($host1,$host2));
                                // check if host exists
								#$d1->addHost($host1);
                                #$d1->addHost($host2);
								$d1->addContact($contact);
                                $d1->addContact($contactt);
                                $d1->addContact($contactb);
								createdomain($conn, $d1);
							}
							catch (eppException $e)
							{
								echo $e->getMessage()."\n";
							}
						}
						break;
					case '4c':
						$domain = readstring("Enter the name of the domain you want to info");
						if ($domain)
						{
							try
							{
								$d1 = new eppDomain($domain);
								domaininfo($conn, $d1);
							}
							catch (eppException $e)
							{
								echo $e->getMessage()."\n";
							}
						}
						break;
					case '4d':
						$domain = readstring("Enter the name of the domain you want to delete");
						if ($domain)
						{
							try
							{
								$d1 = new eppDomain($domain);
								deletedomain($conn, $d1);
							}
							catch (eppException $e)
							{
								echo $e->getMessage()."\n";
							}
						}
						break;
					case '4e':
						$domain = readstring("Enter the name of the domain you want to check transfer of");
						$authcode = readstring("Enter the authorisation code of this domain name");
						try
						{
							$domain = new eppDomain($domain);
							$domain->setAuthorisationCode($authcode);
							querytransfer($conn, $domain);
						}
						catch (eppException $e)
						{
							echo $e->getMessage();
						}
						break;
					case '4f':
						$domain = readstring("Enter the name of the domain you want to check transfer of");
						$authcode = readstring("Enter the authorisation code of this domain name");
						try
						{
							$domain = new eppDomain($domain);
							$domain->setAuthorisationCode($authcode);
							transferdomain($conn, $domain);
						}
						catch (eppException $e)
						{
							echo $e->getMessage();
						}
						break;
					case '4g':
						$domain = readstring("Enter the name of the domain you want to renew");
						try
						{
							$domain = new eppDomain($domain);
							$domain->setPeriod(1);
							renewdomain($conn, $domain);
						}
						catch (eppException $e)
						{
							echo $e->getMessage()."\n";
						}
						break;
                    case '5a':
                        try
                        {
                            $ns1 = new eppHost('ns1.metaregistrar.com');
                            $ns2 = new eppHost('ns2.metaregistrar.com');
                            $ns3 = new eppHost('ns3.metaregistrar.com');
                            #$reminfo = new eppDomain('dnssecdomain.nl',null,null,array($ns2));
                            #$domain = new eppUpdateRequest('dnssecdomain.nl',null,$reminfo);      
                            $pdnssec = new pdnssec();

                            #$key = $pdnssec->getActiveKey('graafadvies.nl');
                            #$secadd = new eppSecdns();
                            #$secadd->setKey($key['flags'],$key['algorithm'],$key['key']);
                            #$domain = new eppDnssecUpdateRequest('graafadvies.nl',$secadd);
                            #echo $domain->saveXML();
                            #if ((($response = $conn->writeandread($domain)) instanceof eppUpdateResponse) && ($response->Success()))
                            #{
                            #    echo "Domain updated\n";
                            #}
                        }
                        catch (eppException $e)
                        {
                            echo $response->saveXML();
                            echo $e->getMessage();
                        }
                        break;
                    case '5b':
                        dnssecremovekeys($conn,'graafadvies.nl');
                        break;
                    case '5c':
                        try
                        {
                            $pdnssec = new pdnssec();
                            
                            $keys = $pdnssec->getKeys('graafadvies.nl');
                            echo "Type Active ID   Tag    Bits\n";
                            foreach ($keys as $key)
                            {
                                printf("%4s  %3s  %3d %6d  %5d",$key[type],($key[active]==1?'yes':'no'),$key[id],$key[tag],$key[bits]);
                                echo "\n";
                            }
                            
                        }
                        catch (eppException $e)
                        {
                            echo 'ERROR: '.$e->getMessage();
                        }
                        break;
                    case '5d':
                        try
                        {
                            $pdnssec = new pdnssec();
                            $pdnssec->removeZoneKey('graafadvies.nl', 14);
                        }
                        catch (eppException $e)
                        {
                            echo $e->getMessage();
                        }
                        break;
                    case '7a':
						try
						{
    						echo "Creating tech contact for unifiedroot\n";
							$contactpostal = new eppContactPostalInfo('Erik Seeboldt', 'Amsterdam', 'NL', 'Unifiedroot', array('Arent Janszoon Ernststraat 199'), '', '1083 GV');
							$contact = new eppContact($contactpostal, 'info@unifiedroot.com', '+31.623515366');
							$contacthandle = createcontact($conn, $contact, $interface);
                            echo $contacthandle."\n";
        					echo "Creating billing contact for unifiedroot\n";
							$contactpostal = new eppContactPostalInfo('Stefan Nijessen', 'Amstelveen', 'NL', 'KPMG Meijburg & Co', array('Laan van Langerhuize 9'), '', '1186 DS');
							$contact = new eppContact($contactpostal, 'Nijessen.Stefan@kpmg.nl', '+31.206561656');
							$contacthandle = createcontact($conn, $contact, $interface);
                            echo $contacthandle."\n";
                        }
						catch (eppException $e)
						{
							echo $e->getMessage()."\n";
						}
                        break;
                    case '7b':
						try
						{
    						echo "Creating registrant/admin contact for unifiedroot\n";
							$contactpostal = new eppContactPostalInfo('Stefan Nijessen', 'Amstelveen', 'NL', 'KPMG Meijburg & Co', array('Laan van Langerhuize 9'), '', '1186 DS');
							$contact = new eppContact($contactpostal, 'Nijessen.Stefan@kpmg.nl', '+31.206561656');
							$contacthandle = createcontact($conn, $contact, $interface);
                            echo $contacthandle."\n";
                        }
						catch (eppException $e)
						{
							echo $e->getMessage()."\n";
						}
                        break;
                    case '7c':
						try
						{
    						echo "Creating nameservers for unifiedroot\n";
                            $host = new eppHost('ns1.metaregistrar.com');
                            createhost($conn, $host);
                            $host = new eppHost('ns2.metaregistrar.com');
                            createhost($conn, $host);
                            $host = new eppHost('ns3.metaregistrar.com');
                            createhost($conn, $host);
                        }
						catch (eppException $e)
						{
							echo $e->getMessage()."\n";
						}
                        break;
                    case '7d':
						$domain = readstring("Enter the name of the domain you want to create");
						if ($domain)
						{
							try
							{
								$d1 = new eppDomain($domain);
								$d1->setRegistrant('101');
								$d1->setAuthorisationCode('foo2bar');
								$contact = new eppContactHandle('102', eppContactHandle::CONTACT_TYPE_ADMIN);
                                $contactt = new eppContactHandle('100', eppContactHandle::CONTACT_TYPE_TECH);
                                $contactb = new eppContactHandle('100', eppContactHandle::CONTACT_TYPE_BILLING);
                                // check if contact exists
                                checkcontact($conn, $contact);
								$host1 = new eppHost('ns1.metaregistrar.com');
								$host2 = new eppHost('ns2.metaregistrar.com');
                                $host3 = new eppHost('ns3.metaregistrar.com');
                                checkhosts($conn, array($host1,$host2,$host3));
                                // check if host exists
								$d1->addHost($host1);
                                $d1->addHost($host2);
                                $d1->addHost($host3);
								$d1->addContact($contact);
                                $d1->addContact($contactt);
                                $d1->addContact($contactb);
								createdomain($conn, $d1);
							}
							catch (eppException $e)
							{
								echo $e->getMessage()."\n";
							}
						}
                        break;
                    case '7e':
                        $domainlist = file('./frldomains',FILE_IGNORE_NEW_LINES);
                        foreach ($domainlist as $domain)
						{
							try
							{
								$d1 = new eppDomain($domain);
								$d1->setRegistrant('150388');
								$d1->setAuthorisationCode('foo2bar');
								$contact = new eppContactHandle('150388', eppContactHandle::CONTACT_TYPE_ADMIN);
                                $contactt = new eppContactHandle('150386', eppContactHandle::CONTACT_TYPE_TECH);
                                $contactb = new eppContactHandle('150387', eppContactHandle::CONTACT_TYPE_BILLING);
                                // check if contact exists
                                checkcontact($conn, $contact);
								$host1 = new eppHost('ns1.metaregistrar.com');
								$host2 = new eppHost('ns2.metaregistrar.com');
                                $host3 = new eppHost('ns3.metaregistrar.com');
                                checkhosts($conn, array($host1,$host2,$host3));
                                // check if host exists
								$d1->addHost($host1);
                                $d1->addHost($host2);
                                $d1->addHost($host3);
								$d1->addContact($contact);
                                $d1->addContact($contactt);
                                $d1->addContact($contactb);
								createdomain($conn, $d1);
							}
							catch (eppException $e)
							{
								echo $e->getMessage()."\n";
							}
						}
                        break;
					case '6a':
						echo "Nice try, but i've hidden this away under 6x...\n";
						break;
					case '6x':
						for ($i = 0; $i < 100; $i++)
						{
							echo "LOOP $i/100\n";
							$contactid = rand(100,100000);
							echo "Checking contact contactid\n";
							if ($contactid)
							{
								try
								{
									$contact = new eppContactHandle($contactid);
									checkcontact($conn, $contact);
								}
								catch (eppException $e)
								{
									echo $e->getMessage()."\n";
								}
							}							
							
							try
							{
								$rand = rand(1,10);
								echo "Creating contact random $rand\n";
								switch ($rand)
								{
									case 1:
										$contactpostal = new eppContactPostalInfo('Klantje 1', 'Rotterdam', 'DE', 'Blaat', array('Straat 1'), '', '1000 AA');
										$contact = new eppContact($contactpostal, 'klant1@mijndomein.nl', '+31.00000001');
										
										break;
									case 2:
										$contactpostal = new eppContactPostalInfo('Klantje 2', 'Amsterdam', 'NL', 'Foobar', array('Plein 22'), '', '2000 BB');
										$contact = new eppContact($contactpostal, 'klant2@mijndomein.nl', '+31.00000011');
										break;
									case 3:
										$contactpostal = new eppContactPostalInfo('Klantje 3', 'Qwertydorp', 'US', 'Sunny', array('Plaatjes 25'), '', '3000 CC');
										$contact = new eppContact($contactpostal, 'klant3@mijndomein.nl', '+31.00000011');
										break;
									case 4:
										$contactpostal = new eppContactPostalInfo('Klantje 4', 'Leiden', 'NL', 'Blaat', array('Ergens 1'), '', '4000 CC');
										$contact = new eppContact($contactpostal, 'klant3@mijndomein.nl', '+31.00000221');
										break;
									case 5:
										$contactpostal = new eppContactPostalInfo('Klantje 5', 'Dorp', 'NL', 'Blaat', array('Somewhere 45'), '', '5555 AA');
										$contact = new eppContact($contactpostal, 'klant4@mijndomein.nl', '+31.00444441');
										break;
									case 6:
										$contactpostal = new eppContactPostalInfo('Klantje 5', 'Stad', 'NL', 'Blaat', array('Niet hier 5'), '', '6656 AA');
										$contact = new eppContact($contactpostal, 'klant5@mijndomein.nl', '+31.00005551');
										break;
									case 7:
										$contactpostal = new eppContactPostalInfo('Klantje 6', 'Lelydorp', 'DE', 'Blaat', array('Maar daaro 1'), '', '7767 AA');
										$contact = new eppContact($contactpostal, 'klant6@mijndomein.nl', '+31.00666661');
										break;
									case 8:
										$contactpostal = new eppContactPostalInfo('Klantje 7', 'Roosstad', 'BE', 'Blaat', array('Oh nee 12'), '', '10800 AA');
										$contact = new eppContact($contactpostal, 'klant7@mijndomein.nl', '+31.00007771');
										break;
									case 9:
										$contactpostal = new eppContactPostalInfo('Klantje 8', 'Tweaktown', 'FR', 'Blaat', array('Toc niet 14'), '', '100970 AA');
										$contact = new eppContact($contactpostal, 'klant8@mijndomein.nl', '+31.08888881');
										break;
									case 10:
										$contactpostal = new eppContactPostalInfo('Klantje 9', 'Nerdstad', 'JP', 'Blaat', array('Over hiero 100'), '', '55535 AA');
										$contact = new eppContact($contactpostal, 'klant9@mijndomein.nl', '+31.00999991');
										break;
								}
								$contactid = createcontact($conn, $contact, $interface);								
								if ($contactid)
								{
									echo "Info contact $contactid\n";
									try
									{
										$contact = new eppContactHandle($contactid);
										contactinfo($conn, $contact);
									}
									catch (eppException $e)
									{
										echo $e->getMessage()."\n";
									}
								}
								if ($contactid)
								{
									try
									{
										$rand = rand(1,10);
										echo "Updating contact $contactid to random $rand\n";
										switch ($rand)
										{
											case 1:
												$contactpostal = new eppContactPostalInfo('Klantje 1', 'Rotterdam', 'DE', 'Blaat', array('Straat 1'), '', '1000 AA');
												$contact = new eppContact($contactpostal, 'klant1@mijndomein.nl', '+31.00000001');

												break;
											case 2:
												$contactpostal = new eppContactPostalInfo('Klantje 2', 'Amsterdam', 'NL', 'Foobar', array('Plein 22'), '', '2000 BB');
												$contact = new eppContact($contactpostal, 'klant2@mijndomein.nl', '+31.00000011');
												break;
											case 3:
												$contactpostal = new eppContactPostalInfo('Klantje 3', 'Qwertydorp', 'US', 'Sunny', array('Plaatjes 25'), '', '3000 CC');
												$contact = new eppContact($contactpostal, 'klant3@mijndomein.nl', '+31.00000011');
												break;
											case 4:
												$contactpostal = new eppContactPostalInfo('Klantje 4', 'Leiden', 'NL', 'Blaat', array('Ergens 1'), '', '4000 CC');
												$contact = new eppContact($contactpostal, 'klant3@mijndomein.nl', '+31.00000221');
												break;
											case 5:
												$contactpostal = new eppContactPostalInfo('Klantje 5', 'Dorp', 'NL', 'Blaat', array('Somewhere 45'), '', '5555 AA');
												$contact = new eppContact($contactpostal, 'klant4@mijndomein.nl', '+31.00444441');
												break;
											case 6:
												$contactpostal = new eppContactPostalInfo('Klantje 5', 'Stad', 'NL', 'Blaat', array('Niet hier 5'), '', '6656 AA');
												$contact = new eppContact($contactpostal, 'klant5@mijndomein.nl', '+31.00005551');
												break;
											case 7:
												$contactpostal = new eppContactPostalInfo('Klantje 6', 'Lelydorp', 'DE', 'Blaat', array('Maar daaro 1'), '', '7767 AA');
												$contact = new eppContact($contactpostal, 'klant6@mijndomein.nl', '+31.00666661');
												break;
											case 8:
												$contactpostal = new eppContactPostalInfo('Klantje 7', 'Roosstad', 'BE', 'Blaat', array('Oh nee 12'), '', '10800 AA');
												$contact = new eppContact($contactpostal, 'klant7@mijndomein.nl', '+31.00007771');
												break;
											case 9:
												$contactpostal = new eppContactPostalInfo('Klantje 8', 'Tweaktown', 'FR', 'Blaat', array('Toc niet 14'), '', '100970 AA');
												$contact = new eppContact($contactpostal, 'klant8@mijndomein.nl', '+31.08888881');
												break;
											case 10:
												$contactpostal = new eppContactPostalInfo('Klantje 9', 'Nerdstad', 'JP', 'Blaat', array('Over hiero 100'), '', '55535 AA');
												$contact = new eppContact($contactpostal, 'klant9@mijndomein.nl', '+31.00999991');
												break;
										}
										$contacthandle = updatecontact($conn, $contactid, null, null, $contact);
									}
									catch (eppException $e)
									{
										echo $e->getMessage()."\n";
									}
								}								
								if ($contactid)
								{
									$rand = rand(1,5);
									if ($rand === 5)
									{
										echo "Deleting contact $contactid to random $rand\n";
										try
										{
											$contact = new eppContactHandle($contactid);
											deletecontact($conn, $contact);
										}
										catch (eppException $e)
										{
											echo $e->getMessage()."\n";
										}
									}
								}
								echo "Polling...\n";
								poll($conn, $interface);
								$domain = Tools::randomString(rand(8,32)).".frl";
								echo "Checking domain: $domain\n";
								checkdomains($conn, array($domain));
								usleep(rand(100,5000));
								
								$hostname = "ns1.".Tools::randomString(rand(8,32)).".frl";
								try
								{
									echo "Checkhost hostname $hostname\n";
									$host = new eppHost($hostname);
									checkhosts($conn, array($host));
								}
								catch (eppException $e)
								{
									echo $e->getMessage()."\n";
								}
								
								$hostname = "ns1.".Tools::randomString(rand(8,32)).".frl";
								if ($hostname)
								{
									echo "Creating host $hostname with IP address 1.2.3.4\n";
									try
									{
										$host = new eppHost($hostname, array('1.2.3.4'));
										createhost($conn, $host);
									}
									catch (eppException $e)
									{
										echo $e->getMessage()."\n";
									}
								}																
								if ($hostname)
								{
									try
									{
										echo "Info hostname $hostname\n";
										$host = new eppHost($hostname);
										hostinfo($conn, $host);
									}
									catch (eppException $e)
									{
										echo $e->getMessage()."\n";
									}
								}
								$rand = rand(1,5);
								if ($rand === 5)
								{
									if ($hostname)
									{
										echo "Deleting hostname $hostname to random $rand\n";
										try
										{
											$host = new eppHost($hostname);
											deletehost($conn, $host);
										}
										catch (eppException $e)
										{
											echo $e->getMessage()."\n";
										}
									}
								}
								$domain = Tools::randomString(rand(8,32)).".frl";
								if ($domain)
								{
									try
									{
										echo "Create domain $domain \n";
										$d1 = new eppDomain($domain);
										$d1->setRegistrant('100');
										$d1->setAuthorisationCode('foo2bar');
										$contact = new eppContactHandle('101', eppContactHandle::CONTACT_TYPE_ADMIN);
										$host = new eppHost('ns1.metaregistrar.com');
										$d1->addHost($host);
										$d1->addContact($contact);
										createdomain($conn, $d1);
									}
									catch (eppException $e)
									{
										echo $e->getMessage()."\n";
									}
								}
								if ($domain)
								{
									try
									{
										echo "Info domain $domain \n";
										$d1 = new eppDomain($domain);
										domaininfo($conn, $d1);
									}
									catch (eppException $e)
									{
										echo $e->getMessage()."\n";
									}
								}								
								
							}
							catch (eppException $e)
							{
								echo $e->getMessage()."\n";
							}
						}
						break;
					default:
						echo "Invalid selection.\n";
				}
			}
			logout($conn);
		}
	}
	$conn->disconnect();
}

function mainmenu()
{

	echo "\n\nPossible actions:\n";
	echo "q. Quit this application\n";
	echo "\n";
	echo "1a. Poll new message\n";
	echo "1b. Poll message with id\n";
	echo "1c. Poll acknowledge with id\n";
	echo "\n";
	echo "2a. Contact:check\n";
	echo "2b. Contact:create\n";
	echo "2c. Contact:info\n";
	echo "2d. Contact:delete\n";
	echo "2e. Contact:update\n";
	echo "2f. Contact:add clientUpdateProhibited status\n";
	echo "2g. Contact:remove clientUpdateProhibited status\n";
	echo "2h. Contact:add serverUpdateProhibited status (not allowed)\n";
	echo "2i. Contact:remove serverUpdateProhibited status (not allowed)\n";
	echo "\n";
	echo "3a. Host:check\n";
	echo "3b. Host:create\n";
	echo "3c. Host:info\n";
	echo "3d. Host:delete\n";
	echo "\n";
	echo "4a. Domain:check\n";
	echo "4b. Domain:create\n";
	echo "4c. Domain:info\n";
	echo "4d. Domain:delete\n";
	echo "4e. Domain:transfer query\n";
	echo "4f. Domain:transfer request\n";
	echo "4g. Domain:renew\n";
	echo "\n";
	echo "5a. Sign a domain name with a DS key\n";
    echo "5b. Unsign a domain name\n";
    echo "5c. PDNSSEC\n";
	echo "\n";
	echo "6a. Stress:test1, DONT RUN ON PRODUCTION SERVERS, I MEAN IT\n";
	echo "\n";
	echo "7a. Create TECH and BILLING contacts for UnifiedRoot\n";    
	echo "7b. Create REG and ADMIN contacts for UnifiedRoot\n";    
	echo "7c. Create host objects for UnifiedRoot\n";    
	echo "7d. Create domain name for UnifiedRoot\n";    
    echo "\n\n";
	echo "Please input: ";
	$char = fgets(STDIN, 16);
	$char = trim($char);
	if ($char != 'q')
	{
		echo "\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n";
	}
	echo "\n\n";
	return $char;
}


function dnssecremovekeys($conn, $domainname)
{
    $domain = new eppDomain($domainname);
    $info = new eppDnssecInfoRequest($domain, 'all');
	if ((($response = $conn->writeandread($info)) instanceof eppDnssecInfoResponse) && ($response->Success()))
	{
        $keydata = $response->getKeydata();
        if (count($keydata)>0)
        {
            echo 'DNSSEC data for domain name: '.$response->getDomain()->getDomainname()."\n";
            foreach ($keydata as $key)
            {
                echo 'Flags: '.$key->getFlags()."\n";
                echo 'Protocol: '.$key->getProtocol()."\n";
                echo 'Alg: '.$key->getAlgorithm()."\n";
                echo 'Public key: '.$key->getPubkey()."\n";            
                $domain = new eppDnssecUpdateRequest($domainname,null,$key);
                if ((($resp = $conn->writeandread($domain)) instanceof eppUpdateResponse) && ($resp->Success()))
                {
                    echo "Domain updated\n";
                }
            }
        }
    else 
        {
            echo "No keydata for domain $domainname\n";
        }
	}    
}

function renewdomain($conn, $domain)
{
	$renew = new eppRenewRequest($domain, '2011-01-01');
	if ((($response = $conn->writeandread($renew)) instanceof eppRenewResponse) && ($response->Success()))
	{
		echo "Renew OK\n";
	}
	else
	{
		echo $response->saveXML();
	}
}

function transferdomain($conn, $domain)
{
    try
    {
        $transfer = new dnsbeEppTransferRequest(eppTransferRequest::OPERATION_REQUEST, $domain);
        if ((($response = $conn->writeandread($transfer)) instanceof eppTransferResponse) && ($response->Success()))
        {
            echo "Transfer OK\n";
        }
    }
    catch (eppException $e)
    {
        echo $e->getMessage();
    }
}

function querytransfer($conn, $domain)
{
	$transfer = new eppTransferRequest(eppTransferRequest::OPERATION_QUERY, $domain);
	if ((($response = $conn->writeandread($transfer)) instanceof eppTransferResponse) && ($response->Success()))
	{
		echo 'Transfer of '.$response->getDomainName().' has been requested on '.$response->getTransferRequestDate().' and has status '.$response->getTransferStatus()."\n";
		echo 'Transfer is executed on '.$response->getTransferActionDate()."\n";
	}
}

function domaininfo($conn, $domain)
{
	$info = new eppInfoDomainRequest($domain, 'all');
	if ((($response = $conn->writeandread($info)) instanceof eppInfoDomainResponse) && ($response->Success()))
	{
		echo $response->saveXML();
		echo "Info from domain ".$response->getDomainName()." (roid = ".$response->getDomainRoid().")\n";
		echo "Registered by: ".$response->getDomainClientId()."\n";
		echo "Registrant: ".$response->getDomainRegistrant()."\n";
		echo "Admin-C: ".$response->getDomainContact(eppContactHandle::CONTACT_TYPE_ADMIN)."\n";
		echo "Tech-C: ".$response->getDomainContact(eppContactHandle::CONTACT_TYPE_TECH)."\n";
		echo "Billing-C: ".$response->getDomainContact(eppContactHandle::CONTACT_TYPE_BILLING)."\n";
		echo "Authcode: ".$response->getDomainAuthInfo()."\n";
		echo "Status: ".$response->getDomainStatusCSV()."\n";
		echo "Expiration date: ".$response->getDomainExpirationDate()."\n";
		echo "Nameservers: ".$response->getDomainNameserversCSV()."\n";
		return $response->getDomain();
	}
}

function contactinfo($conn, $contact)
{
	$info = new eppInfoContactRequest($contact);
	if ((($response = $conn->writeandread($info)) instanceof eppInfoContactResponse) && ($response->Success()))
	{
		echo "Contact ID: ".$response->getContactId()."\n";
		echo "Contact Company: ".$response->getContactCompanyname()."\n";
		echo "Contact Name: ".$response->getContactName()."\n";
		echo "Contact Address: ".$response->getContactStreet()."\n";
		echo "Contact Postcode: ".$response->getContactZipcode()."\n";
		echo "Contact City: ".$response->getContactCity()."\n";
		echo "Contact Telephone: ".$response->getContactVoice()."\n";
		echo "Email address: ".$response->getContactEmail()."\n";
		echo "Status: ".$response->getContactStatusCSV()."\n";
		$contact = $response->getContact();
		return $contact;
	}
}

function hostinfo($conn, $host)
{
	$info = new eppInfoHostRequest($host);
	if ((($response = $conn->writeandread($info)) instanceof eppInfoHostResponse) && ($response->Success()))
	{
		echo "Host ".$response->getHostName()." gevonden, status is ".$response->getHostStatusCSV()."\n";
		return $response->getHost();
	}
}

function deletehost($conn, eppHost $host)
{
	$delete = new eppDeleteRequest($host);
	if ((($response = $conn->writeandread($delete)) instanceof eppDeleteResponse) && ($response->Success()))
	{
		echo "Delete of ".$host->getHostname()." succesful\n";
	}
#	echo $response->saveXML();
}

function deletedomain($conn, eppDomain $domain)
{
	$delete = new eppDeleteRequest($domain);
	if ((($response = $conn->writeandread($delete)) instanceof eppDeleteResponse) && ($response->Success()))
	{
		echo "Delete of ".$domain->getDomainname()." succesful\n";
	}
}

function deletecontact($conn, eppContactHandle $contact)
{
	$delete = new eppDeleteRequest($contact);
	if ((($response = $conn->writeandread($delete)) instanceof eppDeleteResponse) && ($response->Success()))
	{
		echo "Delete of ".$contact->getContactHandle()." succesful\n";
	}
}

function hostexists($conn, $host)
{
	try
	{
		$check = new eppCheckRequest(array($host));
		if ((($response = $conn->writeandread($check)) instanceof eppCheckResponse) && ($response->Success()))
		{
			$checks = $response->getCheckedHosts();
			if ($checks[$host->getHostName()] == '0')
				return true;
			else
				return false;
		}
	}
	catch (eppException $e)
	{
		return false;
	}
}

function checkhosts($conn, $hosts)
{
	try
	{
		$check = new eppCheckRequest($hosts);
		if ((($response = $conn->writeandread($check)) instanceof eppCheckResponse) && ($response->Success()))
		{
			$checks = $response->getCheckedHosts();
			#var_dump($checks);
			foreach ($checks as $hostname => $check)
			{
				echo "$hostname ".($check ? 'does not exist' : 'exists')."\n";
			}
		}
	}
	catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
}

function createhost($conn, $hostinfo)
{
	try
	{
		$host = new eppCreateRequest($hostinfo);
		if ((($response = $conn->writeandread($host)) instanceof eppCreateResponse) && ($response->Success()))
		{
			echo "Host created on ".$response->getHostCreateDate()." with name ".$response->getHostName()."\n";
		}
	}
	catch (eppException $e)
	{
        echo $response->saveXML();
		echo $e->getMessage()."\n";
	}
}

function updatehost($conn, $updhost)
{
	#$updinfo = new eppHost('ns2.metaregistrar.be');
	$addinfo = new eppHost('ns1.metaregistrar.be', null, eppHost::STATUS_CLIENT_UPDATE_PROHIBITED);
	$host = new eppUpdateRequest($updhost, $addinfo, null, null);
	if ((($response = $conn->writeandread($host)) instanceof eppUpdateResponse) && ($response->Success()))
	{
		echo "Host updated succesfully\n";
	}
	else
	{
		echo $response->saveXML();
	}
}

function checkcontact($conn, $contactinfo)
{
	try
	{
		$check = new eppCheckRequest($contactinfo);
		if ((($response = $conn->writeandread($check)) instanceof eppCheckResponse) && ($response->Success()))
		{
			$checks = $response->getCheckedContacts();
			foreach ($checks as $contact => $check)
			{
				echo "Contact $contact ".($check ? 'does not exist' : 'exists')."\n";
			}
		}
	}
	catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
}

function createcontact($conn, $contactinfo, $interface)
{
	if ($interface['class'] == 'sidnEppConnection')
	{
		$contact = new sidnEppCreateRequest($contactinfo);
	}
	else
	{
		if ($interface['class'] == 'euridEppConnection')
		{
			$contact = new euridEppCreateRequest($contactinfo);
		}
		else
		{
			$contact = new eppCreateRequest($contactinfo);
		}
	}
	if ((($response = $conn->writeandread($contact)) instanceof eppCreateResponse) && ($response->Success()))
	{
		echo "Contact created on ".$response->getContactCreateDate()." with id ".$response->getContactId()."\n";
		return $response->getContactId();
	}
	return null;
}

/**
 *
 * @param resource $conn
 * @param string $contactid
 * @param eppContact $contactadd
 * @param eppContact $contactrem
 * @param eppContact $contactupd
 */
function updatecontact($conn, $contactid, $contactadd, $contactrem, $contactupd)
{
	$contact = new eppUpdateRequest($contactid, $contactadd, $contactrem, $contactupd);
	if ((($response = $conn->writeandread($contact)) instanceof eppUpdateResponse) && ($response->Success()))
	{
		echo "Contact $contactid succesfully updated\n";
	}
}

function createdomain($conn, $domaininfo)
{
    echo "Create domain ".$domaininfo->getDomainName()."\n";
	$domain = new eppCreateRequest($domaininfo);
	#echo $domain->saveXML();
	if ((($response = $conn->writeandread($domain)) instanceof eppCreateResponse) && ($response->Success()))
	{
		#echo $response->saveXML();
		echo "Domain ".$response->getDomainName()." created on ".$response->getDomainCreateDate().", expiration date is ".$response->getDomainExpirationDate()."\n";
	}
}

function updatedomain($conn, $domainname)
{
	$contacts[] = new eppContactHandle('MRG2094', eppContactHandle::CONTACT_TYPE_ADMIN);
	$contacts[] = new eppContactHandle('MRG2095', eppContactHandle::CONTACT_TYPE_TECH);
	$contacts[] = new eppContactHandle('MRG2095', eppContactHandle::CONTACT_TYPE_BILLING);
	$nameservers[] = new eppHost('ns1.metaregistrar.nu', array('192.0.2.2'));
	$nameservers[] = new eppHost('ns2.metaregistrar.nu', array('192.0.2.3'));
	$changeinfo = new eppDomain($domainname, 'MRG2093', $contacts);
	$addinfo = new eppDomain($domainname, null, null, $nameservers);
	$domain = new eppUpdateRequest($domainname, $addinfo, null, $changeinfo);
	#echo $domain->saveXML();
	if ((($response = $conn->writeandread($domain)) instanceof eppUpdateResponse) && ($response->Success()))
	{
		echo "Domain updated\n";
	}
}

function greet($conn)
{
	try
	{
		$greeting = new eppHelloRequest();
		if ((($response = $conn->writeandread($greeting)) instanceof eppHelloResponse) && ($response->Success()))
		{
			echo "\n\n\n\n\n\n\n\n";
			echo "Welcome to ".$response->getServerName().", date and time: ".$response->getServerDate()."\n";
			$languages = $response->getLanguages();
			if (is_array($languages))
			{
				echo "Supported languages:\n";
				foreach ($languages as $language)
				{
					echo "-".$language."\n";
				}
			}
			$versions = $response->getVersions();
			if (is_array($versions))
			{
				echo "Supported versions:\n";
				foreach ($versions as $version)
				{
					echo "-".$version."\n";
				}
			}
			$services = $response->getServices();
			if (is_array($services))
			{
				echo "Supported services:\n";
				foreach ($services as $service)
				{
					echo "-".$service."\n";
				}
			}
			$extensions = $response->getExtensions();
			if (is_array($extensions))
			{
				echo "Supported extensions:\n";
				foreach ($extensions as $extension)
				{
					echo "-".$extension."\n";
				}
			}
			# Workaround: extra read to clear the buffer after a greeting response
			$result = $conn->read();
			return $response;
		}
	}
	catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
	return null;
}

function poll($conn, $interface, $id = null)
{
	try
	{
		if ($interface['class'] == 'sidnEppConnection')
		{
			$poll = new sidnEppPollRequest(eppPollRequest::POLL_REQ);
		}
		else
		{
			$poll = new eppPollRequest(eppPollRequest::POLL_REQ, $id);
		}
		if ((($response = $conn->writeandread($poll)) instanceof eppPollResponse) && ($response->Success()))
		{
            echo $response->saveXML();
			if ($response->getResultCode() == eppResponse::RESULT_MESSAGE_ACK)
			{
				echo $response->getMessageCount()." messages waiting in the queue\n";
				$messageid = $response->getMessageId();
				echo "Picked up message ".$response->getMessageId()."\n";
				if ($interface['class'] == 'sidnEppConnection')
				{
					echo "Command: ".$response->getPolledCommand()."\n";
					echo "Result code: ".$response->getPolledResultCode()."\n";
					echo "Result message: ".$response->getPolledResultMessage()."\n";
					echo "Domain name: ".$response->getPolledDomainName()."\n";
					echo "trID: ".$response->getPolledTransactionID()."\n";
				}
                else
                {
                    echo $response->saveXML();
                }
				#          pollack($conn,$messageid);
			}
			else
			{
				echo $response->getResultMessage()."\n";
			}
		}
		else
		{
			echo "FOUT\n";
		}
	}
	catch (eppException $e)
	{
		echo $e->getMessage()."\n";
	}
}

function pollack($conn, $messageid, $interface = null)
{
	try
	{
		if ($interface)
		{
			if ($interface['class'] == 'sidnEppConnection')
			{
				$poll = new sidnEppPollRequest(eppPollRequest::POLL_ACK, $messageid);
			}
			else
			{
				$poll = new eppPollRequest(eppPollRequest::POLL_ACK, $messageid);
			}	
		}
		else
		{
			$poll = new sidnEppPollRequest(eppPollRequest::POLL_ACK, $messageid);
		}
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

function checkdomains($conn, $domains)
{
	try
	{
		$check = new eppCheckRequest($domains);
		if ((($response = $conn->writeandread($check)) instanceof eppCheckResponse) && ($response->Success()))
		{
			$checks = $response->getCheckedDomains();
			foreach ($checks as $domainname => $check)
			{
				echo "$domainname is ".($check ? 'free' : 'taken')."\n";
			}
		}
	}
	catch (eppException $e)
	{
		#$error = $response->getCheckResults();
		echo $e->getMessage()."\n";
		#foreach ($error as $index => $err)
		#{
	#		echo "Error $index field $err[field]: $err[message]\n";
	#	}
	}
}

function login($conn, $interface)
{
	try
	{
		if ($interface['user'])
		{
			$conn->setUsername($interface['user']);
		}
		if ($interface['pass'])
		{
			$conn->setPassword($interface['pass']);
		}
		$login = new metaregEppLoginRequest();
		if ((($response = $conn->writeandread($login)) instanceof eppLoginResponse) && ($response->Success()))
		{
			return true;
		}
	}
	catch (eppException $e)
	{
		echo $e->getMessage()."\n";
		return false;
	}
}

function logout($conn)
{
	try
	{
		$logout = new eppLogoutRequest();
		if ((($response = $conn->writeandread($logout)) instanceof eppLogoutResponse) && ($response->Success()))
		{
			return true;
		}
		else
		{
			echo "Logout failed with message ".$response->getResultMessage()."\n";
			return false;
		}
	}
	catch (eppException $e)
	{
		echo $e->getMessage()."\n";
		return false;
	}
}

function readstring($prompt)
{
	echo $prompt;
	echo ": ";
	$fp = fopen('php://stdin', 'r');
	$line = fgets($fp, 4096);
	$line = trim($line);
	if (strlen($line) > 0)
	{
		return $line;
	}
	return null;
}
