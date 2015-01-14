<?php
/*
 * This object contains all the logic to create an EPP create command
 */


class eppLaunchCreateDomainRequest extends eppCreateDomainRequest
{

    function __construct(eppDomain $domain)
    {
        $this->setForcehostattr(true);
        parent::__construct($domain);

    }


    /**
     * @param $name String name of phase to use
     * @param $launchType String type of create to use, application or registration
     * @param null $customName String when $name is custom, you should specify this one.
     * @throws RegistrarException
     */
    public function setLaunchPhase($name, $launchType, $customName = null)
    {
        if (($launchType != "application") && ($launchType != "registration"))
        {
            throw new RegistrarException("launchType should be either 'application' or 'registration'");
        }
        $launchCreate = $this->createElement("launch:create");
        $launchCreate->setAttribute("type", $launchType);

        $launchPhase = $this->createElement("launch:phase", $name);
        if ($customName != null && $name == "custom")
        {
            $launchPhase->setAttribute("name", $customName);
        }
        $launchCreate->appendChild($launchPhase);

        $this->getExtension()->appendchild($launchCreate);
        $this->addSessionId();
    }

}
