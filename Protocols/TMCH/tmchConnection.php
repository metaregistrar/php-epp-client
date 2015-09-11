<?php
namespace Metaregistrar\TMCH;

class tmchConnection {
    private $lastinfo = null;

    /**
     * Hostname of this connection
     * @var string
     */
    protected $hostname = '';

    /**
     * Port of the connection
     * @var string
     */
    protected $port = 700;

    /**
     * Time-out value for the server connection
     * @var integer
     */
    protected $timeout = 5;

    /**
     * Username to be used in the connection
     * @var string
     */
    protected $username = '';

    /**
     * Password to be used in the connection
     * @var string
     */
    protected $password = '';

    public function getTimeout() {
        return $this->timeout;
    }

    public function setTimeout($timeout) {
        $this->timeout = $timeout;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getHostname() {
        return $this->hostname;
    }

    public function setHostname($hostname) {
        $this->hostname = $hostname;
    }

    public function getPort() {
        return $this->port;
    }

    public function setPort($port) {
        $this->port = $port;
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

    public function __construct($logging = false, $settingsfile = null) {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $path = str_replace('Metaregistrar\TMCH\\',dirname(__FILE__).'\..\..\Registries\\',get_called_class());
        } else {
            $path = str_replace('Metaregistrar\TMCH\\',dirname(__FILE__).'/../../Registries/',get_called_class());
        }
        if (!$settingsfile) {
            $settingsfile = 'settings.ini';
        }
        if ($settings = $this->loadSettings($path,$settingsfile)) {
            $this->setHostname($settings['hostname']);
            $this->setUsername($settings['userid']);
            $this->setPassword($settings['password']);
            $this->setPort($settings['port']);
        } else {
            var_dump($path);
            var_dump($settingsfile);
            $this->setHostname('tmcnis.org/cnis');
            $this->setPort(143);
            $this->setUsername('cnis143');
            $this->setPassword('kf96kjFdoajij!y');
        }

    }

    protected function loadSettings($directory, $file) {
        $result = array();
        if (is_readable($directory . '/' . $file)) {
            $settings = file($directory . '/' . $file, FILE_IGNORE_NEW_LINES);
            foreach ($settings as $setting) {
                list($param, $value) = explode('=', $setting);
                $result[$param] = $value;
            }
            return $result;
        }
        return null;
    }
}