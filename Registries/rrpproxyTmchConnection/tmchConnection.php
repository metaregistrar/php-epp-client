<?php
namespace Metaregistrar\TMCH;

class rrpproxyTmchConnection extends cnisTmchConnection {

    /**
     * Retrieves standard claim info from TMCH via db.claimnotification.info (Key Systems site)
     * @param string $key
     * @return tmchClaimData
     * @throws tmchException
     */
    public function getCnis($key) {
        if (!is_string($key)) {
            throw new tmchException("Key must be filled when requesting CNIS information");
        }
        $url = "http://db.claimnotification.info?token=".urlencode($key);
        if ($this->logging) {
            echo "Calling interface $url\n\n";
        }
        $output = file_get_contents($url);
        if (strlen($output)==0) {
            throw new tmchException("Empty output received from CNIS service");
        }
        if (strpos($output,'404 Not Found')!==false)
        {
            throw new tmchException("Requested URL was not found on this server");
        }
        $result = new tmchClaimData();
        $result->loadXML($output);
        $result->setClaims();
        return $result;
    }
}