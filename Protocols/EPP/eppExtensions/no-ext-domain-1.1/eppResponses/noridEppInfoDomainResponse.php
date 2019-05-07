<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=dinf-ad for example request/response

class noridEppInfoDomainResponse extends eppInfoDomainResponse {

    use noridEppResponseTrait;

    /**
     * @return array|null
     */
    public function getExtApplicantDataset() {
        $result = $this->xPath()->query('/epp:epp/epp:response/epp:extension/no-ext-domain:infData');
        if (!is_object($result) || $result->length < 1) {
            return null;
        }
        $applicant_dataset_elem = $result->item(0)->getElementsByTagName('applicantDataset');
        if (!is_object($applicant_dataset_elem) || $applicant_dataset_elem->length < 1) {
            return null;
        }
        return array(
            'versionNumber'  => $this->queryPath('versionNumber', $applicant_dataset_elem->item(0)),
            'acceptName'     => $this->queryPath('acceptName', $applicant_dataset_elem->item(0)),
            'acceptDate'     => $this->queryPath('acceptDate', $applicant_dataset_elem->item(0)),
            'updateClientID' => $this->queryPath('updateClientID', $applicant_dataset_elem->item(0)),
            'updateDate'     => $this->queryPath('updateDate', $applicant_dataset_elem->item(0)),
        );
    }

}