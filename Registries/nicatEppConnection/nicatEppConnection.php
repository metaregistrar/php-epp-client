<?php

namespace Metaregistrar\EPP;
/**
 * Created by PhpStorm.
 * User: thomasm
 * Date: 23.09.2015
 * Time: 13:51
 */
abstract class nicatEppConnection extends eppConnection
{
    /*
    |--------------------------------------------------------------------------
    | nicatEppConnection
    |--------------------------------------------------------------------------
    |
    | Is a general eppConnection parent class for all upcomming registry extensions
    | provided by Nic.at GmbH.
    |
    */

    private $doPeerVerification=true;

    /**
     * Wraps epp login, this function wrapper makes it easier to unittest the different
     * epp commands using mockery objects
     *
     * @return bool
     * @throws eppException
     */
    public function doLogin()
    {
        $login = new eppLoginRequest;
        if ((($response = $this->writeandread($login)) instanceof eppLoginResponse) && ($response->Success())) {
            $this->loggedin = true;
            return true;
        }
        return false;
    }

    /**
     * Disable Peer Verification for e.g. testing purposes
     *
     * @param bool|true $verifyPeer
     */
    public function setVerifyPeer($verifyPeer=true)
    {
        $this->doPeerVerification = $verifyPeer;
    }


    /**
     * Connect to the address and port fsockopen replaces by general stream_socket_client
     * @param string $address
     * @param int $port
     * @return boolean
     */
    public function connect($hostname = null, $port = null) {
        if ($hostname) {
            $this->hostname = $hostname;
        }
        if ($port) {
            $this->port = $port;
        }
        if ($this->local_cert_path) {
            parent::connect($hostname,$port);
        } else {
            //We don't want our error handler to kick in at this point...

            $target = $this->hostname . ":" . $this->port;
            $errno = '';
            $errstr = '';
            putenv('SURPRESS_ERROR_HANDLER=1');
            $context = stream_context_create();
            if(!$this->doPeerVerification) {
                stream_context_set_option($context, 'ssl', 'verify_peer', false);
                stream_context_set_option($context, 'ssl', 'verify_peer_name', false);
            }
            $this->connection = stream_socket_client($target, $errno, $errstr, $this->timeout, STREAM_CLIENT_CONNECT, $context);
            putenv('SURPRESS_ERROR_HANDLER=0');
            if (is_resource($this->connection)) {
                $this->writeLog("Connection made","CONNECT");
                stream_set_blocking($this->connection, false);
                stream_set_timeout($this->connection, $this->timeout);
                if ($errno == 0) {
                    $this->connected = true;
                    $this->read();
                    return true;
                } else {
                    return false;
                }
            } else {
                $this->writeLog("Connection could not be opened: $errno $errstr","ERROR");
                return false;
            }
        }
        return false;
    }

}