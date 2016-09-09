<?php
namespace Metaregistrar\EPP;

class metaregInfoDomainRequest extends eppInfoDomainRequest {
    private $domaininfoext;

    /**
     * @var metaregInfoDomainOptionsType[]
     */
    private $options = array();

    /**
     * Support tokenized login for the Metaregistrar interface
     * @param string $token
     */
    function __construct($domainname) {
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
        parent::__construct($domainname);


        $this->addSessionId();
    }

    function addOption(metaregInfoDomainOptionsType $option) {
        if (in_array($option, $this->options)) {
            throw new eppException("Duplicate option: $option");
        }
        if (!$this->domaininfoext) {
            $ext = $this->createElement('extension');
            $this->getCommand()->appendChild($ext);

            $commandext = $this->createElement('command-ext:command-ext');
            $ext->appendChild($commandext);

            $infoext = $this->createElement('command-ext-domain:domain');
            $commandext->appendChild($infoext);

            $domaininfoext = $this->createElement('command-ext-domain:info');
            $infoext->appendChild($domaininfoext);

            $this->domaininfoext = $domaininfoext;


        }

        $option = $this->createElement("command-ext-domain:option", $option->getType());
        $this->domaininfoext->appendChild($option);

        $this->options[] = $option;
        $this->addSessionId();
    }
}