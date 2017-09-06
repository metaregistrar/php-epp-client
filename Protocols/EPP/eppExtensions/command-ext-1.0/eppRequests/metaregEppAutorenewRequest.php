<?php
namespace Metaregistrar\EPP;
/*
 * <command>
 * <update>
 * <domain:update>
 * <domain:name>testdomeindnsvanjohn.com</domain:name>
 * </domain:update>
 * </update>
 * <extension>
 * <command-ext:command-ext>
 * <command-ext-domain:domain>
 * <command-ext-domain:update>
 * <command-ext-domain:autoRenew>false</command-ext-domain:autoRenew>
 * </command-ext-domain:update>
 * </command-ext-domain:domain>
 * </command-ext:command-ext>
 * </extension>
 * <clTRID>590c8d500e986</clTRID>
 * </command>
 */

class metaregEppAutorenewRequest extends eppUpdateDomainRequest {

    function __construct($objectname, $onoff) {
        parent::__construct($objectname, null, null, $objectname);
        $this->addAutorenewRequest($onoff);
        $this->addSessionId();
    }

    function addAutorenewRequest($onoff) {
        $commandext = $this->createElement('command-ext:command-ext');
        $domainext = $this->createElement('command-ext-domain:domain');
        $domaininfoext = $this->createElement('command-ext-domain:update');
        $domainoptionext = $this->createElement("command-ext-domain:autoRenew", ($onoff?'true':'false'));
        $domaininfoext->appendChild($domainoptionext);
        $domainext->appendChild($domaininfoext);
        $commandext->appendChild($domainext);
        $this->getExtension()->appendChild($commandext);
    }
}