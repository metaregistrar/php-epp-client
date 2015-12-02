<?php
namespace Metaregistrar\EPP;

class atEppCreateResponse extends eppCreateResponse {

    use atEppResponseTrait;


    /**
     * add -NICAT suffix
     *
     *
     * @return string contact_id
     */
    public function getContactId() {
        $result=parent::getContactId();
        if(!is_null($result))
        {
            $result .= "-NICAT";
        }
        return $result;
    }
}
