<?php
namespace Metaregistrar\EPP;

/*
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:poll="http://www.eurid.eu/xml/epp/poll-1.2">
  <command>
    <poll op="req"/>
    <clTRID>poll01-req</clTRID>
  </command>
</epp>
*/

class euridEppPollRequest extends eppPollRequest {

    function __construct($polltype, $messageid = null, $services = null, $extensions = null) {
        parent::__construct($polltype, $messageid, $services, $extensions);
    }

}

