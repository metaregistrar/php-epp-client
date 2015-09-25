<?php
require('../autoloader.php');
/*
 * This sample script modifies a domain name within your account
 * 
 * The nameservers of metaregistrar are used as nameservers
 * In this scrips, the same contact id is used for registrant, admin-contact, tech-contact and billing contact
 * Recommended usage is that you use a tech-contact and billing contact of your own, and set registrant and admin-contact to the domain name owner or reseller.
 */


if ($argc <= 1) {
    echo "Usage: modifydomain.php <domainname>\n";
    echo "Please enter the domain name to be modified\n\n";
    die();
}

$domainname = $argv[1];

echo "Modifying $domainname\n";
try {
    $conn = new Metaregistrar\EPP\metaregEppConnection();
    // Connect to the EPP server
    if ($conn->connect()) {
        if (login($conn)) {
            modifydomain($conn, $domainname, null, null, null, null, array('ns1.metaregistrar.nl', 'ns2.metaregistrar.nl'));
            logout($conn);
        }
    }
} catch (Metaregistrar\EPP\eppException $e) {
    echo $e->getMessage() . "\n";
    logout($conn);
}


function modifydomain($conn, $domainname, $registrant = null, $admincontact = null, $techcontact = null, $billingcontact = null, $nameservers = null) {
    try {
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        // First, retrieve the current domain info. Nameservers can be unset and then set again.
        $del = null;
        $info = new Metaregistrar\EPP\eppInfoDomainRequest($domain);
        if ((($response = $conn->writeandread($info)) instanceof Metaregistrar\EPP\eppInfoDomainResponse) && ($response->Success())) {
            // If new nameservers are given, get the old ones to remove them
            if (is_array($nameservers)) {
                /* @var Metaregistrar\EPP\eppInfoDomainResponse $response */
                // Check if nameservers are not already set for this domain name
                $oldns = $response->getDomainNameservers();
                if (is_array($oldns)) {
                    foreach($oldns as $index=>$ns) {
                        if (in_array($ns->getHostname(),$nameservers)) {
                            unset($nameservers[array_search($ns->getHostname(),$nameservers)]);
                            unset($oldns[$index]);
                        }
                    }
                    if (count($oldns)>0) {
                        if (!$del) {
                            $del = new Metaregistrar\EPP\eppDomain($domainname);
                        }
                        foreach ($oldns as $ns) {
                            $del->addHost($ns);
                        }
                    }
                }
                // Remove DNSSEC keydata that is present on this domain name
                $keydata = $response->getKeydata();
                if ((is_array($keydata)) && (count($keydata)>0)) {
                    if (!$del) {
                        $del = new Metaregistrar\EPP\eppDomain($domainname);
                    }
                    $del->addSecdns($keydata[0]);
                }
            }
            if ($admincontact) {
                $oldadmin = $response->getDomainContact(Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_ADMIN);
                if (!$del) {
                    $del = new Metaregistrar\EPP\eppDomain($domainname);
                }
                $admin = new Metaregistrar\EPP\eppContactHandle($oldadmin, Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_ADMIN);
                $del>addContact($admin);
            }
        }
        // In the UpdateDomain command you can set or add parameters
        // - Registrant is always set (you can only have one registrant)
        // - Admin, Tech, Billing contacts are Added (you can have multiple contacts, don't forget to remove the old ones)
        // - Nameservers are Added (you can have multiple nameservers, don't forget to remove the old ones
        $mod = null;
        if ($registrant) {
            $mod = new Metaregistrar\EPP\eppDomain($domainname);
            $reg = new Metaregistrar\EPP\eppContactHandle($registrant);
            $mod->setRegistrant($reg);
        }
        $add = null;
        if ($admincontact) {
            if (!$add) {
                $add = new Metaregistrar\EPP\eppDomain($domainname);
            }
            $admin = new Metaregistrar\EPP\eppContactHandle($admincontact, Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_ADMIN);
            $add->addContact($admin);
        }
        if ($techcontact) {
            if (!$add) {
                $add = new Metaregistrar\EPP\eppDomain($domainname);
            }
            $tech = new Metaregistrar\EPP\eppContactHandle($techcontact, Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_TECH);
            $add->addContact($tech);
        }
        if ($billingcontact) {
            if (!$add) {
                $add = new Metaregistrar\EPP\eppDomain($domainname);
            }
            $billing = new Metaregistrar\EPP\eppContactHandle($billingcontact, Metaregistrar\EPP\eppContactHandle::CONTACT_TYPE_BILLING);
            $add->addContact($billing);
        }
        if (is_array($nameservers)) {
            if (!$add) {
                $add = new Metaregistrar\EPP\eppDomain($domainname);
            }
            foreach ($nameservers as $nameserver) {
                $host = new Metaregistrar\EPP\eppHost($nameserver);
                $add->addHost($host);
            }
        }
        if ((!$add) && (!$del) && (!$mod)) {
            echo "Nothing to update";
            return;
        }
        $update = new Metaregistrar\EPP\eppDnssecUpdateDomainRequest($domain, $add, $del, $mod);
        if ((($response = $conn->writeandread($update)) instanceof Metaregistrar\EPP\eppUpdateResponse) && ($response->Success())) {
            /* @var Metaregistrar\EPP\eppUpdateResponse $response */
            echo $response->getResultMessage() . "\n";
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
        if ($response instanceof Metaregistrar\EPP\eppUpdateResponse) {
            echo $response->textContent . "\n";
        }
    }
}