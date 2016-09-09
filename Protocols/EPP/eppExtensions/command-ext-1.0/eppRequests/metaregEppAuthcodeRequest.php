<?php
namespace Metaregistrar\EPP;

/*
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:command-ext-domain="http://www.metaregistrar.com/epp/command-ext-domain-1.0" xmlns:command-ext="http://www.metaregistrar.com/epp/command-ext-1.0" xmlns:ext="http://www.metaregistrar.com/epp/ext-1.0">
<command>
<info>
<domain:info xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
<domain:name hosts="all">ul21chykdq6bxx577fgm.be</domain:name>
</domain:info>
</info>
<extension>
<command-ext:command-ext>
<command-ext-domain:domain>
  <command-ext-domain:info>
    <command-ext-domain:option>dnsbe-request-authcode</command-ext-domain:option>
  </command-ext-domain:info>
</command-ext-domain:domain>
</command-ext:command-ext>
</extension>
<clTRID>5181045b28268</clTRID>
</command>
</epp>
 */


class metaregEppAuthcodeRequest extends eppInfoDomainRequest
{

    function __construct(eppDomain $domain)
    {
        parent::__construct($domain);
        $this->addAuthcodeRequest();
        $this->addSessionId();
    }

    function addAuthcodeRequest() {
        $commandext = $this->createElement('command-ext:command-ext');
        $domainext = $this->createElement('command-ext-domain:domain');
        $domaininfoext = $this->createElement('command-ext-domain:info');
        $domainoptionext = $this->createElement("command-ext-domain:option", "dnsbe-request-authcode");
        $domaininfoext->appendChild($domainoptionext);
        $domainext->appendChild($domaininfoext);
        $commandext->appendChild($domainext);
        $this->getExtension()->appendChild($commandext);

    }
}