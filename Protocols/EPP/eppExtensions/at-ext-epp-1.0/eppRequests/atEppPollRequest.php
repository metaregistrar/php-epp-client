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

<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd">
    <command>
       <poll op="req"/>
        <clTRID>ABC-12345</clTRID>
    </command>
</epp>
*/

class atEppPollRequest extends eppPollRequest
{
    use atEppCommandTrait;

    protected $atEppExtensionChain = null;

    function __construct($polltype, $messageid = null, $services = null, ?atEppExtensionChain $atEppExtensionChain=null)
    {
        $this->atEppExtensionChain = $atEppExtensionChain;
        parent::__construct($polltype, $messageid, $services);
        $this->setAtExtensions();
    }
}