<?php
namespace Metaregistrar\EPP;

/*  <resData>
      <sidn-ext-epp:pollData>
        <sidn-ext-epp:command>domain:transfer</sidn-ext-epp:command>
        <sidn-ext-epp:data>
          <result code="1000">
            <msg>The domain name has been transferred.</msg>
          </result>
          <resData>
            <domain:trnData>
              <domain:name>testmetaregistrar1.nl</domain:name>
              <domain:trStatus>serverApproved</domain:trStatus>
              <domain:reID>GEENP</domain:reID>
              <domain:reDate>2011-11-16T16:22:57.000Z</domain:reDate>
              <domain:acID>X1837</domain:acID>
              <domain:acDate>2011-11-16T16:22:57.941Z</domain:acDate>
            </domain:trnData>
          </resData>
          <trID>
            <svTRID>41910644</svTRID>
          </trID>
        </sidn-ext-epp:data>
      </sidn-ext-epp:pollData>
    </resData>
*/

class sidnEppPollResponse extends eppPollResponse {
    function __construct() {
        parent::__construct();
    }

    public function getPolledCommand() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:command');
        return $result->item(0)->nodeValue;
    }

    public function getPolledResultCode() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:result/@code');
        foreach ($result as $code) {
            return $code->nodeValue;
        }
        return null;
    }

    public function getPolledResultMessage() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:result/epp:msg');
        return $result->item(0)->nodeValue;
    }

    public function getPolledDomainname() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:name');
        return $result->item(0)->nodeValue;
    }

    public function getPolledTransferStatus() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:trStatus');
        return $result->item(0)->nodeValue;
    }

    public function getPolledRequestClientId() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:reID');
        return $result->item(0)->nodeValue;
    }

    public function getPolledRequestDate() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:reDate');
        return $result->item(0)->nodeValue;
    }

    public function getPolledActionClientId() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:acID');
        return $result->item(0)->nodeValue;
    }

    public function getPolledActionDate() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:resData/domain:trnData/domain:acDate');
        return $result->item(0)->nodeValue;
    }

    public function getPolledTransactionId() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn-ext-epp:pollData/sidn-ext-epp:data/epp:trID/epp:svTRID');
        return $result->item(0)->nodeValue;
    }


}