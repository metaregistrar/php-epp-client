<?php

namespace Metaregistrar\EPP;

/**
 * Class euridEppInfoNsgroupResponse
 * @package Metaregistrar\EPP
 */
class euridEppInfoNsgroupResponse extends eppResponse
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * @return string
     */
    public function getNsgroupName()
    {
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/nsgroup:infData/nsgroup:name');
        if ($result->length > 0) {
            return $result->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    /**
     * @return array
     */
    public function getNsgroupHosts()
    {
        $return = [];
        $xpath = $this->xPath();
        $result = $xpath->query('/epp:epp/epp:response/epp:resData/nsgroup:infData/nsgroup:ns');
        if ($result->length > 0) {
            foreach ($result as $item) {
                $return[] = $item->nodeValue;
            }
        }
        return $return;
    }
}
