<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=dupd for example request/response

class noridEppUpdateDomainRequest extends eppDnssecUpdateDomainRequest {

    use noridEppDomainRequestTrait;

    function __construct(noridEppDomain $domain, $addinfo = null, $removeinfo = null, $updateinfo = null, $forcehostattr = false, $namespacesinroot = true) {
        parent::__construct($domain, $addinfo, $removeinfo, $updateinfo, $forcehostattr, $namespacesinroot);
        $this->setExtDomain($domain);
        $this->addSessionId();
    }

    public function setExtDomain(noridEppDomain $domain) {
        // Add Norid applicant dataset
        $this->addDomainExtApplicantDataset($domain);
    }

    private function addDomainExtApplicantDataset(noridEppDomain $domain) {
        $dataset = $domain->getExtApplicantDataset();
        if ($domain->getRegistrant() && (is_null($dataset['versionNumber']) || is_null($dataset['acceptName']) || is_null($dataset['acceptDate']))) {
            throw new eppException('A valid applicant dataset is required to perform an owner change on a domain in the Norid registry');
        }
        $datasetElement = $this->createElement('no-ext-domain:applicantDataset');
        $datasetElement->appendChild($this->createElement('no-ext-domain:versionNumber', $dataset['versionNumber']));
        $datasetElement->appendChild($this->createElement('no-ext-domain:acceptName', $dataset['acceptName']));
        $datasetElement->appendChild($this->createElement('no-ext-domain:acceptDate', $dataset['acceptDate']));
        $this->getExtDomainExtension('update')->appendChild($datasetElement);
    }

}
