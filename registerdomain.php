<?php
// Base EPP objects
include_once('Protocols/EPP/eppConnection.php');
include_once('Protocols/EPP/eppRequests/eppIncludes.php');
include_once('Protocols/EPP/eppResponses/eppIncludes.php');
include_once('Protocols/EPP/eppData/eppIncludes.php');
// Connection object to Metaregistrar EPP server - this contains your userid and passwords!
include_once('Registries/Metaregistrar/metaregEppConnection.php');
// Base EPP commands: hello, login and logout
include_once('base.php');


