<?php
namespace Metaregistrar\EPP;
/*
 * <command>
 * <update>
 * <domain:update>
 * <domain:name>testdomein.com</domain:name>
 * </domain:update>
 * </update>
 * <extension>
 * <command-ext:command-ext>
 * <command-ext-domain:domain>
 * <command-ext-domain:update>
 * <command-ext-domain:privacy>true</command-ext-domain:privacy>
 * </command-ext-domain:update>
 * </command-ext-domain:domain>
 * </command-ext:command-ext>
 * </extension>
 * <clTRID>590c89464b326</clTRID>
 * </command>
 */

class metaregEppPrivacyRequest extends eppUpdateDomainRequest {

    function __construct($objectname, $onoff) {
        parent::__construct($objectname, null, null, $objectname);
        $this->addPrivacyRequest($onoff);
        $this->addSessionId();
    }

    function addPrivacyRequest($onoff) {
        $commandext = $this->createElement('command-ext:command-ext');
        $domainext = $this->createElement('command-ext-domain:domain');
        $domaininfoext = $this->createElement('command-ext-domain:update');
        $domainoptionext = $this->createElement("command-ext-domain:privacy", ($onoff?'true':'false'));
        $domaininfoext->appendChild($domainoptionext);
        $domainext->appendChild($domaininfoext);
        $commandext->appendChild($domainext);
        $this->getExtension()->appendChild($commandext);
    }
}