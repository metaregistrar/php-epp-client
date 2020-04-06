<?php
require('../../autoloader.php');

use Metaregistrar\EPP\eppConnection;
use Metaregistrar\EPP\eppException;
use Metaregistrar\EPP\eppDomain;
use Metaregistrar\EPP\eppInfoDomainRequest;
use Metaregistrar\EPP\eppContactHandle;
use Metaregistrar\EPP\eppHost;
use Metaregistrar\EPP\eppCheckDomainRequest;
use Metaregistrar\EPP\eppCheckDomainResponse;
use Metaregistrar\EPP\eppCheckRequest;
use Metaregistrar\EPP\eppContactPostalInfo;
use Metaregistrar\EPP\eppCreateHostRequest;
use Metaregistrar\EPP\eppInfoContactRequest;
use Metaregistrar\EPP\eppDeleteDomainRequest;
use Metaregistrar\EPP\eppPollRequest;
use Metaregistrar\EPP\eppResponse;
use Metaregistrar\EPP\eppCreateDomainRequest;
use Metaregistrar\EPP\lvEppContact;
use Metaregistrar\EPP\lvEppCreateContactRequest;
use Metaregistrar\EPP\lvEppUpdateContactRequest;
use Metaregistrar\EPP\eppDeleteContactRequest;
use Metaregistrar\EPP\eppUpdateDomainRequest;
use Metaregistrar\EPP\eppUpdateDomainResponse;
use Metaregistrar\EPP\lvEppUpdateDomainRenewStatusRequest;
use Metaregistrar\EPP\eppTransferRequest;
use Metaregistrar\EPP\lvEppInfoContactResponse;

/*
 * This is a basic EPP demo for .LV tld
 */
if ($argc <= 1) {
    echo "Usage: createdomain.php <domainname>\n";
    echo "Please enter the domain name to be created\n\n";
    die();
}

// Put given domains from cli arguments to array
for ($i = 1; $i < $argc; $i++) {
    $domains[] = $argv[$i];
}

try {

    $conn = eppConnection::create(dirname(__FILE__)."/settings.ini");
    if (! $conn) {
        echo "Could'nt establish EPP connection";
        return;
    }

    // Connect and login to the EPP server
    if(! $conn->login()) {
        echo "Could'nt login with bla bla credentials";
        return;
    }

    // Polls message
    $messageid = poll($conn);
    if ($messageid) {
        pollack($conn, $messageid);
    }

    // Check domain names for availability
    checkDomains($conn, $domains);

    // Creates contact for new domains to be created
    $contactid = createContact($conn,  'test@test.com', '+31.61234567890', 'Person name', 'Organization Inc.', 'Address 1', 'lv-1001', 'City', 'LV',  '40003014197');
    $contactid2 = createContact($conn, 'test@test.com', '+31.61234567890', 'Person name', null, 'Address 1', 'lv-1001', 'City', 'LV');

    // Updates contact (adds vat nr )
    updateContact($conn,  $contactid, null, null, null, null, null, null, null, null, null, 'LV40003014197');

    // Updates contact (adds reg nr for private person)
    //updateContact($conn,$contactid2,null,'+371.21234123',null,null,'Updated address 2','lv-1001','City','LV', "010101-10101");

    // Deletes contact
    //deletecontact($conn, $contactid);

    // Gives info about the contact
    infoContact($conn, $contactid);

    /**
     *Possible options:
     *  OPERATION_QUERY   - Gets info about transfer status
     *  OPERATION_REQUEST - Requests transfer, requires transfer code
     *  OPERATION_APPROVE - Approves transfer request
     *  OPERATION_REJECT  - Rejects transfer request
     *  OPERATION_CANCEL  - Cancels transfer request
     */
    transferDomain($conn, "transfer-accept-{client}-{1..12}.lv", "transfer-accept-{client}-{1..12}.lv", eppTransferRequest::OPERATION_APPROVE);

    // Creates domain if contact was created successfully
    if ($contactid) {
        $nameservers = array('ns1.dns.lv','ns2.dns.lv');
        foreach ($domains as $domainname) {
            // Creates domain name
            createDomain($conn, $domainname, $contactid, $contactid2, $nameservers);
            modifyDomain($conn, $domainname, null, null, array('ns3.nic.lv', 'ns4.nic.lv'));

            // Gets info about domain
            infoDomain($conn, $domainname);

            // Cancels domain name
            deleteDomain($conn, $domainname);

            // Allows auto renewal
            renewDomain($conn, $domainname, true, "Client wants to keep domain");

            infoDomain($conn, $domainname);

            // Set to false, if domain shouldnt be scheduled for auto-renewal and supply reason like so:
            //renewDomain($conn, $domainname, false, "Client didnt want to keep domain");
        }
    }
    $conn->logout();

} catch (eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}


