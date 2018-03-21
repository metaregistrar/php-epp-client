<?php
namespace Metaregistrar\EPP;
/**
 * Class euridEppRegistrarInfoResponse
 * @package Metaregistrar\EPP
 *
<resData>
    <registrar:infData></registrar:infData>
</resData>
<extension>
    <dnssi:ext>
        <dnssi:infData>
            <dnssi:registrar>
                <dnssi:balance>0.0</dnssi:balance>
                <dnssi:contractExpire>2011-12-01T09:00:00.000Z</dnssi:contractExpire>
                <dnssi:acl>
                    <dnssi:eppServer access="landRush">
                        <dnssi:addr ip="v4">93.103.22.192</dnssi:addr>
                    </dnssi:eppServer>
                    <dnssi:eppServer access="regular">
                        <dnssi:addr ip="v4">127.0.0.1</dnssi:addr>
                    </dnssi:eppServer>
                    <dnssi:matsi>
                        <dnssi:addr ip="v4">127.0.0.1</dnssi:addr>
                        <dnssi:addr ip="v4">192.168.10.0/24</dnssi:addr>
                    </dnssi:matsi>
                    <dnssi:whois>
                        <dnssi:addr ip="v4">127.0.0.1</dnssi:addr>
                    </dnssi:whois>
                </dnssi:acl>
                <dnssi:orgId>G6928</dnssi:orgId>
                <dnssi:deskOfficerId>O22007</dnssi:deskOfficerId>
                <dnssi:techId>O10</dnssi:techId>
                <dnssi:billingId>O8877</dnssi:billingId>
                <dnssi:url type="loc">
                    <dnssi:link>url</dnssi:link>
                    <dnssi:text>url test</dnssi:text>
                </dnssi:url>
                <dnssi:url type="int">
                    <dnssi:link>url eng</dnssi:link>
                    <dnssi:text>url eng text</dnssi:text>
                </dnssi:url>
            </dnssi:registrar>
        </dnssi:infData>
    </dnssi:ext>
</extension>

 */
class siEppRegistrarInfoResponse extends eppResponse {

    function __construct() {
        parent::__construct();
    }

    public function getBalance() {
        $xpath = $this->xPath();
        $result = $xpath->query('//dnssi:balance');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }
}