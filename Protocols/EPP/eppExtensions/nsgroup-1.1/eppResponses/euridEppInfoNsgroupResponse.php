<?php

namespace Metaregistrar\EPP;

/*
<?xml version="1.0" encoding="UTF-8"?>
<epp
    xmlns="urn:ietf:params:xml:ns:epp-1.0"
    xmlns:nsgroup="http://www.eurid.eu/xml/epp/nsgroup-1.1">
    <response>
        <result code="1000">
            <msg>Command completed successfully</msg>
        </result>
        <resData>
            <nsgroup:infData>
                <nsgroup:name>test1</nsgroup:name>
                <nsgroup:ns>ns2.example.com</nsgroup:ns>
                <nsgroup:ns>ns1.example.com</nsgroup:ns>
            </nsgroup:infData>
        </resData>
        <trID>
            <clTRID>6836ef66e5d2a</clTRID>
            <svTRID>ef986e968-ebf7-479d-9822-ad8ebd47075d</svTRID>
        </trID>
    </response>
</epp>
*/
/**
 * Class euridEppInfoNsgroupResponse
 * @package Metaregistrar\EPP
 */
class euridEppInfoNsgroupResponse extends eppResponse
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * @return string
     */
    public function getNsgroupName()
    {
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
    public function getNsgroupHosts()
    {
        $return = [];
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/nsgroup:infData/nsgroup:ns');
        if ($result->length > 0) {
            foreach ($result as $item) {
                $return[] = $item->nodeValue;
            }
        }
        return $return;
    }
}
