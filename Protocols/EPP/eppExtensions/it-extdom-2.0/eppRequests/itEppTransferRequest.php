<?php

namespace Metaregistrar\EPP;

class itEppTransferRequest extends eppTransferRequest
{
  public function addTrade(eppContactHandle $contactHandle, string $authCode)
  {
    $trade = $this->createElement('extdom:trade');
    $trade->setAttribute('xmlns:extdom', 'http://www.nic.it/ITNIC-EPP/extdom-2.0');
    $trade->setAttribute('xsi:schemaLocation', 'http://www.nic.it/ITNIC-EPP/extdom-2.0 extdom-2.0.xsd');

    $transferTrade = $this->createElement('extdom:transferTrade');
    $transferTrade->appendChild($this->createElement('extdom:newRegistrant', $contactHandle->getContactHandle()));
    $newAuthInfo = $this->createElement('extdom:newAuthInfo');
    $newAuthInfo->appendChild($this->createElement('extdom:pw', $authCode));
    $transferTrade->appendChild($newAuthInfo);
    $trade->appendChild($transferTrade);

    $this->getExtension()->appendChild($trade);
  }
}
