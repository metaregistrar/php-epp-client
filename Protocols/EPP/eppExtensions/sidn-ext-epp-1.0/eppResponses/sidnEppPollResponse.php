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

/*

<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:sidn="http://rxsd.domain-registry.nl/sidn-ext-epp-registry-contacts-delete-1.0">
  <response>
    <result code="1301">
      <msg>The message has been picked up. Please confirm receipt to remove the message from the queue.</msg>
    </result>
    <msgQ id="1234" count="1">
      <qDate>2020-12-14T14:05:50.000Z</qDate>
      <msg>1103 Contacten verwijderd</msg>
    </msgQ>
    <resData>
      <sidn:registryContactsDeleteData>
        <sidn:id>ABC123-XYZ</sidn:id>
        <sidn:id>ABC456-XYZ</sidn:id>
        <sidn:id>ABC789-XYZ</sidn:id>
      </sidn:registryContactsDeleteData>
    </resData>
    <trID>
      <clTRID>123abcdefghij</clTRID>
      <svTRID>XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX</svTRID>
    </trID>
  </response>
</epp>

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

    public function getRegistryContactsDeleteData() {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/sidn:registryContactsDeleteData/sidn:id');
        if (is_object($result) && ($result->length > 0)) {
            $registry_contacts_delete = [];
            foreach ($result as $element) {
                $registry_contacts_delete[] = $element->nodeValue;
            }
            return $registry_contacts_delete;
        } else {
            return null;
        }
    }

}