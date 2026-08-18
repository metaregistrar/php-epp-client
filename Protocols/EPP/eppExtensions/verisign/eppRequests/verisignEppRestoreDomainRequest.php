<?php
namespace Metaregistrar\EPP;

class verisignEppRestoreDomainRequest extends eppUpdateDomainRequest {
    use verisignEppExtension;
    const TYPE_REQUEST = 'request';
    const TYPE_REPORT = 'report';
    /**
     * verisignEppRestoreDomainRequest constructor.
     *
     * @param string $op
     * @param string $domain
     * @param string $expire   赎回前的到期时间
     * @param string $reason   赎回原因
     * @throws eppException
     */
    public function __construct(string $op, eppDomain $domain, ?string $expire=null, ?string $reason=null){
        if (!in_array($op, [self::TYPE_REQUEST,self::TYPE_REPORT])){
            throw new eppException('The op attribute is invalid,only request or report is allow.');
        }
        parent::__construct($domain, null, null, $domain);
        //add namestore extension
        $this->addNamestore($domain);
        //add rgp extension
        $rgp = $this->createElement('rgp:update');
        $restore = $this->createElement('rgp:restore');
        $restore->setAttribute('op', $op);
        if ($op == self::TYPE_REPORT){
            $report = $this->createElement('rgp:report');
            if (empty($expire)){
                throw new eppException('Missing required parameters, registrant or date.');
            }
            $gmtExpireDate = gmdate('Y-m-d\TH:i:s.Z\Z', strtotime($expire));
            $gmtToday = gmdate('Y-m-d\TH:i:s.Z\Z');
            $report->appendChild($this->createElement('rgp:preData', $gmtExpireDate));
            $report->appendChild($this->createElement('rgp:postData', $gmtToday));
            $report->appendChild($this->createElement('rgp:delTime', $gmtExpireDate));
            $report->appendChild($this->createElement('rgp:resTime', $gmtToday));
            $report->appendChild($this->createElement('rgp:resReason', empty($reason)?'Registrant error.':$reason));
            $report->appendChild($this->createElement('rgp:statement', 'This registrar has not restored the Registered Name in order to assume the rights to use or sell the Registered Name for itself or for any third party.'));
            $report->appendChild($this->createElement('rgp:statement', 'The information in this report is true to best of this registrar’s knowledge, and this registrar acknowledges that intentionally supplying false information in this report shall constitute an incurable material breach of the Registry-Registrar Agreement.'));
            $restore->appendChild($report);
        }
        $rgp->appendChild($restore);
        $this->getExtension()->appendChild($rgp);
        $this->addSessionId();
    }
}
