<?php
namespace Metaregistrar\EPP;

class eppBase {

    /**
     * Determines if a log is output after the end of the script or not
     * @var bool
     */
    protected $logging;

    /**
     * Contains the log entries when $logging = true
     * @var array
     */
    protected $logentries;

    /**
     *
     * @var resource $connection
     */
    protected $connection;
    
    /**
     * Time-out value for the server connection
     * @var integer
     */
    protected $timeout = 5;

    public function getTimeout() {
        return $this->timeout;
    }

    public function setTimeout($timeout) {
        $this->timeout = $timeout;
    }

    /**
     * @param string $configfile
     * @param bool|false $debug
     * @return mixed
     * @throws eppException
     */
    static function create($configfile,$debug=false) {
        if ($configfile) {
            if (is_readable($configfile)) {
                $settings = file($configfile, FILE_IGNORE_NEW_LINES);
                foreach ($settings as $setting) {
                    list($param, $value) = explode('=', $setting, 2);
                    $param = trim($param);
                    $value = trim($value);
                    $result[$param] = $value;
                }

            } else {
                throw new eppException('File not found: '.$configfile);
            }
        } else {
            throw new eppException('Configuration file not specified on eppConnection:create');
        }
        if (isset($result['interface'])) {
            $classname = 'Metaregistrar\\EPP\\'.$result['interface'];
            $c = new $classname($debug);
            /* @var $c eppConnection */
            $c->setConnectionDetails($configfile);
            return $c;
        }
        return null;

    }


    /**
     * This will read 1 response from the connection if there is one
     * @param boolean $nonBlocking to prevent the blocking of the thread in case there is nothing to read and not wait for the timeout
     * @return string
     * @throws eppException
     */
    public function read($nonBlocking=false) {
        putenv('SURPRESS_ERROR_HANDLER=1');
        $content = '';
        $time = time() + $this->timeout;
        $read = "";
        while ((!isset ($length)) || ($length > 0)) {
            if (feof($this->connection)) {
                putenv('SURPRESS_ERROR_HANDLER=0');
                throw new eppException ('Unexpected closed connection by remote host...',0,null,null,$read);
            }
            //Check if timeout occured
            if (time() >= $time) {
                putenv('SURPRESS_ERROR_HANDLER=0');
                return false;
            }
            //If we dont know how much to read we read the first few bytes first, these contain the content-length
            //of whats to come
            if ((!isset($length)) || ($length == 0)) {
                $readLength = 4;
                //$readbuffer = "";
                $read = "";
                while ($readLength > 0) {
                    if ($readbuffer = fread($this->connection, $readLength)) {
                        $readLength = $readLength - strlen($readbuffer);
                        $read .= $readbuffer;
                        $time = time() + $this->timeout;
                    }
                    //Check if timeout occured
                    if (time() >= $time) {
                        putenv('SURPRESS_ERROR_HANDLER=0');
                        return false;
                    }
                }
                //$this->writeLog("Read 4 bytes for integer. (read: " . strlen($read) . "):$read","READ");
                $length = $this->readInteger($read) - 4;
                $this->writeLog("Reading next: $length bytes","READ");
            }
            if ($length > 1000000) {
                throw new eppException("Packet size is too big: $length. Closing connection",0,null,null,$read);
            }
            //We know the length of what to read, so lets read the stuff
            if ((isset($length)) && ($length > 0)) {
                $time = time() + $this->timeout;
                if ($read = fread($this->connection, $length)) {
                    //$this->writeLog(print_R(socket_get_status($this->connection), true));
                    $length = $length - strlen($read);
                    $content .= $read;
                    $time = time() + $this->timeout;
                }
                if (strpos($content, 'Session limit exceeded') > 0) {
                    $read = fread($this->connection, 4);
                    $content .= $read;
                }
            }
            if($nonBlocking && strlen($content)<1)
            {
                //there is no content don't keep waiting
                break;
            }

            if (!strlen($read)) {
                usleep(100);
            }

        }
        putenv('SURPRESS_ERROR_HANDLER=0');
        #ob_flush();
        return $content;
    }

    /**
     * This parses the first 4 bytes into an integer for use to compare content-length
     *
     * @param string $content
     * @return integer
     */
    private function readInteger($content) {
        $int = unpack('N', substr($content, 0, 4));
        return $int[1];
    }

    /**
     * This adds the content-length to the content that is about to be written over the EPP Protocol
     *
     * @param string $content Your XML
     * @return string String to write
     */
    private function addInteger($content) {
        $int = pack('N', intval(strlen($content) + 4));
        return $int . $content;
    }

    /**
     * Write stuff over the EPP connection
     * @param string $content
     * @return bool
     * @throws eppException
     */
    public function write($content) {
        $this->writeLog("Writing: " . strlen($content) . " + 4 bytes","WRITE");
        $content = $this->addInteger($content);
        if (!is_resource($this->connection)) {
            throw new eppException ('Writing while no connection is made is not supported.');
        }

        putenv('SURPRESS_ERROR_HANDLER=1');
        #ob_flush();
        if (fwrite($this->connection, $content)) {
            //fpassthru($this->connection);
            putenv('SURPRESS_ERROR_HANDLER=0');
            return true;
        }
        putenv('SURPRESS_ERROR_HANDLER=0');
        return false;
    }


    /**
     * Starts logging
     */
    protected function enableLogging() {
        date_default_timezone_set("Europe/Amsterdam");
        $this->logging = true;
    }

    /**
     * Shows the log when the script has ended
     */
    protected function showLog() {
        echo "==== LOG ====\n";
        if (property_exists($this, 'logentries')) {
            foreach ($this->logentries as $logentry) {
                echo $logentry . "\n";
            }
        }
    }

    /**
     * Writes a new entry to the log
     * @param string $text
     * @param string $action
     */
    protected function writeLog($text,$action) {
        if ($this->logging) {
            //echo "-----".date("Y-m-d H:i:s")."-----".$text."-----end-----\n";
            $this->logentries[] = "-----" . $action . "-----" . date("Y-m-d H:i:s") . "-----\n" . $text . "\n-----END-----" . date("Y-m-d H:i:s") . "-----\n";
        }
    }
}