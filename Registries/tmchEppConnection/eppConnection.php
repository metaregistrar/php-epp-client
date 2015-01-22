<?php
namespace Metaregistrar\EPP;

class tmchEppConnection extends eppConnection {

    private $lastinfo = null;

    public function __construct($logging=false) {
        parent::__construct($logging);
        $settings = $this->loadSettings(dirname(__FILE__));
        parent::setHostname($settings['hostname']);
        parent::setPort($settings['port']);
        parent::setUsername($settings['userid']);
        parent::setPassword($settings['password']);
    }

    public function getCnis($key) {
        if (!is_string($key)) {
            throw new eppException("Key must be filled when requesting CNIS information");
        }
        $url = "https://".parent::getHostname()."/".$key.".xml";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, parent::getUsername().":".parent::getPassword());
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $output = curl_exec($ch);
        $this->setLastInfo(curl_getinfo($ch));
        curl_close($ch);
        return $output;
    }


    /**
     * @param null $lastinfo
     */
    public function setLastinfo($lastinfo) {
        $this->lastinfo = $lastinfo;
    }

    /**
     * @return null
     */
    public function getLastinfo() {
        return $this->lastinfo;
    }

}