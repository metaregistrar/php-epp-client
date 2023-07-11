<?php
namespace Metaregistrar\EPP;

class atEppConstants
{
    /*
    |--------------------------------------------------------------------------
    | Namespace and Schema definitions
    |--------------------------------------------------------------------------
    */
    const atExtResultNamespaceUri= "http://www.nic.at/xsd/at-ext-result-1.0";

    const namespaceAtExt='http://www.nic.at/xsd/at-ext-epp-1.0';
    const schemaLocationAtExt='http://www.nic.at/xsd/at-ext-epp-1.0 at-ext-epp-1.0.xsd';

    const namespaceAtExtContact='http://www.nic.at/xsd/at-ext-contact-1.0';
    const schemaLocationAtExtContact='http://www.nic.at/xsd/at-ext-contact-1.0 at-ext-contact-1.0.xsd';


    const namespaceContact='urn:ietf:params:xml:ns:contact-1.0';
    const schemaLocationContact='urn:ietf:params:xml:ns:contact-1.0 contact-1.0.xsd';

    const namespaceDomain='urn:ietf:params:xml:ns:domain-1.0';
    const schemaLocationDomain='urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd';

    const namespaceAtExtDomain = "http://www.nic.at/xsd/at-ext-domain-1.0";
    const schemaLocationAtExtDomain = "http://www.nic.at/xsd/at-ext-domain-1.0 at-ext-domain-1.0.xsd";

    const w3SchemaLocation = "http://www.w3.org/2001/XMLSchema-instance";

    const namespaceMessage="http://www.nic.at/xsd/at-ext-message-1.0";
    const schemaLocationMessage="http://www.nic.at/xsd/at-ext-message-1.0 at-ext-message-1.0.xsd";

    /*
    |--------------------------------------------------------------------------
    | Epp and extension constants
    |--------------------------------------------------------------------------
    */
    const autoHandle = "AUTO";

    /* atEppDelete */
    const domainDeleteScheduleNow = 'now';
    const domainDeleteScheduleExpiration = 'expiration';

}