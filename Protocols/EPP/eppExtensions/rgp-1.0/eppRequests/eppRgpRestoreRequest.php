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
    public function __construct(eppDomain $objectname, $addinfo = null, $removeinfo = null, $updateinfo = null)
    {
        if ($objectname instanceof eppDomain) {
            $domainname = $objectname->getDomainname();
        } else {
            $domainname = $objectname;
        }
        if ($updateinfo == null) {
            $updateinfo = new eppDomain($domainname);
        }
        parent::__construct($domainname, null, null, $updateinfo);
        $rgp = $this->createElement('rgp:update');
        //$this->addExtension('xmlns:rgp', 'urn:ietf:params:xml:ns:rgp-1.0');
        $restore = $this->createElement('rgp:restore');
        $restore->setAttribute('op', 'request');
        $rgp->appendChild($restore);
        $this->getExtension()->appendChild($rgp);
        $this->addSessionId();
    }
}