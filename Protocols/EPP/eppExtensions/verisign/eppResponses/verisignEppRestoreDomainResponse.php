<?php
namespace Metaregistrar\EPP;

/**
 * Class verisignEppRestoreDomainResponse
 */
class verisignEppRestoreDomainResponse extends eppUpdateDomainResponse {

    public function getRestoreStatuses() {
        $statuses = null;
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:extension/rgp:upData/rgp:rgpStatus/@s');
        foreach ($result as $status) {
            $statuses[] = $status->nodeValue;
        }
        return $statuses;
    }
}