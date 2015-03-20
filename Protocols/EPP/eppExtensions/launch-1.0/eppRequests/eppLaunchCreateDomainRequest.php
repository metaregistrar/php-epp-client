<?php
namespace Metaregistrar\EPP;
/*
 * This object contains all the logic to create an EPP create command
 */


class eppLaunchCreateDomainRequest extends eppCreateDomainRequest {
    private $launchCreate = null;

    function __construct(eppDomain $domain) {
        parent::__construct($domain);
    }


    /**
     * @param $name String name of phase to use
     * @param $launchType String type of create to use, application or registration
     * @param null $customName String when $name is custom, you should specify this one.
     */
    public function setLaunchPhase($name, $launchType = null, $customName = null) {
        if (!$this->launchCreate) {
            $this->launchCreate = $this->createElement("launch:create");
            $this->getExtension()->appendchild($this->launchCreate);
        }
        if ($launchType) {
            $this->launchCreate->setAttribute("type", $launchType);
        }
        $launchPhase = $this->createElement("launch:phase", $name);
        if ($customName != null && $name == "custom") {
            $launchPhase->setAttribute("name", $customName);
        }
        $this->launchCreate->appendChild($launchPhase);
        $this->addSessionId();
    }

    /**
     * @param $mark
     * @param $validatorid
     *  C:    <extension>
     *  C:      <launch:create
     *  C:       xmlns:launch="urn:ietf:params:xml:ns:launch-1.0">
     *  C:        <launch:phase>claims</launch:phase>
     *  C:        <launch:codeMark>
     *  C:          <launch:code validatorID="Metaregistrar">testdomein01.frl;v+RTRX62MEaCzQWU8CKIU6ax+73FiuYjz4B9hp7TdhozHJIggNcDTpBYf+VdZfPityGEVpemvUUOxc1J5clDKg</launch:code>
     *  C:        </launch:codeMark>
     *  C:     </launch:create>
     *  C:    </extension>
     */
    public function setLaunchCodeMark($mark, $validatorid) {
        if (!$this->launchCreate) {
            $this->launchCreate = $this->createElement("launch:create");
        }
        $codeMark = $this->createElement('launch:codeMark');
        $launchCode = $this->createElement('launch:code', $mark);
        $launchCode->setAttribute('validatorID', $validatorid);
        $codeMark->appendChild($launchCode);
        $this->launchCreate->appendChild($codeMark);
        $this->addSessionId();
    }

    public function addLaunchClaim($validator, $noticeid, $notafter, $accepteddate) {
        if (!$this->launchCreate) {
            $this->launchCreate = $this->createElement("launch:create");
        }
        $notice = $this->createElement('launch:notice');
        $noticeid = $this->createElement('launch:noticeID', $noticeid);
        $noticeid->setAttribute('validatorID', $validator);
        $notice->appendChild($noticeid);
        $notice->appendChild($this->createElement('launch:notAfter', $notafter));
        $notice->appendChild($this->createElement('launch:acceptedDate', $accepteddate));
        $this->launchCreate->appendChild($notice);
        $this->addSessionId();
    }
}
