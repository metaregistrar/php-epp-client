<?php
namespace Metaregistrar\EPP;

/*
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd">
    <response>
        <result code="1301">
            <msg>Command completed successfully; ack to dequeue</msg>
        </result>
        <msgQ count="1" id="184328965">
        <qDate>2007-02-23T06:57:17.85Z</qDate>
        <msg>Domain deleted: example.at</msg> </msgQ>
        <resData>
            <message xmlns="http://www.nic.at/xsd/at-ext-message-1.0" xsi:schemaLocation="http://www.nic.at/xsd/at-ext-message-1.0 at-ext-message-1.0.xsd" type="domain-deleted">
                <desc>Domain deleted: example.at</desc>
                <data>
                    <entry name="domain">example.at</entry>
                </data>
            </message>
        </resData>
        <trID>
            <clTRID>ABC-12345</clTRID>
            <svTRID>20070223065827405616-000-nicat</svTRID>
        </trID>
    </response>
</epp>
*/

/**
 * Class atEppPollResponse
 * @package Metaregistrar\EPP
 */
class atEppPollResponse extends eppPollResponse
{
    const TYPE_RESPONSE_PENDING = "response-pending"; # Pending Tickets
    const TYPE_RESPONSE_COPY = "response-copy"; # Delete of unused handles
    const TYPE_DOMAIN_DELETED = "domain-deleted";
    const TYPE_DOMAIN_TRANSFERED_AWAY = "domain-transfered-away";
    const TYPE_DOMAIN_LOCKED_LEGAL_DEPT = "domain-locked-legal-dept";
    const TYPE_DOMAIN_LOCKED_CUSTOMER = "domain-locked-customer";
    const TYPE_DOMAIN_LOCKED_NONPAYMENT = "domain-locked-nonpayment";
    const TYPE_DOMAIN_LOCKED_TECHNICAL = "domain-locked-technical";
    const TYPE_DOMAIN_LOCKED_BANKRUPTCY = "domain-locked-bankruptcy";
    const TYPE_DOMAIN_INFO_LOCK_CUSTOMER = "domain-info-lock-customer";
    const TYPE_DOMAIN_INFO_LOCK_BANKRUPTCY = "domain-info-lock-bankruptcy";
    const TYPE_DOMAIN_UNLOCKED_LEGAL_DEPT = "domain-unlocked-legal-dept";
    const TYPE_DOMAIN_UNLOCKED_CUSTOMER = "domain-unlocked-customer";
    const TYPE_DOMAIN_UNLOCKED_NONPAYMENT = "domain-unlocked-nonpayment";
    const TYPE_DOMAIN_UNLOCKED_TECHNICAL = "domain-unlocked-technical";
    const TYPE_DOMAIN_UNLOCKED_BANKRUPTCY = "domain-unlocked-bankruptcy";
    const TYPE_DOMAIN_TRANSFER_ABORTED = "domain-transfer-aborted";
    const TYPE_DOMAIN_TRANSFERTOKEN_FORWARD = "domain-transfertoken-forward";

    const LOCKTYPE_SRP = "SRP"; # Sperre Rechtsabteilung
    const LOCKTYPE_SKW = "SKW"; # Sperre Kundenwunsch
    const LOCKTYPE_SNZ = "SNZ"; # Sperre Nichtzahlung
    const LOCKTYPE_SPT = "SPT"; # Sperre technische Probleme
    const LOCKTYPE_SKO = "SKO"; # Sperre Konkurs

    /**
     * atEppPollResponse constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * @return null|string
     */
    public function getType()
    {
        $xpath = $this->xPath();
        $xpath->registerNamespace("at-ext", atEppConstants::namespaceMessage);
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/at-ext:message');
        if ($result->length > 0) {
            return $result->item(0)->getAttribute('type');
        } else {
            return null;
        }
    }

    /**
     * @return null|string
     */
    public function getDesc()
    {
        $xpath = $this->xPath();
        $xpath->registerNamespace("at-ext", atEppConstants::namespaceMessage);
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/at-ext:message/at-ext:desc');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * @return null|string
     */
    public function getObjecttype()
    {
        $xpath = $this->xPath();
        $xpath->registerNamespace("at-ext", atEppConstants::namespaceMessage);
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/at-ext:message/at-ext:data/at-ext:entry[@name="objecttype"]');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * @return null|string
     */
    public function getCommand()
    {
        $xpath = $this->xPath();
        $xpath->registerNamespace("at-ext", atEppConstants::namespaceMessage);
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/at-ext:message/at-ext:data/at-ext:entry[@name="command"]');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * @return null|string
     */
    public function getObjectname()
    {
        $xpath = $this->xPath();
        $xpath->registerNamespace("at-ext", atEppConstants::namespaceMessage);
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/at-ext:message/at-ext:data/at-ext:entry[@name="objectname"]');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * @return null|string
     */
    public function getDomain()
    {
        $xpath = $this->xPath();
        $xpath->registerNamespace("at-ext", atEppConstants::namespaceMessage);
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/at-ext:message/at-ext:data/at-ext:entry[@name="domain"]');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * @return null|string
     */
    public function getLocktype()
    {
        $xpath = $this->xPath();
        $xpath->registerNamespace("at-ext", atEppConstants::namespaceMessage);
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/at-ext:message/at-ext:data/at-ext:entry[@name="locktype"]');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * @return null|string
     */
    public function getScheduledate()
    {
        $xpath = $this->xPath();
        $xpath->registerNamespace("at-ext", atEppConstants::namespaceMessage);
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/at-ext:message/at-ext:data/at-ext:entry[@name="scheduledate"]');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * @return null|string
     */
    public function getToken()
    {
        $xpath = $this->xPath();
        $xpath->registerNamespace("at-ext", atEppConstants::namespaceMessage);
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/at-ext:message/at-ext:data/at-ext:entry[@name="token"]');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

}