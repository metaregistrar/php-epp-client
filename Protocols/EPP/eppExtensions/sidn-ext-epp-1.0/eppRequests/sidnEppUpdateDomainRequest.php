<?php

namespace Metaregistrar\EPP;

class sidnEppUpdateDomainRequest extends eppUpdateDomainRequest
{
    function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null, $forcehostattr=false, $namespacesinroot = true, $usecdata = true, $scheduledDeleteOperation = null, $scheduledDeleteDate = null) {
        if (!$updateinfo) {
            $updateinfo = $objectname;
        }

        parent::__construct($objectname, $addinfo, $removeinfo, $updateinfo, $forcehostattr, $namespacesinroot, $usecdata);

        if ($scheduledDeleteOperation) {
            $this->addScheduledDelete($scheduledDeleteOperation, $scheduledDeleteDate);
        }

        parent::addSessionId();
    }

    private function addScheduledDelete($scheduledDeleteOperation = null, $scheduledDeleteDate = null) {
        $sidnExt = $this->createElement('scheduledDelete:update');
        $operationElement = $this->createElement('scheduledDelete:operation', $scheduledDeleteOperation);
        $sidnExt->appendChild($operationElement);

        if ($scheduledDeleteDate) {
            $dateElement = $this->createElement('scheduledDelete:date', $scheduledDeleteDate);
            $sidnExt->appendChild($dateElement);
        }

        $this->getExtension()->appendChild($sidnExt);
    }
}