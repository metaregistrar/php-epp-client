<?php
namespace Metaregistrar\EPP;

class verisignEppCheckHostRequest extends eppCheckHostRequest {
    use verisignEppExtension;
    public function __construct($checkrequest) {
        parent::__construct($checkrequest);
        //add namestore extension
        $this->addNamestore(is_array($checkrequest) ? $checkrequest[0] : $checkrequest);
        $this->addSessionId();

    }
}
