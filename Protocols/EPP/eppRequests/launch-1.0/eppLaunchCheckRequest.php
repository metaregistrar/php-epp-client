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

class eppLaunchCheckRequest extends eppCheckRequest {
    CONST TYPE_CLAIMS = 'claims';
    CONST TYPE_AVAIL = 'avail';
    CONST PHASE_SUNRISE = 'sunrise';
    CONST PHASE_LANDRUSH = 'landrush';
    CONST PHASE_CLAIMS = 'claims';
    CONST PHASE_OPEN = 'open';
    CONST PHASE_CUSTOM = 'custom';
    CONST STATUS_PENDINGVALIDATION = 'pendingValidation';
    CONST STATUS_VALIDATED = 'validated';
    CONST STATUS_INVALID = 'invalid';
    CONST STATUS_PENDINGALLOCATION = 'pendingAllocation';
    CONST STATUS_ALLOCATED = 'allocated';
    CONST STATUS_REJECTED = 'rejected';
    CONST STATUS_CUSTOM = 'custom';

    function __construct($checkrequest) {
        parent::__construct($checkrequest);
        $this->addExtension('xmlns:launch', 'urn:ietf:params:xml:ns:launch-1.0');
    }


    /**
     * @param $name String name of phase to use
     * @param $customName String when $name is PHASE_CUSTOM you should specify this one.
     * @param $type String Type of request, 'claims' or 'avail'
     */
    public function setLaunchPhase($name, $customName = null, $type = self::TYPE_AVAIL) {
        $launchCheck = $this->createElement("launch:check");
        $launchCheck->setAttribute("type", $type);

        $launchPhase = $this->createElement("launch:phase", $name);
        if ($name == self::PHASE_CUSTOM) {
            if ($customName != null) {
                $launchPhase->setAttribute("name", $customName);
            } else {
                throw new eppException ("customName must be filled when LaunchPhase is Custom");
            }
        }
        $launchCheck->appendChild($launchPhase);
        $this->getExtension()->appendchild($launchCheck);
        $this->addSessionId();
    }
}
