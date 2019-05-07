<?php
namespace Metaregistrar\EPP;
/**
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:nsgroup="http://www.dns.be/xml/epp/nsgroup-1.0">
    <response>
        <result code="1000">
        <msg>Command completed successfully</msg> </result>
        <resData>
            <nsgroup:infData>
                <nsgroup:name>test</nsgroup:name>
                <nsgroup:ns>bebe.test.be</nsgroup:ns>
            </nsgroup:infData>
        </resData>
        <trID>
        <clTRID>nsgroup-test-456</clTRID>
        <svTRID>dnsbe-0</svTRID> </trID>
    </response>
</epp>
 */
/**
 * Class dnsbeEppInfoNsgroupResponse
 * @package Metaregistrar\EPP
 */
class dnsbeEppInfoNsgroupResponse extends eppResponse {
    function __construct() {
        parent::__construct();
    }

    /**
     *
     * @return string
     */
    public function getNsgroupName() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/nsgroup:infData/nsgroup:name');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * @return array
     */
    public function getNsgroupHosts() {
        $return = [];
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/nsgroup:infData/nsgroup:ns');
        if ($result->length > 0) {
            foreach ($result->item as $item) {
                $return[] = $item->nodeValue;
            }
        }
        return $return;
    }
}