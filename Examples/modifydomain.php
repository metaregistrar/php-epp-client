<?php
require('../autoloader.php');

use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppContactHandle;
use Metaregistrar\EPP\eppInfoDomainRequest;
use Metaregistrar\EPP\eppUpdateDomainRequest;
use Metaregistrar\EPP\eppUpdateDomainResponse;
use Metaregistrar\EPP\eppHost;
use Metaregistrar\EPP\eppDomain;

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
    // Please enter your own settings file here under before using this example
    if ($conn = eppConnection::create('')) {
        // Connect to the EPP server
        if ($conn->login()) {
            modifydomain($conn, $domainname, null, null, null, null, array('ns1.metaregistrar.nl', 'ns2.metaregistrar.nl'));
            $conn->logout();
        }
    }
} catch (eppException $e) {
    echo $e->getMessage() . "\n";
}

/**
 * @param $conn eppConnection
 * @param $domainname string
 * @param null $registrant string
 * @param null $admincontact string
 * @param null $techcontact string
 * @param null $billingcontact string
 * @param null $nameservers string
 */
function modifydomain($conn, $domainname, $registrant = null, $admincontact = null, $techcontact = null, $billingcontact = null, $nameservers = null) {
    $response = null;
    try {
        // First, retrieve the current domain info. Nameservers can be unset and then set again.
        $del = null;
        $domain = new eppDomain($domainname);
        $info = new eppInfoDomainRequest($domain);
        if ($response = $conn->request($info)) {
            // If new nameservers are given, get the old ones to remove them
            if (is_array($nameservers)) {
                /* @var Metaregistrar\EPP\eppInfoDomainResponse $response */
                $oldns = $response->getDomainNameservers();
                if (is_array($oldns)) {
                    if (!$del) {
                        $del = new eppDomain($domainname);
                    }
                    foreach ($oldns as $ns) {
                        $del->addHost($ns);
                    }
                }
            }
            if ($admincontact) {
                $oldadmin = $response->getDomainContact(eppContactHandle::CONTACT_TYPE_ADMIN);
                if ($oldadmin == $admincontact) {
                    $admincontact = null;
                } else {
                    if (!$del) {
                        $del = new eppDomain($domainname);
                    }
                    $del->addContact(new eppContactHandle($oldadmin, eppContactHandle::CONTACT_TYPE_ADMIN));
                }
            }
            if ($techcontact) {
                $oldtech = $response->getDomainContact(eppContactHandle::CONTACT_TYPE_TECH);
                if ($oldtech == $techcontact) {
                    $techcontact = null;
                } else {
                    if (!$del) {
                        $del = new eppDomain($domainname);
                    }
                    $del->addContact(new eppContactHandle($oldtech, eppContactHandle::CONTACT_TYPE_TECH));
                }
            }
        }
        // In the UpdateDomain command you can set or add parameters
        // - Registrant is always set (you can only have one registrant)
        // - Admin, Tech, Billing contacts are Added (you can have multiple contacts, don't forget to remove the old ones)
        // - Nameservers are Added (you can have multiple nameservers, don't forget to remove the old ones
        $mod = null;
        if ($registrant) {
            $mod = new eppDomain($domainname);
            $mod->setRegistrant(new eppContactHandle($registrant));
        }
        $add = null;
        if ($admincontact) {
            if (!$add) {
                $add = new eppDomain($domainname);
            }
            $add->addContact(new eppContactHandle($admincontact, eppContactHandle::CONTACT_TYPE_ADMIN));
        }
        if ($techcontact) {
            if (!$add) {
                $add = new eppDomain($domainname);
            }
            $add->addContact(new eppContactHandle($techcontact, eppContactHandle::CONTACT_TYPE_TECH));
        }
        if ($billingcontact) {
            if (!$add) {
                $add = new eppDomain($domainname);
            }
            $add->addContact(new eppContactHandle($billingcontact, eppContactHandle::CONTACT_TYPE_BILLING));
        }
        if (is_array($nameservers)) {
            if (!$add) {
                $add = new eppDomain($domainname);
            }
            foreach ($nameservers as $nameserver) {
                $add->addHost(new eppHost($nameserver));
            }
        }
        $update = new eppUpdateDomainRequest($domain, $add, $del, $mod);
        //echo $update->saveXML();
        if ($response = $conn->request($update)) {
            /* @var eppUpdateDomainResponse $response */
            echo $response->getResultMessage() . "\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
        if ($response instanceof eppUpdateDomainResponse) {
            echo $response->textContent . "\n";
        }
    }
}