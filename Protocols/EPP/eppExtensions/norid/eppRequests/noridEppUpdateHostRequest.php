<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=hupd for example request/response

class noridEppUpdateHostRequest extends eppUpdateHostRequest {

    function __construct($objectname, $addInfo = null, $removeInfo = null, $updateInfo = null, $namespacesinroot = true) {
        $this->setNamespacesinroot($namespacesinroot);
        parent::__construct(eppRequest::TYPE_UPDATE);

        if ($objectname instanceof noridEppHost) {
            $hostname = $objectname->getHostname();
        } else {
            if (strlen($objectname)) {
                $hostname = $objectname;
            } else {
                throw new eppException("Object name must be valid string on eppUpdateHostRequest");
            }
        }

        if (($addInfo instanceof noridEppHost) || ($removeInfo instanceof noridEppHost) || ($updateInfo instanceof noridEppHost)) {
            $this->updateHost($hostname, $addInfo, $removeInfo, $updateInfo);
        } else {
            throw new eppException('addInfo, removeInfo and updateInfo need to be noridEppHost objects');
        }

        $this->addSessionId();
    }

    public function updateHost($hostname, $addInfo, $removeInfo, $updateInfo) {
        // Create host object structure
        $this->hostobject->appendChild($this->createElement('host:name', $hostname));
        if ($addInfo instanceof noridEppHost) {
            $addcmd = $this->createElement('host:add');
            $this->addHostChanges($addcmd, $addInfo);
            $this->hostobject->appendChild($addcmd);

            // Add Norid EPP extensions
            if (!is_null($addInfo->getExtContact()) || !is_null($addInfo->getExtSponsoringClientID())) {
                $extaddcmd = $this->createElement('no-ext-host:add');
                $this->addHostExtChanges($extaddcmd, $addInfo);
                $this->getHostExtension()->appendChild($extaddcmd);
            }
        }
        if ($removeInfo instanceof noridEppHost) {
            $remcmd = $this->createElement('host:rem');
            $this->addHostChanges($remcmd, $removeInfo);
            $this->hostobject->appendChild($remcmd);

            // Add Norid EPP extensions
            if (!is_null($removeInfo->getExtContact()) || !is_null($removeInfo->getExtSponsoringClientID())) {
                $extremcmd = $this->createElement('no-ext-host:rem');
                $this->addHostExtChanges($extremcmd, $removeInfo);
                $this->getHostExtension()->appendChild($extremcmd);
            }
        }
        if ($updateInfo instanceof noridEppHost) {
            if ($updateInfo->getHostname() != $hostname) {
                // The only field that can change in the update commmand is the hostname
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

    private function addHostChanges(\DOMElement $element, noridEppHost $host) {
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

    private function addHostExtChanges(\DOMElement $element, noridEppHost $host) {
        if (!is_null($host->getExtContact())) {
            $element->appendChild($this->createElement('no-ext-host:contact', $host->getExtContact()));
        }
    }

}