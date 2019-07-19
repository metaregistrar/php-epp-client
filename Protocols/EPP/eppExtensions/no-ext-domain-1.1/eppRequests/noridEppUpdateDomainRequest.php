<?php
namespace Metaregistrar\EPP;

// See https://www.norid.no/no/registrar/system/dokumentasjon/eksempler/?op=dupd for example request/response

class noridEppUpdateDomainRequest extends eppDnssecUpdateDomainRequest {

    use noridEppDomainRequestTrait;

    function __construct($objectname, $addinfo = null, $removeinfo = null, $updateinfo = null, $forcehostattr = false, $namespacesinroot = true) {
        if ($objectname instanceof eppDomain) {
            $domainname = $objectname->getDomainName();
        } else {
            $domainname = $objectname;
        }
        if ($updateinfo === null) {
            $updateinfo = new noridEppDomain($domainname);
        }
        if ($objectname instanceof noridEppDomain) {
            $dataset = $objectname->getExtApplicantDataset();
            if ($dataset['versionNumber'] !== null && $dataset['acceptName'] !== null && $dataset['acceptDate'] !== null) {
                $updateinfo->setExtApplicantDataset($dataset['versionNumber'], $dataset['acceptName'], $dataset['acceptDate']);
            }
        }
        parent::__construct($domainname, $addinfo, $removeinfo, $updateinfo, $forcehostattr, $namespacesinroot);
        $this->setExtDomain($updateinfo);
        $this->addSessionId();
    }

    public function setExtDomain(noridEppDomain $domain) {
        // Add Norid applicant dataset
        $this->addDomainExtApplicantDataset($domain);
    }

    private function addDomainExtApplicantDataset(noridEppDomain $domain) {
        $dataset = $domain->getExtApplicantDataset();
        if ($dataset['versionNumber'] === null || $dataset['acceptName'] === null || $dataset['acceptDate'] === null) {
            if ($domain->getRegistrant()) {
                throw new eppException('A valid applicant dataset is required to perform an owner change on a domain in the Norid registry');
            } else {
                return;
            }
        }
        $datasetElement = $this->createElement('no-ext-domain:applicantDataset');
        $datasetElement->appendChild($this->createElement('no-ext-domain:versionNumber', $dataset['versionNumber']));
        $datasetElement->appendChild($this->createElement('no-ext-domain:acceptName', $dataset['acceptName']));
        $datasetElement->appendChild($this->createElement('no-ext-domain:acceptDate', $dataset['acceptDate']));
        $changeElement = $this->createElement('no-ext-domain:chg');
        $changeElement->appendChild($datasetElement);
        $this->getExtDomainExtension('update')->appendChild($changeElement);
    }

}
