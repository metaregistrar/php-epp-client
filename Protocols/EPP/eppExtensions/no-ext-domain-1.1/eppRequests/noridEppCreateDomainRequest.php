<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=dcre-ad for example request/response

class noridEppCreateDomainRequest extends eppCreateDomainRequest {

    use noridEppDomainRequestTrait;

    function __construct(noridEppDomain $domain, $forcehostattr = false, $namespacesinroot = true) {
        parent::__construct($domain, $forcehostattr, $namespacesinroot);
        $this->setExtDomain($domain);
        $this->addSessionId();
    }

    public function setExtDomain(noridEppDomain $domain) {
        // Add Norid applicant dataset
        $this->addDomainExtApplicantDataset($domain);
    }

    private function addDomainExtApplicantDataset(noridEppDomain $domain) {
        $dataset = $domain->getExtApplicantDataset();
        if (is_null($dataset['versionNumber']) || is_null($dataset['acceptName']) || is_null($dataset['acceptDate'])) {
            throw new eppException('A valid applicant dataset is required to create a domain in the Norid registry');
        }
        $datasetElement = $this->createElement('no-ext-domain:applicantDataset');
        $datasetElement->appendChild($this->createElement('no-ext-domain:versionNumber', $dataset['versionNumber']));
        $datasetElement->appendChild($this->createElement('no-ext-domain:acceptName', $dataset['acceptName']));
        $datasetElement->appendChild($this->createElement('no-ext-domain:acceptDate', $dataset['acceptDate']));
        $this->getExtDomainExtension('create')->appendChild($datasetElement);
    }

}