/**
 * @param Metaregistrar\EPP\eppConnection $conn
 * @param string $domainname
 */
function checkDomains($conn, $domains) {
    // Create request to be sent to EPP service
    $check = new eppCheckDomainRequest($domains);
    // Write request to EPP service, read and check the results
    if ($response = $conn->request($check)) {
        /* @var $response eppCheckDomainResponse */
        // Walk through the results
        $checks = $response->getCheckedDomains();
        foreach ($checks as $check) {
            echo $check['domainname'] . " is " . ($check['available'] ? 'free' : 'taken');
            if ($check['available']) {
                echo ' (' . $check['reason'] .')';
            }
            echo "\n";
        }
    }
}

/**
 * @param Metaregistrar\EPP\eppConnection $conn
 * @param string $domainname
 */
function infoDomain($conn, $domainname) {
    $info = new eppInfoDomainRequest(new eppDomain($domainname));
    if ($response = $conn->request($info)) {
        /* @var $response Metaregistrar\EPP\eppInfoDomainResponse */
        $d = $response->getDomain();
        echo "Info for domain " . $d->getDomainname() . ":\n";
        echo "\tCreated on " . $response->getDomainCreateDate() . "\n";
        echo "\tLast update on ".$response->getDomainUpdateDate()."\n";
        echo "\tRegistrant " . $d->getRegistrant() . "\n";

        if( !empty($response->getLvDomainStatus()) ){
            echo "\tDomain status: Marked as after payment do not renew. " . $response->getLvDomainStatus() . "\n";
        } else {
            echo "\tDomain status: Marked for auto-renewal\n";
        }

        echo "\tContact info:\n";

        foreach ($d->getContacts() as $contact) {
            /* @var $contact eppContactHandle */
            echo "\t  " . $contact->getContactType() . ": " . $contact->getContactHandle() . "\n";
        }
        echo "\tNameserver info:\n";
        foreach ($d->getHosts() as $nameserver) {
            /* @var $nameserver eppHost */
            echo "\t  " . $nameserver->getHostname() . "\n";
        }
        if($d->getAuthorisationCode() != null){
        	echo "\t Auth Code: " . $d->getAuthorisationCode() . "\n";
        }
    } else {
        echo "ERROR2\n";
    }
    return null;
}

/**
 * @param Metaregistrar\EPP\eppConnection $conn
 * @param string $domainname
 * @param boolean $renew
 * @param string $reason
 */
