<?php
// Base EPP objects
include_once('Protocols/EPP/eppConnection.php');
// Connection object to Metaregistrar EPP server - this contains your userid and passwords!
include_once('Registries/SIDN/sidnEppConnection.php');
// Base EPP commands: hello, login and logout
include_once('base.php');

/*
 * This sample script modifies a domain name within your account
 * 
 * The nameservers of metaregistrar are used as nameservers
 * In this scrips, the same contact id is used for registrant, admin-contact, tech-contact and billing contact
 * Recommended usage is that you use a tech-contact and billing contact of your own, and set registrant and admin-contact to the domain name owner or reseller.
 */


if ($argc <= 1)
{
    echo "Usage: modifydomain.php <domainname>\n";
    echo "Please enter the domain name to be modified\n\n";
    die();
}

$domainname = $argv[1];

echo "Modifying $domainname\n";
$conn = new sidnEppConnection();
// Connect to the EPP server
if ($conn->connect())
{
    if (login($conn))
    {
        modifydomain($conn,$domainname,4068,4068,103,103,array('ns1.metaregistrar.nl','ns2.metaregistrar.nl'));
        logout($conn);
    }
}





function modifydomain($conn,$domainname,$registrant=null,$admincontact=null,$techcontact=null,$billingcontact=null,$nameservers=null)
{
    try
    {
        $domain = new eppDomain($domainname);
        // In the UpdateDomain command you can set or add parameters
        // - Registrant is always set (you can only have one registrant)
        // - Admin, Tech, Billing contacts are Added (you can have multiple contacts, don't forget to remove the old ones)
        // - Nameservers are Added (you can have multiple nameservers, don't forget to remove the old ones
        $mod = null;
        if ($registrant)
        {
            $mod = new eppDomain($domainname);
            $reg = new eppContactHandle($registrant);
            $mod->setRegistrant($reg);
        }
        $add = null;
        $del = null;
        íf ($admincontact)
        {
            if (!$add)
            {
                $add = new eppDomain($domainname);
            }
            $admin = new eppContactHandle($admincontact,eppContactHandle::CONTACT_TYPE_ADMIN);
            $add->addContact($admin);
        }
        if ($techcontact)
        {
            if (!$add)
            {
                $add = new eppDomain($domainname);
            }
            $tech = new eppContactHandle($techcontact,eppContactHandle::CONTACT_TYPE_TECH);
            $add->addContact($tech);
        }
        if ($bilingcontact)
        {
            if (!$add)
            {
                $add = new eppDomain($domainname);
            }
            $billing = new eppContactHandle($billingcontact,eppContactHandle::CONTACT_TYPE_BILLING);
            $add->addContact($billing);
        }
        if (is_array($nameservers))
        {
            if (!$add)
            {
                $add = new eppDomain($domainname);
            }
            foreach ($nameservers as $nameserver)
            {
                $host = new eppHost($nameserver);
                $add->addHost($host);
            }
        }
        $update = new eppUpdateDomainRequest($domain);
        if ((($response = $conn->writeandread($update)) instanceof eppUpdateDomainResponse) && ($response->Success()))
        {
            echo "Domain ".$response->getDomainName()." modified on ".$response->getDomainUpdateDate().", expiration date is ".$response->getDomainExpirationDate()."\n";
        }
    }
    catch (eppException $e)
    {
        echo $e->getMessage()."\n";
    }
}