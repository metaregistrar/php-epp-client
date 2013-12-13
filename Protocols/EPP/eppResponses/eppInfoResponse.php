<?php

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