function renewDomain($conn, $domainname, $renew, $reason = null) {
    try {
        $domain = new eppDomain($domainname);
        $update = new lvEppUpdateDomainRenewStatusRequest($domain, $renew, $reason);

        if ($response = $conn->request($update)) {
            /* @var eppUpdateDomainResponse $response */
            echo $response->getResultMessage() . "\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
    return null;
}

/**
 * @param Metaregistrar\EPP\eppConnection $conn
 * @param string $domainname
 * @param string $registrant
 * @param string $admincontact
 * @param string $techcontact
 * @param string $nameservers
 */
function createDomain($conn, $domainname, $registrant, $admincontact, $nameservers) {
    /* @var $conn Metaregistrar\EPP\eppConnection */
    try {
        $domain = new eppDomain($domainname, $registrant);
        $domain->setPeriod('1');
        $domain->setRegistrant(new eppContactHandle($registrant));
        $domain->addContact(new eppContactHandle($admincontact, eppContactHandle::CONTACT_TYPE_ADMIN));
        echo $domain->getAuthorisationCode();
        if (is_array($nameservers)) {
            foreach ($nameservers as $nameserver) {
                $domain->addHost(new eppHost($nameserver));
            }
        }

        // true ir required, because .lv registry uses domain:hostAttr instead of domain:hostObj
        $create = new eppCreateDomainRequest($domain, true);
        if ($response = $conn->request($create)) {
            /* @var $response Metaregistrar\EPP\eppCreateDomainResponse */
            echo "Domain " . $response->getDomainName() . " created on " . $response->getDomainCreateDate() . ", expiration date is " . $response->getDomainExpirationDate() . "\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * @param Metaregistrar\EPP\eppConnection $conn
 * @param string $domainname
 * @param string $registrant
 * @param string $admincontact
 * @param string $techcontact
 * @param string $nameservers
 */
function modifyDomain($conn, $domainname, $registrant = null, $admincontact = null, $nameservers = null) {
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
        if (is_array($nameservers)) {
            if (!$add) {
                $add = new eppDomain($domainname);
            }
            foreach ($nameservers as $nameserver) {
                $add->addHost(new eppHost($nameserver));
            }
        }
        $update = new eppUpdateDomainRequest($domain, $add, $del, $mod, true);
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

/**
 * @param Metaregistrar\EPP\eppConnection $conn
 * @param string $domainname
 * @return null
 */
function deleteDomain($conn, $domainname) {
    $delete = new eppDeleteDomainRequest(new eppDomain($domainname));
    if ($response = $conn->request($delete)) {
        /* @var $response \Metaregistrar\EPP\eppDeleteResponse */
        echo "Domain status: " . $response->getResultMessage() . "\n";
    }
    return null;
}

/**
 * @param Metaregistrar\EPP\eppConnection $conn
 * @param string $email
 * @param string $telephone
 * @param string $name
 * @param string $organization
 * @param string $address
 * @param string $postcode
 * @param string $city
 * @param string $country
 * @param string $regNr
 * @param string $vatNr
 */
function createContact($conn, $email, $telephone, $name, $organization, $address, $postcode, $city, $country, $regNr = null, $vatNr = null) {
    $postalinfo = new eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode, lvEppContact::TYPE_LOC);
    $contactinfo = new lvEppContact($postalinfo, $email, $telephone);
    $contactinfo->setContactExtReg($regNr);
    $contactinfo->setContactExtVat($vatNr);
    //$contactinfo->setPassword('12345');

    //$contactinfo->setContactExtReg($regNr);
    $contact = new lvEppCreateContactRequest($contactinfo);
    if ($response = $conn->request($contact)) {
        /* @var $response Metaregistrar\EPP\eppCreateContactResponse */
        echo "Contact created on " . $response->getContactCreateDate() . " with id " . $response->getContactId() . "\n";
        return $response->getContactId();
    } else {
        echo "Create contact failed";
    }
    return null;
}

/**
 * @param Metaregistrar\EPP\eppConnection $conn
 * @param string $contactid
 * @param string $email
 * @param string $telephone
 * @param string $name
 * @param string $organization
 * @param string $address
 * @param string $postcode
 * @param string $city
 * @param string $country
 * @param string $regNr
 * @param string $vatNr
*/
function updateContact($conn, $contactid, $email, $telephone, $name, $organization, $address, $postcode, $city, $country, $regNr = null, $vatNr = null) {
    try {
        $postalinfo = new eppContactPostalInfo($name, $city, $country, $organization, $address, null, $postcode, lvEppContact::TYPE_LOC);
        $contact = new eppContactHandle($contactid);
        $update = new lvEppContact($postalinfo, $email, $telephone);
        if(!empty($regNr)){
            $update->setContactExtReg($regNr);
        }
        if(!empty($vatNr)){
            $update->setContactExtVat($vatNr);
        }
        $up = new lvEppUpdateContactRequest($contact, null, null, $update);
        if ($response = $conn->request($up)) {
            /* @var $response Metaregistrar\EPP\eppCreateResponse */
            echo "Contact $contactid updated.\n";
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

function deleteContact($conn, $contactid) {
    try {
        $contact = new eppContactHandle($contactid);
        $del = new eppDeleteContactRequest($contact);
        if ($response = $conn->request($del)) {
            /* @var $response Metaregistrar\EPP\eppCreateResponse */
            echo "Contact $contactid deleted.\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * @param \Metaregistrar\EPP\eppConnection $conn
 * @param string $contactid
 */
function infoContact($conn, $contactid) {
    try {
        $contact = new eppContactHandle($contactid);
        $info = new eppInfoContactRequest($contact);
        if ((($response = $conn->writeandread($info)) instanceof lvEppInfoContactResponse) && ($response->Success())) {
            /* @var $response Metaregistrar\EPP\lvEppInfoContactResponse */
            echo "Contact information:\n";
            echo "\tID: " . $response->getContactId() . "\n";
            echo "\tRoid: " . $response->getContactRoid() . "\n";
            echo "\tclID: " . $response->getContactClientId() . "\n";
            echo "\tcrID: " . $response->getContactCreateClientId() . "\n";
            echo "\tupDate: " . $response->getContactUpdateDate() . "\n";
            echo "\tcrDate: " . $response->getContactCreateDate() . "\n";
            echo "\tstatus: " . $response->getContactStatusCSV() . "\n";
            echo "\tvoice: " . $response->getContactVoice() . "\n";
            echo "\tfax: " . $response->getContactFax() . "\n";
            echo "\temail: " . $response->getContactEmail() . "\n";
            echo "\tname: " . $response->getContactName() . "\n";
            echo "\tstreet: " . $response->getContactStreet() . "\n";
            echo "\tcity: " . $response->getContactCity() . "\n";
            echo "\tpc: " . $response->getContactZipcode() . "\n";
            echo "\tcc: " . $response->getContactCountrycode() . "\n";
            echo "\torg: " . $response->getContactCompanyname() . "\n";
            echo "\tupID: " . $response->getContactUpdateClientId() . "\n";
            echo "\tpw: " . $response->getContactAuthInfo() . "\n";
            //echo $response->saveXML();
        }

    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }

}

/**
 * @param Metaregistrar\EPP\eppConnection $conn
 * @param string $domainname
 * @param string $authcode
 * @param Metaregistrar\EPP\eppTransferRequest $request
 */

function transferDomain($conn, $domainname, $authcode, $request) {
    try {
        $domain = new eppDomain($domainname);
        if($request === eppTransferRequest::OPERATION_REQUEST){
            $domain->setAuthorisationCode($authcode);
        }
        $transfer = new eppTransferRequest($request, $domain);
        $response = $conn->request($transfer);

        if ($response instanceof Metaregistrar\EPP\eppTransferResponse) {
            echo "Transfer info:\n";

            if ($request === eppTransferRequest::OPERATION_QUERY){
                echo "\tname: " . $response->getDomainName() . "\n";
                echo "\ttrStatus: " . $response->getTransferStatus() . "\n";
                echo "\treID: " . $response->getTransferRequestClientId() . "\n";
                echo "\treDate: " . $response->getTransferRequestDate() . "\n";
                echo "\texDate: " . $response->getTransferExpirationDate() . "\n";
                echo "\tacID: " . $response->getTransferActionClientId() . "\n";
                echo "\tacDate: " . $response->getTransferActionDate() . "\n";
            } else {
                echo "\t" . $response->getResultMessage() . "\n";
            }
        } else {
            echo $response->saveXML();
        }

    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}

/**
 * @param Metaregistrar\EPP\eppConnection $conn
 * @return null|string
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
 * @param Metaregistrar\EPP\eppConnection $conn
 * @param string $messageid
 */
function pollack($conn, $messageid) {
    try {
        $poll = new eppPollRequest(eppPollRequest::POLL_ACK, $messageid);
        if ($response = $conn->request($poll)) {
            /* @var $response Metaregistrar\EPP\eppPollResponse */
            echo "Message $messageid is acknowledged\n";
        }
    } catch (eppException $e) {
        echo $e->getMessage() . "\n";
    }
}
