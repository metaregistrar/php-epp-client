<?php
namespace Metaregistrar\EPP;

class eppUpdateHostRequest extends eppHostRequest {
    /**
     * @var \DOMElement
     */
    public $hostobject;

    function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null, $namespacesinroot = true) {
        $this->setNamespacesinroot($namespacesinroot);
        parent::__construct(eppRequest::TYPE_UPDATE);

        if ($objectname instanceof eppHost) {
            $hostname = $objectname->getHostname();
        } else {
            if (strlen($objectname)) {
                $hostname = $objectname;
            } else {
                throw new eppException("Object name must be valid string on eppUpdateHostRequest");
            }
        }
        if (($addinfo instanceof eppHost) || ($removeinfo instanceof eppHost) || ($updateinfo instanceof eppHost)) {
            $this->updateHost($hostname, $addinfo, $removeinfo, $updateinfo);
        } else {
            throw new eppException('addinfo, removeinfo and updateinfo need to be eppDomain, eppContact or eppHost objects');
        }
        $this->addSessionId();
    }

    function __destruct() {
        parent::__destruct();
    }


    /**
     *
     * @param string $hostname
     * @param eppHost $addInfo
     * @param eppHost $removeInfo
     * @param eppHost $updateInfo
     * @return \DOMElement
     * @throws eppException
     */
    public function updateHost($hostname, $addInfo, $removeInfo, $updateInfo) {
        #
        # Object create structure
        #
        $this->hostobject->appendChild($this->createElement('host:name', $hostname));
        if ($addInfo instanceof eppHost) {
            $addcmd = $this->createElement('host:add');
            $this->addHostChanges($addcmd, $addInfo);
            $this->hostobject->appendChild($addcmd);
        }
        if ($removeInfo instanceof eppHost) {
            $remcmd = $this->createElement('host:rem');
            $this->addHostChanges($remcmd, $removeInfo);
            $this->hostobject->appendChild($remcmd);
        }
        if ($updateInfo instanceof eppHost) {
            if ($updateInfo->getHostname() != $hostname) {
                // The update command command can only contain a hostname
                $chgcmd = $this->createElement('host:chg');
                if (strlen($updateInfo->getHostname()) > 0) {
                    $chgcmd->appendChild($this->createElement('host:name', $updateInfo->getHostname()));
                } else {
                    throw new eppException('New hostname must be specified on host:update command');
                }
                $this->hostobject->appendChild($chgcmd);
            }
        }
    }

    /**
     *
     * @param \DOMElement $element
     * @param eppHost $host
     */
    private function addHostChanges($element, eppHost $host) {
        $addresses = $host->getIpAddresses();
        if (is_array($addresses)) {
            foreach ($addresses as $address => $type) {
                $ipaddress = $this->createElement('host:addr', $address);
                $ipaddress->setAttribute('ip', $type);
                $element->appendChild($ipaddress);
            }
        }
        $statuses = $host->getHostStatuses();
        if (is_array($statuses)) {
            foreach ($statuses as $status) {
                $stat = $this->createElement('host:status');
                $stat->setAttribute('s', $status);
                $element->appendChild($stat);
            }
        }
    }

}