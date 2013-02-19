<?php
include_once(dirname(__FILE__).'/../eppRequest.php');
/*
 * This object contains all the logic to create an EPP hello command
 */

class eppLogoutRequest extends eppRequest
{
    function __construct()
    {
        parent::__construct();
        #
        # Create command structure
        #
        $command = $this->createElement('command');

        #
        # Create logout command
        #
        $logout = $this->createElement('logout');
        $command->appendChild($logout);
        $this->epp->appendChild($command);
    }

    function __destruct()
    {

    }

}
