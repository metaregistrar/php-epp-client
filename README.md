PHP EPP Client
==============
[![Latest Stable Version](https://poser.pugx.org/metaregistrar/php-epp-client/v/stable)](https://packagist.org/packages/metaregistrar/php-epp-client)
[![Total Downloads](https://poser.pugx.org/metaregistrar/php-epp-client/downloads)](https://packagist.org/packages/metaregistrar/php-epp-client)
[![Latest Unstable Version](https://poser.pugx.org/metaregistrar/php-epp-client/v/unstable)](https://packagist.org/packages/metaregistrar/php-epp-client)



**Object-oriented PHP EPP Client.**

Welcome to the "object-oriented EPP client in PHP" project.

This project supports the following:
------------------------------------

- Full EPP 57xx RFC standard
- Extensible PHP objects to add registry-specific additions
- Rock-solid object-oriented programming, object inheritance and exception handling
- Interface tested and in use with a registrar that handles 600.000+ domain names
- Public/private key client certificates for connecting to EPP service (for example see Registries/IIS)
- Connection to EPP via HTTP/SSL (for example see Registries/Openprovider)
- DNSSEC transactions
- Registry sunrise, landrush and claims periods (draft-ietf-eppext-launchphase)
- TMCH/TMDB CNIS retrieval examples with standard ICANN registrar message

This code will provide you with a fully functional EPP client to connect to any registry world-wide that supports EPP.
All code is organized in objects, so connecting to a new registry is just a matter of object re-use and extension.
Sample registry connections are provided in the Registries directory.

------

Currently supported registries:
- SIDN (.nl)
- dotAmsterdam
- Donuts
- EurID (.eu)
- DNS Belgium (.be .vlaanderen .brussels)
- .CO.NL
- dotFRL
- IIS (.nu and .se)
- CarDNS (.hr)
- Metaregistrar
- Nic.AT (.at)
- Key Systems RRPPROXY
- .PT
- Switch (.ch)
- Openprovider
- Ficora (.fi)
- DNS.PT (.pt)
- Norid (.no)
- Arnes (.si)
- Nic.lv (.lv)
- SK-NIC (.sk)


All code changes are tested automatically with the phpunit tests in the Tests directory

Example scipts in the main directory:

| File                   | Description                                                                                                                 |
-------------------------|------------------------------------------------------------------------------------------------------------------------------
|checkdomain.php         | Check one domain name.                                                                                                      |
|timeddomaincheck.php    | Check multiple domain names and check how fast the registry is.                                                             |
|registerdomain.php      | Register a domain name.                                                                                                     |
|changepassword.php      | Change EPP password.                                                                                                        |
|infodomain.php          | Get information on a domain name.                                                                                           |
|modifydomain.php        | Update a domain name with new info.                                                                                         |
|createcontact.php       | Create a contact object.                                                                                                    |
|updatecontact.php       | Update contact details of a created contact object.                                                                         |
|createdomain.php        | Register a domain name in general availability phase.                                                                       |
|signdomain.php          | DNSSEC sign a domain name.                                                                                                  |
|poll.php                | List registry poll messages and confirm a message.                                                                          |
|checklaunchdomain.php   | Check domain name in 'claims' phase of draft-ietf-eppext-launchphase and check if the domain is free.                       |
|createlaunchdomain.php  | Register domain name in 'claims' phase of draft-ietf-eppext-launchphase.                                                    |
|checktmchdomain.php     | Check domain name in the 'claims' phase and check if the domain has a claim and retrieve the cnis info from the TMCH.       |
|test-claim.php          | Connect to the TMCH test service and retrieve the list of domain names, query cnis info and display icann std warning msg.  |
|createclaimeddomain.php | Create domain name that has a tmch claim in the claims phase of the launch.                                                 |




How to use this repository
--------------------------

1. Check out the latest version from github. (Or `composer require metaregistrar/php-epp-client`)
2. Use the `Examples/checkdomain.php` and create a connection to your favorite registry (for example `new Metaregistrar\EPP\metaregEppConnection()`).
3. Create a `settings.ini` in with the following contents:
```
        interface=eppConnection
        hostname=ssl://epp-ote.metaregistrar.com
        port=7000
        userid=xxxxxxxx
        password=xxxxxxxxx
        logging=true
        certificatefile=/home/xxxxxx/xxxxxxx.pem
        certificatekey=/home/xxxxxx/xxxxxxx.key
        certificatepassword=xxxxxxx
        verifypeer=true/false
        verifypeername=true/false
        allowselfsigned=true/false
```
4. Now, `checkdomain.php` should be functioning and checking domains.

**If** you do not want to use `settings.ini` files in the Registries directory, you can create a connection as follows: 
`$conn = Metaregistrar\EPP\eppConnection::create('path-to-settings.ini');`

Or set all parameters individually:
```
$conn = new Metaregistrar\EPP\eppConnection();
$conn->setHostname('ssl://epp-ote.metaregistrar.com'); // Hostname may vary depending on the registry selected
$conn->setPort(7000); // Port may vary depending on the registry selected
$conn->setUsername('xxxxxxxx');
$conn->setPassword('xxxxxxxxx');
```
