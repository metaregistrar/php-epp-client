<?php
namespace Metaregistrar\TMCH;

class dnlTmchConnection extends tmchConnection {

    public function getDnl() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, parent::getHostname());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, parent::getUsername() . ":" . parent::getPassword());
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new tmchException(curl_error($ch));
        }
        $this->setLastinfo(curl_getinfo($ch));
        curl_close($ch);
        return explode("\n", $output);
    }
}
