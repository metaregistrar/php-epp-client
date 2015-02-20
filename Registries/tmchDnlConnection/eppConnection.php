<?php
namespace Metaregistrar\EPP;

class tmchDnlConnection extends eppConnection {

    private $lastinfo = null;

    public function __construct($logging = false) {
        parent::__construct($logging);
        if ($settings = $this->loadSettings(dirname(__FILE__))) {
            parent::setHostname($settings['hostname']);
            parent::setPort($settings['port']);
            parent::setUsername($settings['userid']);
            parent::setPassword($settings['password']);

        }
    }

    public function getDnl() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, parent::getHostname());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, parent::getUsername() . ":" . parent::getPassword());
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new eppException(curl_error($ch));
        }
        $this->lastinfo = curl_getinfo($ch);
        curl_close($ch);
        return explode("\n", $output);
    }
}
