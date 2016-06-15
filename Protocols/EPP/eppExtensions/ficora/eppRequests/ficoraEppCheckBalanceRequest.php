<?php
namespace Metaregistrar\EPP;

class ficoraEppCheckBalanceRequest extends eppRequest {
    function __construct() {
        parent::__construct();
        $check = $this->createElement('check');
        $balanceobject = $this->createElement('balance');
        $check->appendChild($balanceobject);
        $this->getCommand()->appendChild($check);
    }
}