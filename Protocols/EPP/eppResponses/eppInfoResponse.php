<?php
include_once(dirname(__FILE__).'/../eppResponse.php');
/*
 * This object contains the logic to read the response of an EPP info command
 * The actual info commands are divided into eppInfoHostResponse, eppInfoContactResponse and eppInfoDomainResponse
 */

class eppInfoResponse extends eppResponse
{
    /**
     *
     * @param array $arr
     * @return string
     */
    protected function arrayToCSV($arr)
    {
        $ret = '';
        if (is_array($arr))
        {
            foreach ($arr as $value)
            {
                if (is_string($value))
                {
                    if (strlen($ret))
                    {
                        $ret .= ',';
                    }
                    $ret .= $value;
                }
            }
        }
        return $ret;
    }


  
   
}
