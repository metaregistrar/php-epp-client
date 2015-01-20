<?php
namespace Metaregistrar\EPP;
/*
 * This object contains all the logic to create an EPP check command for a launch phase
 */

/**
C:<?xml version="1.0" encoding="UTF-8" standalone="no"?>
C:<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
C:  <command>
C:   <check>
C:    <domain:check
C:     xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
C:      <domain:name>example1.tld</domain:name>
C:      <domain:name>example2.tld</domain:name>
C:    </domain:check>
C:   </check>
C:   <extension>
C:    <launch:check
C:     xmlns:launch="urn:ietf:params:xml:ns:launch-1.0"
C:     type="avail">
C:      <launch:phase name="idn-release">custom</launch:phase>
C:    </launch:check>
C:   </extension>
C:   <clTRID>ABC-12345</clTRID>
C:  </command>
C:</epp>
 */

class eppLaunchCheckRequest extends eppCheckRequest
{
    function __construct($checkrequest)
    {
        parent::__construct($checkrequest);
        $this->addExtension('xmlns:launch', 'urn:ietf:params:xml:ns:launch-1.0');

    }


    /**
     * @param $name String name of phase to use
     * @param null $customName String when $name is custom, you should specify this one.
     */
    public function setLaunchPhase($name, $customName = null)
    {
        $launchCheck = $this->createElement("launch:check");
        $launchCheck->setAttribute("type", "avail");

        $launchPhase = $this->createElement("launch:phase", $name);
        if ($customName != null && $name == "custom")
        {
            $launchPhase->setAttribute("name", $customName);
        }

        $launchCheck->appendChild($launchPhase);
        $this->getExtension()->appendchild($launchCheck);
        $this->addSessionId();
    }

}
