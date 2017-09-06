<?php


namespace Metaregistrar\EPP;

/**
 * Class ficoraEppInfoDomainRequest
 * @package Metaregistrar\EPP
 */
class ficoraEppInfoDomainResponse extends eppInfoDomainResponse
{
    /**
     * A helper function for retriving xpath query results.
     * @return mixed query result or null if missing
     */
    protected function getXpathQueryResult($query, $cast = null)
    {
        $xpath = $this->xPath();
        $result = $xpath->query($query);
        if ($result->length > 0) {
            $value = $result->item(0)->nodeValue;
            if ($cast) {
                settype($value, $cast);
            }
            return $value;
        } else {
            return null;
        }
    }

    public function getAutoRenewal()
    {
        return $this->getXpathQueryResult('/epp:epp/epp:response/epp:resData/domain:infData/domain:autorenew', 'integer');
    }
}