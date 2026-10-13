<?php
namespace Metaregistrar\EPP;

/**
 * Class eppRgpRestoreRequest
 */
class eppRgpRestoreRequest extends eppUpdateDomainRequest
{
    /**
     * eppRgpRestoreRequest constructor.
     * @param eppDomain      $objectname
     * @param eppDomain|null $addinfo
     * @param eppDomain|null $removeinfo
     * @param eppDomain|null $updateinfo
     */
    public function __construct(?eppDomain $objectname)
    {
        if ($objectname instanceof eppDomain) {
            $domainname = $objectname->getDomainname();
        } else {
            $domainname = $objectname;
        }
        parent::__construct($domainname, null, null, new eppDomain($domainname));
        $rgp = $this->createElement('rgp:update');
        $restore = $this->createElement('rgp:restore');
        $restore->setAttribute('op', 'request');
        $rgp->appendChild($restore);
        $this->getExtension()->appendChild($rgp);
        $this->addSessionId();
    }
}