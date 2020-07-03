<?php
namespace Metaregistrar\EPP;

/*
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:poll-1.2="http://www.eurid.eu/xml/epp/poll-1.2">
    <response>
        <result code="1301">
            <msg>Command completed successfully; ack to dequeue</msg>
        </result>
        <msgQ count="1" id="667f5673-df48-4edc-8e57-ca538eb88c7c">
            <qDate>2016-05-25T12:31:47.866Z</qDate>
            <msg>Suspended domain name: domain-with-nameserver.eu</msg>
        </msgQ>
        <resData>
            <poll-1.2:pollData>
                <poll-1.2:context>LEGAL</poll-1.2:context>
                <poll-1.2:objectType>DOMAIN</poll-1.2:objectType>
                <poll-1.2:object>domain-with-nameserver.eu</poll-1.2:object>
                <poll-1.2:objectUnicode>domain-with-nameserver.eu</poll-1.2:objectUnicode>
                <poll-1.2:action>SUSPENDED</poll-1.2:action>
                <poll-1.2:code>2110</poll-1.2:code>
                <poll-1.2:detail>test</poll-1.2:detail>
            </poll-1.2:pollData>
        </resData>
        <trID>
            <clTRID>poll01-req</clTRID>
            <svTRID>ef4849f3e-918f-47f8-9b15-b8802d900a2a</svTRID>
        </trID>
    </response>
</epp>
*/

class euridEppPollResponse extends eppPollResponse{
    const TYPE_DOMAIN = "DOMAIN";
    const TYPE_WATERMARK = "WATERMARK";
    const TYPE_CONTACT = "CONTACT";
    const TYPE_KEYGROUP = "KEYGROUP";
    const TYPE_NAMESERVERGROUP = "NAMESERVERGROUP";
    const TYPE_PAYMENT = "PAYMENT";

    function __construct() {
        parent::__construct();
    }

    public function getContext(){
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/poll-1.2:pollData/poll-1.2:context');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }
    public function getObjectType(){
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/poll-1.2:pollData/poll-1.2:objectType');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    public function getObject(){
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/poll-1.2:pollData/poll-1.2:object');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    public function getObjectUnicode(){
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/poll-1.2:pollData/poll-1.2:objectUnicode');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    public function getAction(){
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/poll-1.2:pollData/poll-1.2:action');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    public function getCode(){
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/poll-1.2:pollData/poll-1.2:code');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    public function getDetail(){
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/poll-1.2:pollData/poll-1.2:detail');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

}