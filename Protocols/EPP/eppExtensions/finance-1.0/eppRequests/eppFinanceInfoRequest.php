<?php
namespace Metaregistrar\EPP;
/*

<info>
    <finance:info/>
</info>

*/

class eppFinanceInfoRequest extends eppRequest {

    /**
     * euridEppCreateContactRequest constructor.
     * @throws eppException
     */
    function __construct() {
        parent::__construct();
        $info = $this->createElement('info');
        $el = $this->createElement('finance:info');
        $info->appendChild($el);
        $this->getCommand()->appendChild($info);
    }

}
