<?php
namespace Metaregistrar\EPP;
/*

<info>
    <registrar:info/>
</info>

*/

class euridEppRegistrarInfoRequest extends eppRequest {

    /**
     * euridEppCreateContactRequest constructor.
     * @throws eppException
     */
    function __construct() {
        parent::__construct();
        $info = $this->createElement('info');
        $el = $this->createElement('registrar:info');
        $info->appendChild($el);
        $this->getCommand()->appendChild($info);
    }

}